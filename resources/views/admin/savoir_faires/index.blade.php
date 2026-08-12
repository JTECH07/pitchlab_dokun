@extends('admin.layouts.admin')
@section('title', 'Gestion des Savoir-Faire')
@section('page-title', 'Gestion des Savoir-Faire')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-extrabold text-slate-900">Savoir-Faire & Métiers</h2>
            <p class="text-sm text-slate-500 font-medium mt-1">Gérez le catalogue des savoir-faire artisanaux transmis à Porto-Novo.</p>
        </div>
        <a href="{{ route('admin.savoir-faires.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-amber-500 hover:bg-amber-600 text-white font-bold rounded-xl shadow-lg shadow-amber-500/20 transition-all">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
            Nouveau Savoir-Faire
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        @if($savoirFaires->isEmpty())
        <div class="p-12 text-center">
            <h3 class="text-lg font-bold text-slate-900 mb-1">Aucun savoir-faire enregistré</h3>
            <p class="text-slate-500 text-sm mb-6">Ajoutez votre premier métier artisanal à la plateforme.</p>
            <a href="{{ route('admin.savoir-faires.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-amber-500 text-white font-bold rounded-xl hover:bg-amber-600 transition-colors">
                Créer un savoir-faire
            </a>
        </div>
        @else
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200/80 text-xs font-bold text-slate-500 uppercase tracking-wider">
                        <th class="px-6 py-4">Nom du Savoir-Faire</th>
                        <th class="px-6 py-4">Catégorie</th>
                        <th class="px-6 py-4">Description</th>
                        <th class="px-6 py-4">Artisans</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @foreach($savoirFaires as $sf)
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="px-6 py-4 font-bold text-slate-900">{{ $sf->name }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 bg-amber-50 text-amber-800 font-semibold text-xs rounded-full border border-amber-200/60">
                                {{ $sf->category?->name ?: 'Non catégorisé' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-slate-600 max-w-xs truncate">{{ $sf->description }}</td>
                        <td class="px-6 py-4 font-bold text-slate-700">{{ $sf->artisans_count }} artisan(s)</td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.savoir-faires.edit', $sf) }}" class="p-2 text-slate-500 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                <form action="{{ route('admin.savoir-faires.destroy', $sf) }}" method="POST" onsubmit="return confirm('Supprimer ce savoir-faire ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-slate-500 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors">
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
        @if($savoirFaires->hasPages())
        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50">
            {{ $savoirFaires->links() }}
        </div>
        @endif
        @endif
    </div>
</div>
@endsection
