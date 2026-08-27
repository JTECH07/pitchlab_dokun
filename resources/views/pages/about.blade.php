<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('app.about_title') }}</title>
    <link href="https://fonts.bunny.net/css?family=dm-serif-display:400|manrope:400,600,700,800&display=swap" rel="stylesheet"/>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>tailwind.config={theme:{extend:{colors:{dokun:{green:'#064E3B',gold:'#C99424',ivory:'#F8F6F0',charcoal:'#17201D'}},fontFamily:{sans:['Manrope','sans-serif'],serif:['"DM Serif Display"','serif']}}}}</script>
    <style>
        body { font-family: 'Manrope', sans-serif; }
        h1, h2, h3, .serif { font-family: 'DM Serif Display', serif; }
        .wax-pattern{background-image:url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' stroke='%23C99424' stroke-opacity='0.25'%3E%3Ccircle cx='30' cy='30' r='12'/%3E%3Ccircle cx='0' cy='0' r='8'/%3E%3Ccircle cx='60' cy='0' r='8'/%3E%3Ccircle cx='0' cy='60' r='8'/%3E%3Ccircle cx='60' cy='60' r='8'/%3E%3Cpath d='M30 18l10 12-10 12-10-12z'/%3E%3C/g%3E%3C/svg%3E");}
        @keyframes fadeUp{from{opacity:0;transform:translateY(24px)}to{opacity:1;transform:translateY(0)}}
        .fade-up{animation:fadeUp .7s ease both}
    </style>
</head>
<body class="antialiased bg-dokun-ivory text-dokun-charcoal">
@include('partials.navbar', ['active' => ''])

<main class="pt-28">

    {{-- ══════════ HERO ══════════ --}}
    <section class="relative bg-dokun-charcoal text-white overflow-hidden">
        <img src="{{ url('images/dokun_bg1.jpg') }}" class="absolute inset-0 w-full h-full object-cover opacity-15" alt="" loading="lazy">
        <div class="absolute inset-0 wax-pattern opacity-40"></div>
        <div class="absolute inset-0 bg-gradient-to-b from-dokun-charcoal/70 via-transparent to-dokun-charcoal/80"></div>
        <div class="relative z-10 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-24 md:py-32 text-center">
            <span class="fade-up inline-block mb-6 px-5 py-2 rounded-full border border-dokun-gold/50 bg-dokun-gold/10 text-dokun-gold text-xs font-bold uppercase tracking-[0.2em]">{{ __('app.about_history_badge') }}</span>
            <h1 class="fade-up text-4xl md:text-6xl leading-tight" style="animation-delay:.15s">{{ __('app.about_hero_title') }}</h1>
            <p class="fade-up mt-6 text-white/75 text-lg leading-relaxed max-w-2xl mx-auto" style="animation-delay:.3s">
                {{ __('app.about_hero_sub') }}
            </p>
        </div>
    </section>

    {{-- ══════════ MISSION ══════════ --}}
    <section class="py-20 md:py-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-14 fade-up">
                <span class="text-dokun-gold text-xs font-bold uppercase tracking-[0.2em]">{{ __('app.about_mission_badge') }}</span>
                <h2 class="mt-3 text-3xl md:text-4xl text-dokun-green">{{ __('app.about_mission_title') }}</h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="fade-up bg-white rounded-2xl p-8 shadow-sm hover:shadow-lg transition-shadow">
                    <div class="w-14 h-14 rounded-full bg-dokun-green flex items-center justify-center mb-6">
                        <svg class="w-7 h-7 text-dokun-gold" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                    </div>
                    <h3 class="text-xl text-dokun-green mb-3">{{ __('app.about_preserve') }}</h3>
                    <p class="text-gray-600 leading-relaxed">{{ __('app.about_preserve_desc') }}</p>
                </div>
                <div class="bg-white rounded-2xl p-8 shadow-sm hover:shadow-lg transition-shadow" style="animation-delay:.15s">
                    <div class="w-14 h-14 rounded-full bg-dokun-green flex items-center justify-center mb-6">
                        <svg class="w-7 h-7 text-dokun-gold" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <h3 class="text-xl text-dokun-green mb-3">{{ __('app.about_connect') }}</h3>
                    <p class="text-gray-600 leading-relaxed">{{ __('app.about_connect_desc') }}</p>
                </div>
                <div class="bg-white rounded-2xl p-8 shadow-sm hover:shadow-lg transition-shadow" style="animation-delay:.3s">
                    <div class="w-14 h-14 rounded-full bg-dokun-green flex items-center justify-center mb-6">
                        <svg class="w-7 h-7 text-dokun-gold" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    </div>
                    <h3 class="text-xl text-dokun-green mb-3">{{ __('app.about_transmit') }}</h3>
                    <p class="text-gray-600 leading-relaxed">{{ __('app.about_transmit_desc') }}</p>
                </div>
            </div>
        </div>
    </section>

    {{-- ══════════ LA SOLUTION ══════════ --}}
    <section class="relative py-20 md:py-24 bg-white/60 overflow-hidden">
        <div class="absolute inset-0 wax-pattern opacity-30"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-14 fade-up">
                <span class="text-dokun-gold text-xs font-bold uppercase tracking-[0.2em]">{{ __('app.about_solution_badge') }}</span>
                <h2 class="mt-3 text-3xl md:text-4xl text-dokun-green">{{ __('app.about_solution_title') }}</h2>
                <p class="mt-4 text-gray-600">{{ __('app.about_solution_sub') }}</p>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-8 max-w-4xl mx-auto">
                <div class="fade-up bg-white rounded-2xl p-7 shadow-sm border border-gray-100 hover:border-dokun-gold/50 transition-colors">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-11 h-11 rounded-xl bg-dokun-green flex items-center justify-center shrink-0">
                            <svg class="w-6 h-6 text-dokun-gold" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                        </div>
                        <h3 class="text-lg text-dokun-green">{{ __('app.about_interactive_map') }}</h3>
                    </div>
                    <p class="text-gray-600 text-sm leading-relaxed">{{ __('app.about_interactive_map_desc') }}</p>
                </div>
                <div class="bg-white rounded-2xl p-7 shadow-sm border border-gray-100 hover:border-dokun-gold/50 transition-colors" style="animation-delay:.15s">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-11 h-11 rounded-xl bg-dokun-green flex items-center justify-center shrink-0">
                            <svg class="w-6 h-6 text-dokun-gold" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                        </div>
                        <h3 class="text-lg text-dokun-green">Bridge</h3>
                    </div>
                    <p class="text-gray-600 text-sm leading-relaxed">{{ __('app.about_bridge_desc') }}</p>
                </div>
                <div class="bg-white rounded-2xl p-7 shadow-sm border border-gray-100 hover:border-dokun-gold/50 transition-colors" style="animation-delay:.3s">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-11 h-11 rounded-xl bg-dokun-green flex items-center justify-center shrink-0">
                            <svg class="w-6 h-6 text-dokun-gold" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11a7 7 0 01-14 0m7 7v4m-4 0h8m-4-15a3 3 0 00-3 3v5a3 3 0 006 0V6a3 3 0 00-3-3z"/></svg>
                        </div>
                        <h3 class="text-lg text-dokun-green">Voice</h3>
                    </div>
                    <p class="text-gray-600 text-sm leading-relaxed">{{ __('app.about_voice_desc') }}</p>
                </div>
                <div class="bg-white rounded-2xl p-7 shadow-sm border border-gray-100 hover:border-dokun-gold/50 transition-colors" style="animation-delay:.45s">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-11 h-11 rounded-xl bg-dokun-green flex items-center justify-center shrink-0">
                            <svg class="w-6 h-6 text-dokun-gold" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
                        </div>
                        <h3 class="text-lg text-dokun-green">Learn</h3>
                    </div>
                    <p class="text-gray-600 text-sm leading-relaxed">{{ __('app.about_learn_desc') }}</p>
                </div>
            </div>
        </div>
    </section>

    {{-- ══════════ CHIFFRES ══════════ --}}
    <section class="relative bg-dokun-green text-white py-16 md:py-20 overflow-hidden">
        <img src="{{ url('images/forgeron.jpg') }}" class="absolute inset-0 w-full h-full object-cover opacity-15" alt="" loading="lazy">
        <div class="absolute inset-0 wax-pattern opacity-20"></div>
        <div class="relative z-10 max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-3 gap-10 text-center">
            <div class="fade-up">
                <p class="font-serif text-5xl md:text-6xl text-dokun-gold">8</p>
                <p class="mt-3 font-semibold text-white/80 uppercase tracking-wide text-sm">{{ __('app.about_stat_quartiers') }}</p>
            </div>
            <div class="fade-up" style="animation-delay:.15s">
                <p class="font-serif text-5xl md:text-6xl text-dokun-gold">{{ __('app.about_stat_dozens') }}</p>
                <p class="mt-3 font-semibold text-white/80 uppercase tracking-wide text-sm">{{ __('app.about_stat_artisans') }}</p>
            </div>
            <div class="fade-up" style="animation-delay:.3s">
                <p class="font-serif text-5xl md:text-6xl text-dokun-gold">3</p>
                <p class="mt-3 font-semibold text-white/80 uppercase tracking-wide text-sm">{{ __('app.about_stat_languages') }}</p>
            </div>
        </div>
    </section>

    {{-- ══════════ CTA FINAL ══════════ --}}
    <section class="py-20 md:py-24">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center fade-up">
            <h2 class="text-3xl md:text-5xl text-dokun-green">{{ __('app.about_cta_title') }}</h2>
            <p class="mt-4 text-gray-600 text-lg">{{ __('app.about_cta_sub') }}</p>
            <div class="mt-10 flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="{{ route('carte') }}" class="inline-flex items-center gap-2 px-8 py-4 bg-dokun-green text-white font-bold rounded-full hover:bg-dokun-green/90 transition shadow-lg shadow-dokun-green/20">
                    {{ __('app.about_explore_map') }}
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
                <a href="{{ route('contact') }}" class="inline-flex items-center gap-2 px-8 py-4 border-2 border-dokun-green text-dokun-green font-bold rounded-full hover:bg-dokun-green hover:text-white transition">
                    {{ __('app.about_contact') }}
                </a>
            </div>
        </div>
    </section>

</main>

@include('partials.footer')
</body>
</html>
