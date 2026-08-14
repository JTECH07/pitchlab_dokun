<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Carte Interactive - ƉƆKUN</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=dm-serif-display:400|manrope:400,600,700,800&display=swap" rel="stylesheet" />
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
                    }
                }
            }
        }
    </script>

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <style>
        body { font-family: 'Manrope', sans-serif; }
        h1, h2, h3, h4, .font-serif { font-family: 'DM Serif Display', serif; }
        #map { height: 100vh; width: 100%; z-index: 10; }
        .leaflet-popup-content-wrapper { border-radius: 1rem; overflow: hidden; padding: 0; }
        .leaflet-popup-content { margin: 0; width: 260px !important; }
        .leaflet-container { font-family: 'Manrope', sans-serif; }
    </style>
</head>
<body class="antialiased bg-dokun-ivory text-dokun-charcoal overflow-hidden">

    <!-- Navbar Overlay -->
    <nav class="fixed top-0 w-full z-50 bg-white/95 backdrop-blur-md border-b border-gray-200 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <a href="{{ route('home') }}" class="flex items-center gap-3 hover:opacity-80 transition">
                    <svg class="w-6 h-6 text-dokun-charcoal" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    <span class="font-bold text-sm text-dokun-charcoal">Retour à l'accueil</span>
                </a>
                <div class="flex items-center gap-2">
                    <img src="{{ asset('images/dokun_logo.png') }}" alt="Logo ƉƆKUN" class="w-9 h-9 object-contain bg-dokun-green rounded">
                    <span class="font-serif tracking-wide text-dokun-green text-xl">ƉƆKUN</span>
                </div>
            </div>
        </div>
    </nav>

    <!-- Sidebar & Map Container -->
    <div class="flex h-screen pt-20">

        <!-- Sidebar (Visible on desktop, hidden on mobile) -->
        <div class="hidden md:flex flex-col w-96 bg-white shadow-2xl z-20 h-full overflow-y-auto">
            <div class="p-8 border-b border-gray-100">
                <h2 class="text-3xl font-serif text-dokun-green mb-2">Artisans</h2>
                <p class="text-dokun-charcoal/60 text-sm">Découvrez les ateliers et savoir-faire de Porto-Novo près de vous.</p>
            </div>
            <div class="p-6 space-y-4">
                @foreach($artisans as $artisan)
                <a href="{{ route('artisans.show', $artisan->id) }}" class="block p-5 rounded-2xl bg-dokun-ivory hover:bg-white border border-transparent hover:border-dokun-gold/30 hover:shadow-lg transition-all group">
                    <h3 class="font-serif text-xl text-dokun-charcoal group-hover:text-dokun-green transition-colors">{{ $artisan->professional_name ?? ($artisan->first_name . ' ' . $artisan->last_name) }}</h3>
                    <p class="text-xs text-dokun-charcoal/50 mt-1 mb-3 line-clamp-1 flex items-center gap-1">
                        <svg class="w-3 h-3 text-dokun-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        {{ $artisan->address }}
                    </p>
                    <div class="flex gap-2 flex-wrap">
                        @foreach($artisan->savoirFaires as $sf)
                            <span class="px-2 py-1 bg-white text-dokun-gold border border-dokun-gold/20 text-[10px] font-bold rounded uppercase tracking-wider">{{ $sf->name }}</span>
                        @endforeach
                    </div>
                </a>
                @endforeach
            </div>
        </div>

        <!-- Map -->
        <div class="flex-1 relative">
            <div id="map"></div>
        </div>
    </div>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        // Porto-Novo coordinates
        var map = L.map('map').setView([6.4969, 2.6289], 14);

        // Elegant map style
        L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
            attribution: '&copy; OpenStreetMap &copy; CARTO',
            subdomains: 'abcd',
            maxZoom: 19
        }).addTo(map);

        // Custom icon matching brand
        var artisanIcon = L.divIcon({
            className: 'custom-icon',
            html: '<div style="background-color: #064E3B; width: 40px; height: 40px; border-radius: 50%; border: 3px solid white; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2); display: flex; align-items: center; justify-content: center; color: #C99424; font-weight: bold; font-family: \'DM Serif Display\', serif; font-size: 18px;">Ɖ</div>',
            iconSize: [40, 40],
            iconAnchor: [20, 20],
            popupAnchor: [0, -20]
        });

        // Artisan Data from backend
        const artisans = @json($artisans);

        artisans.forEach(function(artisan) {
            if(artisan.latitude && artisan.longitude) {
                let name = artisan.professional_name || (artisan.first_name + ' ' + artisan.last_name);
                let url = '/artisans/' + artisan.id;
                let imgSrc = artisan.image_url || '/images/hero/hero_dokun.png';

                let popupContent = `
                    <div class="bg-white">
                        <div class="h-32 bg-gray-200 relative">
                            <img src="${imgSrc}" class="w-full h-full object-cover">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                            <div class="absolute bottom-3 left-3 text-white font-serif text-xl">${name}</div>
                        </div>
                        <div class="p-5">
                            <p class="text-xs text-gray-500 mb-4 line-clamp-1">${artisan.address}</p>
                            <a href="${url}" class="block w-full py-2 bg-[#064E3B] text-white text-center text-sm font-bold rounded-lg hover:bg-[#064E3B]/90 transition-colors">Découvrir l'artisan</a>
                        </div>
                    </div>
                `;

                L.marker([artisan.latitude, artisan.longitude], {icon: artisanIcon})
                 .addTo(map)
                 .bindPopup(popupContent);
            }
        });
    </script>
</body>
</html>
