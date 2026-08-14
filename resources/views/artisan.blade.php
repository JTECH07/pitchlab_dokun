<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $artisan->professional_name ?? ($artisan->first_name . ' ' . $artisan->last_name) }} - ƉƆKUN</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=dm-serif-display:400|manrope:400,600,700,800&display=swap" rel="stylesheet" />

    <!-- Tailwind CDN -->
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
                    },
                    backgroundImage: {
                        'dokun-pattern': "url('data:image/svg+xml,%3Csvg width=\\'20\\' height=\\'20\\' viewBox=\\'0 0 20 20\\' xmlns=\\'http://www.w3.org/2000/svg\\'%3E%3Cg fill=\\'%23064E3B\\' fill-opacity=\\'0.05\\' fill-rule=\\'evenodd\\'%3E%3Ccircle cx=\\'3\\' cy=\\'3\\' r=\\'3\\'/%3E%3Ccircle cx=\\'13\\' cy=\\'13\\' r=\\'3\\'/%3E%3C/g%3E%3C/svg%3E')",
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Manrope', sans-serif; }
        h1, h2, h3, h4, .font-serif { font-family: 'DM Serif Display', serif; }
    </style>
</head>
<body class="antialiased bg-dokun-ivory text-dokun-charcoal bg-dokun-pattern">

    @include('partials.navbar', ['active' => 'artisans'])

    <!-- Flash Message -->
    @if(session('success'))
    <div class="fixed top-28 left-1/2 -translate-x-1/2 z-50 bg-dokun-green text-white px-8 py-4 rounded-xl shadow-2xl font-bold text-center border border-dokun-gold/30" id="flash-msg">
        {{ session('success') }}
    </div>
    <script>setTimeout(() => { let el = document.getElementById('flash-msg'); if(el) el.style.opacity='0'; setTimeout(()=>el.remove(), 500); }, 5000);</script>
    @endif

    <main class="pt-32 pb-24 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Breadcrumb -->
        <div class="mb-8 text-sm font-semibold text-dokun-charcoal/50 flex items-center gap-2">
            <a href="{{ route('home') }}" class="hover:text-dokun-gold transition-colors">Accueil</a>
            <span>/</span>
            <a href="{{ route('artisans.index') }}" class="hover:text-dokun-gold transition-colors">Artisans</a>
            <span>/</span>
            <span class="text-dokun-green">{{ $artisan->first_name }}</span>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">

            <!-- Left Column: Portrait & Quick Info -->
            <div class="lg:col-span-4 space-y-8">
                <div class="rounded-[2rem] overflow-hidden bg-gray-200 aspect-[4/5] shadow-2xl relative border border-gray-100 group">
                    <img src="{{ $artisan->image_url }}" alt="{{ $artisan->first_name }} {{ $artisan->last_name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                    <div class="absolute inset-0 bg-gradient-to-t from-dokun-charcoal/80 via-transparent to-transparent"></div>
                    <div class="absolute bottom-6 left-6 text-white pr-6">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="px-3 py-1 bg-dokun-gold text-white rounded-lg text-xs font-bold uppercase tracking-wider shadow-lg">
                                {{ $artisan->experience_years }} ans d'expérience
                            </span>
                        </div>
                        <h2 class="font-serif text-3xl">{{ $artisan->first_name }} {{ $artisan->last_name }}</h2>
                    </div>
                </div>

                <!-- Story & Quick contact -->
                <div class="bg-white p-8 rounded-3xl shadow-lg border border-gray-100 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-dokun-ivory rounded-bl-full -mr-16 -mt-16 opacity-50 z-0"></div>
                    <div class="relative z-10">
                        <h3 class="font-serif text-2xl mb-4 text-dokun-green">Mon Histoire</h3>
                        <p class="text-dokun-charcoal/80 italic text-sm leading-relaxed mb-6">
                            "Je pratique ce savoir-faire depuis {{ $artisan->experience_years }} ans..."
                        </p>

                        <div class="h-px w-full bg-gray-100 my-6"></div>

                        <a href="#reservation-form" class="w-full block text-center py-4 bg-dokun-green text-white font-bold rounded-xl hover:bg-dokun-green/90 transition-colors shadow-lg shadow-dokun-green/20 mb-3">
                            Réserver une expérience
                        </a>

                        @if($artisan->whatsapp)
                        <a href="https://wa.me/{{ str_replace(['+', ' '], '', $artisan->whatsapp) }}" target="_blank"
                           class="w-full flex items-center justify-center gap-2 py-4 border-2 border-green-500 text-green-600 font-bold rounded-xl hover:bg-green-50 transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 00-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                            WhatsApp
                        </a>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Right Column: Story & Details -->
            <div class="lg:col-span-8 space-y-12">

                <!-- Header Info -->
                <div>
                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-serif text-dokun-green tracking-tight mb-4">{{ $artisan->professional_name ?? ($artisan->first_name . ' ' . $artisan->last_name) }}</h1>

                    <div class="flex flex-wrap items-center gap-4 mb-6 text-sm font-bold uppercase tracking-wider text-dokun-gold">
                        @foreach($artisan->savoirFaires as $sf)
                            <span class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                                {{ $sf->name }}
                            </span>
                        @endforeach
                        <span class="text-gray-300">|</span>
                        <span class="flex items-center gap-2 text-dokun-charcoal/60">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            {{ $artisan->address }}
                        </span>
                    </div>
                </div>

                <!-- Content Sections -->
                <div class="prose prose-lg max-w-none text-dokun-charcoal/80 space-y-12">
                    <section>
                        <h2 class="text-3xl font-serif text-dokun-green mb-6 flex items-center gap-4">
                            <span class="w-8 h-px bg-dokun-gold"></span>
                            Mon Savoir-Faire
                        </h2>
                        <p class="leading-relaxed">{{ $artisan->description }}</p>
                    </section>

                    @if($artisan->history)
                    <section>
                        <h2 class="text-3xl font-serif text-dokun-green mb-6 flex items-center gap-4">
                            <span class="w-8 h-px bg-dokun-gold"></span>
                            Mon Parcours
                        </h2>
                        <p class="leading-relaxed">{{ $artisan->history }}</p>
                    </section>
                    @endif
                </div>

                <!-- Reservation Form -->
                <div id="reservation-form" class="mt-16 bg-white rounded-[2rem] shadow-2xl border border-gray-100 overflow-hidden relative">
                    <div class="absolute top-0 right-0 w-64 h-64 bg-dokun-ivory rounded-bl-full -mr-20 -mt-20 opacity-50 z-0"></div>

                    <div class="p-10 lg:p-14 relative z-10">
                        <div class="mb-10 text-center max-w-2xl mx-auto">
                            <h2 class="text-3xl md:text-4xl font-serif text-dokun-green mb-3">Réserver une expérience</h2>
                            <p class="text-dokun-charcoal/60">Rencontrez l'artisan dans son atelier, découvrez ses techniques et participez à la création.</p>
                        </div>

                        @if($errors->any())
                        <div class="bg-red-50 border border-red-200 text-red-700 rounded-xl p-6 mb-8 text-sm font-semibold">
                            <ul class="list-disc list-inside space-y-1">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

                        @if($artisan->experiences->isNotEmpty())
                        <div class="grid sm:grid-cols-2 gap-4 mb-8">
                            @foreach($artisan->experiences as $experience)
                            <div class="bg-dokun-ivory/60 rounded-xl p-5 border border-dokun-gold/15">
                                <h3 class="font-bold text-dokun-green">{{ $experience->title }}</h3>
                                <p class="text-sm text-dokun-charcoal/60 mt-1">{{ $experience->duration_minutes }} min · {{ $experience->capacity }} personnes maximum</p>
                                <p class="font-serif text-xl text-dokun-green mt-3">{{ number_format($experience->price, 0, ',', ' ') }} FCFA <span class="font-sans text-xs text-dokun-charcoal/50">/ personne</span></p>
                            </div>
                            @endforeach
                        </div>
                        @endif

                        <form action="{{ route('reservations.store', $artisan->id) }}" method="POST" class="space-y-6">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-bold text-dokun-charcoal mb-2">Votre nom complet *</label>
                                    <input type="text" name="visitor_name" value="{{ old('visitor_name') }}" required
                                        class="w-full px-5 py-4 bg-dokun-ivory/50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-dokun-gold focus:border-transparent outline-none transition"
                                        placeholder="Ex: Marie Dupont">
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-dokun-charcoal mb-2">Téléphone / WhatsApp *</label>
                                    <input type="text" name="visitor_phone" value="{{ old('visitor_phone') }}" required
                                        class="w-full px-5 py-4 bg-dokun-ivory/50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-dokun-gold focus:border-transparent outline-none transition"
                                        placeholder="+229 01 XX XX XX XX">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-bold text-dokun-charcoal mb-2">Email (optionnel)</label>
                                    <input type="email" name="visitor_email" value="{{ old('visitor_email') }}"
                                        class="w-full px-5 py-4 bg-dokun-ivory/50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-dokun-gold focus:border-transparent outline-none transition"
                                        placeholder="votre@email.com">
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-dokun-charcoal mb-2">Date souhaitée *</label>
                                    <input type="date" name="requested_date" value="{{ old('requested_date') }}" required
                                        min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                        class="w-full px-5 py-4 bg-dokun-ivory/50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-dokun-gold focus:border-transparent outline-none transition">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-bold text-dokun-charcoal mb-2">Nombre de visiteurs *</label>
                                    <input type="number" name="guests_count" value="{{ old('guests_count', 1) }}" min="1" max="20" required
                                        class="w-full px-5 py-4 bg-dokun-ivory/50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-dokun-gold focus:border-transparent outline-none transition">
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-dokun-charcoal mb-2">Expérience</label>
                                    <select name="experience_id"
                                        class="w-full px-5 py-4 bg-dokun-ivory/50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-dokun-gold focus:border-transparent outline-none transition">
                                        <option value="">Visite d'atelier (tarif à confirmer)</option>
                                        @foreach($artisan->experiences as $experience)
                                            <option value="{{ $experience->id }}" @selected(old('experience_id') == $experience->id)>{{ $experience->title }} — {{ number_format($experience->price, 0, ',', ' ') }} FCFA</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-dokun-charcoal mb-2">Modalité de paiement *</label>
                                <div class="grid sm:grid-cols-2 gap-3">
                                    <label class="cursor-pointer border border-dokun-green/20 rounded-xl p-4 flex gap-3 items-start"><input type="radio" name="payment_method" value="pay_on_site" @checked(old('payment_method', 'pay_on_site') === 'pay_on_site')><span><b class="block text-sm">Paiement à l'atelier</b><span class="text-xs text-dokun-charcoal/60">Vous réglerez lors de la visite.</span></span></label>
                                    <label class="cursor-pointer border border-dokun-green/20 rounded-xl p-4 flex gap-3 items-start"><input type="radio" name="payment_method" value="mobile_money" @checked(old('payment_method') === 'mobile_money')><span><b class="block text-sm">Pré-demande Mobile Money</b><span class="text-xs text-dokun-charcoal/60">Le règlement sera organisé après confirmation par l’artisan.</span></span></label>
                                </div>
                                <p class="mt-2 text-xs text-dokun-charcoal/55">Aucun débit n'est effectué sur ƉƆKUN avant confirmation de l’artisan.</p>
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-dokun-charcoal mb-2">Message (optionnel)</label>
                                <textarea name="message" rows="3"
                                    class="w-full px-5 py-4 bg-dokun-ivory/50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-dokun-gold focus:border-transparent outline-none transition resize-none"
                                    placeholder="Informations complémentaires, questions particulières...">{{ old('message') }}</textarea>
                            </div>

                            <button type="submit"
                                class="w-full py-5 mt-4 bg-dokun-green text-white font-bold text-lg rounded-xl hover:bg-dokun-green/90 transition-all shadow-xl shadow-dokun-green/20">
                                Envoyer ma demande de réservation
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </main>

    @include('partials.footer')

</body>
</html>
