<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
            'experience_type' => 'nullable|string|max:100',
            'message'         => 'nullable|string|max:1000',
        ]);

        $validated['artisan_id'] = $artisan_id;
        $validated['status'] = 'pending';

        \App\Models\ReservationRequest::create($validated);

        return redirect()->route('artisans.show', $artisan_id)
            ->with('success', '✅ Votre demande a été envoyée ! L\'artisan vous contactera bientôt.');
    }
}
