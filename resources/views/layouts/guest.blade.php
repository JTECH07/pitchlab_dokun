<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'ƉƆKUN') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=dm-serif-display:400|manrope:400,600,700,800&display=swap" rel="stylesheet" />

        <script src="https://cdn.tailwindcss.com"></script>
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        colors: {
                            dokun: { green: '#064E3B', gold: '#C99424', ivory: '#F8F6F0', charcoal: '#17201D' }
                        },
                        fontFamily: {
                            sans: ['Manrope', 'sans-serif'],
                            serif: ['"DM Serif Display"', 'serif'],
                        }
                    }
                }
            }
        </script>
        <style>
            body { font-family: 'Manrope', sans-serif; }
            h1, h2, h3, h4, .font-serif { font-family: 'DM Serif Display', serif; }
            [x-cloak] { display: none !important; }
            @keyframes float1 { 0%,100%{transform:translateY(0) rotate(0deg)} 50%{transform:translateY(-18px) rotate(3deg)} }
            @keyframes float2 { 0%,100%{transform:translateY(0) rotate(0deg)} 50%{transform:translateY(-12px) rotate(-2deg)} }
            @keyframes float3 { 0%,100%{transform:translateY(0)} 50%{transform:translateY(-22px)} }
            .anim-float1 { animation: float1 6s ease-in-out infinite; }
            .anim-float2 { animation: float2 7s ease-in-out infinite 0.5s; }
            .anim-float3 { animation: float3 8s ease-in-out infinite 1s; }
        </style>
    </head>
    <body class="font-sans text-dokun-charcoal antialiased">

        {{-- ── DESKTOP : Split screen ── --}}
        <div class="hidden md:flex min-h-screen">

            {{-- ── Panneau gauche : illustration / brand ── --}}
            <div class="relative w-1/2 bg-dokun-green flex flex-col items-center justify-center overflow-hidden p-12">

                {{-- Motif géométrique tissage en fond --}}
                <svg class="absolute inset-0 w-full h-full opacity-[0.07]" xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <pattern id="kente" x="0" y="0" width="60" height="60" patternUnits="userSpaceOnUse">
                            <rect width="60" height="60" fill="none"/>
                            <rect x="0" y="0" width="15" height="15" fill="#C99424"/>
                            <rect x="30" y="0" width="15" height="15" fill="#C99424"/>
                            <rect x="15" y="15" width="15" height="15" fill="#C99424"/>
                            <rect x="45" y="15" width="15" height="15" fill="#C99424"/>
                            <rect x="0" y="30" width="15" height="15" fill="#C99424"/>
                            <rect x="30" y="30" width="15" height="15" fill="#C99424"/>
                            <rect x="15" y="45" width="15" height="15" fill="#C99424"/>
                            <rect x="45" y="45" width="15" height="15" fill="#C99424"/>
                            <line x1="0" y1="30" x2="60" y2="30" stroke="#C99424" stroke-width="1"/>
                            <line x1="30" y1="0" x2="30" y2="60" stroke="#C99424" stroke-width="1"/>
                        </pattern>
                    </defs>
                    <rect width="100%" height="100%" fill="url(#kente)"/>
                </svg>

                {{-- Formes flottantes décoratives --}}
                <div class="absolute top-[12%] left-[10%] w-20 h-20 border-2 border-dokun-gold/30 rounded-full anim-float1"></div>
                <div class="absolute bottom-[18%] right-[12%] w-14 h-14 border-2 border-dokun-gold/25 rotate-45 anim-float2"></div>
                <div class="absolute top-[55%] left-[8%] w-10 h-10 bg-dokun-gold/10 rounded-xl anim-float3"></div>
                <div class="absolute top-[30%] right-[15%] w-6 h-6 bg-dokun-gold/20 rounded-full anim-float1" style="animation-delay:2s"></div>

                {{-- Contenu central --}}
                <div class="relative z-10 text-center max-w-sm">
                    <img src="{{ url('images/dokun_logo.png') }}" alt="ƉƆKUN" class="w-36 h-36 mx-auto mb-6 drop-shadow-2xl">
                    <h1 class="font-serif text-5xl text-white mb-4 leading-tight">ƉƆKUN</h1>
                    <p class="text-dokun-gold font-bold text-sm tracking-[0.18em] uppercase mb-3">{{ __('app.brand_tagline') }}</p>
                    <div class="w-16 h-0.5 bg-dokun-gold/60 mx-auto mb-6"></div>
                    <p class="text-white/60 text-sm leading-relaxed">
                        {{ __('app.auth_illustration_desc') }}
                    </p>
                </div>

                {{-- 3 features flottantes en bas --}}
                <div class="relative z-10 mt-16 flex gap-8 text-white/50 text-xs font-semibold">
                    <div class="flex items-center gap-2"><div class="w-2 h-2 bg-dokun-gold rounded-full"></div>{{ __('app.nav_savoir') }}</div>
                    <div class="flex items-center gap-2"><div class="w-2 h-2 bg-dokun-gold rounded-full"></div>{{ __('app.nav_experiences') }}</div>
                    <div class="flex items-center gap-2"><div class="w-2 h-2 bg-dokun-gold rounded-full"></div>{{ __('app.res_payment_method') }}</div>
                </div>
            </div>

            {{-- ── Panneau droit : formulaire ── --}}
            <div class="w-1/2 flex items-center justify-center bg-dokun-ivory p-12">
                <div class="w-full max-w-md">
                    <a href="/" class="flex items-center gap-3 mb-8">
                        <img src="{{ url('images/dokun_logo.png') }}" alt="ƉƆKUN" class="w-11 h-11">
                        <span class="font-serif text-2xl text-dokun-green tracking-wide">ƉƆKUN</span>
                    </a>
                    <div>{{ $slot }}</div>
                </div>
            </div>
        </div>

        {{-- ── MOBILE : formulaire seul avec logo ── --}}
        <div class="md:hidden min-h-screen flex flex-col items-center justify-start bg-dokun-ivory pt-8 pb-12 px-5">
            <a href="/" class="flex items-center gap-3 mb-8">
                <img src="{{ url('images/dokun_logo.png') }}" alt="ƉƆKUN" class="w-12 h-12">
                <span class="font-serif text-2xl text-dokun-green tracking-wide">ƉƆKUN</span>
            </a>
            <div class="w-full max-w-md">{{ $slot }}</div>
        </div>

    </body>
</html>
