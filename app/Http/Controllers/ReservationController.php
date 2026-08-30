<?php

namespace App\Http\Controllers;

use App\Models\Artisan;
use App\Models\Experience;
use App\Models\ReservationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class ReservationController extends Controller
{
    public function store(Request $request, $artisan_id)
    {
        $validated = $request->validate([
            'visitor_name'    => 'required|string|max:255',
            'visitor_phone'   => 'required|string|max:30',
            'visitor_email'   => 'nullable|email|max:255',
            'requested_date'  => 'required|date|after:today',
            'guests_count'    => 'required|integer|min:1|max:20',
            'experience_id'   => 'nullable|exists:experiences,id',
            'experience_type' => 'nullable|string|max:100',
            'payment_method'  => 'required|in:pay_on_site,mobile_money',
            'message'         => 'nullable|string|max:1000',
        ]);

        $artisan = Artisan::findOrFail($artisan_id);
        $experience = ! empty($validated['experience_id'])
            ? Experience::where('artisan_id', $artisan->id)->findOrFail($validated['experience_id'])
            : null;

        $validated['artisan_id'] = $artisan->id;
        $validated['user_id'] = $request->user()?->id;
        $validated['status'] = 'pending';
        $validated['experience_type'] = $experience?->title ?? ($validated['experience_type'] ?? 'Visite d’atelier');
        $validated['total_amount'] = $experience?->price ? $experience->price * $validated['guests_count'] : null;
        $validated['currency'] = $experience?->currency ?? 'XOF';
        $validated['payment_status'] = $validated['payment_method'] === 'mobile_money' ? 'pending' : 'not_required';
        $validated['reference'] = 'DKN-'.strtoupper(Str::random(8));

        \App\Models\ReservationRequest::create($validated);

        // Récupérer la réservation créée
        $reservation = \App\Models\ReservationRequest::where('reference', $validated['reference'])->first();

        return redirect()->route('reservations.receipt', $reservation->qr_code_token)
            ->with('success', 'Votre réservation est enregistrée. Référence : '.$validated['reference'].'.');
    }

    public function showByToken($token)
    {
        $reservation = ReservationRequest::with('artisan', 'experience')
            ->where('qr_code_token', $token)->firstOrFail();

        $scanUrl = route('reservations.scan', $token);
        $qrSvg   = QrCode::format('svg')->size(200)->errorCorrection('H')->generate($scanUrl);

        return view('reservations.receipt', compact('reservation', 'qrSvg'));
    }

    public function scan(Request $request, $token)
    {
        $reservation = ReservationRequest::with('artisan.user')->where('qr_code_token', $token)->firstOrFail();
        $user = $request->user();

        if (! $user || ($user->id !== $reservation->artisan?->user_id && $user->role !== 'admin')) {
            return redirect()->route('reservations.receipt', $token)
                ->with('info', 'Ce billet QR est valide pour la réservation ' . $reservation->reference . '. Seul l\'artisan propriétaire peut gérer cette réservation.');
        }

        $action = $request->input('action', 'accept');
        $oldStatus = $reservation->status;

        $statusMap = [
            'accept'   => 'accepted',
            'reject'   => 'rejected',
            'complete' => 'completed',
        ];

        $newStatus = $statusMap[$action] ?? 'accepted';
        $reservation->update(['status' => $newStatus]);

        if ($newStatus === 'accepted' && $oldStatus !== 'accepted') {
            $this->notifyVisitorAccepted($reservation);
        }

        $messages = [
            'accepted'  => "Réservation acceptée ! {$reservation->visitor_name} ({$reservation->reference}) a été notifié(e).",
            'rejected'  => "Réservation refusée pour {$reservation->visitor_name} ({$reservation->reference}).",
            'completed' => "Visite de {$reservation->visitor_name} ({$reservation->reference}) marquée comme complétée.",
        ];

        return redirect()->route('reservations.receipt', $token)
            ->with('success', $messages[$newStatus] ?? 'Statut mis à jour.');
    }

    private function notifyVisitorAccepted(ReservationRequest $reservation): void
    {
        if (! empty($reservation->visitor_email)) {
            try {
                \Mail::to($reservation->visitor_email)->send(
                    new \App\Mail\VisitorBookingAccepted($reservation)
                );
            } catch (\Throwable $e) {
                \Log::warning('Failed to send acceptance email to visitor: ' . $e->getMessage());
            }
        }

        if (! empty($reservation->visitor_phone)) {
            try {
                $phone = preg_replace('/\D/', '', $reservation->visitor_phone);
                $artisanName = $reservation->artisan?->professional_name
                    ?? ($reservation->artisan?->first_name . ' ' . $reservation->artisan?->last_name);
                $message = "Votre réservation {$reservation->reference} chez {$artisanName} a été acceptée ! "
                    . "Date : {$reservation->requested_date}. "
                    . "Montant : " . ($reservation->total_amount ? number_format($reservation->total_amount, 0, ',', ' ') . ' FCFA' : 'Visite libre') . ". "
                    . route('reservations.receipt', $reservation->qr_code_token);

                \App\Services\WhatsAppService::send($phone, $message);
            } catch (\Throwable $e) {
                \Log::warning('Failed to send WhatsApp to visitor: ' . $e->getMessage());
            }
        }
    }
}
