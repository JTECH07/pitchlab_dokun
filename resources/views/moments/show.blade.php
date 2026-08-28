<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $moment->title }} · ƉƆKUN Moments</title>
    <meta property="og:title" content="{{ $moment->title }}">
    <meta property="og:description" content="{{ $moment->description }}">
    <meta property="og:type" content="video">
    <meta property="og:url" content="{{ request()->url() }}">
    <meta property="og:image" content="{{ $moment->cover_url }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=dm-serif-display:400|manrope:400,500,600,700,800&display=swap" rel="stylesheet"/>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>tailwind.config={theme:{extend:{colors:{dokun:{green:'#064E3B',gold:'#C99424',ivory:'#F8F6F0',charcoal:'#17201D'}},fontFamily:{sans:['Manrope','sans-serif'],serif:['"DM Serif Display"','serif']}}}}</script>
    <style>
        body{font-family:'Manrope',sans-serif;}
        h1,h2,h3,.font-serif{font-family:'DM Serif Display',serif;}
        @keyframes fadeUp{from{opacity:0;transform:translateY(20px)}to{opacity:1;transform:translateY(0)}}
        .fade-up{animation:fadeUp .55s ease both;}
    </style>
</head>
<body class="antialiased bg-[#0D1512] min-h-screen flex flex-col text-dokun-ivory">
@php $shareUrl = route('moments.show', $moment->share_token); @endphp

<main class="flex-1 flex items-center justify-center px-4 py-16">
    <div class="w-full max-w-2xl fade-up">

        <div class="text-center mb-8">
            <span class="inline-block px-4 py-1.5 rounded-full border border-dokun-gold/50 bg-dokun-gold/10 text-dokun-gold text-xs font-bold uppercase tracking-[0.2em]">🎬 ƉƆKUN Moments</span>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 rounded-xl bg-emerald-500/20 border border-emerald-500/40 text-emerald-200 font-semibold text-center">{{ session('success') }}</div>
        @endif

        <div class="bg-white/5 rounded-[2rem] overflow-hidden border border-white/10 shadow-2xl">
            <div class="aspect-[3/4] bg-black flex items-center justify-center overflow-hidden">
                @if($moment->video_url)
                    <video src="{{ $moment->video_url }}" controls playsinline poster="{{ $moment->cover_url }}" class="w-full h-full object-contain"></video>
                @else
                    <img src="{{ $moment->cover_url }}" alt="{{ $moment->title }}" class="w-full h-full object-cover">
                @endif
            </div>

            <div class="p-6 bg-white/5">
                <h1 class="font-serif text-2xl text-white mb-1">{{ $moment->title }}</h1>
                <p class="text-dokun-gold text-sm">👤 {{ $moment->artisan?->professional_name ?? (($moment->artisan?->first_name ?? '') . ' ' . ($moment->artisan?->last_name ?? '')) }}</p>
                @if($moment->description)
                    <p class="text-white/70 text-sm italic mt-3">{{ $moment->description }}</p>
                @endif
                @if($moment->status === 'pending')
                    <p class="mt-3 inline-block px-3 py-1 rounded-full bg-amber-500/20 text-amber-300 text-xs font-bold">⏳ {{ __('app.moments_pending') }}</p>
                @endif
            </div>
        </div>

        {{-- Partage --}}
        <div class="mt-8 text-center">
            <p class="text-white/50 text-sm mb-4">{{ __('app.moments_share_this') }}</p>
            <div class="grid grid-cols-2 sm:grid-cols-5 gap-3">
                <a href="https://api.whatsapp.com/send?text={{ urlencode($moment->title . ' — ' . $shareUrl) }}" target="_blank"
                    class="flex flex-col items-center gap-2 bg-[#25D366]/15 hover:bg-[#25D366]/30 border border-[#25D366]/40 rounded-2xl py-3 text-white/90 font-semibold text-sm transition">
                    <span class="text-2xl">💬</span> WhatsApp
                </a>
                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($shareUrl) }}" target="_blank"
                    class="flex flex-col items-center gap-2 bg-[#1877F2]/15 hover:bg-[#1877F2]/30 border border-[#1877F2]/40 rounded-2xl py-3 text-white/90 font-semibold text-sm transition">
                    <span class="text-2xl">📘</span> Facebook
                </a>
                <a href="https://twitter.com/intent/tweet?url={{ urlencode($shareUrl) }}&text={{ urlencode($moment->title) }}" target="_blank"
                    class="flex flex-col items-center gap-2 bg-white/10 hover:bg-white/20 border border-white/20 rounded-2xl py-3 text-white/90 font-semibold text-sm transition">
                    <span class="text-2xl">🐦</span> X
                </a>
                <button onclick="navigator.clipboard.writeText('{{ $shareUrl }}')"
                    class="flex flex-col items-center gap-2 bg-dokun-gold/15 hover:bg-dokun-gold/30 border border-dokun-gold/40 rounded-2xl py-3 text-white/90 font-semibold text-sm transition" id="copy-btn">
                    <span class="text-2xl">🔗</span> {{ __('app.moments_copy') }}
                </button>
                <a href="{{ route('experiences.index') }}"
                    class="flex flex-col items-center gap-2 bg-dokun-green/40 hover:bg-dokun-green/60 border border-dokun-green rounded-2xl py-3 text-dokun-ivory font-semibold text-sm transition">
                    <span class="text-2xl">🎟️</span> {{ __('app.moments_experience') }}
                </a>
            </div>
            <p id="copied" class="hidden mt-3 text-xs text-emerald-300 font-bold">{{ __('app.moments_copied') }}</p>
        </div>

        <div class="text-center mt-8">
            <a href="{{ route('moments.index') }}" class="text-white/40 hover:text-dokun-gold text-sm font-semibold transition">← {{ __('app.moments_back_feed') }}</a>
        </div>
    </div>
</main>

@include('partials.footer')

<script>
    const cb = document.getElementById('copy-btn');
    if (cb) cb.addEventListener('click', () => {
        const c = document.getElementById('copied');
        if (c) { c.classList.remove('hidden'); setTimeout(() => c.classList.add('hidden'), 2200); }
    });
</script>
</body>
</html>
