<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            // Inscription publique : visiteur ou artisan uniquement.
            // Les autres rôles (guide, institution, admin...) sont attribués par un administrateur.
            'role' => ['nullable', 'in:'.implode(',', User::PUBLIC_ROLES)],
        ]);

        $wantsArtisan = in_array($request->input('role'), User::PUBLIC_ROLES, true)
            && $request->input('role') === 'artisan';

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'tourist', // artisan → d'abord visiteur, puis candidature validée par admin
        ]);

        event(new Registered($user));

        Auth::login($user);

        // Artisan : après vérification email, rediriger vers /devenir-artisan
        if ($wantsArtisan) {
            $request->session()->put('pending_artisan', true);
        }

        $request->session()->put('email', $user->email);
        return redirect()->intended(route('verification.notice'));
    }

    public function updateEmail(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
        ]);

        $user = $request->user();
        $user->forceFill(['email' => $request->input('email')])->save();
        $user->sendEmailVerificationNotification();

        return back()->with('status', 'verification-link-sent');
    }
}
