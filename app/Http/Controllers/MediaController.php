<?php

namespace App\Http\Controllers;

use App\Models\Artisan;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class MediaController extends Controller
{
    public function store(Request $request)
    {
        $artisan = Artisan::where('user_id', $request->user()->id)->first();
        abort_unless($artisan, 403);

        $validated = $request->validate([
            'file'  => 'required|file|image|mimes:jpeg,png,gif,webp|max:5120',
            'title' => 'nullable|string|max:120',
        ]);

        $file = $request->file('file');
        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $dest = public_path('media');
        File::makeDirectory($dest, 0755, true, true);
        $file->move($dest, $filename);
        $path = 'media/' . $filename;

        $media = Media::create([
            'artisan_id' => $artisan->id,
            'type'       => 'image',
            'path'       => $path,
            'title'      => $validated['title'] ?? null,
            'status'     => 'pending',
        ]);

        return response()->json([
            'status'  => 'success',
            'media'   => $media,
            'url'     => $media->url,
        ]);
    }

    public function destroy(Request $request, Media $media)
    {
        $artisan = Artisan::where('user_id', $request->user()->id)->first();
        abort_unless($artisan && $media->artisan_id === $artisan->id, 403);

        if ($media->path && file_exists(public_path($media->path))) {
            @unlink(public_path($media->path));
        }

        $media->delete();

        return response()->json(['status' => 'success']);
    }
}
