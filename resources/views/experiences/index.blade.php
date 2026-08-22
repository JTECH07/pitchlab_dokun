<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Réservez des expériences culturelles authentiques à Porto-Novo, Bénin. Ateliers artisanaux, visites guidées, immersion patrimoniale.">
    <title>{{ __('app.exp_title') }} · ƉƆKUN</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=dm-serif-display:400|manrope:400,500,600,700,800&display=swap" rel="stylesheet"/>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: { extend: {
                colors: { dokun: { green:'#064E3B', gold:'#C99424', ivory:'#F8F6F0', charcoal:'#17201D' } },
                fontFamily: { sans:['Manrope','sans-serif'], serif:['"DM Serif Display"','serif'] }
            }}
        }
    </script>
    <style>
        body{font-family:'Manrope',sans-serif;}
        h1,h2,h3,.font-serif{font-family:'DM Serif Display',serif;}
        .price-display{transition:all .3s ease;}
        input[type="range"]{accent-color:#064E3B;}
        @keyframes fadeUp{from{opacity:0;transform:translateY(12px);}to{opacity:1;transform:translateY(0);}}
        .card-anim{animation:fadeUp .4s ease-out both;}
        .filter-chip{cursor:pointer;transition:all .2s;}
        .filter-chip input:checked + span{background:#064E3B;color:white;border-color:#064E3B;}
    </style>
</head>
<body class="bg-dokun-ivory text-dokun-charcoal">

@include('partials.navbar', ['active' => 'experiences'])

{{-- ══════════════════════════════════════════════════
     HERO SECTION
══════════════════════════════════════════════════ --}}
<section class="pt-24 pb-0 bg-gradient-to-b from-dokun-green to-emerald-900 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-10 pb-16">
        <div class="grid md:grid-cols-2 gap-8 items-center">
            <div>
                <span class="text-dokun-gold text-xs font-bold tracking-[.2em] uppercase mb-4 block">Porto-Novo, Bénin</span>
                <h1 class="font-serif text-4xl md:text-5xl leading-tight mb-4">Des gestes à découvrir,<br>des histoires à vivre.</h1>
                <p class="text-white/70 text-lg mb-6">Choisissez une expérience selon vos intérêts et votre budget. L'artisan vous attend dans son atelier.</p>

                {{-- Barre de recherche rapide --}}
                <form action="{{ route('experiences.index') }}" method="GET" id="search-form" class="flex bg-white/10 backdrop-blur rounded-xl p-1.5 max-w-md border border-white/20">
                    @foreach(request()->except('q', 'page') as $key => $val)
                        @if(is_array($val))
                            @foreach($val as $v)<input type="hidden" name="{{ $key }}[]" value="{{ $v }}">@endforeach
                        @else
                            <input type="hidden" name="{{ $key }}" value="{{ $val }}">
                        @endif
                    @endforeach
                    <input name="q" value="{{ request('q') }}"
                        class="flex-1 bg-transparent border-0 focus:ring-0 text-white placeholder-white/50 px-3 text-sm"
                        placeholder="Poterie, tissage, atelier…">
                    <button class="bg-dokun-gold text-white px-5 py-3 rounded-lg font-bold text-sm">Chercher</button>
                </form>
            </div>

            {{-- Note prix : la conversion de devise n'apparaît que dans le flux de réservation --}}
        </div>
    </div>
</section>

<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="flex flex-col lg:flex-row gap-8">

        {{-- ══ SIDEBAR FILTRES ══ --}}
        <aside class="lg:w-72 flex-shrink-0">
            <form action="{{ route('experiences.index') }}" method="GET" id="filter-form">
                @if(request('q'))<input type="hidden" name="q" value="{{ request('q') }}">@endif

                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-7 sticky top-28">
                    <h2 class="font-serif text-xl text-dokun-green">Filtrer</h2>

                    {{-- Savoir-faire / Intérêts --}}
                    <div>
                        <h3 class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-3">Vos intérêts</h3>
                        <div class="flex flex-wrap gap-2">
                            @foreach($savoirFaires as $sf)
                            <label class="filter-chip">
                                <input type="checkbox" name="savoir_faire[]" value="{{ $sf->id }}" class="sr-only"
                                    {{ in_array($sf->id, $selectedSf) ? 'checked' : '' }}
                                    onchange="document.getElementById('filter-form').submit()">
                                <span class="inline-block px-3 py-1.5 rounded-full text-xs font-bold border-2 border-gray-200 text-gray-500 hover:border-dokun-green hover:text-dokun-green">
                                    {{ $sf->name }}
                                </span>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- Budget --}}
                    <div>
                        <h3 class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-3 flex items-center gap-1.5"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-4 h-4"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg> Budget max</h3>
                        <div class="space-y-3">
                            <div class="flex items-center gap-2">
                                <input type="number" name="budget_max"
                                    value="{{ request('budget_max') }}"
                                    min="0"
                                    placeholder="Ex: 15000"
                                    class="w-full px-3 py-2 bg-dokun-ivory border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-dokun-gold focus:outline-none">
                                <span class="text-sm font-bold text-dokun-green flex-shrink-0">F CFA</span>
                            </div>
                        </div>
                    </div>

                    {{-- Durée --}}
                    <div>
                        <h3 class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-3">⏱ {{ __('app.exp_duration') }} max</h3>
                        <select name="duration" class="w-full px-3 py-2 bg-dokun-ivory border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-dokun-gold focus:outline-none">
                            <option value="">Toutes durées</option>
                            <option value="60"  {{ request('duration') == 60  ? 'selected' : '' }}>< 1h</option>
                            <option value="120" {{ request('duration') == 120 ? 'selected' : '' }}>< 2h</option>
                            <option value="180" {{ request('duration') == 180 ? 'selected' : '' }}>< 3h</option>
                            <option value="480" {{ request('duration') == 480 ? 'selected' : '' }}>Demi-journée</option>
                        </select>
                    </div>

                    {{-- Tri --}}
                    <div>
                        <h3 class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-3">↕ Trier par</h3>
                        <select name="sort" class="w-full px-3 py-2 bg-dokun-ivory border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-dokun-gold focus:outline-none">
                            <option value="price_asc"  {{ $sort === 'price_asc'  ? 'selected' : '' }}>{{ __('app.exp_price') }} croissant</option>
                            <option value="price_desc" {{ $sort === 'price_desc' ? 'selected' : '' }}>{{ __('app.exp_price') }} décroissant</option>
                            <option value="duration"   {{ $sort === 'duration'   ? 'selected' : '' }}>{{ __('app.exp_duration') }}</option>
                            <option value="newest"     {{ $sort === 'newest'     ? 'selected' : '' }}>Plus récentes</option>
                        </select>
                    </div>

                    <div class="flex flex-col gap-2">
                        <button type="submit" class="w-full py-3 bg-dokun-green text-white font-bold rounded-xl text-sm hover:bg-dokun-green/90 transition">
                            Appliquer les filtres
                        </button>
                        @if(request()->hasAny(['savoir_faire', 'budget_max', 'budget_min', 'duration', 'type', 'q', 'sort']))
                        <a href="{{ route('experiences.index', []) }}"
                           class="w-full py-2 text-center text-sm text-gray-400 hover:text-red-500 font-semibold transition">
                            ✕ Effacer les filtres
                        </a>
                        @endif
                    </div>
                </div>
            </form>
        </aside>

        {{-- ══ CONTENU PRINCIPAL ══ --}}
        <div class="flex-1 min-w-0">

            {{-- Recommandations --}}
            @if($recommended->isNotEmpty())
            <div class="mb-10">
                <div class="flex items-center gap-3 mb-5">
                    <span class="text-2xl">✨</span>
                    <h2 class="font-serif text-2xl text-dokun-green">Recommandé pour vous</h2>
                </div>
                <div class="grid sm:grid-cols-3 gap-4">
                    @foreach($recommended as $rec)
                    <article class="bg-white rounded-2xl overflow-hidden border border-dokun-gold/20 shadow-md hover:shadow-xl transition-shadow group relative card-anim" style="animation-delay: {{ $loop->index * 0.1 }}s">
                        <div class="absolute top-3 left-3 z-10">
                            <span class="bg-dokun-gold text-white text-[10px] font-black uppercase tracking-wider px-2 py-1 rounded-full">Recommandé</span>
                        </div>
                        <div class="h-36 overflow-hidden">
                            <img class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                                src="{{ asset($rec->image_path ?: 'images/hero/hero_dokun.png') }}"
                                alt="{{ $rec->title }}">
                        </div>
                        <div class="p-4">
                            <p class="text-[10px] font-bold uppercase tracking-wider text-dokun-gold mb-1">{{ $rec->artisan?->professional_name }}</p>
                            <h3 class="font-serif text-lg text-dokun-green leading-tight">{{ $rec->title }}</h3>
                            <div class="flex justify-between items-center mt-3">
                                <strong class="text-dokun-green text-sm font-bold">
                                    {{ number_format($rec->price, 0, ',', ' ') }} F CFA
                                </strong>
                                <a href="{{ route('artisans.show', $rec->artisan_id) }}#reservation-form"
                                   class="bg-dokun-green text-white px-3 py-1.5 rounded-lg font-bold text-xs hover:bg-dokun-green/90 transition">
                                    {{ __('app.exp_reserve') }}
                                </a>
                            </div>
                        </div>
                    </article>
                    @endforeach
                </div>
                <div class="h-px w-full bg-gray-100 mt-8"></div>
            </div>
            @endif

            {{-- En-tête résultats --}}
            <div class="flex flex-wrap justify-between items-center gap-4 mb-6">
                <div>
                    <h2 class="font-serif text-2xl text-dokun-green">
                        @if(request()->hasAny(['savoir_faire', 'budget_max', 'q']))
                            Résultats de votre recherche
                        @else
                            Toutes les expériences
                        @endif
                    </h2>
                    <p class="text-sm text-gray-400 mt-0.5">
                        {{ $experiences->total() }} expérience(s) · Prix en F CFA
                    </p>
                </div>

                {{-- Tags actifs --}}
                <div class="flex flex-wrap gap-2">
                    @foreach($savoirFaires->whereIn('id', $selectedSf) as $activeSf)
                    <span class="flex items-center gap-1 bg-dokun-green/10 text-dokun-green text-xs font-bold px-3 py-1 rounded-full">
                        {{ $activeSf->name }}
                        <a href="{{ route('experiences.index', array_merge(request()->except('page'), ['savoir_faire' => array_diff($selectedSf, [$activeSf->id]), ])) }}"
                           class="ml-1 hover:text-red-500">×</a>
                    </span>
                    @endforeach
                    @if(request('budget_max'))
                    <span class="flex items-center gap-1 bg-amber-100 text-amber-700 text-xs font-bold px-3 py-1 rounded-full">
                        Budget ≤ {{ number_format(request('budget_max'), 0, ',', ' ') }} F CFA
                        <a href="{{ route('experiences.index', array_merge(request()->except('budget_max', 'page'), [])) }}"
                           class="ml-1 hover:text-red-500">×</a>
                    </span>
                    @endif
                </div>
            </div>

            {{-- Grille d'expériences --}}
            @if($experiences->isEmpty())
            <div class="bg-white rounded-2xl p-16 text-center border border-dokun-gold/20">
                <div class="text-5xl mb-4">🔍</div>
                <h3 class="font-serif text-2xl text-dokun-green mb-2">{{ __('app.exp_empty') }}</h3>
                <p class="text-gray-500 text-sm mb-6">Essayez d'ajuster votre budget ou vos centres d'intérêt.</p>
                <a href="{{ route('experiences.index', []) }}"
                   class="inline-block bg-dokun-green text-white px-6 py-3 rounded-xl font-bold text-sm hover:bg-dokun-green/90 transition">
                    Voir toutes les expériences
                </a>
            </div>
            @else
            <div class="grid sm:grid-cols-2 xl:grid-cols-3 gap-6">
                @foreach($experiences as $exp)
                <article class="bg-white rounded-2xl overflow-hidden border border-black/5 shadow-sm hover:shadow-xl transition-all duration-300 group card-anim" style="animation-delay: {{ ($loop->index % 6) * 0.07 }}s">
                    <div class="relative h-52 overflow-hidden">
                        <img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                            src="{{ asset($exp->image_path ?: 'images/hero/hero_dokun.png') }}"
                            alt="{{ $exp->title }}">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                        {{-- Savoir-faire badge --}}
                        @if($exp->artisan?->savoirFaires->first())
                        <div class="absolute top-3 right-3">
                            <span class="bg-black/40 backdrop-blur text-white text-[10px] font-bold uppercase tracking-wider px-2 py-1 rounded-full">
                                {{ $exp->artisan->savoirFaires->first()->name }}
                            </span>
                        </div>
                        @endif
                    </div>
                    <div class="p-5">
                        <p class="text-xs font-bold uppercase tracking-wider text-dokun-gold mb-1">
                            {{ $exp->artisan?->professional_name ?? $exp->artisan?->first_name }}
                        </p>
                        <h3 class="font-serif text-xl text-dokun-green mb-2 leading-tight">{{ $exp->title }}</h3>
                        <p class="text-sm text-gray-500 line-clamp-2 leading-relaxed mb-4">{{ $exp->summary }}</p>

                        {{-- Métas --}}
                        <div class="flex flex-wrap gap-3 text-xs text-gray-400 mb-4">
                            <span class="flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                {{ $exp->duration_minutes }} {{ __('app.exp_minutes') }}
                            </span>
                            <span class="flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                {{ $exp->capacity }} pers. max
                            </span>
                            @if($exp->language)
                            <span>🌐 {{ $exp->language }}</span>
                            @endif
                        </div>

                        <div class="flex justify-between items-center pt-3 border-t border-gray-50">
                            <div>
                                @php
                                @endphp
                                <strong class="text-dokun-green text-lg font-serif">{{ number_format($exp->price, 0, ',', ' ') }} F CFA</strong>
                                <span class="text-xs text-gray-300">/ pers.</span>
                            </div>
                            <a href="{{ route('artisans.show', $exp->artisan_id) }}#reservation-form"
                               class="bg-dokun-green text-white px-4 py-2.5 rounded-xl font-bold text-sm hover:bg-dokun-green/90 active:scale-95 transition">
                                {{ __('app.exp_reserve') }}
                            </a>
                        </div>
                    </div>
                </article>
                @endforeach
            </div>

            {{-- Pagination --}}
            <div class="mt-10">{{ $experiences->links() }}</div>
            @endif

        </div>{{-- /.flex-1 --}}
    </div>{{-- /.flex --}}
</main>

@include('partials.footer')

<script>
// Auto-submit on sort change
document.querySelector('[name="sort"]')?.addEventListener('change', function() {
    document.getElementById('filter-form').submit();
});
document.querySelector('[name="duration"]')?.addEventListener('change', function() {
    document.getElementById('filter-form').submit();
});
</script>
</body>
</html>
