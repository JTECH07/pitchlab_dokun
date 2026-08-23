<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class UserAdminController extends Controller
{
    public function index(): View
    {
        $users = User::withCount('reservations')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role'     => ['required', 'in:'.implode(',', User::ROLES)],
        ]);

        $user = new User;
        $user->forceFill([
            'name'              => $validated['name'],
            'email'             => $validated['email'],
            'password'          => Hash::make($validated['password']),
            'role'              => $validated['role'],
            'email_verified_at' => now(), // créé par l'admin : vérification immédiate
        ])->save();

        return back()->with('success', "Compte {$user->role} créé pour {$user->name}.");
    }

    public function updateRole(Request $request, User $user): RedirectResponse
    {
        $request->validate(['role' => ['required', 'in:'.implode(',', User::ROLES)]]);

        if ($user->id === $request->user()->id && $request->role !== 'admin') {
            return back()->with('error', 'Vous ne pouvez pas retirer votre propre rôle administrateur.');
        }

        $user->update(['role' => $request->input('role')]);

        return back()->with('success', "{$user->name} est maintenant {$user->role}.");
    }

    public function verifyEmail(Request $request, User $user): RedirectResponse
    {
        $user->markEmailAsVerified();

        return back()->with('success', "Email de {$user->name} marqué comme vérifié.");
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        if ($user->id === $request->user()->id) {
            return back()->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }

        if ($user->isAdmin() && User::where('role', 'admin')->count() <= 1) {
            return back()->with('error', 'Impossible de supprimer le dernier administrateur.');
        }

        $user->delete();

        return back()->with('success', 'Compte supprimé.');
    }
}
