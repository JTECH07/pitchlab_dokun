<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\SavoirFaire;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SavoirFaireAdminController extends Controller
{
    public function index()
    {
        $savoirFaires = SavoirFaire::with('category')->withCount('artisans')->latest()->paginate(15);
        return view('admin.savoir_faires.index', compact('savoirFaires'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.savoir_faires.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name'        => 'required|string|max:100|unique:savoir_faires,name',
            'description' => 'required|string',
        ]);

        SavoirFaire::create([
            'category_id' => $validated['category_id'],
            'name'        => $validated['name'],
            'slug'        => Str::slug($validated['name']),
            'description' => $validated['description'],
        ]);

        return redirect()->route('admin.savoir-faires.index')
            ->with('success', "Savoir-Faire '{$validated['name']}' ajouté avec succès !");
    }

    public function edit(SavoirFaire $savoirFaire)
    {
        $categories = Category::all();
        return view('admin.savoir_faires.edit', compact('savoirFaire', 'categories'));
    }

    public function update(Request $request, SavoirFaire $savoirFaire)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name'        => 'required|string|max:100|unique:savoir_faires,name,' . $savoirFaire->id,
            'description' => 'required|string',
        ]);

        $savoirFaire->update([
            'category_id' => $validated['category_id'],
            'name'        => $validated['name'],
            'slug'        => Str::slug($validated['name']),
            'description' => $validated['description'],
        ]);

        return redirect()->route('admin.savoir-faires.index')
            ->with('success', "Savoir-Faire '{$validated['name']}' mis à jour !");
    }

    public function destroy(SavoirFaire $savoirFaire)
    {
        $name = $savoirFaire->name;
        $savoirFaire->delete();

        return redirect()->route('admin.savoir-faires.index')
            ->with('success', "Savoir-Faire '{$name}' supprimé.");
    }
}
