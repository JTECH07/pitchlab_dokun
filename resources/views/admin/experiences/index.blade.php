<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h1 class="font-serif text-3xl text-dokun-green">Expériences</h1>
            <a href="{{ route('admin.experiences.create') }}" class="bg-dokun-green hover:bg-dokun-green/90 text-white px-5 py-2.5 rounded-xl font-bold text-sm transition shadow-lg shadow-dokun-green/20">+ Nouvelle</a>
        </div>
    </x-slot>

    <div class="py-8 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if(session('success'))<div class="mb-5 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 px-5 py-3.5 font-semibold text-sm">{{ session('success') }}</div>@endif

        <div class="bg-white rounded-2xl border border-black/5 shadow-sm overflow-hidden">
            <table class="min-w-full divide-y divide-gray-100 text-sm">
                <thead class="bg-[#F8F6F0]">
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-bold uppercase tracking-wider text-dokun-charcoal/50">Expérience</th>
                        <th class="px-5 py-3 text-left text-xs font-bold uppercase tracking-wider text-dokun-charcoal/50">Artisan</th>
                        <th class="px-5 py-3 text-left text-xs font-bold uppercase tracking-wider text-dokun-charcoal/50">Prix</th>
                        <th class="px-5 py-3 text-left text-xs font-bold uppercase tracking-wider text-dokun-charcoal/50">Durée</th>
                        <th class="px-5 py-3 text-left text-xs font-bold uppercase tracking-wider text-dokun-charcoal/50">Statut</th>
                        <th class="px-5 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($experiences as $exp)
                        <tr class="hover:bg-[#F8F6F0]/50 transition">
                            <td class="px-5 py-4 font-bold text-dokun-charcoal">{{ $exp->title }}</td>
                            <td class="px-5 py-4 text-dokun-charcoal/70">{{ $exp->artisan->first_name }} {{ $exp->artisan->last_name }}</td>
                            <td class="px-5 py-4 text-dokun-charcoal/70">{{ number_format($exp->price, 0, ',', ' ') }} {{ $exp->currency ?? 'XOF' }}</td>
                            <td class="px-5 py-4 text-dokun-charcoal/70">{{ $exp->duration_minutes }} min</td>
                            <td class="px-5 py-4">
                                @if($exp->is_published)
                                    <span class="inline-block px-2.5 py-0.5 rounded-full bg-emerald-100 text-emerald-800 text-xs font-bold">Publiée</span>
                                @else
                                    <span class="inline-block px-2.5 py-0.5 rounded-full bg-gray-100 text-gray-500 text-xs font-bold">Brouillon</span>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-right space-x-3">
                                <form method="POST" action="{{ route('admin.experiences.toggle', $exp) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="text-xs font-bold {{ $exp->is_published ? 'text-amber-600 hover:text-amber-700' : 'text-emerald-600 hover:text-emerald-700' }}">
                                        {{ $exp->is_published ? 'Masquer' : 'Publier' }}
                                    </button>
                                </form>
                                <a href="{{ route('admin.experiences.edit', $exp) }}" class="text-dokun-green font-bold text-xs hover:underline">Modifier</a>
                                <form method="POST" action="{{ route('admin.experiences.destroy', $exp) }}" class="inline" onsubmit="return confirm('Supprimer cette expérience ?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-500 font-bold text-xs hover:underline">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-5 py-10 text-center text-dokun-charcoal/40 text-sm">Aucune expérience.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-6">{{ $experiences->links() }}</div>
    </div>
</x-app-layout>
