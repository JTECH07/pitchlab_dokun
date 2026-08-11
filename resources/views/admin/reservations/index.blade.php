@extends('admin.layouts.admin')

@section('title', 'Réservations')
@section('page-title', 'Gestion des Réservations')

@section('content')

<!-- Status Tabs -->
<div class="flex gap-2 flex-wrap mb-6">
    @foreach([
        ['key' => 'all',       'label' => 'Toutes',     'color' => 'slate'],
        ['key' => 'pending',   'label' => 'En attente', 'color' => 'amber'],
        ['key' => 'accepted',  'label' => 'Acceptées',  'color' => 'emerald'],
        ['key' => 'rejected',  'label' => 'Refusées',   'color' => 'red'],
        ['key' => 'completed', 'label' => 'Complétées', 'color' => 'blue'],
    ] as $tab)
    <a href="{{ route('admin.reservations.index', ['status' => $tab['key']]) }}"
       class="px-4 py-2 rounded-xl text-sm font-bold border transition-colors
              {{ $status === $tab['key']
                 ? 'bg-slate-900 text-white border-slate-900'
                 : 'bg-white text-slate-600 border-slate-200 hover:border-slate-300' }}">
        {{ $tab['label'] }}
        <span class="ml-1 text-xs {{ $status === $tab['key'] ? 'text-slate-300' : 'text-slate-400' }}">
            ({{ $counts[$tab['key']] }})
        </span>
    </a>
    @endforeach
</div>

<!-- Table -->
<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
    @if($reservations->count() === 0)
        <div class="text-center py-16 text-slate-400">
            <svg class="w-12 h-12 mx-auto mb-3 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            <p class="font-semibold">Aucune réservation dans cette catégorie.</p>
        </div>
    @else
    <table class="w-full">
        <thead>
            <tr class="border-b border-slate-100 bg-slate-50 text-xs font-bold text-slate-500 uppercase tracking-wider">
                <th class="text-left px-6 py-4">Visiteur</th>
                <th class="text-left px-6 py-4">Artisan</th>
                <th class="text-left px-6 py-4">Date souhaitée</th>
                <th class="text-left px-6 py-4">Expérience</th>
                <th class="text-left px-6 py-4">Pers.</th>
                <th class="text-left px-6 py-4">Statut</th>
                <th class="text-left px-6 py-4">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-50">
            @foreach($reservations as $res)
            <tr class="hover:bg-slate-50 transition-colors">
                <td class="px-6 py-4">
                    <p class="font-bold text-slate-900">{{ $res->visitor_name }}</p>
                    <p class="text-xs text-slate-400">{{ $res->visitor_phone }}</p>
                    @if($res->visitor_email)
                    <p class="text-xs text-slate-400">{{ $res->visitor_email }}</p>
                    @endif
                </td>
                <td class="px-6 py-4">
                    <p class="font-semibold text-slate-700">{{ $res->artisan->professional_name ?? ($res->artisan->first_name . ' ' . $res->artisan->last_name) }}</p>
                </td>
                <td class="px-6 py-4">
                    <p class="font-semibold text-slate-700">{{ \Carbon\Carbon::parse($res->requested_date)->format('d/m/Y') }}</p>
                    <p class="text-xs text-slate-400">Demandé {{ $res->created_at->diffForHumans() }}</p>
                </td>
                <td class="px-6 py-4">
                    <span class="text-sm text-slate-600">{{ $res->experience_type ?? '—' }}</span>
                </td>
                <td class="px-6 py-4 text-center font-bold">{{ $res->guests_count }}</td>
                <td class="px-6 py-4">
                    @php
                        $badge = [
                            'pending'   => 'bg-amber-100 text-amber-700',
                            'accepted'  => 'bg-emerald-100 text-emerald-700',
                            'rejected'  => 'bg-red-100 text-red-700',
                            'completed' => 'bg-blue-100 text-blue-700',
                        ][$res->status] ?? 'bg-slate-100 text-slate-600';
                        $labels = ['pending' => 'En attente', 'accepted' => 'Acceptée', 'rejected' => 'Refusée', 'completed' => 'Complétée'];
                    @endphp
                    <span class="px-3 py-1 rounded-full text-xs font-bold {{ $badge }}">
                        {{ $labels[$res->status] ?? $res->status }}
                    </span>
                </td>
                <td class="px-6 py-4">
                    <div class="flex gap-2 flex-wrap">
                        @if($res->status !== 'accepted')
                        <form action="{{ route('admin.reservations.updateStatus', $res) }}" method="POST">
                            @csrf @method('PATCH')
                            <input type="hidden" name="status" value="accepted">
                            <button class="px-3 py-1.5 bg-emerald-500 text-white text-xs font-bold rounded-lg hover:bg-emerald-600 transition-colors">✅ Accepter</button>
                        </form>
                        @endif
                        @if($res->status !== 'rejected')
                        <form action="{{ route('admin.reservations.updateStatus', $res) }}" method="POST">
                            @csrf @method('PATCH')
                            <input type="hidden" name="status" value="rejected">
                            <button class="px-3 py-1.5 bg-red-100 text-red-700 text-xs font-bold rounded-lg hover:bg-red-200 transition-colors">❌ Refuser</button>
                        </form>
                        @endif
                        @if($res->status === 'accepted')
                        <form action="{{ route('admin.reservations.updateStatus', $res) }}" method="POST">
                            @csrf @method('PATCH')
                            <input type="hidden" name="status" value="completed">
                            <button class="px-3 py-1.5 bg-blue-100 text-blue-700 text-xs font-bold rounded-lg hover:bg-blue-200 transition-colors">🎉 Complétée</button>
                        </form>
                        @endif
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="px-6 py-4 border-t border-slate-100">
        {{ $reservations->links() }}
    </div>
    @endif
</div>
@endsection
