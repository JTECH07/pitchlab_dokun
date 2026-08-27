<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('app.pp_title') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=dm-serif-display:400|manrope:400,600,700,800&display=swap" rel="stylesheet"/>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>tailwind.config={theme:{extend:{colors:{dokun:{green:'#064E3B',gold:'#C99424',ivory:'#F8F6F0',charcoal:'#17201D'}},fontFamily:{sans:['Manrope','sans-serif'],serif:['"DM Serif Display"','serif']}}}}</script>
    <style>
        body{font-family:'Manrope',sans-serif;}
        h1,h2,h3,.font-serif{font-family:'DM Serif Display',serif;}
        .wax-pattern{background-image:url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' stroke='%23C99424' stroke-opacity='0.25'%3E%3Ccircle cx='30' cy='30' r='12'/%3E%3Ccircle cx='0' cy='0' r='8'/%3E%3Ccircle cx='60' cy='0' r='8'/%3E%3Ccircle cx='0' cy='60' r='8'/%3E%3Ccircle cx='60' cy='60' r='8'/%3E%3Cpath d='M30 18l10 12-10 12-10-12z'/%3E%3C/g%3E%3C/svg%3E");}
        @keyframes fadeUp{from{opacity:0;transform:translateY(24px)}to{opacity:1;transform:translateY(0)}}
        .fade-up{animation:fadeUp .6s ease both;}
        .badge-slot{transition:all .25s ease;}
        .badge-slot:hover{transform:translateY(-4px);}
        .stamp{transition:transform .3s ease;}
        .stamp:hover{transform:rotate(-4deg) scale(1.05);}
    </style>
</head>
<body class="antialiased bg-dokun-ivory text-dokun-charcoal">

@include('partials.navbar')

<main class="pt-24 pb-24">
    {{-- Hero --}}
    <section class="bg-dokun-charcoal relative overflow-hidden mb-14">
        <img src="{{ url('images/dokun_bg2.jpg') }}" class="absolute inset-0 w-full h-full object-cover opacity-15" alt="" loading="lazy">
        <div class="absolute inset-0 wax-pattern opacity-40"></div>
        <div class="absolute inset-0 bg-gradient-to-b from-dokun-charcoal/70 via-transparent to-dokun-charcoal/90"></div>
        <div class="relative z-10 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pt-16 pb-20 text-center">
            <span class="inline-block mb-5 px-5 py-2 rounded-full border border-dokun-gold/50 bg-dokun-gold/10 text-dokun-gold text-xs font-bold uppercase tracking-[0.2em]">✈ {{ __('app.pp_badge') }}</span>
            <h1 class="font-serif text-4xl md:text-5xl text-white mb-4">{{ __('app.pp_title') }}</h1>
            <p class="text-white/70 text-lg max-w-2xl mx-auto">{{ __('app.pp_hero_sub') }}</p>
        </div>
    </section>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">

        {{-- Carte niveau + stats --}}
        <section class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8 md:p-10 fade-up">
            <div class="grid md:grid-cols-3 gap-8 items-center">
                <div class="text-center md:border-r md:border-gray-100 md:pr-8">
                    <div class="w-24 h-24 mx-auto rounded-full bg-dokun-green text-white flex items-center justify-center font-serif text-4xl mb-4 shadow-lg">
                        {{ strtoupper(substr($level['fr'], 0, 1)) }}
                    </div>
                    <h2 class="font-serif text-2xl text-dokun-green">{{ $level['fr'] }}</h2>
                    <p class="text-xs text-gray-400 uppercase tracking-wider mt-1">{{ __('app.pp_level') }}</p>
                </div>

                <div class="md:col-span-2">
                    <div class="flex items-baseline justify-between mb-2">
                        <span class="text-sm text-gray-500 font-semibold">{{ __('app.nav_my_trip') }}</span>
                    </div>
                    <div class="relative">
                        <div class="h-4 bg-dokun-ivory rounded-full overflow-hidden">
                            @php
                                $cur = $level['threshold'];
                                $nxt = $nextLevel['threshold'] ?? $cur;
                                $span = max($nxt - $cur, 1);
                                $pct = min(100, round((($totalPoints - $cur) / $span) * 100));
                            @endphp
                            <div class="h-full bg-gradient-to-r from-dokun-gold to-dokun-green rounded-full transition-all" style="width:{{ $pct }}%"></div>
                        </div>
                    </div>
                    <div class="flex items-baseline justify-between mt-3">
                        <span class="font-bold text-2xl text-dokun-gold">{{ number_format($totalPoints) }} <span class="text-sm text-gray-400">XP</span></span>
                        @if($nextLevel)
                            <span class="text-xs text-gray-400">{{ $nextLevel['threshold'] - $totalPoints }} XP → {{ $nextLevel['fr'] }}</span>
                        @else
                            <span class="text-xs text-dokun-gold font-bold">{{ __('app.pp_max_level') }}</span>
                        @endif
                    </div>
                    <div class="flex gap-6 mt-6 flex-wrap">
                        <div class="text-center">
                            <div class="font-bold text-2xl text-dokun-green">{{ $summary->streak_days ?? 0 }}</div>
                            <div class="text-xs text-gray-400">{{ __('app.pp_streak') }}</div>
                        </div>
                        <div class="text-center">
                            <div class="font-bold text-2xl text-dokun-green">{{ $discovered->count() }}</div>
                            <div class="text-xs text-gray-400">{{ __('app.pp_discovered') }}</div>
                        </div>
                        <div class="text-center">
                            <div class="font-bold text-2xl text-dokun-green">{{ $earnedBadgeIds->count() }}</div>
                            <div class="text-xs text-gray-400">{{ __('app.pp_badges') }}</div>
                        </div>
                        <div class="text-center">
                            <div class="font-bold text-2xl text-dokun-green">{{ $learnStats['lessons_done'] }}</div>
                            <div class="text-xs text-gray-400">{{ __('app.pp_words') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- Badges --}}
        <section class="fade-up">
            @php
                $iconEmoji = ['check-circle'=>'✓','volume'=>'🔊','graduation'=>'🎓','heart'=>'❤️','star'=>'⭐','compass'=>'🧭','flame'=>'🔥','gem'=>'💎'];
                $iconOf = fn($ic) => $iconEmoji[$ic] ?? '🏅';
            @endphp
            <h2 class="font-serif text-2xl md:text-3xl text-dokun-green mb-6">🏅 {{ __('app.pp_badges') }}</h2>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-4">
                @forelse($allBadges as $badge)
                    @php $earned = isset($earnedBadgeIds[$badge->id]); @endphp
                    <div class="badge-slot rounded-2xl border p-5 text-center {{ $earned ? 'bg-white border-dokun-gold/40 shadow-sm' : 'bg-white/50 border-gray-100 opacity-45 grayscale' }}">
                        <div class="text-4xl mb-3">{{ $iconOf($badge->icon) }}</div>
                        <div class="font-bold text-sm {{ $earned ? 'text-dokun-green' : 'text-gray-400' }}">{{ app()->getLocale() === 'en' ? $badge->name_en : $badge->name_fr }}</div>
                        <div class="text-[11px] text-gray-400 mt-1">{{ app()->getLocale() === 'en' ? $badge->desc_en : $badge->desc_fr }}</div>
                    </div>
                @empty
                    <p class="col-span-full text-gray-500">{{ __('app.pp_no_badges') }}</p>
                @endforelse
            </div>
        </section>

        {{-- Savoir-faire découverts --}}
        <section class="fade-up">
            <h2 class="font-serif text-2xl md:text-3xl text-dokun-green mb-6">🗺️ {{ __('app.pp_discovered') }}</h2>
            @if($discovered->count())
                <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($discovered as $sf)
                        <a href="{{ route('savoir-faire.show', $sf->slug) }}" class="stamp group bg-white rounded-2xl border border-gray-100 p-5 hover:border-dokun-gold/40 hover:shadow-md flex items-center gap-4">
                            <div class="w-14 h-14 rounded-xl bg-dokun-ivory flex items-center justify-center text-2xl shrink-0">{{ $sf->category?->emoji ?? '🎨' }}</div>
                            <div>
                                <div class="font-bold text-dokun-green group-hover:text-dokun-gold transition-colors">{{ $sf->name }}</div>
                                <div class="text-xs text-gray-400">{{ $sf->category?->name }}</div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                <p class="bg-white/60 border border-gray-100 rounded-2xl p-8 text-center text-gray-500">
                    {{ __('app.pp_no_discovered') }}
                </p>
            @endif
        </section>

        {{-- Activité récente --}}
        <section class="fade-up">
            <h2 class="font-serif text-2xl md:text-3xl text-dokun-green mb-6">📜 {{ __('app.pp_activity') }}</h2>
            @if($events->count())
                <div class="bg-white rounded-3xl border border-gray-100 divide-y divide-gray-50">
                    @foreach($events as $event)
                        <div class="flex items-center justify-between px-6 py-4">
                            <div class="flex items-center gap-3">
                                <span class="w-9 h-9 rounded-full bg-dokun-ivory text-dokun-gold flex items-center justify-center text-sm">+{{ $event->points }}</span>
                            <div>
                                <div class="font-semibold text-sm">{{ $eventLabel($event->code) }}</div>
                                <div class="text-xs text-gray-400">{{ $event->created_at->diffForHumans() }}</div>
                            </div>
                            </div>
                            <span class="text-dokun-gold font-bold">+{{ $event->points }} XP</span>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="bg-white/60 border border-gray-100 rounded-2xl p-8 text-center text-gray-500">{{ __('app.pp_no_activity') }}</p>
            @endif
        </section>
    </div>
</main>

@include('partials.footer')
</body>
</html>
