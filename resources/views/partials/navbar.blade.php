{{-- Shared Navbar Partial --}}
{{-- Usage: @include('partials.navbar', ['active' => 'home']) --}}
@php
 $active = $active ?? '';
 $transparent = $transparent ?? false;
@endphp
<style>
 [x-cloak]{display:none !important;}
 .nav-logo{mix-blend-mode:normal;transition:filter .3s}
 #navbar.nav-overlay .nav-logo{filter:brightness(0) invert(1)}
 #navbar .nav-link{position:relative;color:#17201D;opacity:.7;font-weight:600;font-size:15px;transition:color .2s,opacity .2s}
 #navbar .nav-link:hover{color:#064E3B;opacity:1}
 #navbar .nav-link::after{content:'';position:absolute;left:50%;bottom:-3px;width:0;height:2px;background:#C99424;transform:translateX(-50%);transition:width .3s ease;border-radius:2px}
 #navbar .nav-link:hover::after,#navbar .nav-link.is-active::after{width:100%}
 #navbar .nav-link.is-active{color:#064E3B;opacity:1}
 #navbar.nav-overlay{background:transparent;border-color:transparent}
 #navbar.nav-overlay .nav-link{color:#fff;opacity:.88}
 #navbar.nav-overlay .nav-link:hover{color:#fff;opacity:1}
 #navbar.nav-overlay .nav-link.is-active{color:#F2CE8A;opacity:1}
 #navbar.nav-overlay .nav-brand{color:#fff;text-shadow:0 2px 8px rgba(0,0,0,.3)}
 #navbar.nav-overlay .nav-brand-sub{color:rgba(255,255,255,.8)}
 #navbar.nav-overlay .nav-pill{border-color:rgba(255,255,255,.25);color:#fff;background:rgba(255,255,255,.08);backdrop-filter:blur(4px)}
 #navbar.nav-overlay .nav-pill:hover{background:rgba(255,255,255,.18)}
 #navbar .nav-pill{border-color:#e5e5e5;color:#17201D}
 #navbar .nav-pill:hover{background:#F8F6F0}
 .nav-brand{color:#064E3B;transition:color .3s}
 .nav-brand-sub{color:#17201D;opacity:.5;transition:color .3s}
 #navbar.nav-scrolled{background:rgba(255,255,255,.97);backdrop-filter:blur(14px);box-shadow:0 1px 4px rgba(0,0,0,.05)}
</style>
<script>
 (function(){if(window.Alpine)return;var s=document.createElement('script');s.src='https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js';s.defer=true;document.head.appendChild(s);})();
</script>
<nav id="navbar" class="fixed w-full z-50 transition-all duration-500 {{ $transparent ? 'nav-overlay' : 'nav-scrolled' }} border-b"
 x-data="{ mobileOpen:false }">
 <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
 <div class="flex justify-between items-center h-[72px]">

 {{-- Logo --}}
 <a href="{{ route('home') }}" class="flex-shrink-0 flex items-center gap-3">
 <img src="{{ url('images/dokun_logo_final.jpeg') }}" alt="ƉƆKUN" class="nav-logo w-10 h-12 rounded-xl">
 <div class="flex flex-col leading-tight">
 <span class="nav-brand font-serif text-xl tracking-wide leading-none">ƉƆKUN</span>
 <span class="nav-brand-sub text-[7px] tracking-[0.2em] font-bold uppercase">{!! str_replace(' & ', ' &<br>', __('app.brand_tagline')) !!}</span>
 </div>
 </a>

 {{-- Desktop --}}
 <div class="hidden lg:flex items-center gap-1">

 {{-- Explorer --}}
 <div class="relative" x-data="{open:false}" @mouseenter="open=true" @mouseleave="open=false">
 <button @click="open=!open" class="nav-link flex items-center gap-1.5 px-4 py-2.5 rounded-lg {{ in_array($active,['savoir-faire','artisans','carte','experiences'])?'is-active':'' }}">
 {{ __('app.nav_explore') }}
 <svg class="w-3.5 h-3.5 opacity-50 transition-transform" :class="open?'rotate-180':''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
 </button>
 <div x-show="open" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" x-cloak
 class="absolute left-0 mt-1 w-60 bg-white rounded-xl shadow-xl border border-gray-100 z-50 py-2">
 <a href="{{ route('savoir-faire.index') }}" class="flex items-center gap-3 px-4 py-3 text-[15px] text-dokun-charcoal hover:bg-dokun-ivory font-semibold transition">
 <svg class="w-5 h-5 text-dokun-gold flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
 {{ __('app.nav_savoir') }}
 </a>
 <a href="{{ route('artisans.index') }}" class="flex items-center gap-3 px-4 py-3 text-[15px] text-dokun-charcoal hover:bg-dokun-ivory font-semibold transition">
 <svg class="w-5 h-5 text-dokun-gold flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
 {{ __('app.nav_artisans') }}
 </a>
 <a href="{{ route('carte') }}" class="flex items-center gap-3 px-4 py-3 text-[15px] text-dokun-charcoal hover:bg-dokun-ivory font-semibold transition">
 <svg class="w-5 h-5 text-dokun-gold flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
 {{ __('app.nav_carte') }}
 </a>
 <a href="{{ route('experiences.index') }}" class="flex items-center gap-3 px-4 py-3 text-[15px] text-dokun-charcoal hover:bg-dokun-ivory font-semibold transition">
 <svg class="w-5 h-5 text-dokun-gold flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
 {{ __('app.nav_experiences') }}
 </a>
 </div>
 </div>

 {{-- Apprendre --}}
 <div class="relative" x-data="{open:false}" @mouseenter="open=true" @mouseleave="open=false">
 <button @click="open=!open" class="nav-link flex items-center gap-1.5 px-4 py-2.5 rounded-lg {{ in_array($active,['learn','play','moments'])?'is-active':'' }}">
 {{ __('app.nav_learn') }}
 <svg class="w-3.5 h-3.5 opacity-50 transition-transform" :class="open?'rotate-180':''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
 </button>
 <div x-show="open" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" x-cloak
 class="absolute left-0 mt-1 w-60 bg-white rounded-xl shadow-xl border border-gray-100 z-50 py-2">
 <a href="{{ route('learn.index') }}" class="flex items-center gap-3 px-4 py-3 text-[15px] text-dokun-charcoal hover:bg-dokun-ivory font-semibold transition">
 <svg class="w-5 h-5 text-dokun-gold flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
 {{ __('app.nav_learn') }}
 </a>
 <a href="{{ route('play.index') }}" class="flex items-center gap-3 px-4 py-3 text-[15px] text-dokun-charcoal hover:bg-dokun-ivory font-semibold transition">
 <svg class="w-5 h-5 text-dokun-gold flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
 {{ __('app.play_badge') }}
 </a>
 <a href="{{ route('moments.index') }}" class="flex items-center gap-3 px-4 py-3 text-[15px] text-dokun-charcoal hover:bg-dokun-ivory font-semibold transition">
 <svg class="w-5 h-5 text-dokun-gold flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
 {{ __('app.nav_moments') }}
 </a>
 </div>
 </div>

 <a href="{{ route('about') }}" class="nav-link px-4 py-2.5 rounded-lg {{ $active==='about'?'is-active':'' }}">{{ __('app.nav_about') }}</a>
 <a href="{{ route('contact') }}" class="nav-link px-4 py-2.5 rounded-lg {{ $active==='contact'?'is-active':'' }}">{{ __('app.nav_contact') }}</a>
 </div>

 {{-- Desktop right --}}
 <div class="hidden lg:flex items-center gap-2.5">
 {{-- Langue --}}
 <div class="relative" x-data="{open:false}" @click.away="open=false">
 <button @click="open=!open" class="nav-pill flex items-center gap-1.5 text-sm font-bold px-3.5 py-2.5 rounded-lg border transition">
 <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
 <span>{{ strtoupper(App::getLocale()) }}</span>
 <svg class="w-3 h-3 opacity-40" :class="open?'rotate-180':''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
 </button>
 <div x-show="open" x-transition.opacity x-cloak class="absolute right-0 mt-2 w-40 bg-white rounded-xl shadow-xl border border-gray-100 z-50 overflow-hidden">
 <form action="{{ route('locale.switch','fr') }}" method="POST">@csrf
 <button type="submit" class="flex items-center px-4 py-3 text-sm hover:bg-dokun-ivory font-semibold w-full {{ App::getLocale()==='fr'?'bg-dokun-ivory text-dokun-green':'' }}">Fran&ccedil;ais</button>
 </form>
 <form action="{{ route('locale.switch','en') }}" method="POST">@csrf
 <button type="submit" class="flex items-center px-4 py-3 text-sm hover:bg-dokun-ivory font-semibold w-full {{ App::getLocale()==='en'?'bg-dokun-ivory text-dokun-green':'' }}">English</button>
 </form>
 </div>
 </div>

 {{-- User --}}
 @auth
 <div class="relative" x-data="{open:false}" @click.away="open=false">
 <button @click="open=!open" class="flex items-center gap-2.5 pl-2 pr-3 py-1.5 bg-dokun-green text-white rounded-full hover:bg-dokun-green/90 transition shadow text-sm font-semibold">
 <span class="w-8 h-8 bg-dokun-gold rounded-full flex items-center justify-center text-xs font-bold">{{ substr(Auth::user()->name,0,1) }}</span>
 <span class="hidden xl:inline">{{ __('app.nav_my_space') }}</span>
 <svg class="w-3.5 h-3.5 opacity-60" :class="open?'rotate-180':''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
 </button>
 <div x-show="open" x-transition.opacity x-cloak class="absolute right-0 mt-2 w-60 bg-white rounded-xl shadow-xl border border-gray-100 z-50 overflow-hidden py-2">
 <a href="{{ route('visitor.profile') }}" class="flex items-center gap-3 px-4 py-3 text-[15px] text-dokun-charcoal hover:bg-dokun-ivory font-semibold transition">
 <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
 {{ __('app.nav_my_trip') }}
 </a>
 <a href="{{ route('visitor.passport') }}" class="flex items-center gap-3 px-4 py-3 text-[15px] text-dokun-gold hover:bg-dokun-gold/5 font-semibold transition">
 <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l9-3 9 3v12l-9 3-9-3V6zm9 3v11"/></svg>
 {{ __('app.pp_title') }}
 </a>
 @if(Auth::user()->role === 'tourist')
 <a href="{{ route('artisan.apply') }}" class="flex items-center gap-3 px-4 py-3 text-[15px] text-dokun-charcoal hover:bg-dokun-ivory font-semibold transition">
 <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M14.7 6.3a1 1 0 000 1.4l1.6 1.6a1 1 0 001.4 0l3.77-3.77a6 6 0 01-7.94 7.94l-6.91 6.91a2.12 2.12 0 01-3-3l6.91-6.91a6 6 0 017.94-7.94l-3.76 3.76z"/></svg>
 {{ __('app.auth_actor_link_cta') }}
 </a>
 @endif
 <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-3 text-[15px] text-dokun-charcoal hover:bg-dokun-ivory font-semibold transition">
 <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l9-9 9 9M5 10v10a1 1 0 001 1h3a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1h3a1 1 0 001-1V10"/></svg>
 {{ __('app.admin_dashboard') }}
 </a>
 <div class="my-1 border-t border-gray-100"></div>
 <form method="POST" action="{{ route('logout') }}">
 @csrf
 <button type="submit" class="flex items-center gap-3 w-full px-4 py-3 text-[15px] text-red-600 hover:bg-red-50 font-semibold transition">
 <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
 {{ __('app.nav_logout') }}
 </button>
 </form>
 </div>
 </div>
 @else
 <a href="{{ route('login') }}" class="px-5 py-2.5 bg-dokun-gold text-white rounded-full hover:bg-yellow-600 transition shadow text-sm font-semibold">{{ __('app.nav_login') }}</a>
 <a href="{{ route('actor-requests.form') }}" class="px-5 py-2.5 bg-dokun-green text-white rounded-full hover:bg-dokun-green/90 transition shadow text-sm font-semibold">{{ __('app.nav_join') }}</a>
 @endauth
 </div>

 {{-- Mobile --}}
 <div class="flex lg:hidden items-center gap-2">
 <form action="{{ route('locale.switch', App::getLocale() === 'fr' ? 'en' : 'fr') }}" method="POST">
 @csrf
 <button type="submit" class="nav-pill text-sm font-bold px-3 py-2 rounded-lg border transition">
 {{ App::getLocale() === 'fr' ? 'EN' : 'FR' }}
 </button>
 </form>
 <button @click="mobileOpen = !mobileOpen" class="nav-pill p-2.5 rounded-lg border transition" aria-label="Menu">
 <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path x-show="!mobileOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
 <path x-show="mobileOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
 </svg>
 </button>
 </div>
 </div>
 </div>

 {{-- Mobile Menu --}}
 <div x-show="mobileOpen" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0 -translate-y-2" x-cloak class="lg:hidden bg-white border-t border-gray-100 shadow-xl max-h-[80vh] overflow-y-auto">
 <div class="max-w-7xl mx-auto px-4 py-4">

 {{-- Profil --}}
 @auth
 <a href="{{ route('visitor.profile') }}" class="flex items-center gap-3 p-3 bg-dokun-ivory rounded-xl mb-3">
 <span class="w-10 h-10 bg-dokun-green text-white rounded-full flex items-center justify-center text-sm font-bold">{{ substr(Auth::user()->name,0,1) }}</span>
 <div class="min-w-0 flex-1">
 <p class="font-bold text-dokun-charcoal text-sm">{{ Auth::user()->name }}</p>
 <p class="text-dokun-gold text-xs font-semibold">{{ __('app.nav_my_trip') }}</p>
 </div>
 <svg class="w-4 h-4 text-dokun-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
 </a>
 @endauth

 {{-- Grid nav --}}
 <div class="grid grid-cols-2 gap-2 mb-3">
 <a href="{{ route('savoir-faire.index') }}" class="flex items-center gap-2.5 py-3 px-3 rounded-xl hover:bg-dokun-ivory font-semibold text-sm {{ $active==='savoir-faire'?'bg-dokun-ivory text-dokun-gold':'' }}">
 <svg class="w-5 h-5 text-dokun-gold flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
 {{ __('app.nav_savoir') }}
 </a>
 <a href="{{ route('artisans.index') }}" class="flex items-center gap-2.5 py-3 px-3 rounded-xl hover:bg-dokun-ivory font-semibold text-sm {{ $active==='artisans'?'bg-dokun-ivory text-dokun-gold':'' }}">
 <svg class="w-5 h-5 text-dokun-gold flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
 {{ __('app.nav_artisans') }}
 </a>
 <a href="{{ route('carte') }}" class="flex items-center gap-2.5 py-3 px-3 rounded-xl hover:bg-dokun-ivory font-semibold text-sm {{ $active==='carte'?'bg-dokun-ivory text-dokun-gold':'' }}">
 <svg class="w-5 h-5 text-dokun-gold flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
 {{ __('app.nav_carte') }}
 </a>
 <a href="{{ route('experiences.index') }}" class="flex items-center gap-2.5 py-3 px-3 rounded-xl hover:bg-dokun-ivory font-semibold text-sm {{ $active==='experiences'?'bg-dokun-ivory text-dokun-gold':'' }}">
 <svg class="w-5 h-5 text-dokun-gold flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
 {{ __('app.nav_experiences') }}
 </a>
 <a href="{{ route('learn.index') }}" class="flex items-center gap-2.5 py-3 px-3 rounded-xl hover:bg-dokun-ivory font-semibold text-sm {{ $active==='learn'?'bg-dokun-ivory text-dokun-gold':'' }}">
 <svg class="w-5 h-5 text-dokun-gold flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
 {{ __('app.nav_learn') }}
 </a>
 <a href="{{ route('play.index') }}" class="flex items-center gap-2.5 py-3 px-3 rounded-xl hover:bg-dokun-ivory font-semibold text-sm {{ $active==='play'?'bg-dokun-ivory text-dokun-gold':'' }}">
 <svg class="w-5 h-5 text-dokun-gold flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
 {{ __('app.play_badge') }}
 </a>
 <a href="{{ route('moments.index') }}" class="flex items-center gap-2.5 py-3 px-3 rounded-xl hover:bg-dokun-ivory font-semibold text-sm {{ $active==='moments'?'bg-dokun-ivory text-dokun-gold':'' }}">
 <svg class="w-5 h-5 text-dokun-gold flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
 {{ __('app.nav_moments') }}
 </a>
 <a href="{{ route('about') }}" class="flex items-center gap-2.5 py-3 px-3 rounded-xl hover:bg-dokun-ivory font-semibold text-sm {{ $active==='about'?'bg-dokun-ivory text-dokun-gold':'' }}">
 <svg class="w-5 h-5 text-dokun-gold flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
 {{ __('app.nav_about') }}
 </a>
 <a href="{{ route('contact') }}" class="flex items-center gap-2.5 py-3 px-3 rounded-xl hover:bg-dokun-ivory font-semibold text-sm {{ $active==='contact'?'bg-dokun-ivory text-dokun-gold':'' }}">
 <svg class="w-5 h-5 text-dokun-gold flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
 {{ __('app.nav_contact') }}
 </a>
 </div>

 {{-- Auth footer --}}
 @auth
 <div class="flex gap-2 mb-3">
 <a href="{{ route('visitor.passport') }}" class="flex-1 flex items-center justify-center gap-2 py-3 rounded-xl border border-dokun-gold/30 text-dokun-gold font-semibold text-sm hover:bg-dokun-gold/5 transition">
 <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l9-3 9 3v12l-9 3-9-3V6zm9 3v11"/></svg>
 {{ __('app.pp_title') }}
 </a>
 <a href="{{ route('dashboard') }}" class="flex-1 flex items-center justify-center gap-2 py-3 rounded-xl bg-dokun-green text-white font-semibold text-sm hover:bg-dokun-green/90 transition">
 <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l9-9 9 9M5 10v10a1 1 0 001 1h3a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1h3a1 1 0 001-1V10"/></svg>
 {{ __('app.admin_dashboard') }}
 </a>
 </div>
 <form method="POST" action="{{ route('logout') }}">
 @csrf
 <button type="submit" class="flex items-center justify-center gap-2 w-full py-3 bg-red-50 text-red-600 font-bold rounded-xl text-sm hover:bg-red-100 transition">
 <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
 {{ __('app.nav_logout') }}
 </button>
 </form>
 @else
 <div class="grid grid-cols-2 gap-2">
 <a href="{{ route('login') }}" class="flex items-center justify-center gap-2 py-3 bg-dokun-gold text-white font-bold rounded-xl text-sm">
 <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
 {{ __('app.nav_login') }}
 </a>
 <a href="{{ route('actor-requests.form') }}" class="flex items-center justify-center gap-2 py-3 bg-dokun-green text-white font-bold rounded-xl text-sm">
 <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
 {{ __('app.nav_join') }}
 </a>
 </div>
 @endauth
 </div>
 </div>
</nav>

<script>
 (function(){
 const nav=document.getElementById('navbar');
 if(!nav)return;
 const apply=()=>{
 if(window.scrollY>60){nav.classList.remove('nav-overlay');nav.classList.add('nav-scrolled');}
 else if(!nav.classList.contains('nav-overlay')){nav.classList.add('nav-overlay');nav.classList.remove('nav-scrolled');}
 };
 if(nav.classList.contains('nav-overlay')){window.addEventListener('scroll',apply,{passive:true});apply();}
 })();
</script>
