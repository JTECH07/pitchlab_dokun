@extends('admin.layouts.admin')
@section('title', 'Modération des Médias')
@section('page-title', 'Modération des Médias')

@section('content')
<div class="space-y-8" x-data="{ section: 'images', editingAudio: null }">

    <!-- Section Tabs -->
    <div class="flex items-center gap-2 bg-white rounded-xl p-1 border border-slate-200 w-fit shadow-sm">
        <button @click="section = 'images'" :class="section === 'images' ? 'bg-dokun-green text-white shadow' : 'text-slate-600 hover:bg-slate-100'" class="px-5 py-2.5 rounded-lg font-bold text-sm transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg> Images <span class="ml-1.5 text-xs opacity-80">({{ $counts['media']['pending'] }} en attente)</span>
        </button>
        <button @click="section = 'audio'" :class="section === 'audio' ? 'bg-dokun-green text-white shadow' : 'text-slate-600 hover:bg-slate-100'" class="px-5 py-2.5 rounded-lg font-bold text-sm transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"/></svg> Audio <span class="ml-1.5 text-xs opacity-80">({{ $counts['audio']['pending'] }} en attente)</span>
        </button>
    </div>

    <!-- ═══════════════════════════════════════════════════════════════════ -->
    <!-- IMAGES SECTION -->
    <!-- ═══════════════════════════════════════════════════════════════════ -->
    <div x-show="section === 'images'" x-transition>
        <div class="flex items-center gap-2 mb-5 flex-wrap">
            @foreach(['all' => 'Toutes', 'pending' => 'En attente', 'published' => 'Publiées', 'rejected' => 'Rejetées'] as $key => $label)
                <a href="?status={{ $key }}&audio_status={{ $audioStatusFilter }}"
                   class="px-4 py-2 rounded-lg text-sm font-bold transition-all {{ $statusFilter === $key ? 'bg-dokun-green text-white shadow-lg shadow-dokun-green/20' : 'bg-white text-slate-600 border border-slate-200 hover:border-dokun-green hover:text-dokun-green' }}">
                    {{ $label }}
                    @if($key !== 'all') <span class="ml-1 text-xs opacity-80">({{ $counts['media'][$key] }})</span> @endif
                </a>
            @endforeach
        </div>

        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
            @if($mediaItems->isEmpty())
            <div class="p-12 text-center">
                <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto text-slate-400 mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                <h3 class="text-lg font-bold text-slate-900 mb-1">Aucun média trouvé</h3>
                <p class="text-slate-500 text-sm">Les médias uploadés par les artisans apparaîtront ici.</p>
            </div>
            @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5 p-6">
                @foreach($mediaItems as $media)
                <div class="group relative bg-slate-50 rounded-xl overflow-hidden border border-slate-100 hover:border-dokun-gold/40 transition-all hover:shadow-md">
                    <div class="aspect-square bg-slate-100 relative">
                        @if($media->type === 'image')
                            <img src="{{ $media->url }}" alt="{{ $media->title }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-4xl text-slate-300"><svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg></div>
                        @endif
                        @if($media->status === 'published')
                            <span class="absolute top-2 left-2 inline-flex items-center gap-1 px-2 py-0.5 bg-emerald-500 text-white font-bold text-[10px] rounded-full"><svg class="w-2.5 h-2.5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg> Publié</span>
                        @elseif($media->status === 'pending')
                            <span class="absolute top-2 left-2 inline-flex items-center gap-1 px-2 py-0.5 bg-amber-500 text-white font-bold text-[10px] rounded-full"><svg class="w-2.5 h-2.5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg> En attente</span>
                        @else
                            <span class="absolute top-2 left-2 inline-flex items-center gap-1 px-2 py-0.5 bg-red-500 text-white font-bold text-[10px] rounded-full"><svg class="w-2.5 h-2.5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/></svg> Rejeté</span>
                        @endif
                    </div>
                    <div class="p-4">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-bold text-slate-500">{{ $media->artisan->first_name }} {{ $media->artisan->last_name }}</span>
                            <span class="text-[10px] text-slate-400">{{ $media->created_at->diffForHumans() }}</span>
                        </div>
                        <div class="flex items-center gap-2 mt-3">
                            @if($media->status !== 'published')
                            <form action="{{ route('admin.media.moderate', $media) }}" method="POST" class="flex-1">
                                @csrf @method('PATCH')
                                <input type="hidden" name="action" value="published">
                                <button type="submit" class="w-full px-3 py-2 bg-emerald-500 hover:bg-emerald-600 text-white text-xs font-bold rounded-lg transition-colors shadow-sm"><svg class="w-3 h-3 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg> Publier</button>
                            </form>
                            @endif
                            @if($media->status !== 'rejected')
                            <form action="{{ route('admin.media.moderate', $media) }}" method="POST" class="flex-1">
                                @csrf @method('PATCH')
                                <input type="hidden" name="action" value="rejected">
                                <button type="submit" class="w-full px-3 py-2 bg-red-500 hover:bg-red-600 text-white text-xs font-bold rounded-lg transition-colors shadow-sm"><svg class="w-3 h-3 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/></svg> Rejeter</button>
                            </form>
                            @endif
                            <form action="{{ route('admin.media.destroy', $media) }}" method="POST" onsubmit="return confirm('Supprimer ce média définitivement ?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="px-3 py-2 bg-slate-200 hover:bg-slate-300 text-slate-600 text-xs font-bold rounded-lg transition-colors" title="Supprimer"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @if($mediaItems->hasPages())
            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50">{{ $mediaItems->withQueryString() }}</div>
            @endif
            @endif
        </div>
    </div>

    <!-- ═══════════════════════════════════════════════════════════════════ -->
    <!-- AUDIO SECTION -->
    <!-- ═══════════════════════════════════════════════════════════════════ -->
    <div x-show="section === 'audio'" x-transition>
        <div class="flex items-center gap-2 mb-5 flex-wrap">
            @foreach(['all' => 'Toutes', 'pending' => 'En attente', 'published' => 'Publiées', 'rejected' => 'Rejetées'] as $key => $label)
                <a href="?status={{ $statusFilter }}&audio_status={{ $key }}"
                   class="px-4 py-2 rounded-lg text-sm font-bold transition-all {{ $audioStatusFilter === $key ? 'bg-dokun-green text-white shadow-lg shadow-dokun-green/20' : 'bg-white text-slate-600 border border-slate-200 hover:border-dokun-green hover:text-dokun-green' }}">
                    {{ $label }}
                    @if($key !== 'all') <span class="ml-1 text-xs opacity-80">({{ $counts['audio'][$key] }})</span> @endif
                </a>
            @endforeach
        </div>

        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
            @if($audioArchives->isEmpty())
            <div class="p-12 text-center">
                <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto text-slate-400 mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"/></svg>
                </div>
                <h3 class="text-lg font-bold text-slate-900 mb-1">Aucune archive vocale</h3>
                <p class="text-slate-500 text-sm">Les enregistrements vocaux des artisans apparaîtront ici.</p>
            </div>
            @else
            <div class="divide-y divide-slate-100">
                @foreach($audioArchives as $archive)
                <div class="p-5 hover:bg-slate-50/50 transition-colors" x-data="{ open: false }">
                    <div class="flex items-center gap-4">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-3 mb-1">
                                <span class="font-bold text-slate-900 text-sm">{{ $archive->first_name }} {{ $archive->last_name }}</span>
                                <span class="px-2 py-0.5 bg-slate-100 text-slate-600 text-[10px] font-bold rounded-full">{{ strtoupper($archive->language) }}</span>
                                @if($archive->status === 'published')
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-emerald-50 text-emerald-700 font-bold text-[10px] rounded-full border border-emerald-200">Publié</span>
                                @elseif($archive->status === 'pending')
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-amber-50 text-amber-700 font-bold text-[10px] rounded-full border border-amber-200">En attente</span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-red-50 text-red-700 font-bold text-[10px] rounded-full border border-red-200">Rejeté</span>
                                @endif
                                <span class="text-[10px] text-slate-400">{{ \Carbon\Carbon::parse($archive->created_at)->diffForHumans() }}</span>
                            </div>
                            <p class="text-xs text-slate-500 truncate max-w-lg">{{ $archive->transcription ?: 'Pas de transcription' }}</p>
                        </div>
                        <div class="flex items-center gap-2 flex-shrink-0">
                            <form action="{{ route('admin.media.moderate-audio', $archive->id) }}" method="POST">
                                @csrf @method('PATCH')
                                <input type="hidden" name="action" value="published">
                                <button type="submit" class="px-3 py-1.5 bg-emerald-500 hover:bg-emerald-600 text-white text-xs font-bold rounded-lg transition-colors"><svg class="w-3 h-3 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg></button>
                            </form>
                            <form action="{{ route('admin.media.moderate-audio', $archive->id) }}" method="POST">
                                @csrf @method('PATCH')
                                <input type="hidden" name="action" value="rejected">
                                <button type="submit" class="px-3 py-1.5 bg-red-500 hover:bg-red-600 text-white text-xs font-bold rounded-lg transition-colors"><svg class="w-3 h-3 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/></svg></button>
                            </form>
                            <button @click="open = !open" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-bold rounded-lg transition-colors"><svg class="w-3 h-3 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg> Éditer</button>
                        </div>
                    </div>

                    {{-- Editable form (collapsible) --}}
                    <div x-show="open" x-transition class="mt-4 p-4 bg-slate-50 rounded-xl border border-slate-200">
                        @if($archive->audio_path)
                        <div class="mb-4 p-3 bg-white rounded-xl border border-slate-200">
                            <label class="text-[10px] font-bold text-slate-500 uppercase mb-2 block">Écouter l'archive</label>
                            <audio controls class="w-full h-10" style="filter: sepia(20%) saturate(70%) hue-rotate(110deg);">
                                <source src="{{ asset('storage/' . $archive->audio_path) }}" type="audio/mpeg">
                                Votre navigateur ne supporte pas l'élément audio.
                            </audio>
                        </div>
                        @endif
                        <form action="{{ route('admin.media.update-audio', $archive->id) }}" method="POST" class="space-y-3">
                            @csrf @method('PATCH')
                            <div>
                                <label class="text-[10px] font-bold text-slate-500 uppercase">Langue</label>
                                <select name="language" class="w-full mt-1 px-3 py-2 bg-white border border-slate-200 rounded-lg text-sm focus:ring-1 focus:ring-dokun-green focus:border-dokun-green outline-none">
                                    @foreach(['fon' => 'Fon/Gun', 'fr' => 'Français', 'en' => 'Anglais', 'yoruba' => 'Yoruba'] as $val => $lbl)
                                        <option value="{{ $val }}" {{ $archive->language === $val ? 'selected' : '' }}>{{ $lbl }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="text-[10px] font-bold text-slate-500 uppercase">Transcription (langue originale)</label>
                                <textarea name="transcription" rows="2" class="w-full mt-1 px-3 py-2 bg-white border border-slate-200 rounded-lg text-sm focus:ring-1 focus:ring-dokun-green focus:border-dokun-green outline-none resize-none">{{ $archive->transcription }}</textarea>
                            </div>
                            <div>
                                <label class="text-[10px] font-bold text-slate-500 uppercase">Traduction Français</label>
                                <textarea name="translation_fr" rows="2" class="w-full mt-1 px-3 py-2 bg-white border border-slate-200 rounded-lg text-sm focus:ring-1 focus:ring-dokun-green focus:border-dokun-green outline-none resize-none">{{ $archive->translation_fr }}</textarea>
                            </div>
                            <div>
                                <label class="text-[10px] font-bold text-slate-500 uppercase">Traduction English</label>
                                <textarea name="translation_en" rows="2" class="w-full mt-1 px-3 py-2 bg-white border border-slate-200 rounded-lg text-sm focus:ring-1 focus:ring-dokun-green focus:border-dokun-green outline-none resize-none">{{ $archive->translation_en }}</textarea>
                            </div>
                            <div class="flex justify-end gap-2">
                                <button type="button" @click="open = false" class="px-4 py-2 text-xs font-bold text-slate-500 hover:text-slate-700">Annuler</button>
                                <button type="submit" class="px-5 py-2 bg-dokun-green hover:bg-dokun-green/90 text-white text-xs font-bold rounded-lg transition-colors shadow-sm">Enregistrer</button>
                            </div>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
            @if($audioArchives->hasPages())
            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50">{{ $audioArchives->withQueryString('audio_page') }}</div>
            @endif
            @endif
        </div>
    </div>

</div>
@endsection
