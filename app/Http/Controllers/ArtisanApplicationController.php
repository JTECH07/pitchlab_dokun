<?php

namespace App\Http\Controllers;

use App\Models\ArtisanApplication;
use App\Models\Category;
use App\Models\SavoirFaire;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class ArtisanApplicationController extends Controller
{
    public function showForm()
    {
        $existing = ArtisanApplication::where('user_id', Auth::id())->first();

        return view('artisan.apply', [
            'categories'   => Category::orderBy('name')->get(),
            'savoirFaires' => SavoirFaire::orderBy('name')->get(),
            'existing'     => $existing,
            'user'         => Auth::user(),
        ]);
    }

    public function submit(Request $request)
    {
        $existing = ArtisanApplication::where('user_id', Auth::id())->first();

        if ($existing && in_array($existing->status, ['pending'])) {
            throw ValidationException::withMessages([
                'email' => 'Votre candidature est déjà en cours d\'examen.',
            ]);
        }

        $data = $request->validate([
            'first_name'       => 'required|string|max:255',
            'last_name'        => 'required|string|max:255',
            'professional_name'=> 'nullable|string|max:255',
            'phone'            => 'required|string|max:20',
            'whatsapp'         => 'nullable|string|max:20',
            'description'      => 'required|string|max:2000',
            'history'          => 'nullable|string|max:3000',
            'experience_years' => 'required|integer|min:0|max:80',
            'address'          => 'required|string|max:500',
            'category_id'      => 'required|exists:categories,id',
            'trade'            => 'nullable|string|max:255',
        ]);

        $application = ArtisanApplication::updateOrCreate(
            ['user_id' => Auth::id()],
            array_merge($data, ['status' => 'pending'])
        );

        return redirect()->route('artisan.apply.confirmation');
    }

    public function confirmation()
    {
        $application = ArtisanApplication::where('user_id', Auth::id())->firstOrFail();

        return view('artisan.application-confirmation', ['application' => $application]);
    }
}
