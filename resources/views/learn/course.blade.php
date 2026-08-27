<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ app()->getLocale()==='en' ? $course->title_en : $course->title_fr }} — ƉƆKUN Learn</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=dm-serif-display:400|manrope:400,600,700,800&display=swap" rel="stylesheet"/>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>tailwind.config={theme:{extend:{colors:{dokun:{green:'#064E3B',gold:'#C99424',ivory:'#F8F6F0',charcoal:'#17201D'}},fontFamily:{sans:['Manrope','sans-serif'],serif:['"DM Serif Display"','serif']}}}}</script>
    <style>
        body{font-family:'Manrope',sans-serif;}
        h1,h2,h3,.font-serif{font-family:'DM Serif Display',serif;}
        .wax-pattern{background-image:url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' stroke='%23C99424' stroke-opacity='0.25'%3E%3Ccircle cx='30' cy='30' r='12'/%3E%3Ccircle cx='0' cy='0' r='8'/%3E%3Ccircle cx='60' cy='0' r='8'/%3E%3Ccircle cx='0' cy='60' r='8'/%3E%3Ccircle cx='60' cy='60' r='8'/%3E%3Cpath d='M30 18l10 12-10 12-10-12z'/%3E%3C/g%3E%3C/svg%3E");}
        @keyframes fadeUp{from{opacity:0;transform:translateY(24px)}to{opacity:1;transform:translateY(0)}}
        .fade-up{animation:fadeUp .7s ease both;}
        .lesson-row:hover{transform:translateX(6px);}
    </style>
</head>
<body class="antialiased bg-dokun-ivory text-dokun-charcoal">

@include('partials.navbar')

<main class="pt-28 pb-24">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <a href="{{ route('learn.index') }}" class="inline-flex items-center gap-2 text-dokun-charcoal/50 hover:text-dokun-green font-semibold text-sm mb-8 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            {{ __('app.learn_title') }}
        </a>

        {{-- En-tête du cours --}}
        <header class="bg-white rounded-2xl border border-black/5 p-8 mb-10 relative overflow-hidden fade-up">
            <div class="absolute inset-0 wax-pattern opacity-[0.06]"></div>
            <div class="relative z-10 flex items-start gap-5">
                <span class="w-16 h-16 rounded-2xl flex items-center justify-center flex-shrink-0" style="background:{{ $course->accent }}15"><x-icon name="{{ $course->icon }}" class="w-8 h-8" style="color:{{ $course->accent }}"/></span>
                <div>
                    <h1 class="font-serif text-2xl md:text-3xl text-dokun-green mb-2">{{ app()->getLocale()==='en' ? $course->title_en : $course->title_fr }}</h1>
                    <p class="text-dokun-charcoal/60 text-sm leading-relaxed">{{ app()->getLocale()==='en' ? $course->desc_en : $course->desc_fr }}</p>
                </div>
            </div>
        </header>

        {{-- Leçons --}}
        <div class="space-y-3">
            @foreach($lessons as $i => $lesson)
            @php $isUnlocked = $unlocked[$lesson->id]; $isDone = isset($completed[$lesson->id]); @endphp
            @if($isUnlocked)
                <a href="{{ route('learn.play', [$course, $lesson]) }}"
                   class="lesson-row flex items-center gap-5 bg-white rounded-2xl border border-black/5 p-5 transition-all duration-300 hover:shadow-lg fade-up" style="animation-delay:{{ $i * 0.08 }}s">
                    <span class="w-11 h-11 rounded-xl flex items-center justify-center font-bold text-sm flex-shrink-0 text-white" style="background:{{ $isDone ? '#10b981' : $course->accent }}">
                        @if($isDone)<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>@else{{ $i + 1 }}@endif
                    </span>
                    <div class="flex-1 min-w-0">
                        <h3 class="font-bold text-dokun-charcoal">{{ app()->getLocale()==='en' ? $lesson->title_en : $lesson->title_fr }}</h3>
                        <p class="text-xs text-dokun-charcoal/45 mt-0.5">{{ $lesson->words_count }} {{ __('app.learn_words') }} · ~{{ max(2, ceil($lesson->words_count / 2)) }} {{ __('app.learn_min') }}</p>
                    </div>
                    <svg class="w-5 h-5 text-dok-charcoal/25 flex-shrink-0" style="color:{{ $course->accent }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            @else
                <div class="flex items-center gap-5 bg-black/[0.03] rounded-2xl border border-black/5 p-5 opacity-60 cursor-not-allowed select-none">
                    <span class="w-11 h-11 rounded-xl flex items-center justify-center bg-slate-200 text-slate-400 flex-shrink-0">
                        <svg class="w-4.5 h-4.5 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    </span>
                    <div class="flex-1 min-w-0">
                        <h3 class="font-bold text-dokun-charcoal/50">{{ app()->getLocale()==='en' ? $lesson->title_en : $lesson->title_fr }}</h3>
                        <p class="text-xs text-dokun-charcoal/35 mt-0.5">{{ __('app.learn_locked') }}</p>
                    </div>
                </div>
            @endif
            @endforeach
        </div>
    </div>
</main>

@include('partials.footer')
</body>
</html>
