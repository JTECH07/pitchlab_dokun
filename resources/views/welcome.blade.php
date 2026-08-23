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
        .slide{transition:opacity 1s ease, visibility 1s;}
        .slide:not(.active){visibility:hidden;}
        .slider-content{opacity:0;transform:translateY(20px);transition:all .8s ease .3s;}
        .slide.active .slider-content{opacity:1;transform:translateY(0);}
        .kente-stripe{background:repeating-linear-gradient(90deg,#064E3B 0 24px,#C99424 24px 32px,#17201D 32px 40px,#C99424 40px 48px);}
        .wax-pattern{background-image:url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' stroke='%23C99424' stroke-opacity='0.25'%3E%3Ccircle cx='30' cy='30' r='12'/%3E%3Ccircle cx='0' cy='0' r='8'/%3E%3Ccircle cx='60' cy='0' r='8'/%3E%3Ccircle cx='0' cy='60' r='8'/%3E%3Ccircle cx='60' cy='60' r='8'/%3E%3Cpath d='M30 18l10 12-10 12-10-12z'/%3E%3C/g%3E%3C/svg%3E");}
        @keyframes fadeUp{from{opacity:0;transform:translateY(24px)}to{opacity:1;transform:translateY(0)}}
        .fade-up{animation:fadeUp .7s ease both;}

        /* Illustrations « Comment ça marche » */
        @keyframes floatY{0%,100%{transform:translateY(0)}50%{transform:translateY(-8px)}}
        .illus-float{animation:floatY 4s ease-in-out infinite;}
        @keyframes pulseSoft{0%,100%{transform:scale(1);opacity:.9}50%{transform:scale(1.12);opacity:1}}
        .illus-pulse{animation:pulseSoft 2.6s ease-in-out infinite;transform-origin:center;transform-box:fill-box;}
        @keyframes popIn{0%{transform:scale(.6)}60%{transform:scale(1.08)}100%{transform:scale(1)}}
        .illus-pop{animation:popIn 1.4s ease infinite alternate;transform-origin:center;transform-box:fill-box;}
        @keyframes smoke{0%{opacity:.15;transform:translateY(3px)}50%{opacity:.8}100%{opacity:.15;transform:translateY(-5px)}}
        .illus-smoke{animation:smoke 2.4s ease-in-out infinite;transform-origin:center;transform-box:fill-box;}

        /* Révélation au scroll */
        .reveal{opacity:0;transform:translateY(28px);transition:all .7s cubic-bezier(.22,.61,.36,1);}
        .reveal.visible{opacity:1;transform:translateY(0);}

        /* Ken Burns : léger zoom sur l'image active du slider */
        @keyframes kenburns{0%{transform:scale(1)}100%{transform:scale(1.08)}}
        .slide.active img{animation:kenburns 7s ease-out forwards;}

        /* Cartes savoir-faire : lift + balayage lumineux */
        .sf-card{transition:transform .45s cubic-bezier(.22,1,.36,1),box-shadow .45s,border-color .45s;}
        .sf-card:hover{transform:translateY(-8px);box-shadow:0 24px 48px -16px rgba(6,78,59,.18);}
        .sf-card::after{content:'';position:absolute;top:0;left:-80%;width:55%;height:100%;background:linear-gradient(105deg,transparent,rgba(201,148,36,.14),transparent);transform:skewX(-20deg);transition:left .8s ease;pointer-events:none;}
        .sf-card:hover::after{left:135%;}

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
            ['img'=>'images/tisserand.jpg','tag'=>'Transmission','title'=>'Rencontrez nos Maîtres Artisans','sub'=>'Du tissage Kanvo à la poterie, des techniques transmises de génération en génération.','cta_label'=>'Voir le répertoire','cta_url'=>route('artisans.index'),'cta_style'=>'green'],
            ['img'=>'images/forgeron.jpg','tag'=>'Savoir-Faire','title'=>'Des Savoir-Faire Inestimables','sub'=>'Derrière chaque objet se cache une histoire, un visage, des mains expertes.','cta_label'=>'Découvrir les métiers','cta_url'=>route('savoir-faire.index'),'cta_style'=>'outline'],
            ['img'=>'images/dokun_bg3.jpg','tag'=>'Opportunités Locales','title'=>'Saisissez les Opportunités','sub'=>'ƉƆKUN crée de nouvelles opportunités économiques pour les communautés locales.','cta_label'=>'Explorer la carte','cta_url'=>route('carte'),'cta_style'=>'gold'],
            ['img'=>'images/poterie_en_action.png','tag'=>'Expériences','title'=>'Vivez une Expérience Unique','sub'=>'Réservez une visite d\'atelier et apprenez directement auprès d\'un maître artisan.','cta_label'=>'Voir les expériences','cta_url'=>route('experiences.index'),'cta_style'=>'green'],
        ];
        @endphp
        @foreach($slides as $i => $slide)
        <div class="slide absolute inset-0 w-full h-full {{ $i===0?'opacity-100 active':'opacity-0' }}">
            <img src="/{{ $slide['img'] }}" class="w-full h-full object-cover" alt="{{ $slide['tag'] }}" loading="eager">
            <div class="absolute inset-0 bg-gradient-to-b from-[#17201D]/65 via-[#17201D]/35 to-[#17201D]/80 pointer-events-none"></div>
            <div class="absolute inset-0 flex items-center justify-center pt-20 z-10">
                <div class="slider-content max-w-4xl mx-auto px-4 text-center text-white">
                    <span class="inline-block py-1.5 px-4 rounded-full bg-[#C99424]/20 text-[#C99424] font-bold text-xs tracking-[0.2em] uppercase mb-6 border border-[#C99424]/30">{{ $slide['tag'] }}</span>
                    <h1 class="serif text-5xl md:text-7xl mb-6 leading-tight" style="text-shadow:0 2px 28px rgba(23,32,29,.6)">{{ $slide['title'] }}</h1>
                    <p class="text-white/90 text-lg md:text-xl max-w-2xl mx-auto mb-10 font-light leading-relaxed" style="text-shadow:0 1px 16px rgba(23,32,29,.7)">{{ $slide['sub'] }}</p>
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

<script>
    // Révélation au scroll — exécuté après le chargement du DOM complet
    document.addEventListener('DOMContentLoaded', () => {
        const io = new IntersectionObserver((entries)=>{
            entries.forEach(e=>{ if(e.isIntersecting){ e.target.classList.add('visible'); io.unobserve(e.target); } });
        },{threshold:.12, rootMargin:'0px 0px -40px 0px'});
        document.querySelectorAll('.reveal').forEach(el=>io.observe(el));
        // Sécurité : tout révéler si l'observer ne supporte pas le navigateur
        if (!('IntersectionObserver' in window)) {
            document.querySelectorAll('.reveal').forEach(el=>el.classList.add('visible'));
        }
    });
</script>

<!-- STATS BAR -->
<section class="bg-[#064E3B] text-white py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
        <div><div class="serif text-4xl text-[#C99424]">{{ number_format($artisansCount) }}+</div><div class="text-white/70 text-sm mt-1 font-semibold uppercase tracking-wider">Artisans</div></div>
        <div><div class="serif text-4xl text-[#C99424]">{{ number_format($categoriesCount) }}+</div><div class="text-white/70 text-sm mt-1 font-semibold uppercase tracking-wider">Savoir-faire</div></div>
        <div><div class="serif text-4xl text-[#C99424]">1</div><div class="text-white/70 text-sm mt-1 font-semibold uppercase tracking-wider">Ville pilote</div></div>
        <div><div class="serif text-4xl text-[#C99424]">100%</div><div class="text-white/70 text-sm mt-1 font-semibold uppercase tracking-wider">Patrimoine vivant</div></div>
    </div>
</section>

<!-- COMMENT ÇA MARCHE -->
<section id="comment-ca-marche" class="py-24 relative overflow-hidden bg-[#F8F6F0]">
    <img src="{{ url('images/tisserand.jpg') }}" class="absolute inset-0 w-full h-full object-cover opacity-[0.1] box-shadow" alt="" loading="lazy">
    <div class="absolute inset-0 wax-pattern opacity-30"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center mb-16 fade-up">
            <h2 class="serif text-4xl md:text-5xl text-[#064E3B] mb-4">Comment ça marche ?</h2>
            <p class="text-[#17201D]/60 max-w-2xl mx-auto text-lg">Simple comme une visite chez un ami artisan.</p>
            <div class="h-1 w-20 bg-[#C99424] mx-auto mt-5"></div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8 relative">
            {{-- Ligne de connexion entre les étapes (desktop) --}}
            <div class="hidden md:block absolute top-24 left-[12%] right-[12%] h-0.5 bg-gradient-to-r from-[#C99424]/20 via-[#C99424]/50 to-[#C99424]/20" aria-hidden="true"></div>
            @php $steps = [
                ['num'=>'1','title'=>'Cherchez','desc'=>'Recherchez un savoir-faire, un artisan ou une expérience selon vos envies.','illus'=>'search'],
                ['num'=>'2','title'=>'Découvrez','desc'=>'Consultez les profils détaillés, les photos et l\'histoire des artisans.','illus'=>'discover'],
                ['num'=>'3','title'=>'Réservez','desc'=>'Envoyez une demande de visite directement depuis la plateforme.','illus'=>'reserve'],
                ['num'=>'4','title'=>'Vivez','desc'=>'Rencontrez l\'artisan, participez à l\'atelier, vivez l\'expérience.','illus'=>'live'],
            ]; @endphp
            @foreach($steps as $i => $step)
            <div class="relative bg-white rounded-2xl p-8 pt-10 text-center shadow-lg border border-gray-100 hover:-translate-y-2 hover:shadow-xl transition-all duration-300 reveal" style="transition-delay:{{ $i * 100 }}ms">
                <span class="absolute -top-4 left-1/2 -translate-x-1/2 px-4 py-1.5 bg-[#064E3B] text-white text-xs font-bold rounded-full shadow-md tracking-wider">Étape {{ $step['num'] }}</span>

                {{-- Illustration vectorielle animée --}}
                <div class="h-32 mb-5 flex items-center justify-center illus-float" style="animation-delay:{{ $i * 0.6 }}s">
                    @if($step['illus'] === 'search')
                    <svg viewBox="0 0 160 110" class="h-full w-auto" fill="none" role="img" aria-label="Recherche">
                        <ellipse cx="80" cy="98" rx="46" ry="6" fill="#064E3B" opacity=".08"/>
                        <rect x="30" y="18" width="70" height="52" rx="6" fill="#F8F6F0" stroke="#064E3B" stroke-width="2"/>
                        <circle cx="52" cy="38" r="7" fill="#C99424" opacity=".85"/>
                        <path d="M42 58l14-14 10 9 16-16 12 21" stroke="#064E3B" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <circle cx="104" cy="62" r="17" fill="#fff" stroke="#C99424" stroke-width="4" class="illus-pulse"/>
                        <path d="M117 74l13 13" stroke="#C99424" stroke-width="6" stroke-linecap="round"/>
                    </svg>
                    @elseif($step['illus'] === 'discover')
                    <svg viewBox="0 0 160 110" class="h-full w-auto" fill="none" role="img" aria-label="Découverte des profils">
                        <ellipse cx="80" cy="98" rx="46" ry="6" fill="#064E3B" opacity=".08"/>
                        <rect x="44" y="12" width="72" height="86" rx="8" fill="#F8F6F0" stroke="#064E3B" stroke-width="2"/>
                        <circle cx="80" cy="38" r="13" fill="#C99424" opacity=".9" class="illus-pulse"/>
                        <path d="M60 60c4-9 11-13 20-13s16 4 20 13" stroke="#064E3B" stroke-width="2.5" stroke-linecap="round"/>
                        <rect x="58" y="68" width="44" height="4.5" rx="2.25" fill="#064E3B" opacity=".55"/>
                        <rect x="66" y="78" width="28" height="4.5" rx="2.25" fill="#064E3B" opacity=".3"/>
                    </svg>
                    @elseif($step['illus'] === 'reserve')
                    <svg viewBox="0 0 160 110" class="h-full w-auto" fill="none" role="img" aria-label="Réservation">
                        <ellipse cx="80" cy="98" rx="46" ry="6" fill="#064E3B" opacity=".08"/>
                        <rect x="40" y="18" width="76" height="64" rx="8" fill="#F8F6F0" stroke="#064E3B" stroke-width="2"/>
                        <path d="M40 34h76" stroke="#064E3B" stroke-width="2"/>
                        <rect x="54" y="10" width="5" height="14" rx="2.5" fill="#C99424"/>
                        <rect x="97" y="10" width="5" height="14" rx="2.5" fill="#C99424"/>
                        <g class="illus-pop"><circle cx="102" cy="66" r="15" fill="#064E3B"/><path d="M95 66l5 5 10-10" stroke="#fff" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></g>
                        <rect x="52" y="44" width="22" height="5" rx="2.5" fill="#C99424" opacity=".7"/>
                        <rect x="52" y="56" width="30" height="5" rx="2.5" fill="#064E3B" opacity=".25"/>
                    </svg>
                    @else
                    <svg viewBox="0 0 160 110" class="h-full w-auto" fill="none" role="img" aria-label="Vivre l'expérience">
                        <ellipse cx="80" cy="98" rx="46" ry="6" fill="#064E3B" opacity=".08"/>
                        <path d="M46 84c0-22 12-36 34-36s34 14 34 36z" fill="#F8F6F0" stroke="#064E3B" stroke-width="2"/>
                        <path d="M62 48c2-14 8-22 18-22s16 8 18 22" stroke="#C99424" stroke-width="2.5" stroke-linecap="round" class="illus-smoke"/>
                        <path d="M70 26c1.5-5 4.5-8 10-8" stroke="#C99424" stroke-width="2.5" stroke-linecap="round" opacity=".55" class="illus-smoke"/>
                        <circle cx="80" cy="66" r="9" fill="#C99424" opacity=".85" class="illus-pulse"/>
                        <path d="M46 84c10 6 24 9 34 9s24-3 34-9" stroke="#064E3B" stroke-width="2"/>
                    </svg>
                    @endif
                </div>

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
            @foreach($categories as $i => $cat)
            <a href="{{ route('artisans.index') }}?savoir_faire={{ $cat->id }}" class="sf-card group relative bg-[#F8F6F0] rounded-2xl p-8 border border-gray-100 hover:border-[#C99424]/40 overflow-hidden reveal" style="transition-delay:{{ ($i % 3) * 120 }}ms">
                {{-- Barre accent animée --}}
                <span class="absolute top-0 left-0 h-1 w-0 bg-gradient-to-r from-[#064E3B] to-[#C99424] group-hover:w-full transition-all duration-500"></span>
                {{-- Motif wax au survol --}}
                <span class="absolute inset-0 wax-pattern opacity-0 group-hover:opacity-[0.07] transition-opacity duration-700 pointer-events-none"></span>
                {{-- Badge : artisans référencés --}}
                <span class="absolute top-5 right-5 inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white border border-[#064E3B]/15 text-[11px] font-bold text-[#064E3B] shadow-sm">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-3.5 h-3.5"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    {{ $artisanCounts[$cat->id] ?? 0 }} artisan{{ ($artisanCounts[$cat->id] ?? 0) > 1 ? 's' : '' }}
                </span>
                <div class="relative w-16 h-16 mb-6">
                    <span class="absolute inset-0 rounded-2xl bg-gradient-to-br from-[#C99424]/25 to-transparent opacity-0 group-hover:opacity-100 group-hover:scale-125 transition-all duration-500"></span>
                    <span class="relative w-16 h-16 bg-white rounded-2xl flex items-center justify-center text-[#064E3B] group-hover:bg-[#064E3B] group-hover:text-white transition-all duration-300 shadow-sm group-hover:shadow-lg group-hover:-rotate-6 group-hover:scale-110">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                    </span>
                </div>
                <h3 class="serif text-2xl text-[#17201D] mb-3 group-hover:text-[#064E3B] transition-colors">{{ $cat->name }}</h3>
                <p class="text-[#17201D]/60 text-sm leading-relaxed mb-6">{{ $cat->description }}</p>
                <span class="text-[#C99424] font-bold text-xs uppercase tracking-wider inline-flex items-center gap-1 group-hover:gap-3 transition-all">Découvrir
                    <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </span>
            </a>
            @endforeach
        </div>
    </div>
</section>

<!-- IMAGE CULTURELLE IMMERSIVE -->
<section class="relative h-80 overflow-hidden">
    <img src="{{ url('images/poterie_en_action.png') }}" class="w-full h-full object-cover" alt="Artisanat en action" onerror="this.src='{{ url('images/hero/tourisme_porto_novo.png') }}'">
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

<!-- GALERIE TERRAIN -->
<section id="terrain" class="py-24 bg-[#17201D] relative overflow-hidden">
    <div class="absolute inset-0 wax-pattern opacity-25"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center mb-14 fade-up">
            <h2 class="serif text-4xl md:text-5xl text-white mb-3">ƉƆKUN sur le terrain</h2>
            <p class="text-white/60 text-lg max-w-2xl mx-auto">Des ateliers, des gestes, des matières vivantes — capturés au cœur de Porto-Novo.</p>
            <div class="h-1 w-20 bg-[#C99424] mx-auto mt-5"></div>
        </div>
        @php
        $galleryTiles = [
            ['img'=>'images/van2.jpeg',   'label'=>'Vannerie & fibres de raphia', 'span'=>'md:col-span-2 md:row-span-2'],
            ['img'=>'images/d1.jpeg',     'label'=>'Mains à l’ouvrage',          'span'=>''],
            ['img'=>'images/drum.jpg',    'label'=>'Rythmes & cérémonies',        'span'=>''],
            ['img'=>'images/d2.jpeg',     'label'=>'Terre et savoir-faire',       'span'=>''],
            ['img'=>'images/van1.jpeg',   'label'=>'Tressage au quotidien',       'span'=>''],
            ['img'=>'images/dokun_terrain.jpeg','label'=>'Rencontres du quartier', 'span'=>''],
            ['img'=>'images/d3.jpeg',     'label'=>'Couleurs d’atelier',         'span'=>''],
            ['img'=>'images/van3.jpeg',   'label'=>'Fibres tressées main',        'span'=>''],
            ['img'=>'images/d4.jpeg',     'label'=>'Transmission vivante',        'span'=>''],
        ];
        @endphp
        <div class="grid grid-cols-2 md:grid-cols-4 auto-rows-[180px] md:auto-rows-[200px] gap-4">
            @foreach($galleryTiles as $i => $tile)
            <figure class="group relative rounded-2xl overflow-hidden {{ $tile['span'] }} reveal" style="transition-delay:{{ ($i % 4) * 90 }}ms">
                <img src="{{ url($tile['img']) }}" alt="{{ $tile['label'] }}" loading="lazy"
                     class="absolute inset-0 w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/10 to-transparent opacity-80 group-hover:opacity-95 transition-opacity duration-500"></div>
                <figcaption class="absolute bottom-0 left-0 right-0 p-4 translate-y-2 group-hover:translate-y-0 transition-transform duration-500">
                    <span class="text-white text-sm font-bold drop-shadow">{{ $tile['label'] }}</span>
                </figcaption>
                <span class="absolute top-3 left-3 w-8 h-0.5 bg-[#C99424] scale-x-0 origin-left group-hover:scale-x-100 transition-transform duration-500"></span>
            </figure>
            @endforeach
        </div>
    </div>
</section>

<!-- POUR QUI ? -->
<section class="py-24 bg-[#064E3B] text-white relative overflow-hidden">
    <img src="{{ url('images/vannerie.jpg') }}" class="absolute inset-0 w-full h-full object-cover opacity-15" alt="" loading="lazy">
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
    <img src="{{ url('images/dokun_carte.jpg') }}" class="absolute inset-0 w-full h-full object-cover opacity-10" alt="" loading="lazy">
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
    <img src="{{ url('images/dokun_bg.jpeg') }}" class="absolute inset-0 w-full h-full object-cover" alt="" loading="lazy">
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

    const artisans = @json($mapArtisans ?? []);

    // Un marqueur = un artisan (les quartiers servent uniquement au cadrage)
    artisans.forEach(a => {
        if (!a.latitude || !a.longitude) return;
        L.marker([a.latitude, a.longitude], { icon: dropIcon }).addTo(homeMap)
            .bindPopup(`<strong style="font-family:'Manrope'">${a.professional_name || (a.first_name + ' ' + a.last_name)}</strong><br><a href="/artisans/${a.id}" style="color:#2563EB;font-size:12px;">Voir la fiche</a>`);
    });
</script>

</body>
</html>
