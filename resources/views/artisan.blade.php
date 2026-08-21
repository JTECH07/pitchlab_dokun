<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $artisan->professional_name ?? ($artisan->first_name . ' ' . $artisan->last_name) }} · ƉƆKUN</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=dm-serif-display:400|manrope:400,500,600,700,800&display=swap" rel="stylesheet"/>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        tailwind.config = {
            theme: { extend: {
                colors: { dokun: { green:'#064E3B', gold:'#C99424', ivory:'#F8F6F0', charcoal:'#17201D' } },
                fontFamily: { sans:['Manrope','sans-serif'], serif:['"DM Serif Display"','serif'] },
                backgroundImage: { 'dokun-pattern':"url('data:image/svg+xml,%3Csvg width=\\'20\\' height=\\'20\\' viewBox=\\'0 0 20 20\\' xmlns=\\'http://www.w3.org/2000/svg\\'%3E%3Cg fill=\\'%23064E3B\\' fill-opacity=\\'0.05\\' fill-rule=\\'evenodd\\'%3E%3Ccircle cx=\\'3\\' cy=\\'3\\' r=\\'3\\'/%3E%3Ccircle cx=\\'13\\' cy=\\'13\\' r=\\'3\\'/%3E%3C/g%3E%3C/svg%3E')" }
            }}
        }
    </script>
    <style>
        body{font-family:'Manrope',sans-serif;}
        h1,h2,h3,h4,.font-serif{font-family:'DM Serif Display',serif;}
        .dokun-section{opacity:1;transform:translateY(0);transition:opacity .5s,transform .5s;}
    </style>
</head>
<body class="antialiased bg-dokun-ivory text-dokun-charcoal bg-dokun-pattern">

@include('partials.navbar', ['active' => 'artisans'])

@if(session('success'))
<div id="flash-msg" class="fixed top-28 left-1/2 -translate-x-1/2 z-50 bg-dokun-green text-white px-8 py-4 rounded-xl shadow-2xl font-bold text-center border border-dokun-gold/30 max-w-xl w-full">
    {{ session('success') }}
</div>
<script>setTimeout(()=>{let e=document.getElementById('flash-msg');if(e){e.style.opacity='0';e.style.transition='opacity .5s';setTimeout(()=>e.remove(),500);}},6000);</script>
@endif
@if(session('error'))
<div id="flash-err" class="fixed top-28 left-1/2 -translate-x-1/2 z-50 bg-red-600 text-white px-8 py-4 rounded-xl shadow-2xl font-bold text-center max-w-xl w-full">
    {{ session('error') }}
</div>
<script>setTimeout(()=>{let e=document.getElementById('flash-err');if(e){e.style.opacity='0';e.style.transition='opacity .5s';setTimeout(()=>e.remove(),500);}},7000);</script>
@endif

