<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('app.learn_title') }} — ƉƆKUN</title>
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
        .course-card{transition:all .3s ease;}
        .course-card:hover{transform:translateY(-6px);box-shadow:0 20px 40px rgba(6,78,59,.18);}
    </style>
</head>
<body class="antialiased bg-dokun-ivory text-dokun-charcoal">

@include('partials.navbar')

<main class="pt-28 pb-24">
    {{-- Hero --}}
    <section class="bg-dokun-charcoal relative overflow-hidden mb-16">
        <img src="{{ url('images/tisserand.jpg') }}" class="absolute inset-0 w-full h-full object-cover opacity-15" alt="" loading="lazy">
        <div class="absolute inset-0 wax-pattern opacity-30"></div>
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-16 relative z-10 text-center fade-up">
            <span class="inline-block px-4 py-1.5 bg-dokun-gold/15 text-dokun-gold border border-dokun-gold/30 rounded-full text-xs font-bold uppercase tracking-widest mb-5">ƉƆKUN Learn</span>
            <h1 class="font-serif text-4xl md:text-5xl text-white mb-4 leading-tight">{{ __('app.learn_title') }}</h1>
            <p class="text-white/70 max-w-2xl mx-auto leading-relaxed">{{ __('app.learn_subtitle') }}</p>
        </div>
    </section>

    {{-- Cours --}}
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            @foreach($courses as $i => $course)
            @php
                $totalWords = $course->lessons->sum('words_count');
                $done = collect($course->lessons)->filter(fn($l) => isset($completed[$l->id]))->count();
                $pct = count($course->lessons) ? round($done / count($course->lessons) * 100) : 0;
                $isEn = app()->getLocale() === 'en';
            @endphp
            <a href="{{ route('learn.course', $course) }}"
               class="course-card bg-white rounded-2xl border border-black/5 p-7 block fade-up" style="animation-delay:{{ $i * 0.08 }}s">
                <div class="flex items-start justify-between mb-4">
                    <span class="w-14 h-14 rounded-2xl flex items-center justify-center" style="background:{{ $course->accent }}15"><x-icon name="{{ $course->icon }}" class="w-7 h-7" style="color:{{ $course->accent }}"/></span>
                    @if($pct === 100)
                        <span class="px-3 py-1 bg-emerald-50 text-emerald-600 text-[10px] font-bold uppercase tracking-wider rounded-full border border-emerald-200 inline-flex items-center gap-1"><x-icon name="check-circle" class="w-3.5 h-3.5"/> Terminé</span>
                    @elseif($pct > 0)
                        <span class="text-xs font-bold text-dokun-gold">{{ $pct }}%</span>
                    @endif
                </div>
                <h2 class="font-serif text-xl text-dokun-green mb-1.5">{{ $isEn ? $course->title_en : $course->title_fr }}</h2>
                <p class="text-dokun-charcoal/55 text-sm leading-relaxed line-clamp-2 mb-4">{{ $isEn ? $course->desc_en : $course->desc_fr }}</p>
                <div class="flex items-center justify-between text-xs font-bold text-dokun-charcoal/50">
                    <span>{{ count($course->lessons) }} leçons · {{ $totalWords }} mots</span>
                    <span style="color:{{ $course->accent }}">Commencer →</span>
                </div>
                @if($pct > 0)
                <div class="mt-3 h-1.5 bg-black/5 rounded-full overflow-hidden"><div class="h-full rounded-full transition-all" style="width:{{ $pct }}%;background:{{ $course->accent }}"></div></div>
                @endif
            </a>
            @endforeach
        </div>
    </div>
</main>

@include('partials.footer')
</body>
</html>
