<?php

namespace App\Http\Controllers;

use App\Models\ActorRequest;
use Illuminate\Http\Request;

class ActorRequestController extends Controller
{
    private const ROLES_META = [
        'guide'       => ['label' => 'Guide touristique', 'icon' => 'M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z M15 11a3 3 0 11-6 0 3 3 0 016 0z', 'desc' => 'Accompagnez les visiteurs et partagez votre connaissance du territoire.'],
        'institution' => ['label' => 'Institution culturelle', 'icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4', 'desc' => 'Mise en valeur de votre patrimoine auprès d\'un public curieux.'],
        'researcher'  => ['label' => 'Chercheur', 'icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253', 'desc' => 'Partagez vos travaux et collaborez avec la communauté.'],
        'partner'     => ['label' => 'Partenaire tourisme', 'icon' => 'M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z', 'desc' => 'Hébergement, restauration, transport — boostez votre visibilité.'],
    ];

    public function showForm()
    {
        return view('actor-requests.form', ['roles' => self::ROLES_META]);
    }

    public function submit(Request $request)
    {
        $request->validate([
            'role'         => 'required|in:guide,institution,researcher,partner',
            'name'         => 'required|string|max:255',
            'email'        => 'required|email|max:255',
            'phone'        => 'nullable|string|max:20',
            'organization' => 'nullable|string|max:255',
            'motivation'   => 'required|string|max:2000',
        ]);

        ActorRequest::create($request->only([
            'role', 'name', 'email', 'phone', 'organization', 'motivation',
        ]));

        return redirect()->route('actor-requests.confirmation');
    }

    public function confirmation()
    {
        return view('actor-requests.confirmation');
    }
}
