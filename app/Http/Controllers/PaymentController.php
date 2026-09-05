<?php

namespace App\Http\Controllers;

use App\Models\Artisan;
use App\Models\Experience;
use App\Models\PendingPayment;
use App\Models\ReservationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use FedaPay\FedaPay;
use FedaPay\Transaction;

class PaymentController extends Controller
{
    /**
     * Commission ƉƆKUN sur une réservation : 10% du montant de l'expérience
     * (minimum 500 XOF), configurable via config/dokun.php.
     */
    private static function calculateServiceFee(float $experienceTotal): int
    {
        $rate = (float) config('dokun.commission_reservation_rate', 0.10);
        $min  = (int) config('dokun.min_service_fee', 500);
        $fee  = (int) ceil($experienceTotal * $rate);
        return max($fee, $min);
    }

    /**
     * Etape 1 — Affiche le récapitulatif + choix de paiement avant de lancer FedaPay.
     * GET /artisans/{artisan_id}/reserver?experience_id=X
     */
    public function showConfirmation(Request $request, $artisan_id)
    {
        $artisan = Artisan::with(['experiences', 'savoirFaires'])->findOrFail($artisan_id);

        $experience = null;
        if ($request->filled('experience_id')) {
            $experience = Experience::where('artisan_id', $artisan->id)
                ->where('is_published', true)
                ->findOrFail($request->experience_id);
        }

        return view('reservations.confirm', compact('artisan', 'experience'));
    }

    /**
     * Etape 2 — Reçoit le formulaire, crée la transaction FedaPay, redirige.
     * POST /artisans/{artisan_id}/pay
     */
    public function initiate(Request $request, $artisan_id)
    {
        $validated = $request->validate([
            'visitor_name'   => 'required|string|max:255',
            'visitor_phone'  => 'required|string|max:30',
            'visitor_email'  => 'nullable|email|max:255',
            'requested_date' => 'required|date|after:today',
            'guests_count'   => 'required|integer|min:1|max:20',
            'experience_id'  => 'nullable|integer|exists:experiences,id',
            'message'        => 'nullable|string|max:1000',
        ]);

        $artisan    = Artisan::findOrFail($artisan_id);
        $experience = null;
        if (!empty($validated['experience_id'])) {
            $experience = Experience::where('artisan_id', $artisan->id)
                ->where('is_published', true)
                ->findOrFail($validated['experience_id']);
        }

        $reference   = 'DKN-' . strtoupper(Str::random(8));
        $expPrice    = $experience ? (float) $experience->price * $validated['guests_count'] : 0;

        // ── Paiement 100% en ligne via FedaPay ──────────────────────────
        // Montant total = prix expérience + commission ƉƆKUN (10%, min 500 XOF)
        $serviceFee  = self::calculateServiceFee($expPrice);
        $totalAmount = $expPrice;
        $fedaAmount  = $expPrice + $serviceFee;
        $fedaDesc    = 'Réservation ƉƆKUN : ' . ($experience?->title ?? 'Visite libre') . ' — ' . $reference;

        // ── Données de réservation à conserver ─────────────────────────
        $reservationData = [
            'artisan_id'     => $artisan->id,
            'user_id'        => $request->user()?->id,
            'experience_id'  => $experience?->id,
            'visitor_name'   => $validated['visitor_name'],
            'visitor_phone'  => $validated['visitor_phone'],
            'visitor_email'  => $validated['visitor_email'] ?? null,
            'requested_date' => $validated['requested_date'],
            'guests_count'   => $validated['guests_count'],
            'experience_type'=> $experience?->title ?? 'Visite libre',
            'total_amount'   => $totalAmount,
            'service_fee'    => $serviceFee,
            'currency'       => 'XOF',
            'payment_method' => 'mobile_money',
            'message'        => $validated['message'] ?? null,
            'reference'      => $reference,
        ];

        // ── Créer la transaction FedaPay ────────────────────────────────
        $this->configureFedaPay();

        try {
            $nameParts = explode(' ', trim($validated['visitor_name']), 2);
            $firstName = $nameParts[0];
            $lastName  = $nameParts[1] ?? 'N/A';

            $isSandbox = config('services.fedapay.environment', 'sandbox') === 'sandbox';
            $fedaPhone = $isSandbox ? '64000001' : preg_replace('/\D/', '', $validated['visitor_phone']);

            $transaction = Transaction::create([
                'description'  => $fedaDesc,
                'amount'       => (int) $fedaAmount,
                'currency'     => ['iso' => 'XOF'],
                'callback_url' => route('payment.callback'),
                'customer'     => [
                    'firstname'    => $firstName,
                    'lastname'     => $lastName,
                    'email'        => $validated['visitor_email']
                                     ?? ('visitor+' . Str::random(6) . '@dokun.bj'),
                    'phone_number' => [
                        'number'  => $fedaPhone,
                        'country' => 'BJ',
                    ],
                ],
            ]);

            // ── Stocker en DB (plus fiable que session) ─────────────────
            $pending = PendingPayment::create([
                'reference'              => $reference,
                'reservation_data'       => $reservationData,
                'fedapay_transaction_id' => (string) $transaction->id,
                'status'                 => 'pending',
            ]);

            $token = $transaction->generateToken();
            return redirect($token->url);

        } catch (\Exception $e) {
            Log::error('FedaPay transaction creation failed', [
                'error'    => $e->getMessage(),
                'artisan'  => $artisan_id,
                'amount'   => $fedaAmount,
            ]);

            return redirect()->route('artisans.show', $artisan_id)
                ->with('error', 'Erreur de paiement FedaPay : ' . $e->getMessage() . '. Veuillez réessayer.');
        }
    }

