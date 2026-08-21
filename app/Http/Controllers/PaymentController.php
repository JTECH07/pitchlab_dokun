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
     * Calcule les frais de service ƉƆKUN (5% du montant, minimum 500 XOF).
     */
    private static function calculateServiceFee(float $experienceTotal): int
    {
        $fee = (int) ceil($experienceTotal * 0.05);
        return max($fee, 500); // minimum 500 XOF
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
            'payment_method' => 'required|in:pay_on_site,mobile_money',
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
        $totalAmount = $expPrice; // montant de l'expérience hors frais de service
        $serviceFee  = self::calculateServiceFee($expPrice);

        // ── Calcul du montant FedaPay selon la méthode ─────────────────
        // pay_on_site  → FedaPay prélève UNIQUEMENT les frais de service (5%)
        // mobile_money → FedaPay prélève l'intégralité (expérience + frais 5%)
        $fedaAmount = ($validated['payment_method'] === 'mobile_money')
            ? ($expPrice + $serviceFee)
            : $serviceFee;

        $fedaDesc = $validated['payment_method'] === 'mobile_money'
            ? 'Réservation ƉƆKUN : ' . ($experience?->title ?? 'Visite libre') . ' — ' . $reference
            : 'Frais de réservation ƉƆKUN — ' . $reference;

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
            'experience_type'=> $experience?->title ?? 'Visite d\'atelier libre',
            'total_amount'   => $totalAmount,
            'service_fee'    => $serviceFee,
            'currency'       => 'XOF',
            'payment_method' => $validated['payment_method'],
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

                // Notifier l'artisan
                $this->notifyArtisan($reservation);

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
            ReservationRequest::where('fedapay_transaction_id', (string) $transactionId)
                ->update(['payment_status' => 'paid']);
            PendingPayment::where('fedapay_transaction_id', (string) $transactionId)
                ->update(['status' => 'completed']);
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

            $isExperience = !empty($reservation->experience_id);
            $typeLabel    = $isExperience
                ? 'Expérience pratique : ' . $reservation->experience_type
                : 'Visite d\'atelier libre';

            $subject = '🎟 Nouvelle réservation ƉƆKUN — ' . $reservation->reference;
            $body = "Bonjour {$artisan->first_name},\n\n"
                . "Vous avez reçu une nouvelle réservation ƉƆKUN.\n\n"
                . "═══════════════════════════════\n"
                . "RÉFÉRENCE   : {$reservation->reference}\n"
                . "TYPE        : {$typeLabel}\n"
                . "VISITEUR    : {$reservation->visitor_name}\n"
                . "TÉLÉPHONE   : {$reservation->visitor_phone}\n"
                . "DATE        : {$reservation->requested_date}\n"
                . "PERSONNES   : {$reservation->guests_count}\n"
                . "PAIEMENT    : " . ($reservation->payment_method === 'mobile_money' ? 'Mobile Money (payé)' : 'À régler à l\'atelier') . "\n"
                . "MONTANT     : " . ($reservation->total_amount ? number_format($reservation->total_amount, 0, ',', ' ') . ' FCFA' : 'Visite libre') . "\n"
                . "═══════════════════════════════\n\n";

            if ($reservation->message) {
                $body .= "Message du visiteur :\n\"{$reservation->message}\"\n\n";
            }

            $body .= "Connectez-vous sur ƉƆKUN pour confirmer ou refuser cette réservation.\n"
                   . "URL : " . route('artisan-space.index') . "\n\n"
                   . "— L'équipe ƉƆKUN";

            Mail::raw($body, function ($message) use ($artisan, $subject) {
                $message->to($artisan->user->email, $artisan->first_name)
                        ->subject($subject)
                        ->from(config('mail.from.address'), 'ƉƆKUN Réservations');
            });

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
