<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ƉƆKUN - Le Patrimoine Vivant de Porto-Novo</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=outfit:300,400,600,700,900&display=swap" rel="stylesheet" />

    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body { font-family: 'Outfit', sans-serif; }
        .glass { background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.2); }
        .hero-pattern { background-image: url('https://www.transparenttextures.com/patterns/stardust.png'); }
        
        /* Subtle float animation */
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0px); }
        }
        .animate-float { animation: float 6s ease-in-out infinite; }
    </style>
</head>
<body class="antialiased bg-slate-50 text-slate-800">

    <!-- Navbar -->
    <nav class="fixed w-full z-50 glass bg-white/80 border-b border-slate-200/50 transition-all">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <div class="flex-shrink-0 flex items-center gap-3">
                    <div class="w-10 h-10 bg-amber-500 rounded-full flex items-center justify-center text-white font-bold text-xl shadow-lg shadow-amber-500/30">Ɖ</div>
                    <span class="font-black text-2xl tracking-tighter text-slate-900">ƆKUN</span>
                </div>
                <div class="hidden md:flex space-x-8 items-center font-semibold">
                    <a href="#savoir-faire" class="text-slate-900 hover:text-amber-500 transition-colors">Savoir-faire</a>
                    <a href="#artisans" class="text-slate-600 hover:text-amber-500 transition-colors">Artisans</a>
                    <a href="{{ route('carte') }}" class="text-slate-600 hover:text-amber-500 transition-colors">Carte</a>
                    @auth
                        <a href="{{ url('/dashboard') }}" class="px-5 py-2.5 bg-slate-900 text-white rounded-full hover:bg-slate-800 transition shadow-lg shadow-slate-900/20">Mon Espace</a>
                    @else
                        <a href="{{ route('login') }}" class="px-5 py-2.5 bg-amber-500 text-white rounded-full hover:bg-amber-600 transition shadow-lg shadow-amber-500/30">Connexion Pro</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="relative pt-32 pb-20 lg:pt-48 lg:pb-32 overflow-hidden bg-slate-900 hero-pattern">
        <!-- Abstract Shapes -->
        <div class="absolute top-0 right-0 -mr-20 -mt-20 w-96 h-96 rounded-full bg-amber-500/20 blur-3xl"></div>
        <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-96 h-96 rounded-full bg-blue-500/20 blur-3xl"></div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <span class="inline-block py-1 px-3 rounded-full bg-amber-500/20 text-amber-400 font-bold text-sm tracking-wider uppercase mb-6 border border-amber-500/30">PitchLab 2026</span>
            <h1 class="text-5xl md:text-7xl font-black text-white tracking-tight mb-8">
                Découvrez le <span class="text-transparent bg-clip-text bg-gradient-to-r from-amber-400 to-orange-500">Patrimoine Vivant</span><br> de Porto-Novo
            </h1>
            <p class="mt-4 text-xl text-slate-300 max-w-3xl mx-auto mb-10 font-light leading-relaxed">
                Plongez au cœur des savoir-faire traditionnels. Rencontrez les artisans locaux, comprenez leur histoire et réservez des expériences culturelles authentiques.
            </p>
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <a href="{{ route('carte') }}" class="px-8 py-4 bg-gradient-to-r from-amber-500 to-orange-500 text-white font-bold rounded-full hover:scale-105 transition-transform shadow-xl shadow-orange-500/25 flex items-center justify-center gap-2">
                    Explorer la carte <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                </a>
                <a href="#savoir-faire" class="px-8 py-4 bg-white/10 text-white font-bold rounded-full hover:bg-white/20 transition-colors border border-white/10 backdrop-blur-sm">
                    Découvrir les métiers
                </a>
            </div>
        </div>
        
        <!-- UI Mockup graphic -->
        <div class="mt-16 relative max-w-5xl mx-auto px-4 sm:px-6 animate-float">
            <div class="glass bg-white/5 rounded-2xl p-2 border border-white/10 shadow-2xl">
                <div class="rounded-xl overflow-hidden bg-slate-800 aspect-video relative flex items-center justify-center">
                    <img src="https://images.unsplash.com/photo-1606041011872-59659ceb7eb8?q=80&w=2069&auto=format&fit=crop" class="absolute inset-0 w-full h-full object-cover opacity-60 mix-blend-overlay">
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-transparent to-transparent"></div>
                    <div class="relative z-10 text-center">
                        <button class="w-20 h-20 bg-white/20 backdrop-blur-md rounded-full flex items-center justify-center text-white hover:bg-white/30 transition-all group">
                            <svg class="w-8 h-8 ml-1 group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Savoir-Faire Section -->
    <section id="savoir-faire" class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-black text-slate-900 tracking-tight">Savoir-Faire Traditionnels</h2>
                <div class="h-1 w-20 bg-amber-500 mx-auto mt-4 rounded-full"></div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($categories as $category)
                <div class="group relative rounded-3xl overflow-hidden bg-slate-100 p-8 hover:bg-amber-50 transition-colors border border-slate-200 hover:border-amber-200">
                    <div class="w-14 h-14 bg-white rounded-2xl shadow-sm flex items-center justify-center text-amber-500 mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                    </div>
                    <h3 class="text-2xl font-bold text-slate-900 mb-3">{{ $category->name }}</h3>
                    <p class="text-slate-600 mb-6">{{ $category->description }}</p>
                    <a href="#" class="text-amber-500 font-semibold inline-flex items-center gap-1 group-hover:gap-2 transition-all">
                        Explorer <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Artisans Section -->
    <section id="artisans" class="py-24 bg-slate-50 border-t border-slate-200/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-end mb-16">
                <div>
                    <h2 class="text-4xl font-black text-slate-900 tracking-tight">Artisans à la Une</h2>
                    <div class="h-1 w-20 bg-amber-500 mt-4 rounded-full"></div>
                </div>
                <a href="#" class="hidden md:inline-flex items-center gap-2 text-slate-600 font-semibold hover:text-amber-500 transition-colors">
                    Voir tous les artisans <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
                @foreach($artisans as $artisan)
                <div class="bg-white rounded-[2rem] overflow-hidden shadow-xl shadow-slate-200/50 border border-slate-100 group">
                    <div class="h-48 bg-slate-200 relative overflow-hidden">
                        <!-- Placeholder Image -->
                        <img src="https://images.unsplash.com/photo-1610756041697-7427282eb111?q=80&w=2070&auto=format&fit=crop" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                        <div class="absolute top-4 right-4 bg-white/90 backdrop-blur-sm px-3 py-1 rounded-full text-xs font-bold text-amber-600 shadow-sm">
                            ⭐ {{ $artisan->experience_years }} ans d'exp.
                        </div>
                    </div>
                    <div class="p-8 relative">
                        <!-- Artisan Avatar -->
                        <div class="absolute -top-12 left-8 w-20 h-20 rounded-2xl bg-white p-1 shadow-lg">
                            <div class="w-full h-full bg-slate-800 rounded-xl flex items-center justify-center text-white text-2xl font-bold">
                                {{ substr($artisan->first_name, 0, 1) }}
                            </div>
                        </div>
                        
                        <div class="mt-8">
                            <h3 class="text-2xl font-bold text-slate-900">{{ $artisan->professional_name ?? ($artisan->first_name . ' ' . $artisan->last_name) }}</h3>
                            <div class="flex items-center gap-2 text-slate-500 mt-2 text-sm font-medium">
                                <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                {{ $artisan->address }}
                            </div>
                            
                            <div class="mt-4 flex flex-wrap gap-2">
                                @foreach($artisan->savoirFaires as $sf)
                                    <span class="px-3 py-1 bg-amber-50 text-amber-700 text-xs font-bold rounded-lg border border-amber-100">{{ $sf->name }}</span>
                                @endforeach
                            </div>
                            
                            <p class="mt-5 text-slate-600 line-clamp-2 leading-relaxed">
                                {{ $artisan->description }}
                            </p>
                            
                            <div class="mt-8 flex gap-3">
                                <a href="{{ route('artisans.show', $artisan->id) }}" class="flex-1 text-center py-3 bg-slate-900 text-white rounded-xl font-semibold hover:bg-slate-800 transition-colors">Profil</a>
                                <a href="https://wa.me/{{ str_replace(['+', ' '], '', $artisan->whatsapp) }}" class="flex-none w-12 h-12 flex items-center justify-center bg-green-50 text-green-600 rounded-xl hover:bg-green-100 transition-colors" title="WhatsApp">
                                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 00-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            
            <div class="mt-12 text-center md:hidden">
                <a href="#" class="inline-flex items-center gap-2 text-slate-600 font-semibold hover:text-amber-500 transition-colors">
                    Voir tous les artisans <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                </a>
            </div>
        </div>
    </section>

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
