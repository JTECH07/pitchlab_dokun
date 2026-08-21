<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Modération des Avis · Admin ƉƆKUN</title>
    <link href="https://fonts.bunny.net/css?family=dm-serif-display:400|manrope:400,600,700&display=swap" rel="stylesheet"/>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>tailwind.config={theme:{extend:{colors:{dokun:{green:'#064E3B',gold:'#C99424',ivory:'#F8F6F0',charcoal:'#17201D'}},fontFamily:{sans:['Manrope'],serif:['"DM Serif Display"']}}}}</script>
</head>
<body class="bg-dokun-ivory text-dokun-charcoal min-h-screen">
@include('partials.navbar', ['active' => ''])

<main class="pt-32 pb-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="font-serif text-3xl text-dokun-green">Modération des Avis</h1>
            <p class="text-gray-500 text-sm">Validez ou rejetez les avis publiés par les visiteurs</p>
        </div>
        <div class="flex gap-2">
            <a href="?status=" class="px-4 py-2 rounded-xl text-sm font-bold border {{ request('status') === null ? 'bg-dokun-green text-white border-dokun-green' : 'bg-white text-gray-700 border-gray-200' }}">Tous</a>
            <a href="?status=pending" class="px-4 py-2 rounded-xl text-sm font-bold border {{ request('status') === 'pending' ? 'bg-dokun-green text-white border-dokun-green' : 'bg-white text-gray-700 border-gray-200' }}">En attente</a>
            <a href="?status=published" class="px-4 py-2 rounded-xl text-sm font-bold border {{ request('status') === 'published' ? 'bg-dokun-green text-white border-dokun-green' : 'bg-white text-gray-700 border-gray-200' }}">Publiés</a>
            <a href="?status=rejected" class="px-4 py-2 rounded-xl text-sm font-bold border {{ request('status') === 'rejected' ? 'bg-dokun-green text-white border-dokun-green' : 'bg-white text-gray-700 border-gray-200' }}">Rejetés</a>
        </div>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border border-green-300 text-green-800 px-6 py-4 rounded-xl mb-6 font-semibold">
        {{ session('success') }}
    </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-left text-sm">
            <thead class="bg-dokun-ivory border-b border-gray-100 text-xs uppercase font-bold text-gray-500">
                <tr>
                    <th class="p-4">Auteur</th>
                    <th class="p-4">Artisan</th>
                    <th class="p-4">Note</th>
                    <th class="p-4">Commentaire</th>
                    <th class="p-4">Statut</th>
                    <th class="p-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($reviews as $rev)
                <tr class="hover:bg-gray-50/50">
                    <td class="p-4 font-bold">{{ $rev->user?->name ?? 'Utilisateur' }}<br><span class="text-xs font-normal text-gray-400">{{ $rev->user?->email }}</span></td>
                    <td class="p-4 font-semibold text-dokun-green">{{ $rev->artisan?->first_name }} {{ $rev->artisan?->last_name }}</td>
                    <td class="p-4 font-bold text-dokun-gold">
                        {{ str_repeat('★', $rev->rating) }}{{ str_repeat('☆', 5 - $rev->rating) }}
                        <span class="text-xs text-gray-400 ml-1">({{ $rev->rating }}/5)</span>
                    </td>
                    <td class="p-4 max-w-xs truncate" title="{{ $rev->comment }}">{{ $rev->comment }}</td>
                    <td class="p-4">
                        @php
                            $stClass = match($rev->status) {
                                'published' => 'bg-green-100 text-green-700',
                                'rejected'  => 'bg-red-100 text-red-700',
                                default     => 'bg-amber-100 text-amber-700'
                            };
                        @endphp
                        <span class="px-3 py-1 rounded-full text-xs font-bold {{ $stClass }}">{{ ucfirst($rev->status) }}</span>
                    </td>
                    <td class="p-4 text-right">
                        <div class="flex justify-end gap-2">
                            @if($rev->status !== 'published')
                            <form action="{{ route('admin.reviews.moderate', $rev->id) }}" method="POST">
                                @csrf @method('PATCH')
                                <input type="hidden" name="action" value="published">
                                <button type="submit" class="px-3 py-1.5 bg-dokun-green text-white text-xs font-bold rounded-lg hover:bg-dokun-green/90 transition">
                                    Publier
                                </button>
                            </form>
                            @endif
                            @if($rev->status !== 'rejected')
                            <form action="{{ route('admin.reviews.moderate', $rev->id) }}" method="POST">
                                @csrf @method('PATCH')
                                <input type="hidden" name="action" value="rejected">
                                <button type="submit" class="px-3 py-1.5 bg-red-600 text-white text-xs font-bold rounded-lg hover:bg-red-700 transition">
                                    Rejeter
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="p-8 text-center text-gray-400">Aucun avis trouvé.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $reviews->links() }}
    </div>
</main>

@include('partials.footer')
</body>
</html>
