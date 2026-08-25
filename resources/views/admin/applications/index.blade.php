<x-app-layout>
    <x-slot name="header">
        <h1 class="font-serif text-3xl text-dokun-green">Candidatures artisans</h1>
    </x-slot>

    <div class="py-8 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if(session('success'))<div class="mb-5 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 px-5 py-3.5 font-semibold text-sm">{{ session('success') }}</div>@endif

        {{-- Stats rapides --}}
        @php
            $pending = \App\Models\ArtisanApplication::where('status', 'pending')->count();
            $approved = \App\Models\ArtisanApplication::where('status', 'approved')->count();
            $rejected = \App\Models\ArtisanApplication::where('status', 'rejected')->count();
        @endphp
        <div class="grid grid-cols-3 gap-4 mb-8">
            <div class="bg-white rounded-2xl border border-black/5 p-5 text-center">
                <span class="font-serif text-3xl text-amber-600">{{ $pending }}</span>
                <p class="text-xs font-bold text-dokun-charcoal/50 mt-1">En attente</p>
            </div>
            <div class="bg-white rounded-2xl border border-black/5 p-5 text-center">
                <span class="font-serif text-3xl text-emerald-600">{{ $approved }}</span>
                <p class="text-xs font-bold text-dokun-charcoal/50 mt-1">Approuvées</p>
            </div>
            <div class="bg-white rounded-2xl border border-black/5 p-5 text-center">
                <span class="font-serif text-3xl text-red-400">{{ $rejected }}</span>
                <p class="text-xs font-bold text-dokun-charcoal/50 mt-1">Rejetées</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-black/5 shadow-sm overflow-hidden">
            <table class="min-w-full divide-y divide-gray-100 text-sm">
                <thead class="bg-[#F8F6F0]">
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-bold uppercase tracking-wider text-dokun-charcoal/50">Demandeur</th>
                        <th class="px-5 py-3 text-left text-xs font-bold uppercase tracking-wider text-dokun-charcoal/50">Catégorie</th>
                        <th class="px-5 py-3 text-left text-xs font-bold uppercase tracking-wider text-dokun-charcoal/50">Exp.</th>
                        <th class="px-5 py-3 text-left text-xs font-bold uppercase tracking-wider text-dokun-charcoal/50">Statut</th>
                        <th class="px-5 py-3 text-left text-xs font-bold uppercase tracking-wider text-dokun-charcoal/50">Date</th>
                        <th class="px-5 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($applications as $app)
                        <tr class="hover:bg-[#F8F6F0]/50 transition">
                            <td class="px-5 py-4">
                                <p class="font-bold text-dokun-charcoal">{{ $app->first_name }} {{ $app->last_name }}</p>
                                <p class="text-xs text-dokun-charcoal/50">{{ $app->user->email }}</p>
                            </td>
                            <td class="px-5 py-4 text-dokun-charcoal/70">{{ $app->category->name ?? '—' }}</td>
                            <td class="px-5 py-4 text-dokun-charcoal/70">{{ $app->experience_years }} ans</td>
                            <td class="px-5 py-4">
                                @if($app->status === 'pending')
                                    <span class="inline-block px-2.5 py-0.5 rounded-full bg-amber-100 text-amber-800 text-xs font-bold">En attente</span>
                                @elseif($app->status === 'approved')
                                    <span class="inline-block px-2.5 py-0.5 rounded-full bg-emerald-100 text-emerald-800 text-xs font-bold">Approuvée</span>
                                @else
                                    <span class="inline-block px-2.5 py-0.5 rounded-full bg-red-100 text-red-700 text-xs font-bold">Rejetée</span>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-xs text-dokun-charcoal/50">{{ $app->created_at->format('d/m/Y') }}</td>
                            <td class="px-5 py-4 text-right">
                                <a href="{{ route('admin.applications.show', $app) }}" class="text-dokun-green font-bold text-xs hover:underline">Détail →</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-5 py-10 text-center text-dokun-charcoal/40 text-sm">Aucune candidature pour le moment.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">{{ $applications->links() }}</div>
    </div>
</x-app-layout>
