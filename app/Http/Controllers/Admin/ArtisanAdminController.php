<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Artisan;
use App\Models\Category;
use App\Models\SavoirFaire;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ArtisanAdminController extends Controller
{
    public function index()
    {
        $artisans = Artisan::with('savoirFaires', 'user')->latest()->paginate(15);
        return view('admin.artisans.index', compact('artisans'));
    }

    public function create()
    {
        $categories  = Category::with('savoirFaires')->get();
        $savoirFaires = SavoirFaire::with('category')->get();
        return view('admin.artisans.create', compact('categories', 'savoirFaires'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name'        => 'required|string|max:100',
            'last_name'         => 'required|string|max:100',
            'professional_name' => 'nullable|string|max:200',
            'phone'             => 'required|string|max:30',
            'whatsapp'          => 'nullable|string|max:30',
            'description'       => 'nullable|string',
            'history'           => 'nullable|string',
            'experience_years'  => 'nullable|integer|min:0|max:100',
            'address'           => 'nullable|string|max:255',
            'latitude'          => 'nullable|numeric',
            'longitude'         => 'nullable|numeric',
            'status'            => 'required|in:draft,published,suspended',
            'email'             => 'required|email|unique:users,email',
            'savoir_faires'     => 'nullable|array',
        ]);

        // Create user account for artisan
        $user = User::create([
            'name'     => $validated['first_name'] . ' ' . $validated['last_name'],
            'email'    => $validated['email'],
            'password' => Hash::make('dokun2026'),
            'role'     => 'artisan',
        ]);

        // Create artisan profile
        $artisan = Artisan::create([
            'user_id'           => $user->id,
            'first_name'        => $validated['first_name'],
            'last_name'         => $validated['last_name'],
            'professional_name' => $validated['professional_name'],
            'phone'             => $validated['phone'],
            'whatsapp'          => $validated['whatsapp'] ?? $validated['phone'],
            'description'       => $validated['description'],
            'history'           => $validated['history'],
            'experience_years'  => $validated['experience_years'] ?? 0,
            'address'           => $validated['address'],
            'latitude'          => $validated['latitude'],
            'longitude'         => $validated['longitude'],
            'status'            => $validated['status'],
        ]);

        if (!empty($validated['savoir_faires'])) {
            $artisan->savoirFaires()->sync($validated['savoir_faires']);
        }

        return redirect()->route('admin.artisans.index')
            ->with('success', "✅ Artisan {$artisan->first_name} {$artisan->last_name} créé avec succès ! Mot de passe provisoire : dokun2026");
    }

    public function toggleStatus(Artisan $artisan)
    {
        $newStatus = $artisan->status === 'published' ? 'suspended' : 'published';
        $artisan->update(['status' => $newStatus]);
        return back()->with('success', "Statut de {$artisan->first_name} mis à jour.");
    }
}
