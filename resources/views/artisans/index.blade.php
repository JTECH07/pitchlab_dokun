<!DOCTYPE html>
<html lang="fr" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ __('app.nav_artisans') }} — ƉƆKUN Porto-Novo</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=dm-serif-display:400|manrope:400,600,700,800&display=swap" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        dokun: {
                            green: '#064E3B',
                            gold: '#C99424',
                            ivory: '#F8F6F0',
                            charcoal: '#17201D',
                        }
                    },
                    fontFamily: {
                        sans: ['Manrope', 'sans-serif'],
                        serif: ['"DM Serif Display"', 'serif'],
                    },
                    backgroundImage: {
                        'dokun-pattern': "url('data:image/svg+xml,%3Csvg width=\\'20\\' height=\\'20\\' viewBox=\\'0 0 20 20\\' xmlns=\\'http://www.w3.org/2000/svg\\'%3E%3Cg fill=\\'%23064E3B\\' fill-opacity=\\'0.05\\' fill-rule=\\'evenodd\\'%3E%3Ccircle cx=\\'3\\' cy=\\'3\\' r=\\'3\\'/%3E%3Ccircle cx=\\'13\\' cy=\\'13\\' r=\\'3\\'/%3E%3C/g%3E%3C/svg%3E')",
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Manrope', sans-serif; }
        h1, h2, h3, h4, .font-serif { font-family: 'DM Serif Display', serif; }
    </style>
</head>
<body class="antialiased bg-dokun-ivory text-dokun-charcoal bg-dokun-pattern min-h-screen flex flex-col">

    @include('partials.navbar', ['active' => 'artisans'])

    <!-- Header Banner -->
    <section class="pt-36 pb-24 bg-dokun-charcoal text-white relative overflow-hidden">
        <div class="absolute inset-0 z-0">
            <img src="{{ url('images/hero/tourisme_porto_novo.png') }}" class="w-full h-full object-cover opacity-20 mix-blend-luminosity" alt="Porto-Novo Culture">
            <div class="absolute inset-0 bg-gradient-to-t from-dokun-charcoal via-dokun-charcoal/80 to-transparent"></div>
        </div>
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <span class="inline-block py-1 px-4 rounded-full bg-dokun-gold/20 text-dokun-gold font-bold text-xs uppercase tracking-[0.2em] mb-4 border border-dokun-gold/30">
                {{ __('app.artisan_official_registry') }}
            </span>
            <h1 class="text-5xl md:text-6xl font-serif tracking-tight mb-4 text-white">
                {{ __('app.artisan_hero_title') }} <br/> <span class="text-dokun-gold">Porto-Novo</span>
            </h1>
            <p class="text-white/70 max-w-2xl mx-auto text-lg font-light mt-4">
                {{ __('app.artisan_hero_sub') }}
            </p>
        </div>
    </section>

    <!-- Search & Filter Bar -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-8 z-10 relative">
        <form method="GET" action="{{ route('artisans.index') }}" class="bg-white rounded-2xl p-4 shadow-xl border border-gray-100 grid grid-cols-1 sm:grid-cols-12 gap-4 items-center">
            <div class="sm:col-span-6 relative">
                <svg class="w-5 h-5 text-gray-400 absolute left-4 top-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('app.artisan_search_placeholder') }}" class="w-full pl-12 pr-4 py-3 bg-dokun-ivory/50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-dokun-gold outline-none text-sm font-semibold">
            </div>
            <div class="sm:col-span-4">
                <select name="savoir_faire" class="w-full px-4 py-3 bg-dokun-ivory/50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-dokun-gold outline-none text-sm font-semibold text-dokun-charcoal">
                    <option value="">{{ __('app.artisan_all_skills') }}</option>
                    @foreach($savoirFaires as $sf)
                    <option value="{{ $sf->id }}" {{ request('savoir_faire') == $sf->id ? 'selected' : '' }}>
                        {{ $sf->name }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="sm:col-span-2 flex gap-2">
                <button type="submit" class="w-full py-3 bg-dokun-green hover:bg-dokun-green/90 text-white font-bold rounded-xl transition-colors shadow-lg text-sm">
                    {{ __('app.search') }}
                </button>
                @if(request('search') || request('savoir_faire'))
                <a href="{{ route('artisans.index') }}" class="p-3 bg-gray-100 text-gray-600 hover:bg-gray-200 rounded-xl transition-colors text-sm font-bold flex items-center justify-center" title="{{ __('app.artisan_reset') }}">
                    ✕
                </a>
                @endif
            </div>
        </form>
    </section>

    <!-- Main Content -->
    <main class="flex-1 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 w-full">
        @if($artisans->isEmpty())
        <div class="bg-white rounded-3xl p-16 text-center border border-gray-100 shadow-sm">
            <div class="w-20 h-20 bg-dokun-ivory text-dokun-gold rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </div>
            <h3 class="text-2xl font-serif text-dokun-green mb-2">{{ __('app.artisan_empty') }}</h3>
            <p class="text-dokun-charcoal/60 max-w-md mx-auto mb-6">{{ __('app.artisan_empty_hint') }}</p>
            <a href="{{ route('artisans.index') }}" class="inline-flex px-6 py-3 bg-dokun-gold text-white font-bold rounded-xl shadow-lg hover:bg-yellow-600 transition-colors">
                {{ __('app.artisan_view_all') }}
            </a>
        </div>
        @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
            @foreach($artisans as $artisan)
            <a href="{{ route('artisans.show', $artisan->id) }}" class="bg-white rounded-2xl overflow-hidden shadow-lg border border-gray-100 group hover:-translate-y-1 transition-all duration-300 flex flex-col h-full">
                <div class="h-64 bg-gray-200 relative overflow-hidden shrink-0">
                    <img src="{{ $artisan->image_url }}" alt="{{ $artisan->first_name }} {{ $artisan->last_name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                    <div class="absolute top-4 left-4 bg-white/95 backdrop-blur px-3 py-1.5 rounded-lg text-xs font-bold text-dokun-green flex items-center gap-1">
                        {{ $artisan->address }}
                    </div>
                    @auth
                    <button type="button" onclick="toggleFavorite(event, {{ $artisan->id }}, this)"
                        data-fav="{{ in_array($artisan->id, $favoriteIds ?? []) ? '1' : '0' }}"
                        class="absolute top-4 right-4 w-10 h-10 rounded-full bg-white/95 backdrop-blur flex items-center justify-center shadow hover:scale-110 transition z-10">
                        <svg class="w-5 h-5 transition {{ in_array($artisan->id, $favoriteIds ?? []) ? 'text-red-500 fill-current' : 'text-dokun-charcoal/40' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                    </button>
                    @endauth
                </div>
                <div class="p-8 relative flex-1 flex flex-col">
                    <div class="absolute -top-12 right-8 w-16 h-16 rounded-xl bg-dokun-green p-1 shadow-lg border-2 border-white">
                        <div class="w-full h-full flex items-center justify-center text-dokun-gold text-2xl font-bold font-serif">
                            {{ substr($artisan->first_name, 0, 1) }}
                        </div>
                    </div>

                    <div class="mt-2 flex-1 flex flex-col">
                        <h3 class="text-2xl font-serif text-dokun-green group-hover:text-dokun-gold transition-colors">{{ $artisan->professional_name ?? ($artisan->first_name . ' ' . $artisan->last_name) }}</h3>

                        <div class="mt-4 flex flex-wrap gap-2">
                            @foreach($artisan->savoirFaires as $sf)
                                <span class="px-2 py-1 bg-dokun-ivory text-dokun-gold text-[10px] font-bold uppercase tracking-wider rounded border border-dokun-gold/20">{{ $sf->name }}</span>
                            @endforeach
                        </div>

                        <p class="mt-5 text-dokun-charcoal/70 line-clamp-3 text-sm leading-relaxed flex-1">
                            {{ $artisan->description }}
                        </p>

                        <div class="mt-6 pt-6 border-t border-gray-100 font-bold text-dokun-green text-sm flex justify-between items-center">
                            {{ __('app.artisan_discover_profile') }}
                            <svg class="w-5 h-5 text-dokun-gold group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </div>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
        @endif
    </main>

    @include('partials.footer')

    <script>
    async function toggleFavorite(event, artisanId, btn) {
        event.preventDefault();
        event.stopPropagation();
        try {
            const res = await fetch(`/favoris/${artisanId}`, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' },
            });
            const data = await res.json();
            if (data.status === 'guest') { window.location.href = '/login'; return; }
            const svg = btn.querySelector('svg');
            const isFav = data.status === 'added';
            svg.classList.toggle('text-red-500', isFav);
            svg.classList.toggle('fill-current', isFav);
            svg.classList.toggle('text-dokun-charcoal/40', !isFav);
        } catch (e) {}
    }
    </script>

</body>
</html>
