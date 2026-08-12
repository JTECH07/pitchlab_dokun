<!DOCTYPE html>
<html lang="fr" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $savoirFaire->name }} — ƉƆKUN Porto-Novo</title>
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
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-3xl">
                <a href="{{ route('savoir-faire.index') }}" class="inline-flex items-center gap-2 text-amber-400 font-bold text-sm mb-4 hover:underline">
                    ← Retour aux savoir-faire
                </a>
                <span class="block px-3 py-1 bg-amber-500/20 text-amber-400 font-bold text-xs uppercase tracking-wider rounded-full w-max mb-3 border border-amber-500/30">
                    {{ $savoirFaire->category?->name ?: 'Savoir-Faire' }}
                </span>
                <h1 class="text-4xl md:text-5xl font-black tracking-tight mb-4">
                    {{ $savoirFaire->name }}
                </h1>
                <p class="text-slate-300 text-lg font-light leading-relaxed">
                    {{ $savoirFaire->description }}
                </p>
            </div>
        </div>
    </section>

    <!-- Artisans practicing this savoir-faire -->
    <main class="flex-1 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 w-full">
        <h2 class="text-2xl font-extrabold text-slate-900 mb-8">
            Artisans pratiquant le métier : <span class="text-amber-600">{{ $savoirFaire->name }}</span>
        </h2>

        @if($savoirFaire->artisans->isEmpty())
        <div class="bg-white rounded-3xl p-12 text-center border border-slate-200">
            <p class="text-slate-500 text-lg">Aucun artisan n'est encore publiquement rattaché à ce savoir-faire.</p>
        </div>
        @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($savoirFaire->artisans as $artisan)
            <div class="bg-white rounded-[2rem] overflow-hidden shadow-xl shadow-slate-200/50 border border-slate-100 group flex flex-col">
                <div class="h-44 bg-slate-200 relative overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1610756041697-7427282eb111?q=80&w=2070&auto=format&fit=crop" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                </div>
                <div class="p-6 relative flex-1 flex flex-col">
                    <div class="absolute -top-10 left-6 w-16 h-16 rounded-xl bg-amber-500 flex items-center justify-center text-white text-xl font-bold border-2 border-white shadow-md">
                        {{ substr($artisan->first_name, 0, 1) }}
                    </div>
                    <div class="mt-6 flex-1 flex flex-col">
                        <h3 class="text-xl font-bold text-slate-900">{{ $artisan->first_name }} {{ $artisan->last_name }}</h3>
                        <p class="text-xs text-amber-600 font-bold uppercase mt-0.5">{{ $artisan->professional_name ?: 'Artisan' }}</p>
                        <p class="text-xs text-slate-500 mt-2 font-medium">📍 {{ $artisan->address ?: 'Porto-Novo' }}</p>
                        <p class="text-slate-600 text-sm line-clamp-2 mt-3 flex-1">{{ $artisan->description }}</p>
                        
                        <div class="mt-6 pt-4 border-t border-slate-100 flex gap-2">
                            <a href="{{ route('artisans.show', $artisan->id) }}" class="flex-1 text-center py-2.5 bg-slate-900 text-white rounded-xl font-bold text-xs hover:bg-slate-800 transition-colors">
                                Voir le profil
                            </a>
                            @if($artisan->whatsapp)
                            <a href="https://wa.me/{{ str_replace(['+', ' '], '', $artisan->whatsapp) }}" target="_blank" class="px-3 py-2.5 bg-emerald-50 text-emerald-600 rounded-xl hover:bg-emerald-100 transition-colors text-xs font-bold flex items-center gap-1">
                                WhatsApp
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
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
