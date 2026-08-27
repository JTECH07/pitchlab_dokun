<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('app.play_title') }}</title>
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
        .choice{transition:all .2s ease;}
        .choice:hover{transform:translateY(-3px);}
    </style>
</head>
<body class="antialiased bg-dokun-ivory text-dokun-charcoal">
@include('partials.navbar')

<main class="pt-24 pb-24">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Feedback flash --}}
        @if(session('success'))
            <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 font-semibold text-center">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-800 font-semibold text-center">{{ session('error') }}</div>
        @endif

        <div class="text-center mb-10 fade-up">
            <span class="inline-block mb-4 px-5 py-2 rounded-full border border-dokun-gold/50 bg-dokun-gold/10 text-dokun-gold text-xs font-bold uppercase tracking-[0.2em]">🎮 {{ __('app.play_badge') }}</span>
            <h1 class="font-serif text-4xl md:text-5xl text-dokun-green mb-4">{{ __('app.play_title') }}</h1>
            <p class="text-gray-500 text-lg">{{ __('app.play_hero_sub') }}</p>
        </div>

        {{-- Carte quiz --}}
        @if($target && $choices->count() >= 2)
            <div class="bg-white rounded-3xl shadow-lg border border-gray-100 p-8 md:p-10 fade-up">
                <div class="text-center mb-8">
                    <div class="text-6xl mb-4">{{ $target->category?->emoji ?? '🧩' }}</div>
                    <h2 class="font-serif text-2xl text-dokun-charcoal mb-2">{{ __('app.play_question') }}</h2>
                    <p class="text-gray-500 italic">"{{ $target->description }}"</p>
                    @if($target->artisans->isNotEmpty())
                        <p class="text-xs text-gray-400 mt-3">👤 {{ $target->artisans->first()->first_name }} {{ $target->artisans->first()->last_name }} · {{ $target->artisans->first()->professional_name }}</p>
                    @endif
                </div>

                <form method="POST" action="{{ route('play.guess') }}" id="quiz-form">
                    @csrf
                    <input type="hidden" name="target_id" value="{{ $target->id }}">
                    <div class="grid sm:grid-cols-2 gap-4">
                        @foreach($choices as $choice)
                            <button type="submit" name="answer_id" value="{{ $choice->id }}"
                                class="choice text-left p-5 rounded-2xl border-2 border-gray-200 hover:border-dokun-gold bg-dokun-ivory/50 hover:bg-white flex items-center gap-3">
                                <span class="text-2xl">{{ $choice->category?->emoji ?? '🧩' }}</span>
                                <span class="font-bold text-dokun-green">{{ $choice->name }}</span>
                            </button>
                        @endforeach
                    </div>
                </form>

                @auth
                    <p class="text-center text-xs text-gray-400 mt-6">🏆 {{ __('app.play_reward') }}</p>
                @else
                    <p class="text-center text-xs text-gray-400 mt-6">🔐 <a href="{{ route('login') }}" class="text-dokun-gold font-semibold underline">{{ __('app.play_login_reward') }}</a></p>
                @endauth
            </div>
        @else
            <div class="bg-white rounded-3xl border border-gray-100 p-12 text-center text-gray-500">{{ __('app.play_empty') }}</div>
        @endif

        <div class="text-center mt-8">
            <a href="{{ route('savoir-faire.index') }}" class="text-dokun-gold font-semibold hover:underline">→ {{ __('app.nav_savoir') }}</a>
        </div>
    </div>
</main>

@include('partials.footer')
</body>
</html>
