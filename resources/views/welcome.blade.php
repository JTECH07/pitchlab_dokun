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
    <style>
        body{font-family:'Manrope',sans-serif;}
        h1,h2,.serif{font-family:'DM Serif Display',serif;}
        .slide{transition:opacity 1s ease;}
        .slider-content{opacity:0;transform:translateY(20px);transition:all .8s ease .3s;}
        .slide.active .slider-content{opacity:1;transform:translateY(0);}
    </style>
</head>
<body class="antialiased bg-[#F8F6F0] text-[#17201D]">

<!-- NAVBAR -->
<nav id="navbar" class="fixed w-full z-50 transition-all duration-500 bg-transparent border-b border-white/10 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-20">
            <a href="{{ route('home') }}" class="flex items-center gap-3">
                <div class="w-12 h-12 bg- rounded flex items-center justify-center overflow-hidden">
                    <img src="{{ asset('images/dokun_logo.png') }}" alt="ƉƆKUN" class="w-full h-full object-contain" onerror="this.outerHTML='<span class=\'text-[#C99424] font-bold text-2xl\'>Ɖ</span>'">
                </div>
                <div class="flex flex-col leading-tight">
                    <span class="serif text-3xl">ƉƆKUN</span>
                    <span class="text-[9.5px] tracking-[0.15em] opacity-70 uppercase font-semibold">Patrimoine Vivant &<br>Tourisme Culturel</span>
                </div>
            </a>
            <div class="hidden md:flex items-center gap-7 font-semibold text-sm">
                <a href="#savoir-faire" class="hover:text-[#C99424] transition-colors">Savoir-faire</a>
                <a href="#artisans" class="hover:text-[#C99424] transition-colors">Artisans</a>
                <a href="{{ route('carte') }}" class="hover:text-[#C99424] transition-colors">Carte</a>
                <a href="{{ route('savoir-faire.index') }}" class="hover:text-[#C99424] transition-colors">Expériences</a>
                @auth
                    <a href="{{ url('/dashboard') }}" class="px-5 py-2.5 bg-[#064E3B] text-white rounded-full hover:bg-[#064E3B]/90 transition shadow-lg text-sm">Mon Espace</a>
                @else
                    <a href="{{ route('login') }}" class="px-5 py-2.5 bg-[#C99424] text-white rounded-full hover:bg-yellow-600 transition shadow-lg text-sm">Connexion</a>
                @endauth
            </div>
            <button id="menu-btn" class="md:hidden p-2 rounded-lg hover:bg-white/20 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
        </div>
    </div>
    <div id="mobile-menu" class="hidden md:hidden bg-white text-[#17201D] border-t border-gray-100 shadow-xl">
        <div class="p-5 space-y-2">
            <a href="#savoir-faire" class="flex items-center gap-3 py-3 px-4 rounded-xl hover:bg-[#F8F6F0] font-semibold">Savoir-faire</a>
            <a href="#artisans" class="flex items-center gap-3 py-3 px-4 rounded-xl hover:bg-[#F8F6F0] font-semibold">Artisans</a>
            <a href="{{ route('carte') }}" class="flex items-center gap-3 py-3 px-4 rounded-xl hover:bg-[#F8F6F0] font-semibold">Carte interactive</a>
            <a href="{{ route('experiences.index') }}" class="flex items-center gap-3 py-3 px-4 rounded-xl hover:bg-[#F8F6F0] font-semibold">Expériences</a>
            <div class="pt-3 border-t border-gray-100">
                @auth
                    <a href="{{ url('/dashboard') }}" class="block w-full text-center py-4 bg-[#064E3B] text-white font-bold rounded-xl">Mon Espace</a>
                @else
                    <a href="{{ route('login') }}" class="block w-full text-center py-4 bg-[#C99424] text-white font-bold rounded-xl">Se connecter</a>
                @endauth
            </div>
        </div>
    </div>
</nav>

