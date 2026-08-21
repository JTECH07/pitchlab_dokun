@extends('admin.layouts.admin')
@section('title', 'Gestion des Artisans')
@section('page-title', 'Gestion des Artisans')

@section('content')
<div class="space-y-6">
    <!-- Action Header -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-extrabold text-slate-900">Artisans Référencés</h2>
            <p class="text-sm text-slate-500 font-medium mt-1">Gérez le catalogue des artisans de Porto-Novo et leurs statuts.</p>
        </div>
        <a href="{{ route('admin.artisans.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-amber-500 hover:bg-amber-600 text-white font-bold rounded-xl shadow-lg shadow-amber-500/20 transition-all">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
            Ajouter un artisan
        </a>
    </div>

    <!-- Table Container -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        @if($artisans->isEmpty())
        <div class="p-12 text-center">
            <div class="w-16 h-16 bg-amber-50 rounded-full flex items-center justify-center mx-auto text-amber-500 mb-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </div>
            <h3 class="text-lg font-bold text-slate-900 mb-1">Aucun artisan trouvé</h3>
            <p class="text-slate-500 text-sm mb-6">Commencez par ajouter le premier artisan à la plateforme.</p>
            <a href="{{ route('admin.artisans.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-amber-500 text-white font-bold rounded-xl hover:bg-amber-600 transition-colors">
                Ajouter un artisan
            </a>
        </div>
        @else
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200/80 text-xs font-bold text-slate-500 uppercase tracking-wider">
                        <th class="px-6 py-4">Artisan & Atelier</th>
                        <th class="px-6 py-4">Contact</th>
                        <th class="px-6 py-4">Savoir-Faire</th>
                        <th class="px-6 py-4">Statut</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @foreach($artisans as $artisan)
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-amber-100 text-amber-800 rounded-full flex items-center justify-center font-bold text-sm shrink-0">
                                    {{ substr($artisan->first_name, 0, 1) }}{{ substr($artisan->last_name, 0, 1) }}
                                </div>
                                <div>
                                    <span class="font-bold text-slate-900 block">{{ $artisan->first_name }} {{ $artisan->last_name }}</span>
                                    <span class="text-xs text-slate-500 font-medium">{{ $artisan->professional_name ?: 'Indépendant' }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="space-y-0.5">
                                <p class="text-slate-700 font-semibold text-xs flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                    {{ $artisan->phone }}
                                </p>
                                @if($artisan->user)
                                <p class="text-xs text-slate-400 truncate">{{ $artisan->user->email }}</p>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-wrap gap-1.5 max-w-xs">
                                @forelse($artisan->savoirFaires as $sf)
                                <span class="px-2.5 py-0.5 bg-slate-100 text-slate-700 font-medium text-xs rounded-full border border-slate-200">
                                    {{ $sf->name }}
                                </span>
                                @empty
                                <span class="text-xs text-slate-400 italic">Aucun</span>
                                @endforelse
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @if($artisan->status === 'pending' && $artisan->pending_profile_data)
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-amber-50 text-amber-700 font-bold text-xs rounded-full border border-amber-200">
                                <span class="w-1.5 h-1.5 bg-amber-500 rounded-full"></span> Profil à valider
                            </span>
                            @elseif($artisan->status === 'published')
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-emerald-50 text-emerald-700 font-bold text-xs rounded-full border border-emerald-200">
                                <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span> Publié
                            </span>
                            @elseif($artisan->status === 'draft')
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-slate-100 text-slate-700 font-bold text-xs rounded-full border border-slate-200">
                                <span class="w-1.5 h-1.5 bg-slate-500 rounded-full"></span> Brouillon
                            </span>
                            @else
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-red-50 text-red-700 font-bold text-xs rounded-full border border-red-200">
                                <span class="w-1.5 h-1.5 bg-red-500 rounded-full"></span> Suspendu
                            </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                @if($artisan->status === 'pending' && $artisan->pending_profile_data)
                                <form action="{{ route('admin.artisans.approve-profile', $artisan) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" title="Approuver le profil" class="px-3 py-1.5 bg-emerald-500 hover:bg-emerald-600 text-white text-xs font-bold rounded-lg transition-colors shadow-sm">✓ Approuver</button>
                                </form>
                                <form action="{{ route('admin.artisans.reject-profile', $artisan) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" title="Rejeter les modifications" class="px-3 py-1.5 bg-red-500 hover:bg-red-600 text-white text-xs font-bold rounded-lg transition-colors shadow-sm">✕ Rejeter</button>
                                </form>
                                @else
                                <form action="{{ route('admin.artisans.toggle', $artisan) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" title="{{ $artisan->status === 'published' ? 'Suspendre' : 'Publier' }}" class="p-2 text-slate-500 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition-colors">
                                        @if($artisan->status === 'published')
                                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        @else
                                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858-5.908a10.018 10.018 0 014.122-.888c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21M3 3l18 18"/></svg>
                                        @endif
                                    </button>
                                </form>
                                @endif
                                <a href="{{ route('admin.artisans.edit', $artisan) }}" title="Modifier" class="p-2 text-slate-500 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                <form action="{{ route('admin.artisans.destroy', $artisan) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet artisan ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" title="Supprimer" class="p-2 text-slate-500 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if($artisans->hasPages())
        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50">
            {{ $artisans->links() }}
        </div>
        @endif
        @endif
    </div>
</div>
@endsection
