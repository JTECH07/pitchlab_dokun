<!DOCTYPE html>
<html lang="fr" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Savoir-Faire Traditionnels — ƉƆKUN Porto-Novo</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=outfit:300,400,600,700,900&display=swap" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Outfit', sans-serif; }
        .glass { background: rgba(255, 255, 255, 0.85); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); }
    </style>
</head>
<body class="antialiased bg-slate-50 text-slate-800 min-h-screen flex flex-col">

    <!-- Navbar -->
    <nav class="fixed w-full z-50 glass bg-white/80 border-b border-slate-200/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <a href="{{ route('home') }}" class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-amber-500 rounded-full flex items-center justify-center text-white font-bold text-xl shadow-lg shadow-amber-500/30">Ɖ</div>
                    <span class="font-black text-2xl tracking-tighter text-slate-900">ƆKUN</span>
                </a>
                <div class="hidden md:flex space-x-8 items-center font-semibold">
                    <a href="{{ route('savoir-faire.index') }}" class="text-amber-600 font-bold border-b-2 border-amber-500 pb-1">Savoir-faire</a>
                    <a href="{{ route('artisans.index') }}" class="text-slate-600 hover:text-amber-500 transition-colors">Artisans</a>
                    <a href="{{ route('carte') }}" class="text-slate-600 hover:text-amber-500 transition-colors">Carte Interactive</a>
                    @auth
                        <a href="{{ url('/dashboard') }}" class="px-5 py-2.5 bg-slate-900 text-white rounded-full hover:bg-slate-800 transition shadow-lg shadow-slate-900/20">Mon Espace</a>
                    @else
                        <a href="{{ route('login') }}" class="px-5 py-2.5 bg-amber-500 text-white rounded-full hover:bg-amber-600 transition shadow-lg shadow-amber-500/30">Connexion Pro</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Header Banner -->
    <section class="pt-32 pb-16 bg-slate-900 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <span class="inline-block py-1 px-3.5 rounded-full bg-amber-500/20 text-amber-400 font-bold text-xs uppercase tracking-wider mb-4 border border-amber-500/30">
                Patrimoine Immmatériel
            </span>
            <h1 class="text-4xl md:text-5xl font-black tracking-tight mb-4">
                Les Savoir-Faire d'Exception de <span class="text-amber-400">Porto-Novo</span>
            </h1>
            <p class="text-slate-300 max-w-2xl mx-auto text-lg font-light">
                Explorez la richesse des techniques artisanales transmises de génération en génération.
            </p>
        </div>
    </section>

    <!-- Categories & Savoir-Faire Grid -->
    <main class="flex-1 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 w-full space-y-16">
        @foreach($categories as $cat)
        <div class="space-y-6">
            <div class="border-b border-slate-200 pb-4 flex items-end justify-between">
                <div>
                    <h2 class="text-3xl font-black text-slate-900">{{ $cat->name }}</h2>
                    <p class="text-slate-500 text-sm mt-1">{{ $cat->description }}</p>
                </div>
                <span class="px-3 py-1 bg-amber-100 text-amber-800 text-xs font-bold rounded-full">
                    {{ $cat->savoirFaires->count() }} métier(s)
                </span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($cat->savoirFaires as $sf)
                <a href="{{ route('savoir-faire.show', $sf->slug) }}" class="group bg-white rounded-2xl p-6 shadow-sm border border-slate-200/80 hover:border-amber-400 hover:shadow-xl hover:-translate-y-1 transition-all">
                    <div class="w-12 h-12 bg-amber-50 rounded-xl flex items-center justify-center text-amber-600 mb-4 group-hover:scale-110 group-hover:bg-amber-500 group-hover:text-white transition-all">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 group-hover:text-amber-600 transition-colors mb-2">{{ $sf->name }}</h3>
                    <p class="text-slate-600 text-sm line-clamp-2 leading-relaxed mb-4">{{ $sf->description }}</p>
                    <div class="flex items-center justify-between pt-4 border-t border-slate-100 text-xs font-bold text-slate-500">
                        <span>{{ $sf->artisans->count() }} artisan(s) praticien(s)</span>
                        <span class="text-amber-500 group-hover:translate-x-1 transition-transform inline-flex items-center gap-1">Voir les artisans →</span>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        @endforeach
    </main>

    <!-- Footer -->
    <footer class="bg-slate-900 text-slate-300 py-12 border-t border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row justify-between items-center gap-6">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-amber-500 rounded-full flex items-center justify-center text-white font-bold text-sm">Ɖ</div>
                <span class="font-black tracking-tighter text-white">ƆKUN</span>
            </div>
            <p class="text-sm">© 2026 Projet PitchLab - Porto-Novo, Bénin.</p>
        </div>
    </footer>

</body>
</html>