<!-- HERO SLIDER -->
<section id="hero" class="relative h-screen overflow-hidden bg-[#17201D]">
    <div id="slides-container" class="absolute inset-0">
        @php
        $slides = [
            ['img'=>'images/hero/tourisme_porto_novo.png','tag'=>'Voyage Culturel','title'=>"L'Âme de Porto-Novo",'sub'=>'Le patrimoine vivant, une richesse partagée. Explorez la ville aux trois noms.','cta_label'=>'Découvrir sur la carte','cta_url'=>route('carte'),'cta_style'=>'gold'],
            ['img'=>'images/artisans/koffi_dossou.png','tag'=>'Transmission','title'=>'Rencontrez nos Maîtres Artisans','sub'=>'Derrière chaque objet se cache une histoire, un visage, des mains expertes.','cta_label'=>'Voir le répertoire','cta_url'=>route('artisans.index'),'cta_style'=>'green'],
            ['img'=>'images/artisans/messan_akakpo.png','tag'=>'Valorisation','title'=>'Des Savoir-Faire Inestimables','sub'=>'Du tissage Kanvo à la poterie, des techniques transmises de génération en génération.','cta_label'=>'Découvrir les métiers','cta_url'=>'#savoir-faire','cta_style'=>'outline'],
            ['img'=>'images/artisans/yvette_gbaguidi.png','tag'=>'Opportunités Locales','title'=>'Saisissez les Opportunités','sub'=>'ƉƆKUN crée de nouvelles opportunités économiques pour les communautés locales.','cta_label'=>'Explorer la carte','cta_url'=>route('carte'),'cta_style'=>'gold'],
            ['img'=>'images/poterie_en_action.png','tag'=>'Expériences','title'=>'Vivez une Expérience Unique','sub'=>'Réservez une visite d\'atelier et apprenez directement auprès d\'un maître artisan.','cta_label'=>'Voir les expériences','cta_url'=>route('savoir-faire.index'),'cta_style'=>'green'],
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
    // Mobile menu
    document.getElementById('menu-btn').addEventListener('click',()=>document.getElementById('mobile-menu').classList.toggle('hidden'));
    // Navbar scroll
    const nav=document.getElementById('navbar');
    window.addEventListener('scroll',()=>{
        if(window.scrollY>60){nav.classList.replace('bg-transparent','bg-white');nav.classList.replace('text-white','text-[#17201D]');nav.classList.replace('border-white/10','border-gray-200');nav.classList.add('shadow-sm');}
        else{nav.classList.replace('bg-white','bg-transparent');nav.classList.replace('text-[#17201D]','text-white');nav.classList.replace('border-gray-200','border-white/10');nav.classList.remove('shadow-sm');}
    });
    // Slider
    const slides=document.querySelectorAll('.slide'),dots=document.querySelectorAll('#dots button');
    let cur=0,timer;
    function goTo(i){
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
<section id="comment-ca-marche" class="py-24 bg-[#F8F6F0]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
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
                <div class="w-14 h-14 rounded-full bg-[#064E3B]/10 text-[#064E3B] flex items-center justify-center mx-auto mb-4"><svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 6v6l4 2m4-2a8 8 0 11-16 0 8 8 0 0116 0z"/></svg></div>
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
        <div class="flex flex-col md:flex-row justify-between items-end mb-14 gap-6">
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
        <div class="flex flex-col md:flex-row justify-between items-end mb-14 gap-6">
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
<section class="py-24 bg-[#064E3B] text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="serif text-4xl md:text-5xl mb-4">Pour qui est ƉƆKUN ?</h2>
            <div class="h-1 w-20 bg-[#C99424] mx-auto mt-5"></div>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-6">
            @php $publics = [
                ['label'=>'Touristes','desc'=>'Visiteurs qui cherchent des expériences authentiques'],
                ['label'=>'Artisans','desc'=>'Détenteurs qui souhaitent valoriser leurs savoir-faire'],
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

<!-- CTA FINAL -->
<section class="py-24 bg-white text-center relative overflow-hidden">
    <div class="absolute inset-0 opacity-5">
        <img src="{{ asset('images/reel_marche_arts.png') }}" class="w-full h-full object-cover" alt="background" onerror="">
    </div>
    <div class="relative z-10 max-w-3xl mx-auto px-4">
        <h2 class="serif text-4xl md:text-5xl text-[#064E3B] mb-6">Prêt à découvrir Porto-Novo autrement ?</h2>
        <p class="text-[#17201D]/70 text-lg mb-10">Explorez la carte interactive, trouvez un artisan près de vous et vivez une expérience culturelle unique.</p>
        <div class="flex flex-col sm:flex-row justify-center gap-5">
            <a href="{{ route('carte') }}" class="px-8 py-4 bg-[#064E3B] text-white font-bold rounded-full hover:bg-[#064E3B]/90 shadow-xl transition-all text-lg">Explorer la carte</a>
            <a href="{{ route('artisans.index') }}" class="px-8 py-4 border-2 border-[#064E3B] text-[#064E3B] font-bold rounded-full hover:bg-[#064E3B] hover:text-white transition-all text-lg">Voir les artisans</a>
        </div>
    </div>
</section>

<!-- FOOTER -->
@include('partials.footer')

</body>
</html>
