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
 @keyframes drift { 0%{transform:translate(0,0) scale(1)} 50%{transform:translate(20px,-25px) scale(1.12)} 100%{transform:translate(0,0) scale(1)} }
 @keyframes glowpulse { 0%,100%{opacity:.35;transform:scale(1)} 50%{opacity:.65;transform:scale(1.06)} }
 @keyframes spinslow { from{transform:rotate(0deg)} to{transform:rotate(360deg)} }
 @keyframes sparks { 0%,100%{opacity:0;transform:translateY(8px)} 50%{opacity:1;transform:translateY(-4px)} }
 @keyframes dash { to{stroke-dashoffset:0;} }
 .anim-float1 { animation: float1 6s ease-in-out infinite; }
 .anim-float2 { animation: float2 7s ease-in-out infinite 0.5s; }
 .anim-float3 { animation: float3 8s ease-in-out infinite 1s; }
 .anim-drift { animation: drift 11s ease-in-out infinite; }
 .anim-glow { animation: glowpulse 6s ease-in-out infinite; }
 .anim-spin-slow { animation: spinslow 40s linear infinite; }
 .spark { animation: sparks 3.2s ease-in-out infinite; }
 .draw { stroke-dasharray: 600; stroke-dashoffset: 600; animation: dash 3.4s ease forwards; }
 .draw-1 { animation-delay:.3s } .draw-2 { animation-delay:.9s } .draw-3 { animation-delay:1.6s } .draw-4 { animation-delay:2.3s }
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

 {{-- Halos lumineux animés --}}
 <div class="absolute top-[-10%] right-[-5%] w-[28rem] h-[28rem] bg-dokun-gold/20 rounded-full blur-3xl anim-glow"></div>
 <div class="absolute bottom-[-12%] left-[-8%] w-[30rem] h-[30rem] bg-teal-500/10 rounded-full blur-3xl anim-glow" style="animation-delay:2s"></div>

 {{-- Formes flottantes décoratives --}}
 <div class="absolute top-[12%] left-[10%] w-20 h-20 border-2 border-dokun-gold/30 rounded-full anim-float1"></div>
 <div class="absolute bottom-[18%] right-[12%] w-14 h-14 border-2 border-dokun-gold/25 rotate-45 anim-float2"></div>
 <div class="absolute top-[55%] left-[8%] w-10 h-10 bg-dokun-gold/10 rounded-xl anim-float3"></div>
 <div class="absolute top-[30%] right-[15%] w-6 h-6 bg-dokun-gold/20 rounded-full anim-float1" style="animation-delay:2s"></div>

 {{-- Anneau décoratif en rotation lente --}}
 <svg class="absolute top-[16%] right-[8%] w-24 h-24 opacity-30 anim-spin-slow" viewBox="0 0 100 100" fill="none" stroke="#C99424" stroke-width="1.5">
 <circle cx="50" cy="50" r="46" stroke-dasharray="4 8"/>
 <circle cx="50" cy="50" r="30" stroke-dasharray="2 6"/>
 </svg>

 {{-- Contenu central --}}
 <div class="relative z-10 text-center max-w-sm">
 <div class="relative inline-block mb-6">
 <div class="absolute inset-0 bg-dokun-gold/30 blur-2xl rounded-full"></div>
 <img src="{{ url('images/dokun_logo_final.jpeg') }}" alt="ƉƆKUN" class="relative w-36 h-36 drop-shadow-2xl">
 </div>
 <h1 class="font-serif text-5xl text-white mb-4 leading-tight">ƉƆKUN</h1>
 <p class="text-dokun-gold font-bold text-sm tracking-[0.18em] uppercase mb-3">{{ __('app.brand_tagline') }}</p>
 <div class="w-16 h-0.5 bg-dokun-gold/60 mx-auto mb-6"></div>
 <p class="text-white/60 text-sm leading-relaxed">
 {{ __('app.auth_illustration_desc') }}
 </p>

 {{-- Étincelles animées --}}
 <svg class="mx-auto mt-6 w-8 h-8 opacity-70" viewBox="0 0 32 32" fill="#C99424">
 <path class="spark" d="M16 2c1 5 3 7 6 8-3 1-5 3-6 8-1-5-3-7-6-8 3-1 5-3 6-8z"/>
 <path class="spark" style="animation-delay:1.4s" d="M24 14c.5 3 1.6 4 3.4 4.4-1.8.4-2.9 1.4-3.4 4.4-.5-3-1.6-4-3.4-4.4 1.8-.4 2.9-1.4 3.4-4.4z" transform="scale(.7) translate(6 6)"/>
 </svg>
 </div>

 {{-- 3 features flottantes en bas (pillules professionnelles) --}}
 <div class="relative z-10 mt-16 flex flex-wrap justify-center gap-3">
 <div class="flex items-center gap-2 px-4 py-2 rounded-full bg-white/5 ring-1 ring-white/15 text-white/70 text-xs font-semibold backdrop-blur anim-float1 hover:bg-dokun-gold/10 hover:text-white transition-colors">
 <span class="w-2 h-2 bg-dokun-gold rounded-full"></span>{{ __('app.nav_savoir') }}
 </div>
 <div class="flex items-center gap-2 px-4 py-2 rounded-full bg-white/5 ring-1 ring-white/15 text-white/70 text-xs font-semibold backdrop-blur anim-float2 hover:bg-dokun-gold/10 hover:text-white transition-colors">
 <span class="w-2 h-2 bg-dokun-gold rounded-full"></span>{{ __('app.nav_experiences') }}
 </div>
 <div class="flex items-center gap-2 px-4 py-2 rounded-full bg-white/5 ring-1 ring-white/15 text-white/70 text-xs font-semibold backdrop-blur anim-float3 hover:bg-dokun-gold/10 hover:text-white transition-colors">
 <span class="w-2 h-2 bg-dokun-gold rounded-full"></span>{{ __('app.res_payment_method') }}
 </div>
 </div>
 </div>

 {{-- ── Panneau droit : formulaire ── --}}
 <div class="w-1/2 flex items-center justify-center bg-dokun-ivory p-12">
 <div class="w-full max-w-md">
 <a href="/" class="flex items-center gap-3 mb-8">
 <img src="{{ url('images/dokun_logo_final.jpeg') }}" alt="ƉƆKUN" class="w-11 h-11 rounded-lg">
 <span class="font-serif text-2xl text-dokun-green tracking-wide">ƉƆKUN</span>
 </a>
 <div>{{ $slot }}</div>
 </div>
 </div>
 </div>

 {{-- ── MOBILE : formulaire seul avec logo ── --}}
 <div class="md:hidden min-h-screen flex flex-col items-center justify-start bg-dokun-ivory pt-8 pb-12 px-5">
 <a href="/" class="flex items-center gap-3 mb-8">
 <img src="{{ url('images/dokun_logo_final.jpeg') }}" alt="ƉƆKUN" class="w-12 h-12 rounded-lg">
 <span class="font-serif text-2xl text-dokun-green tracking-wide">ƉƆKUN</span>
 </a>
 <div class="w-full max-w-md">{{ $slot }}</div>
 </div>

 </body>
</html>
