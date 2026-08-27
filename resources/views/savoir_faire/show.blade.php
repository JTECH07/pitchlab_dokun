<!DOCTYPE html>
<html lang="fr" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $savoirFaire->name }} — ƉƆKUN Porto-Novo</title>
    <link href="https://fonts.bunny.net/css?family=dm-serif-display:400|manrope:400,600,700,800&display=swap" rel="stylesheet"/>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>tailwind.config={theme:{extend:{colors:{dokun:{green:'#064E3B',gold:'#C99424',ivory:'#F8F6F0',charcoal:'#17201D'}},fontFamily:{sans:['Manrope','sans-serif'],serif:['"DM Serif Display"','serif']}}}}</script>
    <style>body{font-family:'Manrope',sans-serif;}h1,h2,h3,.serif{font-family:'DM Serif Display',serif;}</style>
</head>
<body class="antialiased bg-[#F8F6F0] text-[#17201D] min-h-screen flex flex-col">

    @include('partials.navbar', ['active' => 'savoir-faire'])

    <!-- Hero -->
    <section class="pt-36 pb-20 bg-[#17201D] text-white relative overflow-hidden">
        <div class="absolute inset-0">
            @if($savoirFaire->image_url)
            <img src="{{ $savoirFaire->image_url }}" class="w-full h-full object-cover opacity-20" alt="{{ $savoirFaire->name }}">
            @endif
            <div class="absolute inset-0 bg-gradient-to-t from-[#17201D] via-[#17201D]/70 to-transparent"></div>
        </div>
        <div class="relative max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <a href="{{ route('savoir-faire.index') }}" class="inline-flex items-center gap-2 text-[#C99424] font-bold text-sm mb-6 hover:underline">
                ← {{ __('app.sf_back_to_skills') }}
            </a>
            <span class="block w-max px-4 py-1.5 bg-[#C99424]/20 text-[#C99424] font-bold text-xs uppercase tracking-widest rounded-full mb-5 border border-[#C99424]/30">
                {{ $savoirFaire->category?->name ?: __('app.sf_singular') }}
            </span>
            <h1 class="serif text-5xl md:text-6xl mb-5 leading-tight">{{ $savoirFaire->name }}</h1>
            <p class="text-white/75 text-lg font-light leading-relaxed max-w-3xl">{{ $savoirFaire->description }}</p>
        </div>
    </section>

    <!-- Content -->
    <main class="flex-1 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 w-full">

        <!-- Info Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-16">
            <div class="bg-white rounded-2xl p-7 shadow-sm border border-gray-100 text-center">
                <svg class="w-9 h-9 mx-auto mb-3 text-[#C99424]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M9 3h6m-5 0v6L4.8 18.3A2 2 0 006.4 21h11.2a2 2 0 001.6-2.7L14 9V3"/></svg>
                <div class="serif text-3xl text-[#064E3B]">{{ $savoirFaire->artisans->count() }}</div>
                <div class="text-[#17201D]/60 text-sm font-semibold mt-1">{{ trans_choice('app.sf_practitioners', $savoirFaire->artisans->count(), ['count' => $savoirFaire->artisans->count()]) }}</div>
            </div>
            <div class="bg-white rounded-2xl p-7 shadow-sm border border-gray-100 text-center">
                <svg class="w-9 h-9 mx-auto mb-3 text-[#C99424]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M12 21s7-5.2 7-12a7 7 0 10-14 0c0 6.8 7 12 7 12z"/><circle cx="12" cy="9" r="2"/></svg>
                <div class="serif text-2xl text-[#064E3B]">Porto-Novo</div>
                <div class="text-[#17201D]/60 text-sm font-semibold mt-1">{{ __('app.sf_experimentation_zone') }}</div>
            </div>
            <div class="bg-white rounded-2xl p-7 shadow-sm border border-gray-100 text-center">
                <svg class="w-9 h-9 mx-auto mb-3 text-[#C99424]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M8 3v3m8-3v3M4 10h16M6 5h12a2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V7a2 2 0 012-2z"/></svg>
                <div class="serif text-2xl text-[#064E3B]">{{ __('app.sf_experiences') }}</div>
                <div class="text-[#17201D]/60 text-sm font-semibold mt-1">{{ __('app.sf_experiences_bookable') }}</div>
            </div>
        </div>

        <!-- Artisans -->
        <div class="mb-16">
            <h2 class="serif text-3xl md:text-4xl text-[#064E3B] mb-2">
                {{ __('app.sf_artisans_practicing') }} <span class="text-[#C99424]">{{ $savoirFaire->name }}</span>
            </h2>
            <div class="h-1 w-16 bg-[#C99424] mb-10"></div>

            @if($savoirFaire->artisans->isEmpty())
            <div class="bg-white rounded-3xl p-14 text-center border border-gray-100">
                <svg class="w-12 h-12 mx-auto mb-4 text-[#C99424]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M12 12a4 4 0 100-8 4 4 0 000 8zm7 9a7 7 0 00-14 0"/></svg>
                <p class="text-[#17201D]/60 text-lg">{{ __('app.sf_no_artisans') }}</p>
                <a href="{{ route('artisans.index') }}" class="mt-6 inline-block px-6 py-3 bg-[#064E3B] text-white font-bold rounded-xl">{{ __('app.artisan_view_all') }}</a>
            </div>
            @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($savoirFaire->artisans as $artisan)
                <a href="{{ route('artisans.show', $artisan->id) }}" class="bg-white rounded-2xl overflow-hidden shadow-lg border border-gray-100 group hover:-translate-y-1 transition-all duration-300 block">
                    <div class="h-52 relative overflow-hidden">
                        <img src="{{ $artisan->image_url }}" alt="{{ $artisan->first_name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                        <div class="absolute top-4 left-4 bg-white/95 px-3 py-1.5 rounded-lg text-xs font-bold text-[#064E3B]">{{ $artisan->address }}</div>
                    </div>
                    <div class="p-7">
                        <h3 class="serif text-2xl text-[#17201D] mb-1 group-hover:text-[#064E3B] transition-colors">{{ $artisan->first_name }} {{ $artisan->last_name }}</h3>
                        <p class="text-[#C99424] text-xs font-bold uppercase tracking-wider mb-4">{{ $artisan->professional_name ?: __('app.welcome_master_artisan') }}</p>
                        <p class="text-[#17201D]/60 text-sm line-clamp-2 leading-relaxed mb-5">"{{ $artisan->description }}"</p>
                        <div class="flex gap-3">
                            <span class="flex-1 text-center py-3 bg-[#064E3B] text-white rounded-xl font-semibold text-sm">{{ __('app.sf_view_profile') }}</span>
                            @if($artisan->whatsapp)
                            <a href="https://wa.me/{{ str_replace(['+', ' '], '', $artisan->whatsapp) }}" onclick="event.stopPropagation()" target="_blank" class="px-4 py-3 bg-green-50 text-green-600 rounded-xl text-sm font-bold hover:bg-green-100 transition-colors">WhatsApp</a>
                            @endif
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
            @endif
        </div>
    </main>

    @include('partials.footer')
</body>
</html>