    /**
     * Callback FedaPay — utilisateur redirigé depuis la page de paiement.
     * GET /payment/callback?id=TX_ID&status=approved
     */
    public function callback(Request $request)
    {
        $transactionId = $request->query('id');

        if (!$transactionId) {
            return redirect()->route('home')
                ->with('error', 'Paramètre de transaction manquant. Contactez le support.');
        }

        $this->configureFedaPay();

        try {
            // Récupérer le statut depuis FedaPay (vérification côté serveur)
            $tx     = Transaction::retrieve($transactionId);
            $status = $tx->status ?? 'unknown';

            // Retrouver le pendingPayment en base
            $pending = PendingPayment::where('fedapay_transaction_id', (string) $transactionId)
                ->where('status', 'pending')
                ->first();

            if (!$pending) {
                Log::warning('FedaPay callback: no pending payment found', ['tx' => $transactionId]);
                return redirect()->route('home')
                    ->with('error', 'Réservation introuvable. Si vous avez été débité, contactez le support avec la référence : ' . $transactionId);
            }

            $reservationData = $pending->reservation_data;

            if ($status === 'approved') {
                $reservationData['payment_status']         = 'paid';
                $reservationData['fedapay_transaction_id'] = (string) $transactionId;
                $reservationData['status']                 = 'pending'; // En attente de confirmation artisan

                $reservation = ReservationRequest::create($reservationData);
                $pending->update(['status' => 'completed']);

                // Notifier l'artisan + points fidélité si visiteur connecté
                $this->notifyArtisan($reservation);
                if (!empty($reservationData['user_id'])) {
                    $member = \App\Models\User::find($reservationData['user_id']);
                    if ($member) app(\App\Services\LoyaltyService::class)->award($member, 'reservation_made', ['reference' => $reservationData['reference']]);
                }

                return redirect()->route('reservations.receipt', $reservation->qr_code_token)
                    ->with('success', 'Paiement confirmé ! Votre billet est prêt. Référence : ' . $reservationData['reference']);

            } else {
                $pending->update(['status' => 'failed']);

                return redirect()->route('artisans.show', $reservationData['artisan_id'])
                    ->with('error', 'Paiement non validé (statut : ' . $status . '). Aucun débit n\'a été effectué. Référence : ' . $reservationData['reference']);
            }

        } catch (\Exception $e) {
            Log::error('FedaPay callback error', ['error' => $e->getMessage(), 'tx' => $transactionId]);

            // Enregistrer avec statut pending pour vérification via webhook
            $pending = PendingPayment::where('fedapay_transaction_id', (string) $transactionId)->first();
            if ($pending) {
                $reservationData = $pending->reservation_data;
                $reservationData['payment_status'] = 'pending';
                $reservationData['fedapay_transaction_id'] = (string) $transactionId;
                $reservationData['status'] = 'pending';
                $reservation = ReservationRequest::create($reservationData);
                $pending->update(['status' => 'pending']);

                // Paiement en cours de vérification : prévenir quand même l'artisan
                $this->notifyArtisan($reservation);

                return redirect()->route('reservations.receipt', $reservation->qr_code_token)
                    ->with('success', 'Réservation enregistrée. Vérification du paiement en cours. Référence : ' . $reservationData['reference']);
            }

            return redirect()->route('home')
                ->with('error', 'Erreur lors de la vérification du paiement. Référence transaction : ' . $transactionId);
        }
    }

    /**
     * Webhook FedaPay — notifications push serveur-à-serveur.
     * POST /api/fedapay/webhook
     */
    public function webhook(Request $request)
    {
        $payload       = $request->all();
        $eventName     = $payload['name'] ?? null;
        $transactionId = $payload['object']['id'] ?? null;

        Log::info('FedaPay Webhook received', ['event' => $eventName, 'tx' => $transactionId]);

        if (!$transactionId) {
            return response()->json(['received' => true], 200);
        }

        if ($eventName === 'transaction.approved') {
            // Callback raté ? Si le pending était encore 'pending', l'artisan n'a pas été notifié
            $callbackMissed = PendingPayment::where('fedapay_transaction_id', (string) $transactionId)
                ->where('status', 'pending')->exists();

            ReservationRequest::where('fedapay_transaction_id', (string) $transactionId)
                ->update(['payment_status' => 'paid']);
            PendingPayment::where('fedapay_transaction_id', (string) $transactionId)
                ->update(['status' => 'completed']);

            if ($callbackMissed) {
                $missed = ReservationRequest::where('fedapay_transaction_id', (string) $transactionId)->first();
                if ($missed) $this->notifyArtisan($missed);
            }
        }

        if ($eventName === 'transaction.declined' || $eventName === 'transaction.canceled') {
            ReservationRequest::where('fedapay_transaction_id', (string) $transactionId)
                ->update(['payment_status' => 'failed']);
            PendingPayment::where('fedapay_transaction_id', (string) $transactionId)
                ->update(['status' => 'failed']);
        }

        return response()->json(['received' => true], 200);
    }

    /**
     * Envoyer une notification à l'artisan par email (et optionnellement WhatsApp).
     */
    private function notifyArtisan(ReservationRequest $reservation): void
    {
        try {
            $artisan = $reservation->artisan;
            if (!$artisan?->user?->email) return;

            \Illuminate\Support\Facades\Mail::to($artisan->user->email)->queue(
                new \App\Mail\ArtisanBookingNotification($reservation)
            );
        } catch (\Exception $e) {
            Log::warning('Artisan notification failed', ['error' => $e->getMessage()]);
        }
    }

    private function configureFedaPay(): void
    {
        FedaPay::setApiKey(config('services.fedapay.secret_key'));
        FedaPay::setEnvironment(config('services.fedapay.environment', 'sandbox'));
    }
}
