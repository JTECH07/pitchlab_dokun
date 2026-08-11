<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin') — ƉƆKUN Admin</title>
    <link href="https://fonts.bunny.net/css?family=outfit:300,400,600,700,900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>body { font-family: 'Outfit', sans-serif; }</style>
</head>
<body class="antialiased bg-slate-100 text-slate-800 flex h-screen overflow-hidden">

    <!-- Sidebar -->
    <aside class="w-64 bg-slate-900 flex flex-col h-full flex-shrink-0">
        <div class="p-6 border-b border-slate-800">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-amber-500 rounded-full flex items-center justify-center text-white font-bold shadow-lg">Ɖ</div>
                <div>
                    <span class="font-black text-white tracking-tight text-lg">ƆKUN</span>
                    <p class="text-xs text-slate-400 font-medium">Espace Admin</p>
                </div>
            </div>
        </div>

        <nav class="flex-1 p-4 space-y-1">
            <p class="text-slate-500 text-xs font-bold uppercase tracking-wider px-3 py-2">Navigation</p>
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-slate-300 hover:bg-slate-800 hover:text-white transition-colors font-medium {{ request()->routeIs('dashboard') ? 'bg-slate-800 text-white' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2 2v0M3 7l9-4 9 4"/></svg>
                Tableau de bord
            </a>
            <a href="{{ route('admin.artisans.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-slate-300 hover:bg-slate-800 hover:text-white transition-colors font-medium {{ request()->routeIs('admin.artisans.*') ? 'bg-amber-500/20 text-amber-400' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                Artisans
            </a>
            <a href="{{ route('admin.reservations.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-slate-300 hover:bg-slate-800 hover:text-white transition-colors font-medium {{ request()->routeIs('admin.reservations.*') ? 'bg-amber-500/20 text-amber-400' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                Réservations
            </a>
            <div class="border-t border-slate-800 my-3"></div>
            <a href="{{ route('home') }}" target="_blank" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-slate-400 hover:bg-slate-800 hover:text-white transition-colors font-medium text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                Voir le site public
            </a>
        </nav>

        <div class="p-4 border-t border-slate-800">
            <div class="flex items-center gap-3 px-3 py-2">
                <div class="w-8 h-8 bg-amber-500/20 rounded-full flex items-center justify-center text-amber-400 font-bold text-sm">{{ substr(Auth::user()->name, 0, 1) }}</div>
                <div class="flex-1 min-w-0">
                    <p class="text-white text-sm font-bold truncate">{{ Auth::user()->name }}</p>
                    <p class="text-slate-400 text-xs">Administrateur</p>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}" class="mt-2">
                @csrf
                <button class="w-full text-left px-3 py-2 text-slate-400 hover:text-red-400 text-sm font-medium transition-colors rounded-lg hover:bg-slate-800">
                    ⎋ Déconnexion
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col overflow-hidden">
        <!-- Top bar -->
        <header class="bg-white border-b border-slate-200 px-8 py-4 flex items-center justify-between flex-shrink-0">
            <h1 class="text-xl font-black text-slate-900">@yield('page-title', 'Administration')</h1>
            @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-2 rounded-xl text-sm font-bold">
                {{ session('success') }}
            </div>
            @endif
        </header>

        <!-- Page Content -->
        <main class="flex-1 overflow-y-auto p-8">
            @yield('content')
        </main>
    </div>

</body>
</html>
