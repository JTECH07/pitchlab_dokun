<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
 <meta charset="utf-8">
 <meta name="viewport" content="width=device-width, initial-scale=1">
 <title>{{ __('app.sf_index_title') }} — ƉƆKUN Porto-Novo</title>
 <link href="https://fonts.bunny.net/css?family=dm-serif-display:400|manrope:400,600,700,800&display=swap" rel="stylesheet"/>
 <script src="https://cdn.tailwindcss.com"></script>
 <script>tailwind.config={darkMode:'class',theme:{extend:{colors:{dokun:{green:'#064E3B',gold:'#C99424',ivory:'#F8F6F0',charcoal:'#17201D'}},fontFamily:{sans:['Manrope','sans-serif'],serif:['"DM Serif Display"','serif']}}}}</script>
 <style>body{font-family:'Manrope',sans-serif;}h1,h2,h3,.serif{font-family:'DM Serif Display',serif;}</style>
</head>
<body class="antialiased bg-[#F8F6F0] dark:bg-[#17201D] text-[#17201D] dark:text-[#e2e8e0] min-h-screen flex flex-col">

 @include('partials.navbar', ['active' => 'savoir-faire'])

 <!-- Hero -->
 <section class="pt-36 pb-24 bg-[#17201D] text-white relative overflow-hidden">
 <div class="absolute inset-0">
 <img src="{{ url('images/reel_marche_arts.png') }}" class="w-full h-full object-cover opacity-30" alt="Arts béninois" onerror="">
 <div class="absolute inset-0 bg-gradient-to-t from-[#064E3B] via-[#17201D]/80 to-transparent"></div>
 </div>
 <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
 <span class="inline-block py-1.5 px-4 rounded-full bg-[#C99424]/20 text-[#C99424] font-bold text-xs tracking-[0.2em] uppercase mb-5 border border-[#C99424]/30">{{ __('app.sf_index_badge') }}</span>
 <h1 class="serif text-5xl md:text-6xl mb-5">{{ __('app.sf_index_hero') }} <span class="text-[#C99424]">Porto-Novo</span></h1>
 <p class="text-white/70 max-w-2xl mx-auto text-lg font-light">{{ __('app.sf_index_hero_sub') }}</p>
 </div>
 </section>

 <main class="flex-1 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 w-full space-y-20" x-data="{ search: '' }">

  <!-- Barre de recherche -->
  <div class="max-w-2xl mx-auto -mt-8 mb-8 relative z-10">
   <div class="relative">
    <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
    <input type="text" x-model="search" placeholder="{{ __('app.sf_search_placeholder') ?? 'Rechercher un savoir-faire...' }}"
     class="w-full pl-12 pr-5 py-4 bg-white dark:bg-[#1e2a24] border border-gray-200 dark:border-white/10 rounded-2xl text-sm font-medium placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-[#C99424]/30 focus:border-[#C99424] transition shadow-lg">
    <button x-show="search.length > 0" @click="search = ''" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
     <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
    </button>
   </div>
  </div>

 @foreach($categories as $cat)
  <div x-show="search === '' || '{{ mb_strtolower($cat->name) }}'.includes(search.toLowerCase()) || {{ $cat->savoirFaires->contains(fn($sf) => mb_strtolower($sf->name) !== '') ? 'true' : 'false' }}">
  <div class="flex items-end justify-between mb-8 pb-5 border-b border-gray-200 dark:border-white/10">
   <div>
    <h2 class="serif text-3xl md:text-4xl text-[#064E3B]">{{ $cat->name }}</h2>
    <p class="text-[#17201D]/60 dark:text-[#e2e8e0]/60 mt-2 text-sm">{{ $cat->description }}</p>
   </div>
   <span class="shrink-0 px-3 py-1.5 bg-[#064E3B]/10 text-[#064E3B] text-xs font-bold rounded-full">
    {{ trans_choice('app.sf_index_trade_count', $cat->savoirFaires->count(), ['count' => $cat->savoirFaires->count()]) }}
   </span>
  </div>
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-7">
   @foreach($cat->savoirFaires as $sf)
   <a href="{{ route('savoir-faire.show', $sf->slug) }}"
      x-show="search === '' || '{{ mb_strtolower($sf->name) }}'.includes(search.toLowerCase()) || '{{ mb_strtolower($sf->description ?? '') }}'.includes(search.toLowerCase())"
      class="group bg-white dark:bg-[#1e2a24] rounded-2xl p-7 shadow-sm border border-gray-100 dark:border-white/10 hover:border-[#C99424]/40 hover:shadow-xl hover:-translate-y-1 transition-all">
    @if($sf->image_url)
    <div class="h-40 rounded-xl overflow-hidden mb-5">
     <img src="{{ $sf->image_url }}" alt="{{ $sf->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
    </div>
    @else
    <div class="w-14 h-14 bg-[#F8F6F0] dark:bg-[#25332c] rounded-full flex items-center justify-center text-[#064E3B] mb-5 group-hover:bg-[#064E3B] group-hover:text-white transition-all shadow-sm">
     <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
    </div>
    @endif
    <h3 class="serif text-xl text-[#17201D] dark:text-[#e2e8e0] group-hover:text-[#064E3B] transition-colors mb-3">{{ $sf->name }}</h3>
    <p class="text-[#17201D]/60 dark:text-[#e2e8e0]/60 text-sm line-clamp-2 leading-relaxed mb-5">{{ $sf->description }}</p>
    <div class="flex items-center justify-between pt-4 border-t border-gray-100 dark:border-white/10 text-xs font-bold">
     <span class="text-[#17201D]/50 dark:text-[#e2e8e0]/50">{{ trans_choice('app.sf_index_practitioner_count', $sf->artisans->count(), ['count' => $sf->artisans->count()]) }}</span>
     <span class="text-[#C99424] group-hover:translate-x-1 transition-transform inline-flex items-center gap-1">{{ __('app.sf_index_view') }} →</span>
    </div>
   </a>
   @endforeach
  </div>
  </div>
 @endforeach
 </main>

 @include('partials.footer')
</body>
</html>
