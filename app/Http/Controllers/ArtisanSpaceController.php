<?php

namespace App\Http\Controllers;

use App\Models\Artisan;
use App\Models\ReservationRequest;
use App\Models\SavoirFaire;
use Illuminate\Http\Request;

class ArtisanSpaceController extends Controller
{
    public function editProfile(Request $request)
    {
        $artisan = Artisan::with('savoirFaires')->where('user_id', $request->user()->id)->firstOrFail();
        $savoirFaires = SavoirFaire::all();
        $quartiers = \App\Models\Quartier::orderBy('sort_order')->orderBy('name')->get();

        return view('artisan-space.edit-profile', compact('artisan', 'savoirFaires', 'quartiers'));
    }

    public function updateProfile(Request $request)
    {
        $artisan = Artisan::where('user_id', $request->user()->id)->firstOrFail();

        $data = $request->validate([
            'first_name'         => 'required|string|max:255',
            'last_name'          => 'required|string|max:255',
            'professional_name'  => 'nullable|string|max:255',
            'phone'              => 'required|string|max:255',
            'whatsapp'           => 'nullable|string|max:255',
            'description'        => 'nullable|string',
            'history'            => 'nullable|string',
            'experience_years'   => 'nullable|integer|min:0',
            'address'            => 'required|string|max:255',
            'latitude'           => 'nullable|numeric',
            'longitude'          => 'nullable|numeric',
            'quartier'           => 'nullable|string|exists:quartiers,slug',
            'savoir_faires'      => 'nullable|array',
            'savoir_faires.*'    => 'exists:savoir_faires,id',
        ]);

        // Si un quartier est choisi et qu'aucune coordonnée précise n'existe,
        // on positionne l'artisan au centre du quartier (localisation exacte garantie).
        if (!empty($data['quartier']) && (empty($data['latitude']) || empty($data['longitude']))) {
            $quartier = \App\Models\Quartier::where('slug', $data['quartier'])->first();
            if ($quartier) {
                $data['latitude'] = $quartier->lat;
                $data['longitude'] = $quartier->lng;
            }
        }
        unset($data['quartier']);

        $data['savoir_faires'] = $data['savoir_faires'] ?? [];

        $artisan->update([
            'pending_profile_data' => $data,
            'status' => 'pending',
        ]);

        return back()->with('success', 'Votre profil a été soumis pour validation. Les modifications seront visibles après approbation par un administrateur.');
    }

    public function index(Request $request)
    {
        $artisan = Artisan::where('user_id', $request->user()->id)->first();
        if (! $artisan) {
            return view('artisan-space.index', compact('artisan'))->with('notice', 'Votre profil est en cours de création.');
        }

        $stats = [
            'pending' => ReservationRequest::where('artisan_id', $artisan->id)->where('status', 'pending')->count(),
            'accepted' => ReservationRequest::where('artisan_id', $artisan->id)->where('status', 'accepted')->count(),
            'completed' => ReservationRequest::where('artisan_id', $artisan->id)->where('status', 'completed')->count(),
        ];

        $reservations = ReservationRequest::with('experience')
            ->where('artisan_id', $artisan->id)
            ->latest()
            ->get();

        return view('artisan-space.index', compact('artisan', 'stats', 'reservations'));
    }

    public function updateReservation(Request $request, ReservationRequest $reservation)
    {
        abort_unless($reservation->artisan?->user_id === $request->user()->id, 403);
        $data = $request->validate(['status' => 'required|in:accepted,rejected,completed']);
        $reservation->update($data);

        return back()->with('success', 'La réservation a été mise à jour.');
    }

    public function updateReservationStatus(Request $request, ReservationRequest $reservation)
    {
        abort_unless($reservation->artisan?->user_id === $request->user()->id, 403);
        $data = $request->validate(['status' => 'required|in:accepted,rejected,completed']);
        $reservation->update($data);

        return response()->json(['status' => 'success', 'reservation' => $reservation->fresh()]);
    }

    public function uploadPhoto(Request $request)
    {
        $artisan = Artisan::where('user_id', $request->user()->id)->firstOrFail();

        $request->validate([
            'photo' => 'required|file|image|mimes:jpeg,png,webp,gif|max:5120',
        ]);

        $file = $request->file('photo');
        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $file->storeAs('photos', $filename, 'public');
        $path = 'photos/' . $filename;

        $artisan->pending_photo_path = $path;

        if (!$artisan->photo_path) {
            $artisan->photo_path = $path;
        }

        $artisan->save();

        return response()->json([
            'status' => 'success',
            'url' => asset('storage/' . $path),
            'photo_path' => $artisan->photo_path,
            'pending_photo_path' => $artisan->pending_photo_path,
        ]);
    }
}
