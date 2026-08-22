<!DOCTYPE html>
<html lang="fr" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ƉƆKUN — Patrimoine Vivant de Porto-Novo</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=dm-serif-display:400|manrope:400,600,700,800&display=swap" rel="stylesheet"/>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>tailwind.config={theme:{extend:{colors:{dokun:{green:'#064E3B',gold:'#C99424',ivory:'#F8F6F0',charcoal:'#17201D'}},fontFamily:{sans:['Manrope','sans-serif'],serif:['"DM Serif Display"','serif']}}}}</script>
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        body{font-family:'Manrope',sans-serif;}
        h1,h2,.serif{font-family:'DM Serif Display',serif;}
        .slide{transition:opacity 1s ease;}
        .slider-content{opacity:0;transform:translateY(20px);transition:all .8s ease .3s;}
        .slide.active .slider-content{opacity:1;transform:translateY(0);}
        .kente-stripe{background:repeating-linear-gradient(90deg,#064E3B 0 24px,#C99424 24px 32px,#17201D 32px 40px,#C99424 40px 48px);}
        .wax-pattern{background-image:url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' stroke='%23C99424' stroke-opacity='0.25'%3E%3Ccircle cx='30' cy='30' r='12'/%3E%3Ccircle cx='0' cy='0' r='8'/%3E%3Ccircle cx='60' cy='0' r='8'/%3E%3Ccircle cx='0' cy='60' r='8'/%3E%3Ccircle cx='60' cy='60' r='8'/%3E%3Cpath d='M30 18l10 12-10 12-10-12z'/%3E%3C/g%3E%3C/svg%3E");}
        @keyframes fadeUp{from{opacity:0;transform:translateY(24px)}to{opacity:1;transform:translateY(0)}}
        .fade-up{animation:fadeUp .7s ease both;}

        /* Home map card styles */
        #home-map .leaflet-control-zoom { border: none !important; box-shadow: 0 2px 10px rgba(0,0,0,0.15) !important; border-radius: 10px !important; overflow: hidden; }
        #home-map .leaflet-control-zoom a { background: #1a1a1a !important; color: #ffffff !important; width: 32px !important; height: 32px !important; line-height: 32px !important; font-size: 16px !important; font-weight: 700 !important; border: none !important; }
        #home-map .leaflet-control-zoom a:hover { background: #333 !important; }
        #home-map .leaflet-control-zoom a:first-child { border-radius: 10px 10px 0 0 !important; }
        #home-map .leaflet-control-zoom a:last-child { border-radius: 0 0 10px 10px !important; }
        #home-map .leaflet-control-attribution { display: none !important; }

        .drop-marker {
            width: 28px; height: 36px;
            position: relative;
        }
        .drop-marker svg { width: 100%; height: 100%; }
        .drop-marker .drop-shadow {
            position: absolute; bottom: -3px; left: 50%; transform: translateX(-50%);
            width: 14px; height: 6px; background: rgba(0,0,0,0.25); border-radius: 50%;
            filter: blur(2px);
        }
    </style>
</head>
<body class="antialiased bg-[#F8F6F0] text-[#17201D]">

{{-- Navbar partagée (transparente sur le hero, devient blanche au scroll) --}}
@include('partials.navbar', ['transparent' => true])

<!-- HERO SLIDER -->
<section id="hero" class="relative h-screen overflow-hidden bg-[#17201D]">
    <div id="slides-container" class="absolute inset-0">
        @php
        $slides = [
            ['img'=>'images/dokun_bg1.jpg','tag'=>'Voyage Culturel','title'=>"L'Âme de Porto-Novo",'sub'=>'Le patrimoine vivant, une richesse partagée. Explorez la ville aux trois noms.','cta_label'=>'Découvrir sur la carte','cta_url'=>route('carte'),'cta_style'=>'gold'],
            ['img'=>'images/tisserand.png','tag'=>'Transmission','title'=>'Rencontrez nos Maîtres Artisans','sub'=>'Derrière chaque objet se cache une histoire, un visage, des mains expertes.','cta_label'=>'Voir le répertoire','cta_url'=>route('artisans.index'),'cta_style'=>'green'],
            ['img'=>'images/tisserand.jpg','tag'=>'Savoir-Faire','title'=>'Le Tissage Kanvo','sub'=>'Un art textile unique, fierté des familles de Porto-Novo depuis des générations.','cta_label'=>'Découvrir les métiers','cta_url'=>route('savoir-faire.index'),'cta_style'=>'outline'],
            ['img'=>'images/forgeron.jpg','tag'=>'Valorisation','title'=>'Des Savoir-Faire Inestimables','sub'=>'Du tissage Kanvo à la poterie, des techniques transmises de génération en génération.','cta_label'=>'Découvrir les métiers','cta_url'=>route('savoir-faire.index') ,'cta_style'=>'outline'],
            ['img'=>'images/dokun_bg3.jpg','tag'=>'Opportunités Locales','title'=>'Saisissez les Opportunités','sub'=>'ƉƆKUN crée de nouvelles opportunités économiques pour les communautés locales.','cta_label'=>'Explorer la carte','cta_url'=>route('carte'),'cta_style'=>'gold'],
            ['img'=>'images/poterie_en_action.png','tag'=>'Expériences','title'=>'Vivez une Expérience Unique','sub'=>'Réservez une visite d\'atelier et apprenez directement auprès d\'un maître artisan.','cta_label'=>'Voir les expériences','cta_url'=>route('experiences.index'),'cta_style'=>'green'],
        ];
        @endphp
        @foreach($slides as $i => $slide)
        <div class="slide absolute inset-0 w-full h-full {{ $i===0?'opacity-100 active':'opacity-0' }}">
            <img src="{{ asset($slide['img']) }}" class="w-full h-full object-cover" alt="{{ $slide['tag'] }}" onerror="this.src='{{ asset('images/hero/hero_dokun.png') }}'">
            <div class="absolute inset-0 bg-gradient-to-b from-[#17201D]/80 via-[#17201D]/55 to-[#17201D]/90"></div>
            <div class="absolute inset-0 flex items-center justify-center pt-20">
                <div class="slider-content max-w-4xl mx-auto px-4 text-center text-white">
                    <span class="inline-block py-1.5 px-4 rounded-full bg-[#C99424]/20 text-[#C99424] font-bold text-xs tracking-[0.2em] uppercase mb-6 border border-[#C99424]/30">{{ $slide['tag'] }}</span>
                    <h1 class="serif text-5xl md:text-7xl mb-6 leading-tight">{{ $slide['title'] }}</h1>
                    <p class="text-white/80 text-lg md:text-xl max-w-2xl mx-auto mb-10 font-light leading-relaxed">{{ $slide['sub'] }}</p>
                    <a href="{{ $slide['cta_url'] }}" class="{{ $slide['cta_style']==='gold' ? 'bg-[#C99424] text-white hover:bg-yellow-600 shadow-xl shadow-[#C99424]/20' : ($slide['cta_style']==='green' ? 'bg-[#064E3B] text-white hover:bg-[#064E3B]/90' : 'bg-white/10 border border-white/20 text-white hover:bg-white/20') }} px-8 py-4 rounded-full font-semibold transition-all inline-block">
                        {{ $slide['cta_label'] }}
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    <div class="absolute bottom-10 left-1/2 -translate-x-1/2 z-10 flex gap-3" id="dots">
        @foreach($slides as $i => $slide)
        <button onclick="goTo({{ $i }})" class="w-2.5 h-2.5 rounded-full transition-all duration-300 {{ $i===0?'bg-[#C99424]':'bg-white/30' }}"></button>
        @endforeach
    </div>
</section>

<script>
    // Slider héros — autonome (la navbar partagée gère elle-même son menu/scroll)
    const slides=document.querySelectorAll('.slide'),dots=document.querySelectorAll('#dots button');
    let cur=0,timer;
    function goTo(i){
        if(!slides.length) return;
        slides[cur].classList.remove('opacity-100','active');slides[cur].classList.add('opacity-0');
        dots[cur].classList.remove('bg-[#C99424]');dots[cur].classList.add('bg-white/30');
        cur=i;
        slides[cur].classList.remove('opacity-0');slides[cur].classList.add('opacity-100','active');
        dots[cur].classList.remove('bg-white/30');dots[cur].classList.add('bg-[#C99424]');
        clearInterval(timer);timer=setInterval(()=>goTo((cur+1)%slides.length),6000);
    }
    timer=setInterval(()=>goTo((cur+1)%slides.length),6000);
</script>

<!-- STATS BAR -->
<section class="bg-[#064E3B] text-white py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
        <div><div class="serif text-4xl text-[#C99424]">{{ $artisans->count() }}+</div><div class="text-white/70 text-sm mt-1 font-semibold uppercase tracking-wider">Artisans</div></div>
        <div><div class="serif text-4xl text-[#C99424]">{{ $categories->count() }}+</div><div class="text-white/70 text-sm mt-1 font-semibold uppercase tracking-wider">Savoir-faire</div></div>
        <div><div class="serif text-4xl text-[#C99424]">1</div><div class="text-white/70 text-sm mt-1 font-semibold uppercase tracking-wider">Ville pilote</div></div>
        <div><div class="serif text-4xl text-[#C99424]">100%</div><div class="text-white/70 text-sm mt-1 font-semibold uppercase tracking-wider">Patrimoine vivant</div></div>
    </div>
</section>

<!-- COMMENT ÇA MARCHE -->
<section id="comment-ca-marche" class="py-24">
    <img src="{{ asset('images/tisserand.jpg') }}" class="absolute inset-0 w-full h-full object-cover opacity-10" alt="Tisserand en action" onerror="this.src='{{ asset('images/hero/tourisme_porto_novo.png') }}'">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16 fade-up">
            <h2 class="serif text-4xl md:text-5xl text-[#064E3B] mb-4">Comment ça marche ?</h2>
            <p class="text-[#17201D]/60 max-w-2xl mx-auto text-lg">Simple comme une visite chez un ami artisan.</p>
            <div class="h-1 w-20 bg-[#C99424] mx-auto mt-5"></div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            @php $steps = [
                ['num'=>'1','title'=>'Cherchez','desc'=>'Recherchez un savoir-faire, un artisan ou une expérience selon vos envies.'],
                ['num'=>'2','title'=>'Découvrez','desc'=>'Consultez les profils détaillés, les photos et l\'histoire des artisans.'],
                ['num'=>'3','title'=>'Réservez','desc'=>'Envoyez une demande de visite directement depuis la plateforme.'],
                ['num'=>'4','title'=>'Vivez','desc'=>'Rencontrez l\'artisan, participez à l\'atelier, vivez l\'expérience.'],
            ]; @endphp
            @foreach($steps as $step)
            <div class="relative bg-white rounded-2xl p-8 text-center shadow-lg border border-gray-100 hover:-translate-y-1 transition-transform">                
                <div class="absolute -top-5 left-1/2 -translate-x-1/2 w-12 h-12 bg-[#C99424] text-white rounded-full flex items-center justify-center font-bold text-lg shadow-md">✓</div>
                <div class="w-8 h-8 bg-[#064E3B] text-white rounded-full flex items-center justify-center font-bold text-sm mx-auto mb-4">{{ $step['num'] }}</div>
                <h3 class="serif text-2xl text-[#064E3B] mb-3">{{ $step['title'] }}</h3>
                <p class="text-[#17201D]/70 text-sm leading-relaxed">{{ $step['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- SAVOIR-FAIRE -->
<section id="savoir-faire" class="py-24 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row justify-between items-end mb-14 gap-6 fade-up">
            <div>
                <h2 class="serif text-4xl md:text-5xl text-[#064E3B] mb-3">Savoir-Faire Traditionnels</h2>
                <p class="text-[#17201D]/60 text-lg max-w-xl">L'héritage d'un peuple raconté par la matière et le geste.</p>
                <div class="h-1 w-20 bg-[#C99424] mt-5"></div>
            </div>
            <a href="{{ route('savoir-faire.index') }}" class="hidden md:inline-flex items-center gap-2 px-6 py-3 border-2 border-[#064E3B] text-[#064E3B] font-bold rounded-full hover:bg-[#064E3B] hover:text-white transition-colors">
                Voir tout
            </a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($categories as $cat)
            <a href="{{ route('artisans.index') }}?savoir_faire={{ $cat->id }}" class="group bg-[#F8F6F0] rounded-2xl p-8 border border-gray-100 hover:border-[#C99424]/30 hover:shadow-xl transition-all hover:-translate-y-1">
                <div class="w-14 h-14 bg-white rounded-full flex items-center justify-center text-[#064E3B] mb-6 group-hover:bg-[#064E3B] group-hover:text-white transition-all shadow-sm">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                </div>
                <h3 class="serif text-2xl text-[#17201D] mb-3 group-hover:text-[#064E3B] transition-colors">{{ $cat->name }}</h3>
                <p class="text-[#17201D]/60 text-sm leading-relaxed mb-6">{{ $cat->description }}</p>
                <span class="text-[#C99424] font-bold text-xs uppercase tracking-wider inline-flex items-center gap-1 group-hover:gap-2 transition-all">Découvrir →</span>
            </a>
            @endforeach
        </div>
    </div>
</section>

<!-- IMAGE CULTURELLE IMMERSIVE -->
<section class="relative h-80 overflow-hidden">
    <img src="{{ asset('images/poterie_en_action.png') }}" class="w-full h-full object-cover" alt="Artisanat en action" onerror="this.src='{{ asset('images/hero/tourisme_porto_novo.png') }}'">
    <div class="absolute inset-0 bg-[#064E3B]/75 flex items-center justify-center">
        <div class="text-center text-white px-4">
            <p class="serif text-3xl md:text-5xl mb-4">"La technologie sert le patrimoine.<br>Elle ne le remplace pas."</p>
            <span class="text-[#C99424] font-bold text-sm uppercase tracking-widest">La règle d'or de ƉƆKUN</span>
        </div>
    </div>
</section>

<!-- ARTISANS -->
<section id="artisans" class="py-24 bg-[#F8F6F0]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row justify-between items-end mb-14 gap-6 fade-up">
            <div>
                <h2 class="serif text-4xl md:text-5xl text-[#064E3B] mb-3">Portraits d'Artisans</h2>
                <p class="text-[#17201D]/60 text-lg max-w-xl">Les visages et les histoires de ceux qui font vivre notre patrimoine.</p>
                <div class="h-1 w-20 bg-[#C99424] mt-5"></div>
            </div>
            <a href="{{ route('artisans.index') }}" class="hidden md:inline-flex items-center gap-2 px-6 py-3 border-2 border-[#064E3B] text-[#064E3B] font-bold rounded-full hover:bg-[#064E3B] hover:text-white transition-colors">
                Explorer le répertoire
            </a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
            @foreach($artisans as $artisan)
            <a href="{{ route('artisans.show', $artisan->id) }}" class="bg-white rounded-2xl overflow-hidden shadow-lg border border-gray-100 group hover:-translate-y-1 transition-all duration-300 block">
                <div class="h-64 relative overflow-hidden">
                    <img src="{{ $artisan->image_url }}" alt="{{ $artisan->first_name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                    <div class="absolute top-4 left-4 bg-white/95 px-3 py-1.5 rounded-lg text-xs font-bold text-[#064E3B]">{{ $artisan->address }}</div>
                </div>
                <div class="p-7">
                    <h3 class="serif text-2xl text-[#17201D] mb-1 group-hover:text-[#064E3B] transition-colors">{{ $artisan->first_name }} {{ $artisan->last_name }}</h3>
                    <p class="text-[#C99424] text-xs font-bold uppercase tracking-wider mb-4">{{ $artisan->professional_name ?? 'Maître Artisan' }}</p>
                    <p class="text-[#17201D]/60 text-sm leading-relaxed line-clamp-2 mb-6">"{{ $artisan->description }}"</p>
                    <div class="flex flex-wrap gap-2">
                        @foreach($artisan->savoirFaires as $sf)
                        <span class="px-2.5 py-1 bg-[#F8F6F0] text-[#C99424] text-[10px] font-bold uppercase tracking-wider rounded border border-[#C99424]/20">{{ $sf->name }}</span>
                        @endforeach
                    </div>
                </div>
            </a>
            @endforeach
        </div>
        <div class="text-center mt-10 md:hidden">
            <a href="{{ route('artisans.index') }}" class="inline-flex items-center gap-2 px-8 py-4 border-2 border-[#064E3B] text-[#064E3B] font-bold rounded-full hover:bg-[#064E3B] hover:text-white transition-colors">Explorer le répertoire</a>
        </div>
    </div>
</section>

<!-- POUR QUI ? -->
<section class="py-24 bg-[#064E3B] text-white relative overflow-hidden">
    <img src="{{ asset('images/vannerie.jpg') }}" class="absolute inset-0 w-full h-full object-cover opacity-15" alt="" loading="lazy">
    <div class="absolute inset-0 wax-pattern opacity-40"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center mb-16 fade-up">
            <h2 class="serif text-4xl md:text-5xl mb-4">Pour qui est ƉƆKUN ?</h2>
            <div class="h-1 w-20 bg-[#C99424] mx-auto mt-5"></div>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-6">
            @php $publics = [
                ['label'=>'Artisans','desc'=>'Détenteurs qui souhaitent valoriser leurs savoir-faire'],
                ['label'=>'Touristes','desc'=>'Visiteurs qui cherchent des expériences authentiques'],
                ['label'=>'Guides','desc'=>'Professionnels qui construisent des parcours culturels'],
                ['label'=>'Écoles','desc'=>'Établissements qui organisent des sorties pédagogiques'],
                ['label'=>'Chercheurs','desc'=>'Universitaires et étudiants en quête de documentation'],
                ['label'=>'Partenaires','desc'=>'Hôtels, agences, structures culturelles et touristiques'],
            ]; @endphp
            @foreach($publics as $pub)
            <div class="text-center bg-white/10 rounded-2xl p-6 hover:bg-white/20 transition-colors">
                <div class="w-10 h-10 rounded-full border border-[#C99424]/50 flex items-center justify-center mx-auto mb-3"><svg class="w-5 h-5 text-[#C99424]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 12a4 4 0 100-8 4 4 0 000 8zm7 9a7 7 0 00-14 0"/></svg></div>
                <h3 class="font-bold text-lg mb-2 text-[#C99424]">{{ $pub['label'] }}</h3>
                <p class="text-white/70 text-xs leading-relaxed">{{ $pub['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- CARTE INTÉGRÉE -->
<section class="py-20 bg-[#17201D] relative overflow-hidden">
    <img src="{{ asset('images/dokun_carte.jpg') }}" class="absolute inset-0 w-full h-full object-cover opacity-10" alt="" loading="lazy">
    <div class="absolute inset-0 wax-pattern opacity-30"></div>
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center mb-10 fade-up">
            <h2 class="serif text-3xl md:text-4xl text-white mb-3">{{ __('app.home_map_title') }}</h2>
            <p class="text-white/60 text-base max-w-xl mx-auto">{{ __('app.home_map_desc') }}</p>
        </div>
        <div class="bg-white rounded-2xl shadow-2xl overflow-hidden" style="height: 420px;">
            <div id="home-map" style="height: 100%; width: 100%;"></div>
        </div>
        <div class="text-center mt-6">
            <a href="{{ route('carte') }}" class="inline-flex items-center gap-2 px-7 py-3 bg-[#C99424] text-white font-bold rounded-full hover:bg-[#b3831f] transition shadow-lg text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                {{ __('app.home_cta_map') }}
            </a>
        </div>
    </div>
</section>

<!-- CTA FINAL -->
<section class="py-28 text-center relative overflow-hidden">
    <img src="{{ asset('images/dokun_bg.jpeg') }}" class="absolute inset-0 w-full h-full object-cover" alt="" loading="lazy">
    <div class="absolute inset-0 bg-[#064E3B]/85"></div>
    <div class="absolute inset-0 wax-pattern opacity-40"></div>
    <div class="relative z-10 max-w-3xl mx-auto px-4 fade-up">
        <h2 class="serif text-4xl md:text-5xl text-white mb-6">{{ __('app.home_final_title') }}</h2>
        <p class="text-white/80 text-lg mb-10">{{ __('app.home_final_desc') }}</p>
        <div class="flex flex-col sm:flex-row justify-center gap-5">
            <a href="{{ route('carte') }}" class="px-8 py-4 bg-[#C99424] text-white font-bold rounded-full hover:bg-[#b3831f] shadow-xl transition-all text-lg">{{ __('app.home_final_map') }}</a>
            <a href="{{ route('artisans.index') }}" class="px-8 py-4 border-2 border-white text-white font-bold rounded-full hover:bg-white hover:text-[#064E3B] transition-all text-lg">{{ __('app.home_final_artisans') }}</a>
        </div>
    </div>
</section>

<!-- FOOTER -->
@include('partials.footer')

<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    const homeMap = L.map('home-map', { zoomControl: true, scrollWheelZoom: false }).setView([6.4969, 2.6289], 13);
    L.control.zoom({ position: 'topleft' }).addTo(homeMap);

    // OSM standard tiles (gris clair ville, vert végétation, bleu lagune, routes orange/jaune)
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap',
        maxZoom: 19
    }).addTo(homeMap);

    // Marqueurs goutte bleu roi
    const dropIcon = L.divIcon({
        className: 'drop-marker',
        html: `<svg viewBox="0 0 28 36" xmlns="http://www.w3.org/2000/svg">
            <path d="M14 0C6.3 0 0 6.3 0 14c0 10.5 14 22 14 22s14-11.5 14-22C28 6.3 21.7 0 14 0z" fill="#2563EB"/>
            <circle cx="14" cy="13.5" r="5.5" fill="#ffffff"/>
        </svg><div class="drop-shadow"></div>`,
        iconSize: [28, 36],
        iconAnchor: [14, 34],
        popupAnchor: [0, -30]
    });

    const quartiers = @json($quartiers ?? []);
    const artisans = @json($mapArtisans ?? []);

    (quartiers.length ? quartiers : [{ name: 'Porto-Novo', lat: 6.4969, lng: 2.6289 }]).forEach(q => {
        const m = L.marker([q.lat, q.lng], { icon: dropIcon }).addTo(homeMap);
        m.bindPopup(`<strong style="font-family:'Manrope'">${q.name}</strong>`);
    });

    artisans.forEach(a => {
        if (!a.latitude || !a.longitude) return;
        L.marker([a.latitude, a.longitude], { icon: dropIcon }).addTo(homeMap)
            .bindPopup(`<strong style="font-family:'Manrope'">${a.professional_name || (a.first_name + ' ' + a.last_name)}</strong><br><a href="/artisans/${a.id}" style="color:#2563EB;font-size:12px;">Voir la fiche</a>`);
    });
</script>

</body>
</html>
