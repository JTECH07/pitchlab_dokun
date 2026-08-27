{{-- Shared Navbar Partial --}}
{{-- Usage: @include('partials.navbar', ['active' => 'home']) --}}
@php
    $active = $active ?? '';
    $transparent = $transparent ?? false;
@endphp
<style>
    [x-cloak]{display:none !important;}
    /* --- Barre de navigation : couleurs pilotées par CSS pour la robustesse au scroll --- */
    #navbar .nav-link{position:relative;color:#17201D;opacity:.8;transition:color .3s,opacity .3s;}
    #navbar .nav-link:hover{color:#C99424;opacity:1;}
    #navbar .nav-link::after{content:'';position:absolute;left:50%;bottom:2px;width:0;height:2px;background:linear-gradient(90deg,#C99424,#064E3B);transform:translateX(-50%);transition:width .3s ease;border-radius:2px;}
    #navbar .nav-link:hover::after,#navbar .nav-link.is-active::after{width:70%;}
    #navbar .nav-link.is-active{color:#C99424;opacity:1;font-weight:700;}

    /* Navbar transparente (accueil, pas encore scrollé) */
    #navbar.nav-overlay{background:transparent;border-color:rgba(255,255,255,.12);}
    #navbar.nav-overlay .nav-link{color:#fff;opacity:.9;}
    #navbar.nav-overlay .nav-link:hover{color:#fff;opacity:1;}
    #navbar.nav-overlay .nav-link.is-active{color:#F2CE8A;opacity:1;}
    #navbar.nav-overlay .nav-brand{color:#fff;}
    #navbar.nav-overlay .nav-brand-sub{color:rgba(255,255,255,.75);}
    #navbar.nav-overlay .nav-tool{border-color:rgba(255,255,255,.3);color:#fff;}
    #navbar.nav-overlay .nav-tool:hover{background:rgba(255,255,255,.12);}
    #navbar .nav-tool{border-color:#e5e5e5;color:#17201D;}
    #navbar .nav-tool:hover{background:#F8F6F0;}

    .nav-brand{color:#17201D;transition:color .3s;}
    .nav-brand-sub{color:#17201D;opacity:.6;transition:color .3s;}
</style>
<script>
    (function () {
        if (window.Alpine) return;
        var s = document.createElement('script');
        s.src = 'https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js';
        s.defer = true;
        document.head.appendChild(s);
    })();
</script>
<nav id="navbar" class="fixed w-full z-50 transition-all duration-500 {{ $transparent ? 'nav-overlay' : 'bg-white border-gray-200 shadow-sm' }} border-b">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-18 py-3">
            <!-- Logo -->
            <a href="{{ route('home') }}" class="flex-shrink-0 flex items-center gap-3">
                <img src="{{ url('images/dokun_logo.png') }}" alt="ƉƆKUN" class="w-12 h-12 rounded-xl shadow-sm">
                <div class="flex flex-col leading-tight">
                    <span class="nav-brand font-serif text-2xl tracking-wide leading-none">ƉƆKUN</span>
                    <span class="nav-brand-sub text-[8px] tracking-[0.18em] font-semibold uppercase">{!! str_replace(' & ', ' &<br>', __('app.brand_tagline')) !!}</span>
                </div>
            </a>

            <!-- Desktop Links -->
            <div class="hidden lg:flex items-center gap-1 font-semibold text-sm">
                <a href="{{ route('savoir-faire.index') }}" class="nav-link px-3 py-2 rounded-lg {{ $active==='savoir-faire' ? 'is-active' : '' }}">{{ __('app.nav_savoir') }}</a>
                <a href="{{ route('artisans.index') }}" class="nav-link px-3 py-2 rounded-lg {{ $active==='artisans' ? 'is-active' : '' }}">{{ __('app.nav_artisans') }}</a>
                <a href="{{ route('carte') }}" class="nav-link px-3 py-2 rounded-lg {{ $active==='carte' ? 'is-active' : '' }}">{{ __('app.nav_carte') }}</a>
                <a href="{{ route('experiences.index') }}" class="nav-link px-3 py-2 rounded-lg {{ $active==='experiences' ? 'is-active' : '' }}">{{ __('app.nav_experiences') }}</a>
                <a href="{{ route('learn.index') }}" class="nav-link px-3 py-2 rounded-lg {{ $active==='learn' ? 'is-active' : '' }}">{{ __('app.nav_learn') }}</a>
                <a href="{{ route('play.index') }}" class="nav-link px-3 py-2 rounded-lg {{ $active==='play' ? 'is-active' : '' }}">🎮 {{ __('app.play_badge') }}</a>
                <a href="{{ route('about') }}" class="nav-link px-3 py-2 rounded-lg {{ $active==='about' ? 'is-active' : '' }}">{{ __('app.nav_about') }}</a>
            </div>

            <!-- Right actions -->
            <div class="hidden lg:flex items-center gap-2">
                <a href="{{ route('contact') }}" class="nav-link px-3 py-2 font-bold  text-sm rounded-lg {{ $active==='contact' ? 'is-active' : '' }}">{{ __('app.nav_contact') }}</a>

                @if(isset($showCurrency) ? $showCurrency : false)
                <!-- Devise (restreinte aux pages expériences/réservation) -->
                <div class="relative" x-data="{ open: false }" @click.away="open = false">
                    <button @click="open = !open" class="nav-tool flex items-center gap-1 text-xs font-bold px-2.5 py-2 rounded-lg border transition">
                        <span>{{ $currencyInfo['flag'] ?? '💰' }}</span>
                        <span>{{ $currentCurrency ?? 'XOF' }}</span>
                        <svg class="w-3 h-3 opacity-50" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="open" x-transition.opacity x-cloak
                         class="absolute right-0 mt-2 w-44 bg-white rounded-xl shadow-xl border border-gray-100 z-50 overflow-hidden">
                        @foreach($allCurrencies as $code => $info)
                            <a href="{{ route('home', ['currency' => $code]) }}" class="flex items-center gap-2 px-4 py-2 text-sm text-dokun-charcoal hover:bg-dokun-ivory font-semibold {{ ($currentCurrency ?? 'XOF') === $code ? 'bg-dokun-ivory text-dokun-green' : '' }}">
                                <span>{{ $info['flag'] }}</span><span>{{ $info['label'] }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Langue -->
                <div class="relative" x-data="{ open: false }" @click.away="open = false">
                    <button @click="open = !open" class="nav-tool flex items-center gap-1 text-xs font-bold px-2.5 py-2 rounded-lg border transition">
                        <span>{{ App::getLocale() === 'fr' ? '🇫🇷' : '🇬🇧' }}</span>
                        <span>{{ strtoupper(App::getLocale()) }}</span>
                        <svg class="w-3 h-3 opacity-50" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="open" x-transition.opacity x-cloak
                         class="absolute right-0 mt-2 w-36 bg-white rounded-xl shadow-xl border border-gray-100 z-50 overflow-hidden">
                        <form action="{{ route('locale.switch', 'fr') }}" method="POST">
                            @csrf
                            <button type="submit" class="flex items-center gap-2 px-4 py-2.5 text-sm text-dokun-charcoal hover:bg-dokun-ivory font-semibold w-full {{ App::getLocale() === 'fr' ? 'bg-dokun-ivory text-dokun-green' : '' }}">
                                <span>🇫🇷</span><span>Français</span>
                            </button>
                        </form>
                        <form action="{{ route('locale.switch', 'en') }}" method="POST">
                            @csrf
                            <button type="submit" class="flex items-center gap-2 px-4 py-2.5 text-sm text-dokun-charcoal hover:bg-dokun-ivory font-semibold w-full {{ App::getLocale() === 'en' ? 'bg-dokun-ivory text-dokun-green' : '' }}">
                                <span>🇬🇧</span><span>English</span>
                            </button>
                        </form>
                    </div>
                </div>

                @auth
                    <div class="relative" x-data="{ open: false }" @click.away="open = false">
                        <button @click="open = !open" class="flex items-center gap-2 px-4 py-2.5 bg-dokun-green text-white rounded-full hover:bg-dokun-green/90 transition shadow-md text-sm font-semibold">
                            <span class="w-5 h-5 bg-dokun-gold rounded-full flex items-center justify-center text-xs font-bold">{{ substr(Auth::user()->name, 0, 1) }}</span>
                            {{ __('app.nav_my_space') }}
                        </button>
                        <div x-show="open" x-transition.opacity x-cloak
                             class="absolute right-0 mt-2 w-52 bg-white rounded-xl shadow-xl border border-gray-100 z-50 overflow-hidden">
                            <a href="{{ route('visitor.profile') }}" class="flex items-center gap-2 px-4 py-3 text-sm text-dokun-charcoal hover:bg-dokun-ivory hover:text-dokun-green font-semibold">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                {{ __('app.nav_my_trip') }}
                            </a>
                            <a href="{{ route('visitor.passport') }}" class="flex items-center gap-2 px-4 py-3 text-sm text-[#C99424] hover:bg-[#C99424]/5 font-semibold">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l9-3 9 3v12l-9 3-9-3V6zm9 3v11M6 8v.01M12 8v.01M18 8v.01M6 11v.01M18 11v.01M6 14v.01M18 14v.01"/></svg>
                                {{ __('app.pp_title') }}
                            </a>
                            @if(Auth::user()->role === 'tourist')
                                <a href="{{ route('artisan.apply') }}" class="flex items-center gap-2 px-4 py-3 text-sm text-[#C99424] hover:bg-[#C99424]/5 font-semibold">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M14.7 6.3a1 1 0 000 1.4l1.6 1.6a1 1 0 001.4 0l3.77-3.77a6 6 0 01-7.94 7.94l-6.91 6.91a2.12 2.12 0 01-3-3l6.91-6.91a6 6 0 017.94-7.94l-3.76 3.76z"/></svg>
                                    {{ __('app.auth_actor_link_cta') }}
                                </a>
                            @endif
                            <a href="{{ route('dashboard') }}" class="flex items-center gap-2 px-4 py-3 text-sm text-dokun-charcoal hover:bg-dokun-ivory hover:text-dokun-green font-semibold">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l9-9 9 9M5 10v10a1 1 0 001 1h3a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1h3a1 1 0 001-1V10"/></svg>
                                {{ __('app.admin_dashboard') }}
                            </a>
                            <div class="my-1 border-t border-gray-100"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="flex items-center gap-2 w-full text-left px-4 py-3 text-sm text-red-600 hover:bg-red-50 rounded-b-xl font-semibold">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                    {{ __('app.nav_logout') }}
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('actor-requests.form') }}" class="px-4 py-2.5 bg-dokun-green text-white rounded-full hover:bg-dokun-green/90 transition shadow-md text-sm font-semibold">{{ __('app.nav_join') }}</a>
                    <a href="{{ route('login') }}" class="px-4 py-2.5 bg-dokun-gold text-white rounded-full hover:bg-yellow-600 transition shadow-md text-sm font-semibold">{{ __('app.nav_login') }}</a>
                @endauth
            </div>

            <!-- Mobile: lang toggle + hamburger -->
            <div class="flex lg:hidden items-center gap-2">
                <form action="{{ route('locale.switch', App::getLocale() === 'fr' ? 'en' : 'fr') }}" method="POST">
                    @csrf
                    <button type="submit" class="nav-tool text-sm font-bold px-3 py-2 rounded-lg border transition">
                        {{ App::getLocale() === 'fr' ? '🇬🇧 EN' : '🇫🇷 FR' }}
                    </button>
                </form>
                <button id="mobile-menu-btn" class="nav-tool p-2.5 rounded-lg border transition" aria-label="Menu">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path id="hamburger-icon" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div id="mobile-menu" class="hidden lg:hidden bg-white border-t border-gray-100 text-dokun-charcoal shadow-xl">
        <div class="max-w-7xl mx-auto px-4 py-5">

            <p class="text-[10px] uppercase tracking-[0.2em] font-bold text-dokun-gold px-2 mb-2">{{ __('app.footer_explore') }}</p>
            <div class="grid grid-cols-2 gap-1 mb-5">
                <a href="{{ route('savoir-faire.index') }}" class="flex items-center gap-2.5 py-3 px-3 rounded-xl hover:bg-dokun-ivory font-semibold text-sm {{ $active==='savoir-faire' ? 'bg-dokun-ivory text-dokun-gold' : '' }}">
                    <svg class="w-5 h-5 text-dokun-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 3h6m-5 0v6L4.8 18.3A2 2 0 006.4 21h11.2a2 2 0 001.6-2.7L14 9V3"/></svg>{{ __('app.nav_savoir') }}
                </a>
                <a href="{{ route('artisans.index') }}" class="flex items-center gap-2.5 py-3 px-3 rounded-xl hover:bg-dokun-ivory font-semibold text-sm {{ $active==='artisans' ? 'bg-dokun-ivory text-dokun-gold' : '' }}">
                    <svg class="w-5 h-5 text-dokun-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 12a4 4 0 100-8 4 4 0 000 8zm7 9a7 7 0 00-14 0"/></svg>{{ __('app.nav_artisans') }}
                </a>
                <a href="{{ route('carte') }}" class="flex items-center gap-2.5 py-3 px-3 rounded-xl hover:bg-dokun-ivory font-semibold text-sm {{ $active==='carte' ? 'bg-dokun-ivory text-dokun-gold' : '' }}">
                    <svg class="w-5 h-5 text-dokun-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 21s7-5.2 7-12a7 7 0 10-14 0c0 6.8 7 12 7 12z"/><circle cx="12" cy="9" r="2"/></svg>{{ __('app.nav_carte') }}
                </a>
                <a href="{{ route('experiences.index') }}" class="flex items-center gap-2.5 py-3 px-3 rounded-xl hover:bg-dokun-ivory font-semibold text-sm {{ $active==='experiences' ? 'bg-dokun-ivory text-dokun-gold' : '' }}">
                    <svg class="w-5 h-5 text-dokun-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 3v3m8-3v3M4 10h16M6 5h12a2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V7a2 2 0 012-2z"/></svg>{{ __('app.nav_experiences') }}
                </a>
                <a href="{{ route('learn.index') }}" class="flex items-center gap-2.5 py-3 px-3 rounded-xl hover:bg-dokun-ivory font-semibold text-sm {{ $active==='learn' ? 'bg-dokun-ivory text-dokun-gold' : '' }}">
                    <svg class="w-5 h-5 text-dokun-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>{{ __('app.nav_learn') }}
                </a>
                <a href="{{ route('play.index') }}" class="flex items-center gap-2.5 py-3 px-3 rounded-xl hover:bg-dokun-ivory font-semibold text-sm {{ $active==='play' ? 'bg-dokun-ivory text-dokun-gold' : '' }}">
                    <svg class="w-5 h-5 text-dokun-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.196-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118L2.977 10.1c-.783-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>{{ __('app.play_badge') }}
                </a>
                <a href="{{ route('about') }}" class="flex items-center gap-2.5 py-3 px-3 rounded-xl hover:bg-dokun-ivory font-semibold text-sm {{ $active==='about' ? 'bg-dokun-ivory text-dokun-gold' : '' }}">
                    <svg class="w-5 h-5 text-dokun-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>{{ __('app.nav_about') }}
                </a>
                <a href="{{ route('contact') }}" class="flex items-center gap-2.5 py-3 px-3 rounded-xl hover:bg-dokun-ivory font-semibold text-sm {{ $active==='contact' ? 'bg-dokun-ivory text-dokun-gold' : '' }}">
                    <svg class="w-5 h-5 text-dokun-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>{{ __('app.nav_contact') }}
                </a>
            </div>

            @if(isset($showCurrency) ? $showCurrency : false)
            <p class="text-[10px] uppercase tracking-[0.2em] font-bold text-dokun-gold px-2 mb-2">{{ __('exp_your_currency') }}</p>
            <div class="flex flex-wrap gap-2 px-2 mb-5">
                @foreach($allCurrencies as $code => $info)
                    <a href="{{ route('home', ['currency' => $code]) }}" class="flex-1 text-center py-2 text-xs font-bold rounded-lg border transition {{ ($currentCurrency ?? 'XOF') === $code ? 'bg-dokun-gold text-white border-dokun-gold' : 'bg-white text-dokun-charcoal border-gray-200' }}">
                        {{ $info['flag'] }} {{ $code }}
                    </a>
                @endforeach
            </div>
            @endif

            @auth
                <a href="{{ route('visitor.profile') }}" class="flex items-center gap-2 py-3.5 px-3 bg-dokun-ivory rounded-xl font-semibold text-sm mb-2">
                    <span class="w-6 h-6 bg-dokun-green text-white rounded-full flex items-center justify-center text-xs font-bold">{{ substr(Auth::user()->name,0,1) }}</span>
                    {{ Auth::user()->name }}
                </a>
                <a href="{{ route('visitor.passport') }}" class="flex items-center gap-2 py-3 px-3 rounded-xl font-semibold text-sm mb-2 text-dokun-gold">
                    🎫 {{ __('app.pp_title') }}
                </a>
                <a href="{{ route('dashboard') }}" class="flex items-center justify-center gap-2 w-full py-3.5 bg-dokun-green text-white font-bold rounded-xl mb-2">
                    {{ __('app.admin_dashboard') }}
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center justify-center gap-2 w-full py-3.5 bg-red-50 text-red-600 font-bold rounded-xl">
                        {{ __('app.nav_logout') }}
                    </button>
                </form>
            @else
                <div class="grid grid-cols-2 gap-2">
                    <a href="{{ route('login') }}" class="flex items-center justify-center gap-2 w-full py-3.5 bg-dokun-gold text-white font-bold rounded-xl">
                        {{ __('app.nav_login') }}
                    </a>
                    <a href="{{ route('actor-requests.form') }}" class="flex items-center justify-center gap-2 w-full py-3.5 bg-dokun-green text-white font-bold rounded-xl">
                        {{ __('app.nav_join') }}
                    </a>
                </div>
            @endauth
        </div>
    </div>
</nav>

<script>
    (function () {
        const nav = document.getElementById('navbar');
        if (!nav) return;
        const isOverlay = nav.classList.contains('nav-overlay');
        const btn = document.getElementById('mobile-menu-btn');
        const menu = document.getElementById('mobile-menu');
        if (btn && menu) {
            btn.addEventListener('click', () => { menu.classList.toggle('hidden'); });
        }
        if (!isOverlay) return;
        const apply = () => {
            if (window.scrollY > 60) {
                nav.classList.remove('nav-overlay','border-white/10','text-white');
                nav.classList.add('bg-white','border-gray-200','shadow-sm');
            } else {
                nav.classList.add('nav-overlay');
                nav.classList.remove('bg-white','border-gray-200','shadow-sm');
            }
        };
        window.addEventListener('scroll', apply, { passive: true });
        apply();
    })();
</script>
