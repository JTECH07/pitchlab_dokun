<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Artisan;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MediaAdminController extends Controller
{
    public function index(Request $request)
    {
        $statusFilter = $request->get('status', 'all');

        $mediaQuery = Media::with('artisan')->latest();
        if ($statusFilter !== 'all') {
            $mediaQuery->where('status', $statusFilter);
        }
        $mediaItems = $mediaQuery->paginate(20);

        $audioQuery = DB::table('dokun_audio_archives')->join('artisans', 'artisans.id', '=', 'dokun_audio_archives.artisan_id');
        $audioStatusFilter = $request->get('audio_status', 'all');
        if ($audioStatusFilter !== 'all') {
            $audioQuery->where('dokun_audio_archives.status', $audioStatusFilter);
        }
        $audioArchives = $audioQuery->select('dokun_audio_archives.*', 'artisans.first_name', 'artisans.last_name')
            ->orderByDesc('dokun_audio_archives.created_at')
            ->paginate(20, ['*'], 'audio_page');

        $counts = [
            'media' => [
                'all'       => Media::count(),
                'pending'   => Media::where('status', 'pending')->count(),
                'published' => Media::where('status', 'published')->count(),
                'rejected'  => Media::where('status', 'rejected')->count(),
            ],
            'audio' => [
                'all'       => DB::table('dokun_audio_archives')->count(),
                'pending'   => DB::table('dokun_audio_archives')->where('status', 'pending')->count(),
                'published' => DB::table('dokun_audio_archives')->where('status', 'published')->count(),
                'rejected'  => DB::table('dokun_audio_archives')->where('status', 'rejected')->count(),
            ],
        ];

        return view('admin.media.index', compact('mediaItems', 'audioArchives', 'counts', 'statusFilter', 'audioStatusFilter'));
    }

    public function moderate(Request $request, Media $media)
    {
        $validated = $request->validate(['action' => 'required|in:published,rejected']);
        $media->update([
            'status'       => $validated['action'],
            'moderated_by' => $request->user()->id,
            'moderated_at' => now(),
        ]);

        return back()->with('success', 'Média ' . ($validated['action'] === 'published' ? 'publié' : 'rejeté') . ' avec succès.');
    }

    public function moderateAudio(Request $request, $archiveId)
    {
        $validated = $request->validate(['action' => 'required|in:published,rejected']);
        DB::table('dokun_audio_archives')->where('id', $archiveId)->update([
            'status'       => $validated['action'],
            'moderated_by' => $request->user()->id,
            'moderated_at' => now(),
        ]);

        return back()->with('success', 'Archive vocale ' . ($validated['action'] === 'published' ? 'publiée' : 'rejetée') . ' avec succès.');
    }

    public function updateAudio(Request $request, $archiveId)
    {
        $archive = DB::table('dokun_audio_archives')->where('id', $archiveId)->first();
        abort_unless($archive, 404, 'Archive introuvable.');

        $data = $request->validate([
            'language'        => 'nullable|string|max:20',
            'transcription'   => 'nullable|string',
            'translation_fr'  => 'nullable|string',
            'translation_en'  => 'nullable|string',
        ]);

        $updates = array_filter($data, fn($v) => $v !== null);
        $updates['updated_at'] = now();
        DB::table('dokun_audio_archives')->where('id', $archiveId)->update($updates);

        return back()->with('success', 'Archive vocale mise à jour avec succès.');
    }

    public function destroyMedia(Request $request, Media $media)
    {
        $diskPath = public_path($media->path);
        if ($media->path && file_exists($diskPath)) {
            @unlink($diskPath);
        }
        $media->delete();

        return back()->with('success', 'Média supprimé avec succès.');
    }
}
