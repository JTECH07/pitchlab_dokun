{{-- Shared Navbar Partial --}}
{{-- Usage: @include('partials.navbar', ['active' => 'home']) --}}
@php
 $active = $active ?? '';
 $transparent = $transparent ?? false;
@endphp
<style>
 [x-cloak]{display:none !important;}
 #navbar .nav-link{position:relative;color:#17201D;opacity:.75;font-weight:600;transition:color .25s,opacity .25s;}
 #navbar .nav-link:hover{color:#064E3B;opacity:1;}
 #navbar .nav-link::after{content:'';position:absolute;left:50%;bottom:-2px;width:0;height:2px;background:#C99424;transform:translateX(-50%);transition:width .3s ease;border-radius:2px;}
 #navbar .nav-link:hover::after,#navbar .nav-link.is-active::after{width:100%;}
 #navbar .nav-link.is-active{color:#064E3B;opacity:1;}
 #navbar.nav-overlay{background:transparent;border-color:transparent;}
 #navbar.nav-overlay .nav-link{color:#fff;opacity:.88;text-shadow:0 1px 3px rgba(0,0,0,.25);}
 #navbar.nav-overlay .nav-link:hover{color:#fff;opacity:1;}
 #navbar.nav-overlay .nav-link.is-active{color:#F2CE8A;opacity:1;text-shadow:none;}
 #navbar.nav-overlay .nav-brand{color:#fff;text-shadow:0 2px 6px rgba(0,0,0,.35);}
 #navbar.nav-overlay .nav-brand-sub{color:rgba(255,255,255,.85);text-shadow:0 1px 3px rgba(0,0,0,.3);}
 #navbar.nav-overlay .nav-pill{border-color:rgba(255,255,255,.3);color:#fff;background:rgba(0,0,0,.08);backdrop-filter:blur(4px);}
 #navbar.nav-overlay .nav-pill:hover{background:rgba(255,255,255,.18);}
 #navbar .nav-pill{border-color:#e5e5e5;color:#17201D;}
 #navbar .nav-pill:hover{background:#F8F6F0;}
 .nav-brand{color:#064E3B;transition:color .3s;}
 .nav-brand-sub{color:#17201D;opacity:.55;transition:color .3s;}
 #navbar.nav-scrolled{background:rgba(255,255,255,.96);backdrop-filter:blur(12px);box-shadow:0 1px 3px rgba(0,0,0,.06);}
</style>
<script>
 (function(){if(window.Alpine)return;var s=document.createElement('script');s.src='https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js';s.defer=true;document.head.appendChild(s);})();
</script>
<nav id="navbar" class="fixed w-full z-50 transition-all duration-500 {{ $transparent ? 'nav-overlay' : 'nav-scrolled' }} border-b"
 x-data="{ mobileOpen:false }">
 <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
 <div class="flex justify-between items-center h-[68px]">

 {{-- Logo --}}
 <a href="{{ route('home') }}" class="flex-shrink-0 flex items-center gap-2.5">
 <img src="{{ url('images/dokun_logo_final.jpeg') }}" alt="ƉƆKUN" class="w-10 h-12 rounded-2xl shadow-sm">
 <div class="flex flex-col leading-tight">
 <span class="nav-brand font-serif text-xl tracking-wide leading-none">ƉƆKUN</span>
 <span class="nav-brand-sub text-[7px] tracking-[0.18em] font-semibold uppercase">{!! str_replace(' & ', ' &<br>', __('app.brand_tagline')) !!}</span>
 </div>
 </a>

 {{-- Desktop nav --}}
 <div class="hidden lg:flex items-center gap-0.5 text-[13px]">
 <a href="{{ route('savoir-faire.index') }}" class="nav-link px-3 py-2 rounded-lg {{ $active==='savoir-faire'?'is-active':'' }}">{{ __('app.nav_savoir') }}</a>
 <a href="{{ route('artisans.index') }}" class="nav-link px-3 py-2 rounded-lg {{ $active==='artisans'?'is-active':'' }}">{{ __('app.nav_artisans') }}</a>
 <a href="{{ route('carte') }}" class="nav-link px-3 py-2 rounded-lg {{ $active==='carte'?'is-active':'' }}">{{ __('app.nav_carte') }}</a>
 <a href="{{ route('experiences.index') }}" class="nav-link px-3 py-2 rounded-lg {{ $active==='experiences'?'is-active':'' }}">{{ __('app.nav_experiences') }}</a>
 <a href="{{ route('learn.index') }}" class="nav-link px-3 py-2 rounded-lg {{ $active==='learn'?'is-active':'' }}">{{ __('app.nav_learn') }}</a>
 <a href="{{ route('play.index') }}" class="nav-link px-3 py-2 rounded-lg {{ $active==='play'?'is-active':'' }}">{{ __('app.play_badge') }}</a>
 <a href="{{ route('moments.index') }}" class="nav-link px-3 py-2 rounded-lg {{ $active==='moments'?'is-active':'' }}">{{ __('app.nav_moments') }}</a>
 </div>

 {{-- Desktop right actions --}}
 <div class="hidden lg:flex items-center gap-2">
 <a href="{{ route('about') }}" class="nav-link px-2.5 py-2 text-[13px] rounded-lg {{ $active==='about'?'is-active':'' }}">{{ __('app.nav_about') }}</a>
 <a href="{{ route('contact') }}" class="nav-link px-2.5 py-2 text-[13px] font-bold rounded-lg {{ $active==='contact'?'is-active':'' }}">{{ __('app.nav_contact') }}</a>

 {{-- Language --}}
 <div class="relative" x-data="{open:false}" @click.away="open=false">
 <button @click="open=!open" class="nav-pill flex items-center gap-1.5 text-xs font-bold px-3 py-2 rounded-lg border transition">
 <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
 <span>{{ strtoupper(App::getLocale()) }}</span>
 <svg class="w-3 h-3 opacity-40" :class="open?'rotate-180':''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
 </button>
 <div x-show="open" x-transition.opacity x-cloak class="absolute right-0 mt-2 w-36 bg-white rounded-xl shadow-xl border border-gray-100 z-50 overflow-hidden">
 <form action="{{ route('locale.switch','fr') }}" method="POST">@csrf
 <button type="submit" class="flex items-center px-4 py-2.5 text-sm hover:bg-dokun-ivory font-semibold w-full {{ App::getLocale()==='fr'?'bg-dokun-ivory text-dokun-green':'' }}"><span>Fran&ccedil;ais</span></button>
 </form>
 <form action="{{ route('locale.switch','en') }}" method="POST">@csrf
 <button type="submit" class="flex items-center px-4 py-2.5 text-sm hover:bg-dokun-ivory font-semibold w-full {{ App::getLocale()==='en'?'bg-dokun-ivory text-dokun-green':'' }}"><span>English</span></button>
 </form>
 </div>
 </div>

 {{-- User / Auth --}}
 @auth
 <div class="relative" x-data="{open:false}" @click.away="open=false">
 <button @click="open=!open" class="flex items-center gap-2 pl-2 pr-3 py-1.5 bg-dokun-green text-white rounded-full hover:bg-dokun-green/90 transition shadow text-sm font-semibold">
 <span class="w-7 h-7 bg-dokun-gold rounded-full flex items-center justify-center text-xs font-bold">{{ substr(Auth::user()->name,0,1) }}</span>
 <span class="hidden xl:inline">{{ __('app.nav_my_space') }}</span>
 <svg class="w-3.5 h-3.5 opacity-60" :class="open?'rotate-180':''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
 </button>
 <div x-show="open" x-transition.opacity x-cloak class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-xl border border-gray-100 z-50 overflow-hidden">
 <a href="{{ route('visitor.profile') }}" class="flex items-center gap-3 px-4 py-3 text-dokun-charcoal hover:bg-dokun-ivory font-semibold text-sm">
 <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
 {{ __('app.nav_my_trip') }}
 </a>
 <a href="{{ route('visitor.passport') }}" class="flex items-center gap-3 px-4 py-3 text-dokun-gold hover:bg-dokun-gold/5 font-semibold text-sm">
 <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l9-3 9 3v12l-9 3-9-3V6zm9 3v11"/></svg>
 {{ __('app.pp_title') }}
 </a>
 @if(Auth::user()->role === 'tourist')
 <a href="{{ route('artisan.apply') }}" class="flex items-center gap-3 px-4 py-3 text-dokun-charcoal hover:bg-dokun-ivory font-semibold text-sm">
 <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M14.7 6.3a1 1 0 000 1.4l1.6 1.6a1 1 0 001.4 0l3.77-3.77a6 6 0 01-7.94 7.94l-6.91 6.91a2.12 2.12 0 01-3-3l6.91-6.91a6 6 0 017.94-7.94l-3.76 3.76z"/></svg>
 {{ __('app.auth_actor_link_cta') }}
 </a>
 @endif
 <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-3 text-dokun-charcoal hover:bg-dokun-ivory font-semibold text-sm">
 <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l9-9 9 9M5 10v10a1 1 0 001 1h3a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1h3a1 1 0 001-1V10"/></svg>
 {{ __('app.admin_dashboard') }}
 </a>
 <div class="my-1 border-t border-gray-100"></div>
 <form method="POST" action="{{ route('logout') }}">
 @csrf
 <button type="submit" class="flex items-center gap-3 w-full text-left px-4 py-3 text-sm text-red-600 hover:bg-red-50 font-semibold">
 <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
 {{ __('app.nav_logout') }}
 </button>
 </form>
 </div>
 </div>
 @else
 <a href="{{ route('login') }}" class="px-4 py-2 bg-dokun-gold text-white rounded-full hover:bg-yellow-600 transition shadow text-sm font-semibold">{{ __('app.nav_login') }}</a>
 <a href="{{ route('actor-requests.form') }}" class="px-4 py-2 bg-dokun-green text-white rounded-full hover:bg-dokun-green/90 transition shadow text-sm font-semibold">{{ __('app.nav_join') }}</a>
 @endauth
 </div>

 {{-- Mobile: lang toggle + hamburger --}}
 <div class="flex lg:hidden items-center gap-2">
 <form action="{{ route('locale.switch', App::getLocale() === 'fr' ? 'en' : 'fr') }}" method="POST">
 @csrf
 <button type="submit" class="nav-pill text-xs font-bold px-3 py-2 rounded-lg border transition">
 {{ App::getLocale() === 'fr' ? 'EN' : 'FR' }}
 </button>
 </form>
 <button @click="mobileOpen = !mobileOpen" class="nav-pill p-2 rounded-lg border transition" aria-label="Menu">
 <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path x-show="!mobileOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
 <path x-show="mobileOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
 </svg>
 </button>
 </div>
 </div>
 </div>

 {{-- Mobile Menu --}}
 <div x-show="mobileOpen" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-2" x-cloak class="lg:hidden bg-white border-t border-gray-100 shadow-xl">
 <div class="max-w-7xl mx-auto px-4 py-5">

 {{-- User profile (auth) --}}
 @auth
 <a href="{{ route('visitor.profile') }}" class="flex items-center gap-3 p-4 bg-dokun-ivory rounded-2xl mb-4 hover:shadow-md transition">
 <span class="w-11 h-11 bg-dokun-green text-white rounded-full flex items-center justify-center text-base font-bold flex-shrink-0">{{ substr(Auth::user()->name,0,1) }}</span>
 <div class="min-w-0">
 <p class="font-bold text-dokun-charcoal text-sm truncate">{{ Auth::user()->name }}</p>
 <p class="text-dokun-gold text-xs font-semibold">{{ __('app.nav_my_trip') }}</p>
 </div>
 <svg class="w-4 h-4 text-dokun-gold ml-auto flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
 </a>
 <div class="grid grid-cols-2 gap-1.5 mb-4">
 <a href="{{ route('visitor.passport') }}" class="flex items-center gap-2 py-3 px-3 rounded-xl hover:bg-dokun-ivory font-semibold text-sm text-dokun-gold">
 <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l9-3 9 3v12l-9 3-9-3V6zm9 3v11"/></svg>
 {{ __('app.pp_title') }}
 </a>
 <a href="{{ route('dashboard') }}" class="flex items-center gap-2 py-3 px-3 rounded-xl hover:bg-dokun-ivory font-semibold text-sm text-dokun-charcoal">
 <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l9-9 9 9M5 10v10a1 1 0 001 1h3a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1h3a1 1 0 001-1V10"/></svg>
 {{ __('app.admin_dashboard') }}
 </a>
 </div>
 @endauth

 {{-- Nav links --}}
 <p class="text-[10px] uppercase tracking-[0.2em] font-bold text-dokun-gold px-2 mb-2">{{ __('app.footer_explore') }}</p>
 <div class="grid grid-cols-2 gap-1 mb-4">
 <a href="{{ route('savoir-faire.index') }}" class="flex items-center gap-2 py-2.5 px-3 rounded-xl hover:bg-dokun-ivory font-semibold text-sm {{ $active==='savoir-faire'?'bg-dokun-ivory text-dokun-gold':'' }}">{{ __('app.nav_savoir') }}</a>
 <a href="{{ route('artisans.index') }}" class="flex items-center gap-2 py-2.5 px-3 rounded-xl hover:bg-dokun-ivory font-semibold text-sm {{ $active==='artisans'?'bg-dokun-ivory text-dokun-gold':'' }}">{{ __('app.nav_artisans') }}</a>
 <a href="{{ route('carte') }}" class="flex items-center gap-2 py-2.5 px-3 rounded-xl hover:bg-dokun-ivory font-semibold text-sm {{ $active==='carte'?'bg-dokun-ivory text-dokun-gold':'' }}">{{ __('app.nav_carte') }}</a>
 <a href="{{ route('experiences.index') }}" class="flex items-center gap-2 py-2.5 px-3 rounded-xl hover:bg-dokun-ivory font-semibold text-sm {{ $active==='experiences'?'bg-dokun-ivory text-dokun-gold':'' }}">{{ __('app.nav_experiences') }}</a>
 <a href="{{ route('learn.index') }}" class="flex items-center gap-2 py-2.5 px-3 rounded-xl hover:bg-dokun-ivory font-semibold text-sm {{ $active==='learn'?'bg-dokun-ivory text-dokun-gold':'' }}">{{ __('app.nav_learn') }}</a>
 <a href="{{ route('play.index') }}" class="flex items-center gap-2 py-2.5 px-3 rounded-xl hover:bg-dokun-ivory font-semibold text-sm {{ $active==='play'?'bg-dokun-ivory text-dokun-gold':'' }}">{{ __('app.play_badge') }}</a>
 <a href="{{ route('moments.index') }}" class="flex items-center gap-2 py-2.5 px-3 rounded-xl hover:bg-dokun-ivory font-semibold text-sm {{ $active==='moments'?'bg-dokun-ivory text-dokun-gold':'' }}">{{ __('app.nav_moments') }}</a>
 <a href="{{ route('about') }}" class="flex items-center gap-2 py-2.5 px-3 rounded-xl hover:bg-dokun-ivory font-semibold text-sm {{ $active==='about'?'bg-dokun-ivory text-dokun-gold':'' }}">{{ __('app.nav_about') }}</a>
 <a href="{{ route('contact') }}" class="flex items-center gap-2 py-2.5 px-3 rounded-xl hover:bg-dokun-ivory font-semibold text-sm {{ $active==='contact'?'bg-dokun-ivory text-dokun-gold':'' }}">{{ __('app.nav_contact') }}</a>
 </div>

 {{-- Auth footer --}}
 @auth
 <form method="POST" action="{{ route('logout') }}">
 @csrf
 <button type="submit" class="flex items-center justify-center gap-2 w-full py-3 bg-red-50 text-red-600 font-bold rounded-xl text-sm">
 <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
 {{ __('app.nav_logout') }}
 </button>
 </form>
 @else
 <div class="grid grid-cols-2 gap-2 mt-1">
 <a href="{{ route('login') }}" class="flex items-center justify-center w-full py-3 bg-dokun-gold text-white font-bold rounded-xl text-sm">{{ __('app.nav_login') }}</a>
 <a href="{{ route('actor-requests.form') }}" class="flex items-center justify-center w-full py-3 bg-dokun-green text-white font-bold rounded-xl text-sm">{{ __('app.nav_join') }}</a>
 </div>
 @endauth
 </div>
 </div>
</nav>

<script>
 (function(){
 const nav=document.getElementById('navbar');
 if(!nav)return;
 const isOverlay=nav.classList.contains('nav-overlay');
 if(!isOverlay)return;
 const apply=()=>{
 if(window.scrollY>60){nav.classList.remove('nav-overlay');nav.classList.add('nav-scrolled');}
 else{nav.classList.add('nav-overlay');nav.classList.remove('nav-scrolled');}
 };
 window.addEventListener('scroll',apply,{passive:true});
 apply();
 })();
</script>
