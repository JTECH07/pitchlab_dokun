{{-- Shared Footer Partial --}}
<footer class="bg-dokun-charcoal text-white">
    <!-- Main Footer -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-16 pb-10 grid grid-cols-1 md:grid-cols-4 gap-10">
        <!-- Brand -->
        <div class="md:col-span-2">
            <div class="flex items-center gap-3 mb-5">
                <div class="w-12 h-12 bg-dokun-green rounded flex items-center justify-center shrink-0 overflow-hidden"><img src="{{ asset('images/dokun_logo.png') }}" alt="Logo ƉƆKUN" class="w-full h-full object-contain"></div>
                <div>
                    <span class="font-serif text-2xl tracking-wide block">ƉƆKUN</span>
                    <span class="text-[10px] tracking-[0.15em] text-white/50 uppercase">Patrimoine Vivant & Tourisme Culturel</span>
                </div>
            </div>
            <p class="text-white/60 max-w-sm leading-relaxed text-sm mb-6">
                Le patrimoine vivant, une richesse partagée.<br>
                <em class="text-dokun-gold/80">Transmettre · Valoriser · Connecter.</em>
            </p>
            <div class="flex gap-3">
                <span class="text-xs px-3 py-1.5 rounded-full bg-white/10 text-white/60">Porto-Novo, Bénin</span>
                <span class="text-xs px-3 py-1.5 rounded-full bg-white/10 text-white/60">Projet PitchLab 2026</span>
            </div>
        </div>

        <!-- Navigation -->
        <div>
            <h4 class="font-bold text-sm mb-5 text-dokun-gold uppercase tracking-wider">Explorer</h4>
            <ul class="space-y-3 text-white/70 text-sm">
                <li><a href="{{ route('savoir-faire.index') }}" class="hover:text-white hover:pl-1 transition-all">Savoir-faire</a></li>
                <li><a href="{{ route('artisans.index') }}" class="hover:text-white hover:pl-1 transition-all">Artisans</a></li>
                <li><a href="{{ route('carte') }}" class="hover:text-white hover:pl-1 transition-all">Carte interactive</a></li>
                <li><a href="{{ route('experiences.index') }}" class="hover:text-white hover:pl-1 transition-all">Expériences</a></li>
            </ul>
        </div>

        <!-- Pro Access -->
        <div>
            <h4 class="font-bold text-sm mb-5 text-dokun-gold uppercase tracking-wider">Accès Pro</h4>
            <ul class="space-y-3 text-white/70 text-sm">
                <li><a href="{{ route('login') }}" class="hover:text-white hover:pl-1 transition-all">Connexion</a></li>
                <li><a href="{{ route('register') }}" class="hover:text-white hover:pl-1 transition-all">S'inscrire</a></li>
                @auth
                <li><a href="{{ route('dashboard') }}" class="hover:text-white hover:pl-1 transition-all">Mon espace</a></li>
                @endauth
            </ul>
        </div>
    </div>

    <!-- Bottom Bar -->
    <div class="border-t border-white/10 py-5">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row justify-between items-center gap-3 text-white/40 text-xs">
            <p>© 2026 ƉƆKUN — Projet PitchLab Porto-Novo. Tous droits réservés.</p>
            <p>Valoriser le patrimoine vivant du Bénin.</p>
        </div>
    </div>
</footer>
