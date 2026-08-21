<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Quartier;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class QuartierAdminController extends Controller
{
    public function index()
    {
        $quartiers = Quartier::orderBy('sort_order')->orderBy('name')->get();
        return view('admin.quartiers.index', compact('quartiers'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'       => 'required|string|max:255',
            'lat'        => 'required|numeric|between:-90,90',
            'lng'        => 'required|numeric|between:-180,180',
            'radius_km'  => 'nullable|numeric|min:0|max:5',
            'color'      => 'nullable|string|max:7',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $data['slug'] = Str::slug($data['name']);
        $data['radius_km'] = $data['radius_km'] ?? 0.4;
        $data['color'] = $data['color'] ?? '#064E3B';
        $data['sort_order'] = $data['sort_order'] ?? 0;

        Quartier::create($data);

        return back()->with('success', "Quartier « {$data['name']} » créé avec succès.");
    }

    public function update(Request $request, Quartier $quartier)
    {
        $data = $request->validate([
            'name'       => 'required|string|max:255',
            'lat'        => 'required|numeric|between:-90,90',
            'lng'        => 'required|numeric|between:-180,180',
            'radius_km'  => 'nullable|numeric|min:0|max:5',
            'color'      => 'nullable|string|max:7',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $data['slug'] = Str::slug($data['name']);
        $quartier->update($data);

        return back()->with('success', "Quartier « {$quartier->name } » mis à jour.");
    }

    public function destroy(Quartier $quartier)
    {
        $quartier->delete();
        return back()->with('success', 'Quartier supprimé.');
    }
}
