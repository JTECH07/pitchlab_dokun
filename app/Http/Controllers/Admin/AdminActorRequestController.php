<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActorRequest;
use App\Models\User;
use App\Notifications\ActorRequestApprovedNotification;
use App\Notifications\ActorRequestRejectedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminActorRequestController extends Controller
{
    public function index()
    {
        $requests = ActorRequest::latest()->paginate(15);

        return view('admin.actor-requests.index', ['requests' => $requests]);
    }

    public function show(ActorRequest $actorRequest)
    {
        return view('admin.actor-requests.show', ['request' => $actorRequest]);
    }

    public function approve(Request $request, ActorRequest $actorRequest)
    {
        $tempPassword = Str::random(12);

        $user = User::create([
            'name'     => $actorRequest->name,
            'email'    => $actorRequest->email,
            'password' => Hash::make($tempPassword),
            'role'     => $actorRequest->role,
        ]);

        $user->sendEmailVerificationNotification();

        $actorRequest->update([
            'status'      => 'approved',
            'admin_notes' => $request->input('admin_notes'),
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
        ]);

        $actorRequest->notify(new ActorRequestApprovedNotification($actorRequest, $tempPassword));

        return back()->with('success', "Compte créé pour {$actorRequest->name} ({$actorRequest->role}). Un email de vérification a été envoyé. Mot de passe temporaire communiqué par email.");
    }

    public function reject(Request $request, ActorRequest $actorRequest)
    {
        $actorRequest->update([
            'status'      => 'rejected',
            'admin_notes' => $request->input('admin_notes'),
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
        ]);

        $actorRequest->notify(new ActorRequestRejectedNotification($actorRequest));

        return back()->with('success', "Demande rejetée. L'utilisateur a été notifié par email.");
    }
}
