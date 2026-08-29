<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
 <meta charset="utf-8">
 <meta name="viewport" content="width=device-width, initial-scale=1">
 <title>{{ __('app.moments_title') }} · ƉƆKUN</title>
 <link rel="preconnect" href="https://fonts.bunny.net">
 <link href="https://fonts.bunny.net/css?family=dm-serif-display:400|manrope:400,500,600,700,800&display=swap" rel="stylesheet"/>
 <script src="https://cdn.tailwindcss.com"></script>
 <script>tailwind.config={theme:{extend:{colors:{dokun:{green:'#064E3B',gold:'#C99424',ivory:'#F8F6F0',charcoal:'#17201D'}},fontFamily:{sans:['Manrope','sans-serif'],serif:['"DM Serif Display"','serif']}}}}</script>
 <style>
 body{font-family:'Manrope',sans-serif;}
 h1,h2,h3,.font-serif{font-family:'DM Serif Display',serif;}
 @keyframes fadeUp{from{opacity:0;transform:translateY(20px)}to{opacity:1;transform:translateY(0)}}
 .fade-up{animation:fadeUp .55s ease both;}
 .delay-1{animation-delay:.08s} .delay-2{animation-delay:.16s} .delay-3{animation-delay:.24s}
 .card-hover{transition:transform .25s ease,box-shadow .25s ease;}
 .card-hover:hover{transform:translateY(-4px);box-shadow:0 20px 40px -20px rgba(6,78,59,.4);}
 </style>
</head>
<body class="antialiased bg-dokun-ivory text-dokun-charcoal">
@include('partials.navbar')

<main class="pt-28 pb-20">
 <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

 <div class="text-center mb-12 fade-up">
 <span class="inline-flex items-center gap-1.5 mb-4 px-5 py-2 rounded-full border border-dokun-gold/50 bg-dokun-gold/10 text-dokun-gold text-xs font-bold uppercase tracking-[0.2em]"><x-icon name="sparkles" class="w-4 h-4" /> {{ __('app.moments_badge') }}</span>
 <h1 class="font-serif text-4xl md:text-5xl text-dokun-green mb-4">{{ __('app.moments_title') }}</h1>
 <p class="text-gray-500 text-lg max-w-2xl mx-auto">{{ __('app.moments_hero_sub') }}</p>
 </div>

 @if($moments->isEmpty())
 <div class="text-center py-20 bg-white rounded-3xl border border-gray-100">
 <div class="text-5xl mb-4"><x-icon name="sparkles" class="w-16 h-16 mx-auto text-gray-300" /></div>
 <p class="text-gray-500 mb-6">{{ __('app.moments_empty') }}</p>
 </div>
 @else
 <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
 @foreach($moments as $moment)
 <a href="{{ route('moments.show', $moment->share_token) }}" class="card-hover group bg-white rounded-3xl overflow-hidden shadow-sm border border-gray-100 fade-up delay-1">
 <div class="relative aspect-[3/4] overflow-hidden bg-dokun-green">
 @if($moment->video_url)
 <video src="{{ $moment->video_url }}" class="w-full h-full object-cover" muted loop preload="metadata"
 onmouseover="this.play()" onmouseout="this.pause()"></video>
 @else
 <img src="{{ $moment->cover_url }}" alt="{{ $moment->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
 @endif
 <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-transparent to-transparent"></div>
 <span class="absolute top-4 right-4 flex items-center gap-1 bg-black/50 backdrop-blur px-3 py-1 rounded-full text-white text-xs font-bold"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg> 10–60s</span>
 <div class="absolute bottom-4 left-4 right-4">
 <h3 class="font-serif text-xl text-white leading-tight">{{ $moment->title }}</h3>
 <p class="text-white/70 text-xs mt-1">
 {{ $moment->artisan?->professional_name ?? (($moment->artisan?->first_name ?? '') . ' ' . ($moment->artisan?->last_name ?? '')) }}
 </p>
 </div>
 </div>
 </a>
 @endforeach
 </div>

 <div class="mt-12">
 {{ $moments->links() }}
 </div>
 @endif

 <div class="text-center mt-12 fade-up delay-3">
 <a href="{{ route('experiences.index') }}" class="inline-flex items-center gap-2 bg-dokun-gold text-dokun-charcoal px-8 py-4 rounded-2xl font-bold hover:bg-yellow-500 transition shadow-lg">
 <x-icon name="ticket" class="w-5 h-5" /> {{ __('app.moments_share_cta') }}
 </a>
 </div>
 </div>
</main>

@include('partials.footer')
</body>
</html>
