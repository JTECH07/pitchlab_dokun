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
            'status'            => 'required|in:draft,pending,published',
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
            ->with('success', "Artisan {$artisan->first_name} {$artisan->last_name} créé avec succès. Mot de passe provisoire : dokun2026");
    }

    public function toggleStatus(Artisan $artisan)
    {
        $newStatus = $artisan->status === 'published' ? 'draft' : 'published';
        $artisan->update(['status' => $newStatus]);
        return back()->with('success', "Statut de {$artisan->first_name} mis à jour.");
    }

    public function edit(Artisan $artisan)
    {
        $artisan->load('savoirFaires', 'user');
        $categories  = Category::with('savoirFaires')->get();
        $savoirFaires = SavoirFaire::with('category')->get();
        return view('admin.artisans.edit', compact('artisan', 'categories', 'savoirFaires'));
    }

    public function update(Request $request, Artisan $artisan)
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
            'status'            => 'required|in:draft,pending,published',
            'email'             => 'required|email|unique:users,email,' . $artisan->user_id,
            'savoir_faires'     => 'nullable|array',
        ]);

        if ($artisan->user) {
            $artisan->user->update([
                'name'  => $validated['first_name'] . ' ' . $validated['last_name'],
                'email' => $validated['email'],
            ]);
        }

        $artisan->update([
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

        $artisan->savoirFaires()->sync($validated['savoir_faires'] ?? []);

        return redirect()->route('admin.artisans.index')
            ->with('success', "Profil de {$artisan->first_name} {$artisan->last_name} mis à jour avec succès.");
    }

    public function destroy(Artisan $artisan)
    {
        $name = "{$artisan->first_name} {$artisan->last_name}";
        if ($artisan->user) {
            $artisan->user->delete();
        } else {
            $artisan->delete();
        }
        return redirect()->route('admin.artisans.index')
            ->with('success', "Artisan {$name} supprimé avec succès.");
    }

    public function approveProfile(Artisan $artisan)
    {
        if ($artisan->pending_profile_data) {
            $pending = $artisan->pending_profile_data;
            $updates = [
                'first_name'        => $pending['first_name'] ?? $artisan->first_name,
                'last_name'         => $pending['last_name'] ?? $artisan->last_name,
                'professional_name' => $pending['professional_name'] ?? $artisan->professional_name,
                'phone'             => $pending['phone'] ?? $artisan->phone,
                'whatsapp'          => $pending['whatsapp'] ?? $artisan->whatsapp,
                'description'       => $pending['description'] ?? $artisan->description,
                'history'           => $pending['history'] ?? $artisan->history,
                'experience_years'  => $pending['experience_years'] ?? $artisan->experience_years,
                'address'           => $pending['address'] ?? $artisan->address,
                'latitude'          => $pending['latitude'] ?? $artisan->latitude,
                'longitude'         => $pending['longitude'] ?? $artisan->longitude,
                'status'            => 'published',
                'pending_profile_data' => null,
            ];

            if ($artisan->pending_photo_path) {
                $updates['photo_path'] = $artisan->pending_photo_path;
                $updates['pending_photo_path'] = null;
            }

            $artisan->update($updates);

            if (isset($pending['savoir_faires'])) {
                $artisan->savoirFaires()->sync($pending['savoir_faires']);
            }
        } else {
            $artisan->update(['status' => 'published']);
        }

        return back()->with('success', "Profil de {$artisan->first_name} {$artisan->last_name} approuvé et publié.");
    }

    public function rejectProfile(Artisan $artisan)
    {
        $artisan->update([
            'status' => 'published',
            'pending_profile_data' => null,
            'pending_photo_path' => null,
        ]);

        return back()->with('success', "Modifications de {$artisan->first_name} {$artisan->last_name} rejetées.");
    }
}
