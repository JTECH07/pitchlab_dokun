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
 <span class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-full border border-dokun-gold/50 bg-dokun-gold/10 text-dokun-gold text-xs font-bold uppercase tracking-[0.2em]"><x-icon name="sparkles" class="w-4 h-4" /> ƉƆKUN Moments</span>
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
 <p class="flex items-center gap-2 text-dokun-gold text-sm"><x-icon name="users" class="w-4 h-4" /> {{ $moment->artisan?->professional_name ?? (($moment->artisan?->first_name ?? '') . ' ' . ($moment->artisan?->last_name ?? '')) }}</p>
 @if($moment->description)
 <p class="text-white/70 text-sm italic mt-3">{{ $moment->description }}</p>
 @endif
 @if($moment->status === 'pending')
 <p class="mt-3 inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-amber-500/20 text-amber-300 text-xs font-bold"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg> {{ __('app.moments_pending') }}</p>
 @endif
 </div>
 </div>

 {{-- Partage --}}
 <div class="mt-8 text-center">
 <p class="text-white/50 text-sm mb-4">{{ __('app.moments_share_this') }}</p>
 <div class="grid grid-cols-2 sm:grid-cols-5 gap-3">
 <a href="https://api.whatsapp.com/send?text={{ urlencode($moment->title . ' — ' . $shareUrl) }}" target="_blank"
 class="flex flex-col items-center justify-center gap-2 bg-[#25D366]/15 hover:bg-[#25D366]/30 border border-[#25D366]/40 rounded-2xl py-3 text-white/90 font-semibold text-sm transition">
 <svg class="w-6 h-6 text-[#25D366]" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 00-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 1.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg> WhatsApp
 </a>
 <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($shareUrl) }}" target="_blank"
 class="flex flex-col items-center justify-center gap-2 bg-[#1877F2]/15 hover:bg-[#1877F2]/30 border border-[#1877F2]/40 rounded-2xl py-3 text-white/90 font-semibold text-sm transition">
 <svg class="w-6 h-6 text-[#1877F2]" fill="currentColor" viewBox="0 0 24 24"><path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"/></svg> Facebook
 </a>
 <a href="https://twitter.com/intent/tweet?url={{ urlencode($shareUrl) }}&text={{ urlencode($moment->title) }}" target="_blank"
 class="flex flex-col items-center justify-center gap-2 bg-white/10 hover:bg-white/20 border border-white/20 rounded-2xl py-3 text-white/90 font-semibold text-sm transition">
 <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.9 2H14.1L9.6 8.3L5.4 2H2L7.6 10.3L2 22H6.8L11.7 15.1L16.3 22H19.7L13.7 13.1z"/></svg> X
 </a>
 <button onclick="navigator.clipboard.writeText('{{ $shareUrl }}')"
 class="flex flex-col items-center justify-center gap-2 bg-dokun-gold/15 hover:bg-dokun-gold/30 border border-dokun-gold/40 rounded-2xl py-3 text-white/90 font-semibold text-sm transition" id="copy-btn">
 <svg class="w-6 h-6 text-dokun-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg> {{ __('app.moments_copy') }}
 </button>
 <a href="{{ route('experiences.index') }}"
 class="flex flex-col items-center justify-center gap-2 bg-dokun-green/40 hover:bg-dokun-green/60 border border-dokun-green rounded-2xl py-3 text-dokun-ivory font-semibold text-sm transition">
 <x-icon name="sparkles" class="w-6 h-6 text-dokun-green" /> {{ __('app.moments_experience') }}
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
