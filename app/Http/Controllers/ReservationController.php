<?php

namespace App\Http\Controllers;

use App\Models\Artisan;
use App\Models\Experience;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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

        return redirect()->route('artisans.show', $artisan_id)
            ->with('success', 'Votre réservation est enregistrée. Référence : '.$validated['reference'].'.');
    }
}
