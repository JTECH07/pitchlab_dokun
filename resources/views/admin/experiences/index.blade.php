@extends('admin.layouts.admin')

@section('title', 'Expériences')
@section('page-title', 'Expériences')

@section('content')
<div class="max-w-7xl mx-auto" x-data="{ showDeleteModal: false, deleteAction: '', deleteMessage: '' }">
 @if(session('success'))<div class="mb-5 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 px-5 py-3.5 font-semibold text-sm">{{ session('success') }}</div>@endif

 <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
 <p class="text-dokun-charcoal/60 text-sm">{{ $experiences->total() }} expérience(s) au total</p>
 <a href="{{ route('admin.experiences.create') }}" class="bg-dokun-green hover:bg-dokun-green/90 text-white px-5 py-2.5 rounded-xl font-bold text-sm transition shadow-lg shadow-dokun-green/20">+ Nouvelle</a>
 </div>

 <div class="bg-white rounded-2xl border border-black/5 shadow-sm overflow-hidden">
 <div class="overflow-x-auto">
 <table class="min-w-full divide-y divide-gray-100 text-sm">
 <thead class="bg-[#F8F6F0]">
 <tr>
 <th class="px-5 py-3 text-left text-xs font-bold uppercase tracking-wider text-dokun-charcoal/50">Expérience</th>
 <th class="px-5 py-3 text-left text-xs font-bold uppercase tracking-wider text-dokun-charcoal/50 hidden sm:table-cell">Artisan</th>
 <th class="px-5 py-3 text-left text-xs font-bold uppercase tracking-wider text-dokun-charcoal/50 hidden md:table-cell">Prix</th>
 <th class="px-5 py-3 text-left text-xs font-bold uppercase tracking-wider text-dokun-charcoal/50 hidden md:table-cell">Durée</th>
 <th class="px-5 py-3 text-left text-xs font-bold uppercase tracking-wider text-dokun-charcoal/50">Statut</th>
 <th class="px-5 py-3"></th>
 </tr>
 </thead>
 <tbody class="divide-y divide-gray-50">
 @forelse($experiences as $exp)
 <tr class="hover:bg-[#F8F6F0]/50 transition">
 <td class="px-5 py-4">
 <div class="flex items-center gap-3">
 @if($exp->image_path)
 <img src="{{ asset($exp->image_path) }}" alt="{{ $exp->title }}" class="w-10 h-10 rounded-lg object-cover flex-shrink-0">
 @else
 <div class="w-10 h-10 rounded-lg bg-dokun-ivory flex items-center justify-center text-dokun-charcoal/30 flex-shrink-0">
 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
 </div>
 @endif
 <div>
 <p class="font-bold text-dokun-charcoal">{{ $exp->title }}</p>
 <p class="text-xs text-dokun-charcoal/50 sm:hidden">{{ $exp->artisan->first_name }} {{ $exp->artisan->last_name }}</p>
 </div>
 </div>
 </td>
 <td class="px-5 py-4 text-dokun-charcoal/70 hidden sm:table-cell">{{ $exp->artisan->first_name }} {{ $exp->artisan->last_name }}</td>
 <td class="px-5 py-4 text-dokun-charcoal/70 hidden md:table-cell">{{ number_format($exp->price, 0, ',', ' ') }} {{ $exp->currency ?? 'XOF' }}</td>
 <td class="px-5 py-4 text-dokun-charcoal/70 hidden md:table-cell">{{ $exp->duration_minutes }} min</td>
 <td class="px-5 py-4">
 @if($exp->is_published)
 <span class="inline-block px-2.5 py-0.5 rounded-full bg-emerald-100 text-emerald-800 text-xs font-bold">Publiée</span>
 @else
 <span class="inline-block px-2.5 py-0.5 rounded-full bg-gray-100 text-gray-500 text-xs font-bold">Brouillon</span>
 @endif
 </td>
 <td class="px-5 py-4 text-right">
 <div class="flex items-center justify-end gap-3">
 <form method="POST" action="{{ route('admin.experiences.toggle', $exp) }}" class="inline">
  @method('PATCH')
 @csrf
 <button type="submit" class="text-xs font-bold {{ $exp->is_published ? 'text-amber-600 hover:text-amber-700' : 'text-emerald-600 hover:text-emerald-700' }}">
 {{ $exp->is_published ? 'Masquer' : 'Publier' }}
 </button>
 </form>
 <a href="{{ route('admin.experiences.edit', $exp) }}" class="text-dokun-green font-bold text-xs hover:underline">Modifier</a>
 <form method="POST" action="{{ route('admin.experiences.destroy', $exp) }}" class="inline">
  @csrf @method('DELETE')
  <button type="button" @click="deleteAction = '{{ route('admin.experiences.destroy', $exp) }}'; deleteMessage = 'Supprimer cette expérience ?'; showDeleteModal = true" class="text-red-500 font-bold text-xs hover:underline">Supprimer</button>
  </form>
 </div>
 </td>
 </tr>
 @empty
 <tr><td colspan="6" class="px-5 py-10 text-center text-dokun-charcoal/40 text-sm">Aucune expérience.</td></tr>
 @endforelse
 </tbody>
 </table>
 </div>
 </div>
 <div class="mt-6">{{ $experiences->links() }}</div>
</div>

<div x-show="showDeleteModal" x-cloak class="fixed inset-0 z-[1100] flex items-center justify-center p-4">
  <div class="absolute inset-0 bg-black/40" @click="showDeleteModal = false"></div>
  <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md p-6" x-transition>
    <h3 class="font-serif text-xl text-dokun-green mb-2">Confirmer la suppression</h3>
    <p class="text-sm text-slate-600 mb-6" x-text="deleteMessage"></p>
    <div class="flex justify-end gap-3">
      <button @click="showDeleteModal = false" class="px-5 py-2.5 text-sm font-bold text-slate-500 hover:text-slate-700 transition">Annuler</button>
      <form :action="deleteAction" method="POST" class="inline">
        @csrf
        @method('DELETE')
        <button type="submit" class="px-6 py-2.5 bg-red-500 hover:bg-red-600 text-white text-sm font-bold rounded-xl transition-colors shadow-sm">Supprimer</button>
      </form>
    </div>
  </div>
</div>
@endsection
