<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Artisan;
use App\Models\ArtisanApplication;
use App\Models\User;
use App\Notifications\ApplicationApprovedNotification;
use App\Notifications\ApplicationRejectedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminArtisanApplicationController extends Controller
{
    public function index()
    {
        $applications = ArtisanApplication::with(['user', 'category'])
            ->latest()
            ->paginate(15);

        return view('admin.applications.index', ['applications' => $applications]);
    }

    public function show(ArtisanApplication $application)
    {
        $application->load(['user', 'category']);

        return view('admin.applications.show', ['application' => $application]);
    }

    public function approve(Request $request, ArtisanApplication $application)
    {
        $application->update([
            'status'      => 'approved',
            'admin_notes' => $request->input('admin_notes'),
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
        ]);

        // Mettre à jour le rôle de l'utilisateur en artisan + mdp temporaire
        $tempPassword = Str::random(12);
        $user = $application->user;
        $user->update([
            'role' => 'artisan',
            'password' => Hash::make($tempPassword),
        ]);

        // Créer le profil artisan
        Artisan::create([
            'user_id'         => $user->id,
            'first_name'      => $application->first_name,
            'last_name'       => $application->last_name,
            'professional_name'=> $application->professional_name ?? '',
            'phone'           => $application->phone,
            'whatsapp'        => $application->whatsapp ?? '',
            'description'     => $application->description,
            'history'         => $application->history ?? '',
            'experience_years'=> $application->experience_years,
            'address'         => $application->address,
            'category_id'     => $application->category_id,
            'status'          => 'published',
        ]);

        // Envoyer l'email de notification avec le mot de passe temporaire
        $user->notify(new ApplicationApprovedNotification($application, $tempPassword));

        return back()->with('success', "Candidature approuvée. L'artisan a reçu un email de bienvenue.");
    }

    public function reject(Request $request, ArtisanApplication $application)
    {
        $application->update([
            'status'      => 'rejected',
            'admin_notes' => $request->input('admin_notes'),
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
        ]);

        $application->user->notify(new ApplicationRejectedNotification($application));

        return back()->with('success', "Candidature rejetée. L'utilisateur a été notifié par email.");
    }
}
