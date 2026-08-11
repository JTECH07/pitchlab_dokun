<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $artisan->professional_name ?? ($artisan->first_name . ' ' . $artisan->last_name) }} - ƉƆKUN</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=outfit:300,400,600,700,900&display=swap" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <style>body { font-family: 'Outfit', sans-serif; }</style>
</head>
<body class="antialiased bg-slate-50 text-slate-800">

    <!-- Navbar -->
    <nav class="fixed w-full z-50 bg-white/90 backdrop-blur-md border-b border-slate-200/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center h-20">
            <a href="{{ route('home') }}" class="flex items-center gap-3">
                <div class="w-10 h-10 bg-amber-500 rounded-full flex items-center justify-center text-white font-bold text-xl shadow-lg">Ɖ</div>
                <span class="font-black text-2xl tracking-tighter text-slate-900">ƆKUN</span>
            </a>
            <div class="flex items-center gap-4 font-semibold">
                <a href="{{ route('carte') }}" class="text-slate-600 hover:text-amber-500 transition-colors hidden md:block">Carte Interactive</a>
                @auth
                    <a href="{{ route('dashboard') }}" class="px-4 py-2 bg-slate-900 text-white rounded-full text-sm hover:bg-slate-800 transition">Mon Espace</a>
                @else
                    <a href="{{ route('login') }}" class="px-4 py-2 bg-amber-500 text-white rounded-full text-sm hover:bg-amber-600 transition">Connexion Pro</a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Flash Message -->
    @if(session('success'))
    <div class="fixed top-24 left-1/2 -translate-x-1/2 z-50 bg-emerald-500 text-white px-8 py-4 rounded-2xl shadow-2xl shadow-emerald-500/30 font-bold text-center" id="flash-msg">
        {{ session('success') }}
    </div>
    <script>setTimeout(() => { let el = document.getElementById('flash-msg'); if(el) el.style.display='none'; }, 5000);</script>
    @endif

    <main class="pt-32 pb-24 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Breadcrumb -->
        <div class="mb-8 text-sm font-medium text-slate-500 flex items-center gap-2">
            <a href="{{ route('home') }}" class="hover:text-amber-500">Accueil</a>
            <span>/</span>
            <span class="text-slate-900">{{ $artisan->professional_name ?? $artisan->first_name }}</span>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">

            <!-- Left Column -->
            <div class="lg:col-span-1 space-y-8">
                <div class="rounded-[2.5rem] overflow-hidden bg-slate-200 aspect-[4/5] shadow-2xl shadow-slate-200 relative">
                    <img src="https://images.unsplash.com/photo-1610756041697-7427282eb111?q=80&w=2070&auto=format&fit=crop" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-900/60 to-transparent"></div>
                    <div class="absolute bottom-6 left-6 text-white">
                        <div class="px-3 py-1 bg-amber-500 text-white rounded-full text-xs font-bold inline-block mb-2">⭐ {{ $artisan->experience_years }} ans d'expérience</div>
                    </div>
                </div>

                <!-- Quick Contact -->
                <div class="bg-white p-6 rounded-3xl shadow-xl shadow-slate-100 border border-slate-100">
                    <h3 class="font-bold text-lg mb-4 text-slate-900">Contact rapide</h3>
                    <a href="https://wa.me/{{ str_replace(['+', ' '], '', $artisan->whatsapp) }}" target="_blank"
                       class="w-full flex items-center justify-center gap-3 py-3 bg-green-500 text-white font-bold rounded-xl hover:bg-green-600 transition-colors shadow-lg shadow-green-500/25">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 00-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                        WhatsApp
                    </a>
                </div>
            </div>

            <!-- Right Column -->
            <div class="lg:col-span-2 space-y-10">

                <!-- Header -->
                <div>
                    <h1 class="text-4xl md:text-5xl font-black text-slate-900 tracking-tight mb-3">{{ $artisan->professional_name ?? ($artisan->first_name . ' ' . $artisan->last_name) }}</h1>
                    <div class="flex items-center gap-2 text-amber-600 font-medium mb-5">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        {{ $artisan->address }}
                    </div>
                    <div class="flex flex-wrap gap-3">
                        @foreach($artisan->savoirFaires as $sf)
                            <span class="px-4 py-2 bg-amber-100 text-amber-800 text-sm font-bold rounded-xl border border-amber-200">{{ $sf->name }}</span>
                        @endforeach
                    </div>
                </div>

                <!-- About -->
                <div>
                    <h2 class="text-2xl font-bold text-slate-900 border-b border-slate-200 pb-4 mb-5">À propos</h2>
                    <p class="text-slate-600 leading-relaxed">{{ $artisan->description }}</p>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-slate-900 border-b border-slate-200 pb-4 mb-5">Mon Histoire</h2>
                    <p class="text-slate-600 leading-relaxed">{{ $artisan->history }}</p>
                </div>

                <!-- Reservation Form -->
                <div id="reservation-form" class="bg-white rounded-3xl shadow-xl shadow-slate-100 border border-slate-100 overflow-hidden">
                    <div class="bg-gradient-to-r from-amber-500 to-orange-500 p-6 text-white">
                        <h2 class="text-2xl font-black">Demander une visite</h2>
                        <p class="text-amber-100 text-sm mt-1">Remplissez ce formulaire et l'artisan vous répondra rapidement.</p>
                    </div>
                    <div class="p-8">
                        @if($errors->any())
                        <div class="bg-red-50 border border-red-200 text-red-700 rounded-2xl p-4 mb-6 text-sm font-medium">
                            <ul class="list-disc list-inside space-y-1">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

                        <form action="{{ route('reservations.store', $artisan->id) }}" method="POST" class="space-y-5">
                            @csrf
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                <div>
                                    <label class="block text-sm font-bold text-slate-700 mb-1.5">Votre nom complet <span class="text-red-500">*</span></label>
                                    <input type="text" name="visitor_name" value="{{ old('visitor_name') }}" required
                                        class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-transparent outline-none transition text-slate-800 bg-slate-50"
                                        placeholder="Ex: Marie Dupont">
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-slate-700 mb-1.5">Téléphone / WhatsApp <span class="text-red-500">*</span></label>
                                    <input type="text" name="visitor_phone" value="{{ old('visitor_phone') }}" required
                                        class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-transparent outline-none transition text-slate-800 bg-slate-50"
                                        placeholder="+229 01 XX XX XX XX">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                <div>
                                    <label class="block text-sm font-bold text-slate-700 mb-1.5">Email (optionnel)</label>
                                    <input type="email" name="visitor_email" value="{{ old('visitor_email') }}"
                                        class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-transparent outline-none transition text-slate-800 bg-slate-50"
                                        placeholder="votre@email.com">
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-slate-700 mb-1.5">Date souhaitée <span class="text-red-500">*</span></label>
                                    <input type="date" name="requested_date" value="{{ old('requested_date') }}" required
                                        min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                        class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-transparent outline-none transition text-slate-800 bg-slate-50">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                <div>
                                    <label class="block text-sm font-bold text-slate-700 mb-1.5">Nombre de personnes <span class="text-red-500">*</span></label>
                                    <input type="number" name="guests_count" value="{{ old('guests_count', 1) }}" min="1" max="20" required
                                        class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-transparent outline-none transition text-slate-800 bg-slate-50">
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-slate-700 mb-1.5">Type d'expérience</label>
                                    <select name="experience_type"
                                        class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-transparent outline-none transition text-slate-800 bg-slate-50">
                                        <option value="">-- Choisir --</option>
                                        @foreach($artisan->savoirFaires as $sf)
                                            <option value="{{ $sf->name }}" {{ old('experience_type') == $sf->name ? 'selected' : '' }}>{{ $sf->name }}</option>
                                        @endforeach
                                        <option value="Visite atelier">Visite atelier</option>
                                        <option value="Atelier pratique">Atelier pratique</option>
                                    </select>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-1.5">Message (optionnel)</label>
                                <textarea name="message" rows="3"
                                    class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-transparent outline-none transition text-slate-800 bg-slate-50 resize-none"
                                    placeholder="Informations complémentaires, questions particulières...">{{ old('message') }}</textarea>
                            </div>

                            <button type="submit"
                                class="w-full py-4 bg-gradient-to-r from-amber-500 to-orange-500 text-white font-black text-lg rounded-2xl hover:scale-[1.02] transition-transform shadow-xl shadow-amber-500/25">
                                Envoyer ma demande de visite →
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </main>

    <footer class="bg-slate-900 text-slate-300 py-10 border-t border-slate-800 mt-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row justify-between items-center gap-4">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-amber-500 rounded-full flex items-center justify-center text-white font-bold text-sm">Ɖ</div>
                <span class="font-black tracking-tighter text-white">ƆKUN</span>
            </div>
            <p class="text-sm">© 2026 Projet PitchLab - Porto-Novo, Bénin.</p>
        </div>
    </footer>

</body>
</html>
