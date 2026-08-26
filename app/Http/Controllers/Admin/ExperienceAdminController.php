<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Artisan;
use App\Models\Experience;
use Illuminate\Http\Request;

class ExperienceAdminController extends Controller
{
    public function index()
    {
        $experiences = Experience::with('artisan')->latest()->paginate(15);
        return view('admin.experiences.index', compact('experiences'));
    }

    public function create()
    {
        $artisans = Artisan::where('status', 'published')->orderBy('first_name')->get();
        return view('admin.experiences.create', compact('artisans'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'artisan_id'        => 'required|exists:artisans,id',
            'title'             => 'required|string|max:255',
            'summary'           => 'nullable|string|max:2000',
            'duration_minutes'  => 'required|integer|min:15|max:1440',
            'capacity'          => 'required|integer|min:1|max:50',
            'price'             => 'required|numeric|min:0',
            'currency'          => 'nullable|string|max:5',
            'language'          => 'nullable|string|max:50',
            'image_path'        => 'nullable|string|max:500',
            'is_published'      => 'boolean',
        ]);

        $validated['is_published'] = $request->boolean('is_published');
        Experience::create($validated);

        return redirect()->route('admin.experiences.index')->with('success', 'Expérience créée avec succès.');
    }

    public function edit(Experience $experience)
    {
        $artisans = Artisan::where('status', 'published')->orderBy('first_name')->get();
        return view('admin.experiences.edit', compact('experience', 'artisans'));
    }

    public function update(Request $request, Experience $experience)
    {
        $validated = $request->validate([
            'artisan_id'        => 'required|exists:artisans,id',
            'title'             => 'required|string|max:255',
            'summary'           => 'nullable|string|max:2000',
            'duration_minutes'  => 'required|integer|min:15|max:1440',
            'capacity'          => 'required|integer|min:1|max:50',
            'price'             => 'required|numeric|min:0',
            'currency'          => 'nullable|string|max:5',
            'language'          => 'nullable|string|max:50',
            'image_path'        => 'nullable|string|max:500',
            'is_published'      => 'boolean',
        ]);

        $validated['is_published'] = $request->boolean('is_published');
        $experience->update($validated);

        return redirect()->route('admin.experiences.index')->with('success', 'Expérience mise à jour.');
    }

    public function destroy(Experience $experience)
    {
        $experience->delete();
        return back()->with('success', 'Expérience supprimée.');
    }

    public function toggle(Experience $experience)
    {
        $experience->update(['is_published' => !$experience->is_published]);
        $status = $experience->is_published ? 'publiée' : 'désactivée';
        return back()->with('success', "Expérience {$status}.");
    }
}
