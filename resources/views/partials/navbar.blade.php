{{-- Shared Navbar Partial --}}
{{-- Usage: @include('partials.navbar', ['active' => 'home']) --}}
@php $active = $active ?? ''; @endphp
<nav id="navbar" class="fixed w-full z-50 transition-all duration-500 {{ $transparent ?? false ? 'bg-transparent border-white/10 text-white' : 'bg-white border-gray-200 text-dokun-charcoal shadow-sm' }}  border-b">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-20">
            <!-- Logo -->
            <a href="{{ route('home') }}" class="flex-shrink-0 flex items-center gap-3">
                <div class="w-12 h-12 rounded overflow-hidden flex items-center justify-center bg-dokun-green">
                    <img src="{{ asset('images/dokun_logo.png') }}" alt="ƉƆKUN" class="w-full h-full object-contain" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex'">
                    <span class="hidden text-dokun-gold font-bold text-2xl">Ɖ</span>
                </div>
                <div class="flex flex-col leading-tight">
                    <span class="font-serif text-2xl tracking-wide leading-none">ƉƆKUN</span>
                    <span class="text-[9px] tracking-[0.15em] font-semibold opacity-70 uppercase">Patrimoine Vivant</span>
                </div>
            </a>

            <!-- Desktop Links -->
            <div class="hidden md:flex items-center gap-7 font-semibold text-sm">
                <a href="{{ route('home') }}#savoir-faire" class="hover:text-dokun-gold transition-colors {{ $active==='savoir-faire' ? 'text-dokun-gold' : '' }}">Savoir-faire</a>
                <a href="{{ route('artisans.index') }}" class="hover:text-dokun-gold transition-colors {{ $active==='artisans' ? 'text-dokun-gold' : '' }}">Artisans</a>
                <a href="{{ route('carte') }}" class="hover:text-dokun-gold transition-colors {{ $active==='carte' ? 'text-dokun-gold' : '' }}">Carte</a>
                <a href="{{ route('experiences.index') }}" class="hover:text-dokun-gold transition-colors {{ $active==='experiences' ? 'text-dokun-gold' : '' }}">Expériences</a>
                @auth
                    <a href="{{ url('/dashboard') }}" class="px-5 py-2.5 bg-dokun-green text-white rounded-full hover:bg-dokun-green/90 transition shadow-lg text-sm">Mon Espace</a>
                @else
                    <a href="{{ route('login') }}" class="px-5 py-2.5 bg-dokun-gold text-white rounded-full hover:bg-yellow-600 transition shadow-lg text-sm">Connexion</a>
                @endauth
            </div>

            <!-- Mobile Hamburger -->
            <button id="mobile-menu-btn" class="md:hidden p-2 rounded-lg hover:bg-black/10 transition" aria-label="Menu">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path id="hamburger-icon" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div id="mobile-menu" class="hidden md:hidden bg-white border-t border-gray-100 text-dokun-charcoal shadow-xl">
        <div class="max-w-7xl mx-auto px-4 py-6 space-y-1">
            <a href="{{ route('home') }}#savoir-faire" class="flex items-center gap-3 py-3 px-4 rounded-xl hover:bg-dokun-ivory font-semibold text-base">
                <svg class="w-5 h-5 text-dokun-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 3h6m-5 0v6L4.8 18.3A2 2 0 006.4 21h11.2a2 2 0 001.6-2.7L14 9V3"/></svg> Savoir-faire
            </a>
            <a href="{{ route('artisans.index') }}" class="flex items-center gap-3 py-3 px-4 rounded-xl hover:bg-dokun-ivory font-semibold text-base">
                <svg class="w-5 h-5 text-dokun-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 12a4 4 0 100-8 4 4 0 000 8zm7 9a7 7 0 00-14 0"/></svg> Artisans
            </a>
            <a href="{{ route('carte') }}" class="flex items-center gap-3 py-3 px-4 rounded-xl hover:bg-dokun-ivory font-semibold text-base">
                <svg class="w-5 h-5 text-dokun-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 21s7-5.2 7-12a7 7 0 10-14 0c0 6.8 7 12 7 12z"/><circle cx="12" cy="9" r="2"/></svg> Carte interactive
            </a>
            <a href="{{ route('experiences.index') }}" class="flex items-center gap-3 py-3 px-4 rounded-xl hover:bg-dokun-ivory font-semibold text-base">
                <svg class="w-5 h-5 text-dokun-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 3v3m8-3v3M4 10h16M6 5h12a2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V7a2 2 0 012-2z"/></svg> Expériences
            </a>
            <div class="pt-4 border-t border-gray-100">
                @auth
                    <a href="{{ url('/dashboard') }}" class="flex items-center justify-center gap-2 w-full py-4 bg-dokun-green text-white font-bold rounded-xl">
                        Mon Espace
                    </a>
                @else
                    <a href="{{ route('login') }}" class="flex items-center justify-center gap-2 w-full py-4 bg-dokun-gold text-white font-bold rounded-xl">
                        Se connecter
                    </a>
                @endauth
            </div>
        </div>
    </div>
</nav>

<script>
    const btn = document.getElementById('mobile-menu-btn');
    const menu = document.getElementById('mobile-menu');
    if (btn && menu) {
        btn.addEventListener('click', () => { menu.classList.toggle('hidden'); });
    }
    // Navbar scroll effect (only if transparent mode)
    @if($transparent ?? false)
    const nav = document.getElementById('navbar');
    window.addEventListener('scroll', () => {
        if (window.scrollY > 60) {
            nav.classList.replace('bg-transparent','bg-white');
            nav.classList.replace('text-white','text-dokun-charcoal');
            nav.classList.replace('border-white/10','border-gray-200');
            nav.classList.add('shadow-sm');
        } else {
            nav.classList.replace('bg-white','bg-transparent');
            nav.classList.replace('text-dokun-charcoal','text-white');
            nav.classList.replace('border-gray-200','border-white/10');
            nav.classList.remove('shadow-sm');
        }
    });
    @endif
</script>
