{{-- Shared Footer Partial --}}
<footer class="bg-dokun-charcoal text-white">
 <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-16 pb-10 grid grid-cols-1 md:grid-cols-4 gap-10">
 <!-- Brand -->
 <div class="md:col-span-2">
 <div class="flex items-center gap-3 mb-5">
 <div class="w-12 h-12 bg-dokun-green rounded-xl flex items-center justify-center shrink-0 overflow-hidden"><img src="{{ url('images/dokun_logo_final.jpeg') }}" alt="Logo ƉƆKUN" class="w-full h-full object-contain"></div>
 <div class="flex flex-col leading-tight">
 <span class="font-serif text-3xl">ƉƆKUN</span>
    <span class="text-[10px] tracking-[0.2em] font-bold uppercase">{!! str_replace("patrimoine,", "patrimoine<br>", __('app.brand_tagline')) !!}</span>
 </div>
 </div>
 <p class="text-white/60 max-w-sm leading-relaxed text-sm mb-3">
 {{ __('app.footer_desc') }}
 </p>
 <p class="text-white/80 max-w-sm leading-relaxed text-sm font-semibold mb-1">
 {{ __('app.footer_tagline') }}
 </p>
 <p class="text-dokun-gold/80 max-w-sm leading-relaxed text-sm mb-6">
 {{ __('app.footer_tagline_tag') }}
 </p>
 <div class="flex flex-wrap gap-3">
 <span class="text-xs px-3 py-1.5 rounded-full bg-white/10 text-white/60">
 <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
 {{ __('app.footer_location') }}
 </span>
 <span class="text-xs px-3 py-1.5 rounded-full bg-white/10 text-white/60">
 <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-6 0h6"/></svg>
 {{ __('app.footer_pitchlab') }}
 </span>
 </div>
 <div class="mt-5">
 <a href="mailto:{{ env('MAIL_FROM_ADDRESS') }}" class="text-xs px-3 py-1.5 rounded-full bg-white/10 text-white/60 hover:text-white hover:bg-white/20 transition-all">
 <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
 {{ env('MAIL_FROM_ADDRESS') }}
 </a>
 </div>
 </div>

 <!-- Navigation -->
 <div>
 <h4 class="font-bold text-sm mb-5 text-dokun-gold uppercase tracking-wider">{{ __('app.footer_explore') }}</h4>
 <ul class="space-y-3 text-white/70 text-sm">
 <li><a href="{{ route('savoir-faire.index') }}" class="hover:text-white hover:pl-1 transition-all">{{ __('app.nav_savoir') }}</a></li>
 <li><a href="{{ route('artisans.index') }}" class="hover:text-white hover:pl-1 transition-all">{{ __('app.nav_artisans') }}</a></li>
 <li><a href="{{ route('carte') }}" class="hover:text-white hover:pl-1 transition-all">{{ __('app.nav_carte') }}</a></li>
 <li><a href="{{ route('experiences.index') }}" class="hover:text-white hover:pl-1 transition-all">{{ __('app.nav_experiences') }}</a></li>
 <li><a href="{{ route('about') }}" class="hover:text-white hover:pl-1 transition-all">{{ __('app.nav_about') }}</a></li>
 <li><a href="{{ route('contact') }}" class="hover:text-white hover:pl-1 transition-all">{{ __('app.nav_contact') }}</a></li>
 </ul>
 </div>

 <!-- Pro Access -->
 <div>
 <h4 class="font-bold text-sm mb-5 text-dokun-gold uppercase tracking-wider">{{ __('app.footer_access') }}</h4>
 <ul class="space-y-3 text-white/70 text-sm">
 @auth
 <li><a href="{{ route('visitor.profile') }}" class="hover:text-white hover:pl-1 transition-all">{{ __('app.footer_my_space') }}</a></li>
 <li><a href="{{ route('dashboard') }}" class="hover:text-white hover:pl-1 transition-all">{{ __('app.admin_dashboard') }}</a></li>
 <li>
 <form method="POST" action="{{ route('logout') }}">
 @csrf
 <button type="submit" class="hover:text-white hover:pl-1 transition-all">{{ __('app.nav_logout') }}</button>
 </form>
 </li>
 @else
 <li><a href="{{ route('login') }}" class="hover:text-white hover:pl-1 transition-all">{{ __('app.footer_login') }}</a></li>
 <li><a href="{{ route('register') }}" class="hover:text-white hover:pl-1 transition-all">{{ __('app.footer_register') }}</a></li>
 <li><a href="{{ route('actor-requests.form') }}" class="hover:text-white hover:pl-1 transition-all text-dokun-gold">{{ __('app.nav_join') }}</a></li>
 @endauth
 </ul>
 </div>
 </div>

 <!-- Bottom Bar -->
 <div class="border-t border-white/10 py-5">
 <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row justify-between items-center gap-3 text-white/40 text-xs">
 <p>© 2026 ƉƆKUN — Porto-Novo. {{ __('app.footer_rights') }}</p>
 <p>{{ __('app.footer_rights_note') }}</p>
 </div>
 </div>
</footer>