<main class="pt-32 pb-24 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

    {{-- ═══════════════════════════════════════════
         1. BREADCRUMB
    ═══════════════════════════════════════════ --}}
    <nav class="mb-10 text-sm font-semibold text-dokun-charcoal/50 flex items-center gap-2 flex-wrap">
        <a href="{{ route('home') }}" class="hover:text-dokun-gold transition-colors">Accueil</a>
        <svg class="w-3.5 h-3.5 text-dokun-charcoal/30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <a href="{{ route('artisans.index') }}" class="hover:text-dokun-gold transition-colors">Artisans</a>
        <svg class="w-3.5 h-3.5 text-dokun-charcoal/30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <span class="text-dokun-green">{{ $artisan->professional_name ?? $artisan->first_name }}</span>
    </nav>

    {{-- ═══════════════════════════════════════════
         2. HERO SECTION — 2 columns
    ═══════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 mb-20">

        {{-- LEFT COLUMN: Portrait + Quick Contact --}}
        <div class="lg:col-span-4 space-y-6">
            {{-- Portrait --}}
            <div class="rounded-[2rem] overflow-hidden bg-gray-200 aspect-[3/4] shadow-2xl relative border border-gray-100 group">
                <img src="{{ $artisan->image_url }}" alt="{{ $artisan->first_name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                <div class="absolute inset-0 bg-gradient-to-t from-dokun-charcoal/80 via-transparent to-transparent"></div>
                <div class="absolute bottom-6 left-6 right-6 text-white">
                    <span class="px-3 py-1 bg-dokun-gold text-white rounded-lg text-xs font-bold uppercase tracking-wider shadow-lg mb-2 inline-block">
                        {{ $artisan->experience_years }} {{ __('app.artisan_years') }}
                    </span>
                    <h2 class="font-serif text-3xl leading-tight">{{ $artisan->first_name }} {{ $artisan->last_name }}</h2>
                </div>
            </div>

            {{-- Quick Contact Card --}}
            <div class="bg-white p-6 rounded-3xl shadow-lg border border-gray-100 space-y-3">
                <a href="{{ route('payment.confirm', $artisan->id) }}"
                   class="w-full flex items-center justify-center gap-2 py-4 bg-dokun-green text-white font-bold rounded-xl hover:bg-dokun-green/90 active:scale-[.98] transition shadow-lg shadow-dokun-green/20">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    {{ __('app.bridge_reserve') }}
                </a>

                @if($artisan->whatsapp)
                <a href="https://wa.me/{{ str_replace(['+', ' '], '', $artisan->whatsapp) }}" target="_blank" rel="noopener"
                   class="w-full flex items-center justify-center gap-2 py-3.5 border-2 border-green-500 text-green-600 font-bold rounded-xl hover:bg-green-50 transition">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 00-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 1.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                    WhatsApp
                </a>
                @endif

                <a href="https://www.google.com/maps/dir/?api=1&destination={{ urlencode($artisan->address) }}" target="_blank" rel="noopener" id="itinerary-link"
                   class="w-full flex items-center justify-center gap-2 py-3.5 border-2 border-dokun-green/30 text-dokun-green font-bold rounded-xl hover:bg-emerald-50 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <span id="itinerary-text">📍 Itinéraire</span>
                </a>
            </div>
        </div>

        {{-- RIGHT COLUMN: Info --}}
        <div class="lg:col-span-8 space-y-10">
            {{-- Name + Badges --}}
            <div>
                <h1 class="text-4xl md:text-6xl font-serif text-dokun-green tracking-tight mb-5 leading-tight">
                    {{ $artisan->professional_name ?? ($artisan->first_name . ' ' . $artisan->last_name) }}
                </h1>
                <div class="flex flex-wrap items-center gap-3 text-sm font-bold uppercase tracking-wider mb-4">
                    @foreach($artisan->savoirFaires as $sf)
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-dokun-gold/10 text-dokun-gold rounded-full border border-dokun-gold/20">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                            {{ $sf->name }}
                        </span>
                    @endforeach
                </div>
                <div class="flex items-center gap-2 text-sm text-dokun-charcoal/60">
                    <svg class="w-4 h-4 text-dokun-green/60" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <span>{{ $artisan->address }}</span>
                </div>
            </div>

            {{-- Mon Savoir-Faire --}}
            <section>
                <h2 class="text-2xl font-serif text-dokun-green mb-3 flex items-center gap-3">
                    <span class="w-10 h-1 bg-dokun-gold rounded-full"></span>
                    Mon Savoir-Faire
                </h2>
                <p class="text-dokun-charcoal/80 leading-relaxed text-[15px]">{{ $artisan->description }}</p>
            </section>

            {{-- Mon Parcours --}}
            @if($artisan->history)
            <section>
                <h2 class="text-2xl font-serif text-dokun-green mb-3 flex items-center gap-3">
                    <span class="w-10 h-1 bg-dokun-gold rounded-full"></span>
                    Mon Parcours
                </h2>
                <p class="text-dokun-charcoal/80 leading-relaxed text-[15px]">{{ $artisan->history }}</p>
            </section>
            @endif
        </div>
    </div>

    {{-- ═══════════════════════════════════════════
         3. FEATURE CARDS — Quick Navigation
    ═══════════════════════════════════════════ --}}
    <section class="mb-20">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <a href="{{ route('features.bridge.page', $artisan) }}"
               class="group bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-lg hover:border-dokun-gold/30 transition-all duration-300 text-center">
                <div class="w-12 h-12 mx-auto mb-3 rounded-xl bg-emerald-50 text-dokun-green flex items-center justify-center group-hover:bg-dokun-green group-hover:text-white transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/></svg>
                </div>
                <h3 class="font-bold text-sm text-dokun-charcoal mb-1">🌍 ƉƆKUN Bridge</h3>
                <p class="text-xs text-dokun-charcoal/50 leading-snug">Discuter en temps réel</p>
            </a>

            <a href="{{ route('features.voice.page', $artisan) }}"
               class="group bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-lg hover:border-dokun-gold/30 transition-all duration-300 text-center">
                <div class="w-12 h-12 mx-auto mb-3 rounded-xl bg-dokun-charcoal/5 text-dokun-charcoal flex items-center justify-center group-hover:bg-dokun-charcoal group-hover:text-white transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"/></svg>
                </div>
                <h3 class="font-bold text-sm text-dokun-charcoal mb-1">🎙️ ƉƆKUN Voice</h3>
                <p class="text-xs text-dokun-charcoal/50 leading-snug">Écouter son histoire</p>
            </a>

            <a href="{{ route('features.learn.page', $artisan) }}"
               class="group bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-lg hover:border-dokun-gold/30 transition-all duration-300 text-center">
                <div class="w-12 h-12 mx-auto mb-3 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center group-hover:bg-amber-500 group-hover:text-white transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                </div>
                <h3 class="font-bold text-sm text-dokun-charcoal mb-1">🗣️ ƉƆKUN Learn</h3>
                <p class="text-xs text-dokun-charcoal/50 leading-snug">Apprendre le Fon/Gun</p>
            </a>

            <a href="{{ route('carte') }}"
               class="group bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-lg hover:border-dokun-gold/30 transition-all duration-300 text-center">
                <div class="w-12 h-12 mx-auto mb-3 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center group-hover:bg-blue-600 group-hover:text-white transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                </div>
                <h3 class="font-bold text-sm text-dokun-charcoal mb-1">🗺️ Carte</h3>
                <p class="text-xs text-dokun-charcoal/50 leading-snug">Voir sur la carte</p>
            </a>
        </div>
    </section>

    {{-- ═══════════════════════════════════════════
         4. GALERIE PHOTOS
    ═══════════════════════════════════════════ --}}
    @php
        $images = $artisan->media->where('type', 'image');
    @endphp
    @if($images->count() > 0)
    <section class="mb-20" x-data="gallery()">
        <h2 class="text-3xl font-serif text-dokun-green mb-6 flex items-center gap-3">
            <span class="w-10 h-1 bg-dokun-gold rounded-full"></span>
            Galerie Photos
        </h2>
        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
            @foreach($images as $img)
            <button @click="open({{ $loop->index }})" class="rounded-2xl overflow-hidden aspect-square bg-gray-200 group relative cursor-pointer">
                <img src="{{ $img->url }}" alt="Photo {{ $loop->iteration }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" loading="lazy">
                <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-colors duration-300"></div>
            </button>
            @endforeach
        </div>

        {{-- Lightbox --}}
        <template x-if="open">
            <div class="fixed inset-0 z-[100] bg-black/90 flex items-center justify-center p-4" @click.self="close()" @keydown.escape.window="close()">
                <button @click="prev()" class="absolute left-4 top-1/2 -translate-y-1/2 w-12 h-12 bg-white/20 hover:bg-white/40 text-white rounded-full flex items-center justify-center transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </button>
                <img :src="images[current]" class="max-h-[85vh] max-w-full rounded-xl shadow-2xl object-contain" alt="Galerie">
                <button @click="next()" class="absolute right-4 top-1/2 -translate-y-1/2 w-12 h-12 bg-white/20 hover:bg-white/40 text-white rounded-full flex items-center justify-center transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </button>
                <button @click="close()" class="absolute top-4 right-4 w-10 h-10 bg-white/20 hover:bg-white/40 text-white rounded-full flex items-center justify-center transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
                <div class="absolute bottom-4 text-white/70 text-sm font-semibold" x-text="(current+1)+' / '+images.length"></div>
            </div>
        </template>
    </section>
    @endif

    {{-- ═══════════════════════════════════════════
         5. ƉƆKUN BRIDGE — CTA Card
    ═══════════════════════════════════════════ --}}
    <section class="mb-20">
        <div class="bg-white rounded-2xl border border-emerald-100 shadow-lg overflow-hidden p-8 text-center">
            <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-emerald-50 flex items-center justify-center">
                <svg class="w-8 h-8 text-dokun-green" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/></svg>
            </div>
            <h3 class="font-serif text-2xl text-dokun-green mb-2">🌍 ƉƆKUN Bridge</h3>
            <p class="text-dokun-charcoal/60 text-sm mb-5 max-w-md mx-auto">Discutez en temps réel avec {{ $artisan->first_name }} — votre conversation est traduite automatiquement du Fon/Gun.</p>
            <a href="{{ route('features.bridge.page', $artisan) }}"
               class="inline-flex items-center gap-2 px-7 py-3.5 bg-dokun-green text-white font-bold rounded-xl hover:bg-dokun-green/90 active:scale-[.98] transition shadow-lg shadow-dokun-green/20">
                Ouvrir Bridge →
            </a>
        </div>
    </section>

    {{-- ═══════════════════════════════════════════
         6. ƉƆKUN VOICE — CTA Card
    ═══════════════════════════════════════════ --}}
    <section class="mb-20">
        <div class="bg-dokun-charcoal rounded-2xl border border-dokun-gold/20 shadow-lg overflow-hidden p-8 text-center">
            <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-dokun-gold/20 flex items-center justify-center">
                <svg class="w-8 h-8 text-dokun-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"/></svg>
            </div>
            <h3 class="font-serif text-2xl text-white mb-2">🎙️ ƉƆKUN Voice</h3>
            <p class="text-white/60 text-sm mb-5 max-w-md mx-auto">Écoutez {{ $artisan->first_name }} parler de son art dans sa langue maternelle, avec transcription et traduction.</p>
            <a href="{{ route('features.voice.page', $artisan) }}"
               class="inline-flex items-center gap-2 px-7 py-3.5 bg-dokun-gold text-white font-bold rounded-xl hover:bg-yellow-500 active:scale-[.98] transition shadow-lg shadow-dokun-gold/30">
                Ouvrir Voice →
            </a>
        </div>
    </section>

    {{-- ═══════════════════════════════════════════
         7. ƉƆKUN LEARN — CTA Card
    ═══════════════════════════════════════════ --}}
    <section class="mb-20">
        <div class="bg-amber-50 rounded-2xl border border-amber-200 shadow-lg p-8 text-center">
            <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-amber-200 text-amber-700 flex items-center justify-center">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
            </div>
            <h3 class="font-serif text-2xl text-amber-900 mb-2">🗣️ ƉƆKUN Learn</h3>
            <p class="text-amber-900/60 text-sm mb-5 max-w-md mx-auto">Apprenez le vocabulaire Fon/Gun à travers un jeu interactif avec flashcards et quiz.</p>
            <a href="{{ route('features.learn.page', $artisan) }}"
               class="inline-flex items-center gap-2 px-7 py-3.5 bg-amber-600 text-white font-bold rounded-xl hover:bg-amber-700 active:scale-[.98] transition shadow-lg shadow-amber-600/20">
                Ouvrir Learn →
            </a>
        </div>
    </section>

    {{-- ═══════════════════════════════════════════
         8. EXPÉRIENCES
    ═══════════════════════════════════════════ --}}
    @if($artisan->experiences->where('is_published', true)->count() > 0)
    <section class="mb-20">
        <h2 class="text-3xl font-serif text-dokun-green mb-6 flex items-center gap-3">
            <span class="w-10 h-1 bg-dokun-gold rounded-full"></span>
            {{ __('app.exp_title') }}s proposées
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($artisan->experiences->where('is_published', true) as $exp)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-lg transition-shadow duration-300 flex flex-col">
                <div class="p-6 flex-1">
                    <div class="flex items-center gap-2 mb-3">
                        <span class="px-2.5 py-1 bg-dokun-green/10 text-dokun-green text-xs font-bold rounded-full">🎨 Expérience</span>
                        <span class="px-2.5 py-1 bg-slate-100 text-slate-600 text-xs font-bold rounded-full">{{ $exp->duration_minutes }} min</span>
                    </div>
                    <h3 class="font-serif text-xl text-dokun-green mb-2">{{ $exp->title }}</h3>
                    <p class="text-dokun-charcoal/70 text-sm leading-relaxed mb-4">{{ $exp->summary }}</p>
                    <div class="flex items-center gap-4 text-xs text-dokun-charcoal/50">
                        <span class="flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            Max {{ $exp->capacity }} pers.
                        </span>
                    </div>
                </div>
                <div class="p-6 pt-0 border-t border-gray-50">
                    <div class="flex items-center justify-between pt-4">
                        <span class="font-serif text-2xl text-dokun-green font-bold">{{ number_format($exp->price, 0, ',', ' ') }} <span class="text-sm font-sans font-normal text-dokun-charcoal/50">FCFA</span></span>
                        <a href="{{ route('payment.confirm', $artisan->id) }}" class="px-5 py-2.5 bg-dokun-green text-white font-bold text-sm rounded-xl hover:bg-dokun-green/90 active:scale-[.98] transition shadow-md shadow-dokun-green/15">
                            Réserver
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </section>
    @endif

    {{-- ═══════════════════════════════════════════
         9. AVIS
    ═══════════════════════════════════════════ --}}
    @php
        $publishedReviews = $artisan->reviews->where('status', 'approved');
    @endphp
    @if($publishedReviews->count() > 0)
    <section class="mb-20">
        <h2 class="text-3xl font-serif text-dokun-green mb-6 flex items-center gap-3">
            <span class="w-10 h-1 bg-dokun-gold rounded-full"></span>
            {{ __('app.artisan_reviews') }} des visiteurs
            <span class="text-sm font-sans font-normal text-dokun-charcoal/40">({{ $publishedReviews->count() }})</span>
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($publishedReviews as $review)
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                <div class="flex items-center gap-1 mb-3">
                    @for($i = 1; $i <= 5; $i++)
                    <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-dokun-gold' : 'text-gray-200' }}" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                    @endfor
                </div>
                <p class="text-dokun-charcoal/80 text-sm leading-relaxed mb-4">"{{ $review->comment }}"</p>
                <div class="flex items-center justify-between pt-3 border-t border-gray-100">
                    <span class="text-xs font-bold text-dokun-charcoal/60">{{ $review->user->name ?? 'Anonyme' }}</span>
                    <span class="text-xs text-dokun-charcoal/40">{{ $review->created_at->diffForHumans() }}</span>
                </div>
            </div>
            @endforeach
        </div>
    </section>
    @endif

    @auth
    @if(
        $artisan->reservations->where('user_id', Auth::id())->where('status', 'completed')->count() > 0
        && $artisan->reviews->where('user_id', Auth::id())->count() === 0
    )
    <section class="mb-10">
        <div class="bg-dokun-gold/10 border border-dokun-gold/30 rounded-2xl p-6 text-center">
            <p class="font-bold text-dokun-charcoal mb-2">Vous avez visité l'atelier de {{ $artisan->first_name }} ?</p>
            <p class="text-sm text-dokun-charcoal/60 mb-4">Partagez votre expérience en laissant un avis.</p>
            <a href="{{ route('reviews.create', $artisan->reservations->where('user_id', Auth::id())->where('status', 'completed')->first()->id) }}"
               class="inline-flex items-center gap-2 px-6 py-3 bg-dokun-gold text-white font-bold rounded-xl hover:bg-yellow-600 active:scale-[.98] transition shadow-lg shadow-dokun-gold/20 text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                Laisser un avis
            </a>
        </div>
    </section>
    @endif
    @endauth

    {{-- ═══════════════════════════════════════════
         10. RÉSERVATION CTA
    ═══════════════════════════════════════════ --}}
    <section class="mb-8">
        <div class="bg-dokun-green rounded-[2rem] shadow-2xl overflow-hidden relative text-white">
            <div class="absolute top-0 right-0 w-64 h-64 bg-dokun-gold/10 rounded-bl-full -mr-20 -mt-20 pointer-events-none"></div>
            <div class="absolute bottom-0 left-0 w-40 h-40 bg-white/5 rounded-tr-full -ml-10 -mb-10 pointer-events-none"></div>
            <div class="p-10 lg:p-14 relative z-10 text-center max-w-2xl mx-auto">
                <span class="px-4 py-1.5 bg-dokun-gold text-dokun-charcoal font-bold text-xs uppercase tracking-widest rounded-full mb-5 inline-block shadow-md">
                    {{ __('app.exp_title') }} & Visites
                </span>
                <h2 class="text-3xl md:text-4xl font-serif text-white mb-3">Réserver une expérience avec {{ $artisan->first_name }}</h2>
                <p class="text-white/80 text-sm leading-relaxed mb-8">
                    Choisissez votre formule, personnalisez le nombre de personnes et réglez en toute sécurité via FedaPay.
                </p>
                <a href="{{ route('payment.confirm', $artisan->id) }}"
                   class="inline-flex items-center justify-center gap-3 px-8 py-5 bg-dokun-gold text-dokun-charcoal font-bold text-lg rounded-xl hover:bg-yellow-500 active:scale-95 transition shadow-2xl shadow-black/20">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    Continuer vers la réservation →
                </a>
            </div>
        </div>
    </section>

</main>

@include('partials.footer')

<script>
document.addEventListener('DOMContentLoaded', function () {
    const CSRF       = document.querySelector('meta[name="csrf-token"]').content;
    const ARTISAN_ID = {{ $artisan->id }};

    // ──────────────────────────────────────────
    // GÉOLOCALISATION
    // ──────────────────────────────────────────
    const itinLink = document.getElementById('itinerary-link');
    const itinText = document.getElementById('itinerary-text');
    const addr     = @json($artisan->address ?? 'Porto-Novo, Bénin');
    @if(!empty($artisan->latitude) && !empty($artisan->longitude))
        const destCoords = "{{ $artisan->latitude }},{{ $artisan->longitude }}";
    @else
        const destCoords = encodeURIComponent(addr);
    @endif

    if ("geolocation" in navigator) {
        navigator.geolocation.getCurrentPosition(pos => {
            const {latitude: lat, longitude: lng} = pos.coords;
            itinLink.href = `https://www.google.com/maps/dir/?api=1&origin=${lat},${lng}&destination=${destCoords}`;
            itinText.innerHTML = `📍 ${addr} <span class="text-[10px] font-black uppercase text-dokun-green bg-emerald-100 px-2 py-0.5 rounded-md ml-1">→ Y aller</span>`;
        }, () => {
            itinLink.href = `https://www.google.com/maps/dir/?api=1&destination=${destCoords}`;
        });
    } else {
        itinLink.href = `https://www.google.com/maps/dir/?api=1&destination=${destCoords}`;
    }

});
</script>

<script>
// Alpine.js component for gallery lightbox
function gallery() {
    return {
        open: false,
        current: 0,
        images: [
            @foreach($artisan->media->where('type', 'image') as $img)
                '{{ $img->url }}',
            @endforeach
        ],
        init() {
            this.open = false;
        },
        open(index) {
            this.current = index;
            this.open = true;
        },
        close() {
            this.open = false;
        },
        next() {
            this.current = (this.current + 1) % this.images.length;
        },
        prev() {
            this.current = (this.current - 1 + this.images.length) % this.images.length;
        }
    };
}
</script>
</body>
</html>
