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
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.7a1 1 0 01.7.3l4.3 4.3a1 1 0 010 1.4l-4.3 4.3a1 1 0 01-1.4 0l-4.3-4.3a1 1 0 010-1.4l4.3-4.3a1 1 0 01.7-.3zM7.5 12h.01"/></svg>
                Savoir-Faire
            </a>
            <a href="{{ route('admin.reservations.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-white/70 hover:bg-white/10 hover:text-white transition-colors font-semibold {{ request()->routeIs('admin.reservations.*') ? 'bg-dokun-gold/20 text-dokun-gold' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                Réservations
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

</body>
</html>
