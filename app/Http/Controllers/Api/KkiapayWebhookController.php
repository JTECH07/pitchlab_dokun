<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ReservationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class KkiapayWebhookController extends Controller
{
    /**
     * Vérifie une transaction KKiaPay côté serveur et confirme la réservation.
     * Appelé en AJAX depuis le front après succès du widget.
     */
    public function verifyAndReserve(Request $request)
    {
        $validated = $request->validate([
            'transaction_id'  => 'required|string',
            'visitor_name'    => 'required|string|max:255',
            'visitor_phone'   => 'required|string|max:30',
            'visitor_email'   => 'nullable|email|max:255',
            'requested_date'  => 'required|date|after:today',
            'guests_count'    => 'required|integer|min:1|max:20',
            'experience_id'   => 'nullable|integer|exists:experiences,id',
            'payment_method'  => 'required|in:pay_on_site,mobile_money',
            'message'         => 'nullable|string|max:1000',
            'artisan_id'      => 'required|integer|exists:artisans,id',
        ]);

        $transactionId = $validated['transaction_id'];
        $apiKey        = config('services.kkiapay.private_key');
        $isSandbox     = config('services.kkiapay.sandbox', true);

        // ── Vérification côté serveur KKiaPay ──
        $baseUrl = $isSandbox
            ? 'https://api-sandbox.kkiapay.me'
            : 'https://api.kkiapay.me';

        try {
            $response = Http::withHeaders([
                'x-private-key' => $apiKey,
            ])->get("{$baseUrl}/api/v1/transactions/{$transactionId}/status");

            if (! $response->successful()) {
                Log::error('KKiaPay verify failed', ['status' => $response->status(), 'body' => $response->body()]);
                return response()->json(['status' => 'error', 'message' => 'Impossible de vérifier la transaction.'], 422);
            }

            $txData = $response->json();

            // La transaction doit être SUCCESS et le montant au moins 1000 XOF
            if (($txData['status'] ?? '') !== 'SUCCESS') {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Transaction non validée par KKiaPay (statut : ' . ($txData['status'] ?? 'inconnu') . ').',
                ], 422);
            }

            if (($txData['amount'] ?? 0) < 1000) {
                return response()->json(['status' => 'error', 'message' => 'Montant insuffisant.'], 422);
            }
        } catch (\Exception $e) {
            Log::error('KKiaPay HTTP error', ['error' => $e->getMessage()]);
            return response()->json(['status' => 'error', 'message' => 'Erreur réseau lors de la vérification.'], 500);
        }

        // ── Éviter les doublons ──
        if (ReservationRequest::where('kkiapay_transaction_id', $transactionId)->exists()) {
            return response()->json(['status' => 'error', 'message' => 'Cette transaction a déjà été utilisée.'], 409);
        }

        // ── Créer la réservation confirmée ──
        $experience = null;
        if (! empty($validated['experience_id'])) {
            $experience = \App\Models\Experience::where('artisan_id', $validated['artisan_id'])
                ->find($validated['experience_id']);
        }

        $reservation = ReservationRequest::create([
            'artisan_id'              => $validated['artisan_id'],
            'user_id'                 => $request->user()?->id,
            'experience_id'           => $experience?->id,
            'visitor_name'            => $validated['visitor_name'],
            'visitor_phone'           => $validated['visitor_phone'],
            'visitor_email'           => $validated['visitor_email'] ?? null,
            'requested_date'          => $validated['requested_date'],
            'guests_count'            => $validated['guests_count'],
            'experience_type'         => $experience?->title ?? 'Visite d\'atelier',
            'total_amount'            => $experience?->price ? $experience->price * $validated['guests_count'] : null,
            'currency'                => $experience?->currency ?? 'XOF',
            'payment_method'          => $validated['payment_method'],
            'payment_status'          => 'paid',
            'reference'               => 'DKN-' . strtoupper(\Illuminate\Support\Str::random(8)),
            'kkiapay_transaction_id'  => $transactionId,
            'message'                 => $validated['message'] ?? null,
            'status'                  => 'pending',
        ]);

        return response()->json([
            'status'    => 'success',
            'reference' => $reservation->reference,
            'message'   => 'Réservation confirmée ! Référence : ' . $reservation->reference,
        ]);
    }

    /**
     * Webhook KKiaPay (optionnel) — reçoit les notifications push de KKiaPay.
     * À configurer dans le dashboard KKiaPay : POST /api/kkiapay/webhook
     */
    public function webhook(Request $request)
    {
        $payload = $request->all();
        Log::info('KKiaPay Webhook received', $payload);

        $transactionId = $payload['transactionId'] ?? null;
        $eventType     = $payload['event_type'] ?? ($payload['type'] ?? null);

        if ($transactionId && $eventType === 'PAYMENT_SUCCESS') {
            ReservationRequest::where('kkiapay_transaction_id', $transactionId)
                ->update(['payment_status' => 'paid']);
        }

        return response()->json(['received' => true]);
    }
}
