<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Carte Interactive - ƉƆKUN</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=outfit:300,400,600,700,900&display=swap" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <style>
        body { font-family: 'Outfit', sans-serif; }
        #map { height: 100vh; width: 100%; z-index: 10; }
        .leaflet-popup-content-wrapper { border-radius: 1rem; overflow: hidden; padding: 0; }
        .leaflet-popup-content { margin: 0; width: 250px !important; }
    </style>
</head>
<body class="antialiased bg-slate-900 text-slate-800 overflow-hidden">

    <!-- Navbar Overlay -->
    <nav class="fixed top-0 w-full z-50 bg-white/90 backdrop-blur-md border-b border-slate-200/50 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <a href="{{ route('home') }}" class="flex items-center gap-3 hover:opacity-80 transition">
                    <svg class="w-6 h-6 text-slate-900" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    <span class="font-bold text-lg text-slate-900">Retour à l'accueil</span>
                </a>
                <div class="font-black tracking-tighter text-amber-500 text-xl">ƉƆKUN CARTE</div>
            </div>
        </div>
    </nav>

    <!-- Sidebar & Map Container -->
    <div class="flex h-screen pt-16">
        
        <!-- Sidebar (Visible on desktop, hidden on mobile) -->
        <div class="hidden md:flex flex-col w-96 bg-white shadow-2xl z-20 h-full overflow-y-auto">
            <div class="p-6 border-b border-slate-100">
                <h2 class="text-2xl font-black text-slate-900 mb-2">Artisans de Porto-Novo</h2>
                <p class="text-slate-500 text-sm">Découvrez les ateliers et savoir-faire près de vous.</p>
            </div>
            <div class="p-4 space-y-4">
                @foreach($artisans as $artisan)
                <a href="{{ route('artisans.show', $artisan->id) }}" class="block p-4 rounded-2xl bg-slate-50 hover:bg-amber-50 border border-slate-100 hover:border-amber-200 transition-colors group">
                    <h3 class="font-bold text-slate-900 group-hover:text-amber-600 transition-colors">{{ $artisan->professional_name ?? ($artisan->first_name . ' ' . $artisan->last_name) }}</h3>
                    <p class="text-xs text-slate-500 mt-1 line-clamp-1">{{ $artisan->address }}</p>
                    <div class="mt-3 flex gap-2 flex-wrap">
                        @foreach($artisan->savoirFaires as $sf)
                            <span class="px-2 py-1 bg-white text-slate-600 text-[10px] font-bold rounded-lg border border-slate-200">{{ $sf->name }}</span>
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
        var map = L.map('map').setView([6.4969, 2.6289], 13);

        L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
            attribution: '&copy; OpenStreetMap &copy; CARTO',
            subdomains: 'abcd',
            maxZoom: 19
        }).addTo(map);

        // Custom icon
        var artisanIcon = L.divIcon({
            className: 'custom-icon',
            html: '<div style="background-color: #f59e0b; width: 36px; height: 36px; border-radius: 50%; border: 3px solid white; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); display: flex; align-items: center; justify-content: center; color: white; font-weight: bold;">Ɖ</div>',
            iconSize: [36, 36],
            iconAnchor: [18, 18],
            popupAnchor: [0, -18]
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
                        <div class="h-28 bg-slate-200"><img src="${imgSrc}" class="w-full h-full object-cover"></div>
                        <div class="p-4">
                            <h4 class="font-bold text-slate-900 mb-1 text-lg">${name}</h4>
                            <p class="text-xs text-slate-500 mb-3 line-clamp-1">${artisan.address}</p>
                            <a href="${url}" class="block w-full py-2 bg-amber-500 text-white text-center text-sm font-bold rounded-xl hover:bg-amber-600 transition-colors">Voir le profil</a>
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
