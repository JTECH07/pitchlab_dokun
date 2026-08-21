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
        $reservation = ReservationRequest::with('artisan')->where('qr_code_token', $token)->firstOrFail();
        $user = $request->user();

        // Si l'utilisateur connecté est l'artisan concerné ou un administrateur
        if ($user && ($user->id === $reservation->artisan?->user_id || $user->role === 'admin')) {
            $reservation->update(['status' => 'completed']);

            return redirect()->route('reservations.receipt', $token)
                ->with('success', '🎉 Validation réussie ! La visite de ' . $reservation->visitor_name . ' (' . $reservation->reference . ') a été marquée comme complétée.');
        }

        // Si c't un autre utilisateur (visiteur/touriste)
        return redirect()->route('reservations.receipt', $token)
            ->with('info', 'Ce billet QR est valide pour la réservation ' . $reservation->reference . '. Seul l\'artisan propriétaire peut marquer la visite comme complétée.');
    }
}
