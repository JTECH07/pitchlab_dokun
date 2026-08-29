<!DOCTYPE html>
<html lang="fr">
<head>
 <meta charset="utf-8">
 <meta name="viewport" content="width=device-width, initial-scale=1">
 <title>@yield('title', 'Admin') — ƉƆKUN Admin</title>
 <link href="https://fonts.bunny.net/css?family=dm-serif-display:400|manrope:400,600,700,800&display=swap" rel="stylesheet">
 <script src="https://cdn.tailwindcss.com"></script>
 <script>
 tailwind.config = {
 theme: {
 extend: {
 colors: {
 dokun: {
 green: '#064E3B',
 gold: '#C99424',
 ivory: '#F8F6F0',
 charcoal: '#17201D',
 }
 },
 fontFamily: {
 sans: ['Manrope', 'sans-serif'],
 serif: ['"DM Serif Display"', 'serif'],
 }
 }
 }
 }
 </script>
 <style>body { font-family: 'Manrope', sans-serif; }</style>
</head>
<body class="antialiased bg-dokun-ivory text-dokun-charcoal flex h-screen overflow-hidden">

 <!-- Sidebar -->
 <aside class="w-64 bg-dokun-charcoal flex flex-col h-full flex-shrink-0">
 <div class="p-6 border-b border-white/10">
 <div class="flex items-center gap-3">
 <div class="w-10 h-10 bg-dokun-green rounded flex items-center justify-center text-dokun-gold font-bold shadow-lg">Ɖ</div>
 <div>
 <span class="font-serif text-white tracking-wide text-xl">ƆKUN</span>
 <p class="text-xs text-white/50 font-bold uppercase tracking-wider">Espace Admin</p>
 </div>
 </div>
 </div>

 <nav class="flex-1 p-4 space-y-2">
 <p class="text-white/40 text-xs font-bold uppercase tracking-wider px-3 py-2">Navigation</p>
 <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-white/70 hover:bg-white/10 hover:text-white transition-colors font-semibold {{ request()->routeIs('dashboard') ? 'bg-white/10 text-white' : '' }}">
 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2 2v0M3 7l9-4 9 4"/></svg>
 Tableau de bord
 </a>
 <a href="{{ route('admin.artisans.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-white/70 hover:bg-white/10 hover:text-white transition-colors font-semibold {{ request()->routeIs('admin.artisans.*') ? 'bg-dokun-gold/20 text-dokun-gold' : '' }}">
 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
 Artisans
 </a>
 <a href="{{ route('admin.categories.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-white/70 hover:bg-white/10 hover:text-white transition-colors font-semibold {{ request()->routeIs('admin.categories.*') ? 'bg-dokun-gold/20 text-dokun-gold' : '' }}">
 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
 Catégories
 </a>
 <a href="{{ route('admin.savoir-faires.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-white/70 hover:bg-white/10 hover:text-white transition-colors font-semibold {{ request()->routeIs('admin.savoir-faires.*') ? 'bg-dokun-gold/20 text-dokun-gold' : '' }}">
 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3-.895 3-2-1.343-2-3-2z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14c-4.418 0-8 1.79-8 4v2h16v-2c0-2.21-3.582-4-8-4z"/></svg>
 Savoir-Faire
 </a>
 <a href="{{ route('admin.reservations.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-white/70 hover:bg-white/10 hover:text-white transition-colors font-semibold {{ request()->routeIs('admin.reservations.*') ? 'bg-dokun-gold/20 text-dokun-gold' : '' }}">
 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
 Réservations
 </a>
 <a href="{{ route('admin.media.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-white/70 hover:bg-white/10 hover:text-white transition-colors font-semibold {{ request()->routeIs('admin.media.*') ? 'bg-dokun-gold/20 text-dokun-gold' : '' }}">
 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
 Médias
 </a>
 <a href="{{ route('admin.map') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-white/70 hover:bg-white/10 hover:text-white transition-colors font-semibold {{ request()->routeIs('admin.map') ? 'bg-dokun-gold/20 text-dokun-gold' : '' }}">
 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
 Carte
 </a>
 <a href="{{ route('admin.quartiers.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-white/70 hover:bg-white/10 hover:text-white transition-colors font-semibold {{ request()->routeIs('admin.quartiers.*') ? 'bg-dokun-gold/20 text-dokun-gold' : '' }}">
 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5zm0 0h5" /></svg>
 Quartiers
 </a>
 <a href="{{ route('admin.experiences.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-white/70 hover:bg-white/10 hover:text-white transition-colors font-semibold {{ request()->routeIs('admin.experiences.*') ? 'bg-dokun-gold/20 text-dokun-gold' : '' }}">
 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v2m0 14v2m9-9h-2M5 12H3m15.364 6.364l-1.414-1.414M6.05 6.05L4.636 4.636m12.728 0L15.95 6.05M6.05 17.95l-1.414 1.414M8 12a4 4 0 118 0 4 4 0 01-8 0z"/></svg>
 Expériences
 </a>
 <a href="{{ route('admin.reviews.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-white/70 hover:bg-white/10 hover:text-white transition-colors font-semibold {{ request()->routeIs('admin.reviews.*') ? 'bg-dokun-gold/20 text-dokun-gold' : '' }}">
 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.196-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
 Avis
 </a>
 <p class="text-white/40 text-xs font-bold uppercase tracking-wider px-3 py-2 mt-2">Comptes & demandes</p>
 <a href="{{ route('admin.applications.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-white/70 hover:bg-white/10 hover:text-white transition-colors font-semibold {{ request()->routeIs('admin.applications.*') ? 'bg-dokun-gold/20 text-dokun-gold' : '' }}">
 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
 Candidatures artisans
 </a>
 <a href="{{ route('admin.actor-requests.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-white/70 hover:bg-white/10 hover:text-white transition-colors font-semibold {{ request()->routeIs('admin.actor-requests.*') ? 'bg-dokun-gold/20 text-dokun-gold' : '' }}">
 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
 Demandes acteurs
 </a>
 <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-white/70 hover:bg-white/10 hover:text-white transition-colors font-semibold {{ request()->routeIs('admin.users.*') ? 'bg-dokun-gold/20 text-dokun-gold' : '' }}">
 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
 Utilisateurs
 </a>
 <div class="border-t border-white/10 my-4"></div>
 <a href="{{ route('home') }}" target="_blank" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-white/50 hover:bg-white/10 hover:text-white transition-colors font-semibold text-sm">
 <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
 Voir le site public
 </a>
 </nav>

 <div class="p-4 border-t border-white/10 bg-white/5">
 <div class="flex items-center gap-3 px-3 py-2">
 <div class="w-8 h-8 bg-dokun-gold rounded-full flex items-center justify-center text-white font-bold text-sm shadow-sm">{{ substr(Auth::user()->name, 0, 1) }}</div>
 <div class="flex-1 min-w-0">
 <p class="text-white text-sm font-bold truncate">{{ Auth::user()->name }}</p>
 <p class="text-white/50 text-xs">Administrateur</p>
 </div>
 </div>
 <form method="POST" action="{{ route('logout') }}" class="mt-2">
 @csrf
 <button class="w-full text-left px-4 py-2.5 text-white/50 hover:text-red-400 text-sm font-bold transition-colors rounded-xl hover:bg-white/5">
 ⎋ Déconnexion
 </button>
 </form>
 </div>
 </aside>

 <!-- Main Content -->
 <div class="flex-1 flex flex-col overflow-hidden">
 <!-- Top bar -->
 <header class="bg-white border-b border-gray-200 px-8 py-5 flex items-center justify-between flex-shrink-0">
 <h1 class="text-2xl font-serif text-dokun-green">@yield('page-title', 'Administration')</h1>
 @if(session('success'))
 <div class="bg-dokun-green border border-dokun-gold/30 text-white px-5 py-2.5 rounded-xl text-sm font-bold shadow-lg">
 {{ session('success') }}
 </div>
 @endif
 </header>

 <!-- Page Content -->
 <main class="flex-1 overflow-y-auto p-8 bg-gray-50/50">
 @yield('content')
 </main>
 </div>

 @yield('scripts')
 <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>
</html>
