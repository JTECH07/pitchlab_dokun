<!DOCTYPE html>
<html lang="fr" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Catalogue des Artisans — ƉƆKUN Porto-Novo</title>
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
                    <a href="{{ route('home') }}#savoir-faire" class="text-slate-600 hover:text-amber-500 transition-colors">Savoir-faire</a>
                    <a href="{{ route('artisans.index') }}" class="text-amber-600 font-bold border-b-2 border-amber-500 pb-1">Artisans</a>
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
    <section class="pt-32 pb-12 bg-slate-900 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <span class="inline-block py-1 px-3.5 rounded-full bg-amber-500/20 text-amber-400 font-bold text-xs uppercase tracking-wider mb-4 border border-amber-500/30">
                Répertoire officiel
            </span>
            <h1 class="text-4xl md:text-5xl font-black tracking-tight mb-4">
                Les Maîtres Artisans de <span class="text-amber-400">Porto-Novo</span>
            </h1>
            <p class="text-slate-300 max-w-2xl mx-auto text-lg font-light">
                Découvrez les gardiens des traditions et créateurs passionnés qui façonnent l'artisanat d'art béninois.
            </p>
        </div>
    </section>

    <!-- Search & Filter Bar -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-6 z-10 relative">
        <form method="GET" action="{{ route('artisans.index') }}" class="bg-white rounded-2xl p-4 shadow-xl border border-slate-200/80 grid grid-cols-1 sm:grid-cols-12 gap-4 items-center">
            <div class="sm:col-span-6 relative">
                <svg class="w-5 h-5 text-slate-400 absolute left-4 top-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Rechercher par nom, atelier ou mots-clés..." class="w-full pl-12 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-amber-500 outline-none text-sm font-medium">
            </div>
            <div class="sm:col-span-4">
                <select name="savoir_faire" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-amber-500 outline-none text-sm font-medium text-slate-700">
                    <option value="">Tous les savoir-faire</option>
                    @foreach($savoirFaires as $sf)
                    <option value="{{ $sf->id }}" {{ request('savoir_faire') == $sf->id ? 'selected' : '' }}>
                        {{ $sf->name }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="sm:col-span-2 flex gap-2">
                <button type="submit" class="w-full py-3 bg-amber-500 hover:bg-amber-600 text-white font-bold rounded-xl transition-colors shadow-lg shadow-amber-500/20 text-sm">
                    Filtrer
                </button>
                @if(request('search') || request('savoir_faire'))
                <a href="{{ route('artisans.index') }}" class="p-3 bg-slate-100 text-slate-600 hover:bg-slate-200 rounded-xl transition-colors text-sm font-bold flex items-center justify-center" title="Réinitialiser">
                    ✕
                </a>
                @endif
            </div>
        </form>
    </section>

    <!-- Main Content -->
    <main class="flex-1 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 w-full">
        @if($artisans->isEmpty())
        <div class="bg-white rounded-3xl p-16 text-center border border-slate-200 shadow-sm">
            <div class="w-20 h-20 bg-amber-50 text-amber-500 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </div>
            <h3 class="text-2xl font-bold text-slate-900 mb-2">Aucun artisan trouvé</h3>
            <p class="text-slate-500 max-w-md mx-auto mb-6">Essayez de modifier votre recherche ou vos filtres pour découvrir d'autres artisans.</p>
            <a href="{{ route('artisans.index') }}" class="inline-flex px-6 py-3 bg-amber-500 text-white font-bold rounded-xl shadow-lg shadow-amber-500/20 hover:bg-amber-600 transition-colors">
                Voir tous les artisans
            </a>
        </div>
        @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($artisans as $artisan)
            <div class="bg-white rounded-[2rem] overflow-hidden shadow-xl shadow-slate-200/50 border border-slate-100 group flex flex-col h-full hover:-translate-y-1 transition-all duration-300">
                <div class="h-48 bg-slate-200 relative overflow-hidden shrink-0">
                    <img src="https://images.unsplash.com/photo-1610756041697-7427282eb111?q=80&w=2070&auto=format&fit=crop" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                    <div class="absolute top-4 right-4 bg-white/90 backdrop-blur-sm px-3 py-1 rounded-full text-xs font-bold text-amber-600 shadow-sm">
                        ⭐ {{ $artisan->experience_years }} ans d'exp.
                    </div>
                </div>
                <div class="p-8 relative flex-1 flex flex-col">
                    <div class="absolute -top-12 left-8 w-20 h-20 rounded-2xl bg-white p-1 shadow-lg">
                        <div class="w-full h-full bg-amber-500 rounded-xl flex items-center justify-center text-white text-2xl font-bold">
                            {{ substr($artisan->first_name, 0, 1) }}
                        </div>
                    </div>
                    
                    <div class="mt-8 flex-1 flex flex-col">
                        <h3 class="text-2xl font-bold text-slate-900">{{ $artisan->first_name }} {{ $artisan->last_name }}</h3>
                        <p class="text-xs font-bold text-amber-600 uppercase tracking-wide mt-0.5">{{ $artisan->professional_name ?: 'Artisan Indépendant' }}</p>
                        
                        <div class="flex items-center gap-2 text-slate-500 mt-2 text-sm font-medium">
                            <svg class="w-4 h-4 text-amber-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            {{ $artisan->address ?: 'Porto-Novo, Bénin' }}
                        </div>
                        
                        <div class="mt-4 flex flex-wrap gap-1.5">
                            @foreach($artisan->savoirFaires as $sf)
                                <span class="px-3 py-1 bg-amber-50 text-amber-800 text-xs font-bold rounded-lg border border-amber-200/60">{{ $sf->name }}</span>
                            @endforeach
                        </div>
                        
                        <p class="mt-4 text-slate-600 line-clamp-3 leading-relaxed text-sm flex-1">
                            {{ $artisan->description ?: 'Aucune description disponible pour cet artisan.' }}
                        </p>
                        
                        <div class="mt-6 pt-4 border-t border-slate-100 flex gap-3">
                            <a href="{{ route('artisans.show', $artisan->id) }}" class="flex-1 text-center py-3 bg-slate-900 text-white rounded-xl font-bold text-sm hover:bg-slate-800 transition-colors">
                                Voir le profil
                            </a>
                            @if($artisan->whatsapp)
                            <a href="https://wa.me/{{ str_replace(['+', ' '], '', $artisan->whatsapp) }}" target="_blank" class="flex-none w-12 h-12 flex items-center justify-center bg-emerald-50 text-emerald-600 rounded-xl hover:bg-emerald-100 transition-colors" title="Contacter sur WhatsApp">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 00-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        @if($artisans->hasPages())
        <div class="mt-12">
            {{ $artisans->links() }}
        </div>
        @endif
        @endif
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
