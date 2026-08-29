<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
 <meta charset="utf-8">
 <meta name="viewport" content="width=device-width, initial-scale=1">
 <title>{{ $savoirFaire->name }} — ƉƆKUN Porto-Novo</title>
 <link href="https://fonts.bunny.net/css?family=dm-serif-display:400|manrope:400,600,700,800&display=swap" rel="stylesheet"/>
 <script src="https://cdn.tailwindcss.com"></script>
 <script>tailwind.config={theme:{extend:{colors:{dokun:{green:'#064E3B',gold:'#C99424',ivory:'#F8F6F0',charcoal:'#17201D'}},fontFamily:{sans:['Manrope','sans-serif'],serif:['"DM Serif Display"','serif']}}}}</script>
 <style>body{font-family:'Manrope',sans-serif;}h1,h2,h3,.serif{font-family:'DM Serif Display',serif;}</style>
</head>
<body class="antialiased bg-[#F8F6F0] text-[#17201D] min-h-screen flex flex-col">

 @include('partials.navbar', ['active' => 'savoir-faire'])

 <!-- Hero -->
 <section class="pt-36 pb-20 bg-[#17201D] text-white relative overflow-hidden">
 <div class="absolute inset-0">
 @if($savoirFaire->image_url)
 <img src="{{ $savoirFaire->image_url }}" class="w-full h-full object-cover opacity-20" alt="{{ $savoirFaire->name }}">
 @endif
 <div class="absolute inset-0 bg-gradient-to-t from-[#17201D] via-[#17201D]/70 to-transparent"></div>
 </div>
 <div class="relative max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
 <a href="{{ route('savoir-faire.index') }}" class="inline-flex items-center gap-2 text-[#C99424] font-bold text-sm mb-6 hover:underline">
 ← {{ __('app.sf_back_to_skills') }}
 </a>
 <span class="block w-max px-4 py-1.5 bg-[#C99424]/20 text-[#C99424] font-bold text-xs uppercase tracking-widest rounded-full mb-5 border border-[#C99424]/30">
 {{ $savoirFaire->category?->name ?: __('app.sf_singular') }}
 </span>
 <h1 class="serif text-5xl md:text-6xl mb-5 leading-tight">{{ $savoirFaire->name }}</h1>
 <p class="text-white/75 text-lg font-light leading-relaxed max-w-3xl">{{ $savoirFaire->description }}</p>
 </div>
 </section>

 <!-- Content -->
 <main class="flex-1 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 w-full">

 <!-- Info Cards -->
 <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-16">
 <div class="bg-white rounded-2xl p-7 shadow-sm border border-gray-100 text-center">
 <svg class="w-9 h-9 mx-auto mb-3 text-[#C99424]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M9 3h6m-5 0v6L4.8 18.3A2 2 0 006.4 21h11.2a2 2 0 001.6-2.7L14 9V3"/></svg>
 <div class="serif text-3xl text-[#064E3B]">{{ $savoirFaire->artisans->count() }}</div>
 <div class="text-[#17201D]/60 text-sm font-semibold mt-1">{{ trans_choice('app.sf_practitioners', $savoirFaire->artisans->count(), ['count' => $savoirFaire->artisans->count()]) }}</div>
 </div>
 <div class="bg-white rounded-2xl p-7 shadow-sm border border-gray-100 text-center">
 <svg class="w-9 h-9 mx-auto mb-3 text-[#C99424]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M12 21s7-5.2 7-12a7 7 0 10-14 0c0 6.8 7 12 7 12z"/><circle cx="12" cy="9" r="2"/></svg>
 <div class="serif text-2xl text-[#064E3B]">Porto-Novo</div>
 <div class="text-[#17201D]/60 text-sm font-semibold mt-1">{{ __('app.sf_experimentation_zone') }}</div>
 </div>
 <div class="bg-white rounded-2xl p-7 shadow-sm border border-gray-100 text-center">
 <svg class="w-9 h-9 mx-auto mb-3 text-[#C99424]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M8 3v3m8-3v3M4 10h16M6 5h12a2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V7a2 2 0 012-2z"/></svg>
 <div class="serif text-2xl text-[#064E3B]">{{ __('app.sf_experiences') }}</div>
 <div class="text-[#17201D]/60 text-sm font-semibold mt-1">{{ __('app.sf_experiences_bookable') }}</div>
 </div>
 </div>

 <!-- Artisans -->
 <div class="mb-16">
 <h2 class="serif text-3xl md:text-4xl text-[#064E3B] mb-2">
 {{ __('app.sf_artisans_practicing') }} <span class="text-[#C99424]">{{ $savoirFaire->name }}</span>
 </h2>
 <div class="h-1 w-16 bg-[#C99424] mb-10"></div>

 @if($savoirFaire->artisans->isEmpty())
 <div class="bg-white rounded-3xl p-14 text-center border border-gray-100">
 <svg class="w-12 h-12 mx-auto mb-4 text-[#C99424]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M12 12a4 4 0 100-8 4 4 0 000 8zm7 9a7 7 0 00-14 0"/></svg>
 <p class="text-[#17201D]/60 text-lg">{{ __('app.sf_no_artisans') }}</p>
 <a href="{{ route('artisans.index') }}" class="mt-6 inline-block px-6 py-3 bg-[#064E3B] text-white font-bold rounded-xl">{{ __('app.artisan_view_all') }}</a>
 </div>
 @else
 <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-7">
 @foreach($savoirFaire->artisans as $artisan)
 <div class="group bg-white rounded-2xl overflow-hidden ring-1 ring-gray-100 hover:ring-dokun-gold/40 hover:shadow-2xl hover:-translate-y-1.5 transition-all duration-500 flex flex-col">
 <a href="{{ route('artisans.show', $artisan->id) }}" class="relative h-60 overflow-hidden block">
 <img src="{{ $artisan->image_url }}" alt="{{ $artisan->first_name }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
 <div class="absolute inset-0 bg-gradient-to-t from-dokun-charcoal/80 via-dokun-charcoal/10 to-transparent"></div>
 <div class="absolute top-4 left-4 flex items-center gap-1.5 bg-white/95 backdrop-blur px-3 py-1.5 rounded-full text-xs font-bold text-dokun-green shadow-sm">
 <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
 {{ $artisan->address }}
 </div>
 <div class="absolute bottom-4 left-4 right-4">
 <span class="text-[10px] font-bold uppercase tracking-[0.18em] text-dokun-gold">{{ $artisan->professional_name ?: __('app.welcome_master_artisan') }}</span>
 <h3 class="serif text-2xl text-white leading-tight mt-1">{{ $artisan->first_name }} {{ $artisan->last_name }}</h3>
 </div>
 </a>
 <div class="p-6 flex flex-col flex-1">
 <p class="text-gray-500 text-sm line-clamp-3 leading-relaxed italic">"{{ $artisan->description }}"</p>
 <div class="flex gap-2.5 mt-5 pt-5 border-t border-gray-100">
 <a href="{{ route('artisans.show', $artisan->id) }}" class="flex-1 text-center py-2.5 bg-dokun-green text-white rounded-xl font-bold text-sm hover:bg-dokun-green/90 transition-colors">{{ __('app.sf_view_profile') }}</a>
 @if($artisan->whatsapp)
 <a href="https://wa.me/{{ str_replace(['+', ' '], '', $artisan->whatsapp) }}" target="_blank" rel="noopener" class="w-11 flex items-center justify-center bg-green-50 text-green-600 rounded-xl hover:bg-green-500 hover:text-white transition-colors">
 <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 00-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
 </a>
 @endif
 </div>
 </div>
 </div>
 @endforeach
 </div>
 @endif
 </div>
 </main>

 @include('partials.footer')
</body>
</html>
