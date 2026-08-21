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
    <!-- MarkerCluster CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.Default.css" />
    <!-- Leaflet Routing Machine CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.css" />

    <style>
        *, *::before, *::after { box-sizing: border-box; }
        body { font-family: 'Manrope', sans-serif; margin: 0; overflow: hidden; }
        h1, h2, h3, h4, .font-serif { font-family: 'DM Serif Display', serif; }

        #map { height: 100vh; width: 100%; z-index: 1; }

        .leaflet-popup-content-wrapper { border-radius: 1rem; overflow: hidden; padding: 0; box-shadow: 0 20px 50px rgba(0,0,0,0.18); }
        .leaflet-popup-content { margin: 0; width: 280px !important; }
        .leaflet-popup-tip { display: none; }
        .leaflet-container { font-family: 'Manrope', sans-serif; }
        .leaflet-control-zoom { border: none !important; box-shadow: 0 4px 14px rgba(0,0,0,0.25) !important; border-radius: 10px !important; overflow: hidden; }
        .leaflet-control-zoom a { background: #1a1a1a !important; color: #ffffff !important; width: 34px !important; height: 34px !important; line-height: 34px !important; font-size: 17px !important; font-weight: 700 !important; }
        .leaflet-control-zoom a:hover { background: #333333 !important; }
        .leaflet-control-attribution { font-size: 9px !important; opacity: 0.6; }

        /* Blue drop marker with Ɖ */
        .drop-marker { position: relative; transition: transform 0.15s ease; }
        .drop-marker:hover { transform: scale(1.12); }
        .drop-marker svg { display: block; }
        .drop-marker.active { transform: scale(1.25); filter: drop-shadow(0 4px 8px rgba(37,99,235,0.5)); }

        /* Clusters — royal blue */
        .marker-cluster-small, .marker-cluster-medium, .marker-cluster-large { background: rgba(37, 99, 235, 0.2) !important; }
        .marker-cluster-small div, .marker-cluster-medium div, .marker-cluster-large div {
            background: #2563EB !important; color: white !important;
            font-family: 'Manrope', sans-serif; font-weight: 700; font-size: 12px;
            border: 2px solid white; box-shadow: 0 3px 10px rgba(37,99,235,0.4);
        }

        /* Tooltip */
        .artisan-tooltip {
            background: #17201D; color: white;
            padding: 6px 12px; border-radius: 8px;
            font-family: 'Manrope', sans-serif; font-size: 12px; font-weight: 700;
            white-space: nowrap; pointer-events: none;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        }
        .artisan-tooltip::after {
            content: ''; position: absolute;
            bottom: -5px; left: 50%; transform: translateX(-50%);
            border-left: 5px solid transparent; border-right: 5px solid transparent;
            border-top: 5px solid #17201D;
        }

        /* Sidebar */
        #sidebar { transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
        .artisan-card {
            border-left: 3px solid transparent;
            transition: all 0.2s ease;
        }
        .artisan-card:hover { background: white; border-left-color: #064E3B; }
        .artisan-card.active { background: white; border-left-color: #C99424; box-shadow: 0 4px 14px rgba(0,0,0,0.06); }

        /* Filter chips */
        .filter-chip {
            transition: all 0.2s ease;
            cursor: pointer;
            user-select: none;
        }
        .filter-chip:hover { background: #064E3B; color: white; border-color: #064E3B; }
        .filter-chip.active { background: #064E3B; color: white; border-color: #064E3B; }

        /* Scrollbar */
        .sidebar-scroll::-webkit-scrollbar { width: 4px; }
        .sidebar-scroll::-webkit-scrollbar-track { background: transparent; }
        .sidebar-scroll::-webkit-scrollbar-thumb { background: #d1d5db; border-radius: 4px; }
        .sidebar-scroll::-webkit-scrollbar-thumb:hover { background: #9ca3af; }

        /* District labels */
        .district-label {
            font-family: 'Manrope', sans-serif;
            font-weight: 800;
            font-size: 10px;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            white-space: nowrap;
            pointer-events: none;
            color: #17201D;
            text-shadow: 0 0 6px rgba(255,255,255,0.9), 0 0 12px rgba(255,255,255,0.6);
        }

        /* Mobile bottom sheet */
        @media (max-width: 767px) {
            #sidebar {
                position: fixed !important;
                bottom: 0; left: 0; right: 0;
                width: 100% !important;
                max-width: 100% !important;
                height: auto !important;
                max-height: 60vh;
                border-radius: 1.25rem 1.25rem 0 0;
                box-shadow: 0 -10px 40px rgba(0,0,0,0.15);
                z-index: 1000;
                transform: translateY(calc(100% - 64px));
                transition: transform 0.35s cubic-bezier(0.4, 0, 0.2, 1);
            }
            #sidebar.open { transform: translateY(0); }
            .mobile-handle {
                display: flex !important;
            }
        }

        /* Popup buttons */
        .popup-btn {
            display: flex; align-items: center; justify-content: center; gap: 6px;
            padding: 10px 16px; border-radius: 10px;
            font-family: 'Manrope', sans-serif; font-size: 12px; font-weight: 700;
            text-decoration: none; transition: all 0.2s ease;
            border: none; cursor: pointer; width: 100%;
        }
        .popup-btn-green { background: #064E3B; color: white; }
        .popup-btn-green:hover { background: #043d2e; }
        .popup-btn-gold { background: #C99424; color: white; }
        .popup-btn-gold:hover { background: #b3831f; }
        .popup-btn-outline { background: white; color: #064E3B; border: 1.5px solid #e5e7eb; }
        .popup-btn-outline:hover { border-color: #064E3B; background: #F8F6F0; }

        /* Leaflet Routing Machine overrides */
        .leaflet-routing-container {
            background: white !important;
            border-radius: 12px !important;
            box-shadow: 0 4px 20px rgba(0,0,0,0.12) !important;
            border: 1px solid #e5e7eb !important;
            font-family: 'Manrope', sans-serif !important;
            padding: 12px !important;
            max-width: 280px !important;
        }
        .leaflet-routing-container h2 { font-family: 'Manrope', sans-serif !important; font-size: 13px !important; font-weight: 700 !important; color: #17201D !important; margin: 0 0 8px !important; }
        .leaflet-routing-container table { font-size: 11px !important; }
        .leaflet-routing-container .leaflet-routing-alt { border: none !important; padding: 8px 0 !important; }
        .leaflet-routing-container .leaflet-routing-alt h2 { font-size: 12px !important; color: #064E3B !important; font-weight: 700 !important; }
        .leaflet-routing-container .leaflet-routing-icon { display: none !important; }
        .leaflet-routing-container .leaflet-routing-geocodes input {
            border: 1px solid #e5e7eb !important; border-radius: 8px !important;
            padding: 8px 10px !important; font-size: 12px !important; font-family: 'Manrope', sans-serif !important;
            width: 100% !important; margin-bottom: 6px !important;
        }
        .leaflet-routing-container .leaflet-routing-geocodes input:focus {
            outline: none !important; border-color: #064E3B !important;
            box-shadow: 0 0 0 2px rgba(6,78,59,0.1) !important;
        }
        .leaflet-routing-alt summary { cursor: pointer; font-family: 'Manrope', sans-serif !important; font-size: 12px !important; color: #064E3B !important; font-weight: 700 !important; }
        .leaflet-routing-remove-btn {
            background: #fee2e2 !important; color: #dc2626 !important; border: none !important;
            border-radius: 6px !important; padding: 4px 10px !important; font-size: 11px !important;
            font-weight: 700 !important; cursor: pointer !important; margin-top: 6px !important;
            font-family: 'Manrope', sans-serif !important;
        }
        .leaflet-routing-remove-btn:hover { background: #fecaca !important; }
        .leaflet-routing-instructions { font-size: 11px !important; color: #6b7280 !important; }
        .leaflet-routing-instructions li { margin-bottom: 2px !important; padding: 2px 0 !important; }

        /* Proximity slider */
        .proximity-slider { -webkit-appearance: none; appearance: none; width: 100%; height: 6px; border-radius: 3px; background: #e5e7eb; outline: none; }
        .proximity-slider::-webkit-slider-thumb {
            -webkit-appearance: none; appearance: none; width: 20px; height: 20px;
            border-radius: 50%; background: #064E3B; cursor: pointer; border: 3px solid white;
            box-shadow: 0 2px 6px rgba(0,0,0,0.2);
        }
        .proximity-slider::-moz-range-thumb {
            width: 20px; height: 20px; border-radius: 50%; background: #064E3B;
            cursor: pointer; border: 3px solid white; box-shadow: 0 2px 6px rgba(0,0,0,0.2);
        }

        /* Animated route line */
        @keyframes route-dash {
            to { stroke-dashoffset: -20; }
        }
    </style>
</head>
<body class="antialiased bg-dokun-ivory text-dokun-charcoal overflow-hidden">

    <!-- Navbar Overlay -->
    <nav class="fixed top-0 w-full z-[900] bg-white/95 backdrop-blur-md border-b border-gray-200 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <a href="{{ route('home') }}" class="flex items-center gap-3 hover:opacity-80 transition">
                    <svg class="w-5 h-5 text-dokun-charcoal" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    <span class="font-bold text-sm text-dokun-charcoal">{{ __('app.nav_back') }}</span>
                </a>
                <div class="flex items-center gap-3">
                    <a href="{{ route('artisans.index') }}" class="hidden sm:inline-flex text-sm font-semibold text-dokun-charcoal hover:text-dokun-gold transition">{{ __('app.nav_artisans') }}</a>
                    <span class="hidden sm:inline text-gray-300">|</span>
                    <a href="{{ route('experiences.index') }}" class="hidden sm:inline-flex text-sm font-semibold text-dokun-charcoal hover:text-dokun-gold transition">{{ __('app.nav_experiences') }}</a>
                    <div class="w-px h-5 bg-gray-200 hidden sm:block"></div>
                    <div class="flex items-center gap-2">
                        <img src="{{ asset('images/dokun_logo.png') }}" alt="Logo ƉƆKUN" class="w-9 h-9 object-contain bg-dokun-green rounded">
                        <span class="font-serif tracking-wide text-dokun-green text-xl">ƉƆKUN</span>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main layout -->
    <div class="flex h-screen pt-20">

        <!-- Sidebar -->
        <aside id="sidebar" class="relative md:relative z-[800] w-full md:w-[380px] md:min-w-[380px] bg-dokun-ivory md:bg-white shadow-2xl flex flex-col overflow-hidden" style="height: calc(100vh - 5rem);">

            <!-- Mobile handle -->
            <div class="mobile-handle hidden flex-col items-center pt-3 pb-2 cursor-pointer" onclick="toggleSidebar()">
                <div class="w-10 h-1 bg-gray-300 rounded-full mb-2"></div>
                <div class="flex items-center gap-2 text-sm font-bold text-dokun-charcoal">
                    <svg class="w-4 h-4 text-dokun-green" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <span id="sidebar-count">{{ count($artisans) }} {{ __('app.map_artisans') }}</span>
                </div>
            </div>

            <!-- Header (desktop) -->
            <div class="hidden md:block p-6 pb-4 border-b border-gray-100">
                <h2 class="text-3xl font-serif text-dokun-green mb-1">{{ __('app.map_title') }}</h2>
                <p class="text-dokun-charcoal/50 text-sm">{{ __('app.map_subtitle') }}</p>
                <!-- Proximity radius -->
                <div class="mt-4 flex items-center gap-3">
                    <svg class="w-4 h-4 text-dokun-green flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <input type="range" id="radius-slider" min="1" max="20" value="20" class="proximity-slider flex-1">
                    <span id="radius-label" class="text-xs font-bold text-dokun-green min-w-[36px] text-right">20 km</span>
                </div>
                <p class="text-[10px] text-dokun-charcoal/40 mt-1.5">Rayon de recherche autour de votre position</p>
            </div>

            <!-- Search -->
            <div class="p-4 md:px-6 md:pt-5 md:pb-3">
                <div class="relative">
                    <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input type="text" id="search-input" placeholder="{{ __('app.map_search') }}"
                        class="w-full pl-10 pr-4 py-3 bg-white border border-gray-200 rounded-xl text-sm font-medium placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-dokun-green/20 focus:border-dokun-green transition">
                </div>
            </div>

            <!-- Filter chips -->
            <div class="px-4 md:px-6 pb-4 flex gap-2 overflow-x-auto scrollbar-hide" id="filter-chips">
                <button data-filter="all" class="filter-chip active whitespace-nowrap px-4 py-2 rounded-full border border-gray-200 text-xs font-bold bg-dokun-green text-white">{{ __('app.map_all') }}</button>
                @foreach($categories as $cat)
                    @if($cat->savoir_faires_count > 0)
                        <button data-filter="{{ $cat->slug }}" class="filter-chip whitespace-nowrap px-4 py-2 rounded-full border border-gray-200 text-xs font-bold text-dokun-charcoal/70 bg-white">{{ $cat->name }}</button>
                    @endif
                @endforeach
            </div>

            <!-- Artisan list -->
            <div id="artisan-list" class="sidebar-scroll flex-1 overflow-y-auto px-4 md:px-6 pb-6 space-y-2">
                @forelse($artisans as $artisan)
                    <div class="artisan-card rounded-xl p-4 bg-dokun-ivory cursor-pointer"
                         data-id="{{ $artisan->id }}"
                         data-lat="{{ $artisan->latitude }}"
                         data-lng="{{ $artisan->longitude }}"
                         data-categories="{{ $artisan->savoirFaires->pluck('category.slug')->implode(',') }}"
                         data-search="{{ strtolower($artisan->professional_name ?? $artisan->first_name.' '.$artisan->last_name).' '.strtolower($artisan->address).' '.$artisan->savoirFaires->pluck('name')->implode(' ') }}"
                         onclick="selectArtisan({{ $artisan->id }}, {{ $artisan->latitude }}, {{ $artisan->longitude }})">
                        <div class="flex items-start gap-3">
                            <div class="w-11 h-11 rounded-xl bg-dokun-green flex items-center justify-center flex-shrink-0 overflow-hidden border border-white shadow-sm">
                                @if($artisan->photo_path)
                                    <img src="{{ asset('storage/'.$artisan->photo_path) }}" alt="" class="w-full h-full object-cover">
                                @else
                                    <span class="text-dokun-gold font-serif text-base font-bold">Ɖ</span>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <h4 class="font-serif text-base text-dokun-charcoal truncate">{{ $artisan->professional_name ?? $artisan->first_name.' '.$artisan->last_name }}</h4>
                                <p class="text-xs text-dokun-charcoal/50 mt-0.5 flex items-center gap-1 truncate">
                                    <svg class="w-3 h-3 flex-shrink-0 text-dokun-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    {{ $artisan->address }}
                                </p>
                                <div class="flex gap-1.5 mt-2 flex-wrap">
                                    @foreach($artisan->savoirFaires as $sf)
                                        <span class="px-2 py-0.5 bg-white text-dokun-gold border border-dokun-gold/15 text-[10px] font-bold rounded-md uppercase tracking-wider">{{ $sf->name }}</span>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div id="empty-state" class="flex flex-col items-center justify-center py-16 text-center">
                        <div class="w-16 h-16 bg-dokun-ivory rounded-full flex items-center justify-center mb-4">
                            <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </div>
                        <p class="text-sm font-bold text-dokun-charcoal/60">{{ __('app.artisan_empty') }}</p>
                        <p class="text-xs text-dokun-charcoal/40 mt-1">Essayez un autre filtre ou recherche</p>
                    </div>
                @endforelse
                <div id="no-results" class="hidden flex-col items-center justify-center py-16 text-center">
                    <div class="w-16 h-16 bg-dokun-ivory rounded-full flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                    <p class="text-sm font-bold text-dokun-charcoal/60">Aucun résultat</p>
                    <p class="text-xs text-dokun-charcoal/40 mt-1">Modifiez vos critères de recherche</p>
                </div>
            </div>
        </aside>

        <!-- Map container -->
        <div class="flex-1 relative">
            <div id="map"></div>

            <!-- Geolocation FAB -->
            <button id="locate-btn" onclick="locateUser()" title="{{ __('app.map_locate') }}"
                class="absolute bottom-8 right-4 md:bottom-8 md:right-6 z-[800] w-12 h-12 bg-white rounded-full shadow-lg border border-gray-200 flex items-center justify-center hover:bg-dokun-ivory transition group">
                <svg class="w-5 h-5 text-dokun-green group-hover:text-dokun-gold transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </button>

            <!-- Mobile FAB toggle sidebar -->
            <button id="sidebar-toggle" onclick="toggleSidebar()" class="md:hidden absolute bottom-8 left-4 z-[800] w-12 h-12 bg-dokun-green rounded-full shadow-lg flex items-center justify-center hover:bg-dokun-green/90 transition">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
        </div>
    </div>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <!-- MarkerCluster JS -->
    <script src="https://unpkg.com/leaflet.markercluster@1.5.3/dist/leaflet.markercluster.js"></script>
    <!-- Leaflet Routing Machine JS -->
    <script src="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.js"></script>
    <script src="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine-control.js"></script>

    <script>
        // ============================================================
        // DATA
        // ============================================================
        const artisans = @json($artisans);
        let userLat = null, userLng = null;
        const PORTO_NOVO = { lat: 6.4969, lng: 2.6289 };

        // ============================================================
        // MAP INITIALIZATION
        // ============================================================
        const map = L.map('map', { zoomControl: false }).setView([PORTO_NOVO.lat, PORTO_NOVO.lng], 14);
        L.control.zoom({ position: 'topleft' }).addTo(map);

        // OpenStreetMap standard tiles (gris clair ville, vert végétation, bleu lagune, routes orange/jaune)
        const osm = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap',
            maxZoom: 19
        });
        osm.addTo(map);

        // ============================================================
        // BLUE DROP MARKER WITH Ɖ
        // ============================================================
        function createIcon(isActive) {
            return L.divIcon({
                className: 'drop-marker' + (isActive ? ' active' : ''),
                html: `<svg width="30" height="38" viewBox="0 0 28 36" xmlns="http://www.w3.org/2000/svg">
                    <path d="M14 0C6.3 0 0 6.3 0 14c0 10.5 14 22 14 22s14-11.5 14-22C28 6.3 21.7 0 14 0z" fill="#2563EB" stroke="#ffffff" stroke-width="2"/>
                    <text x="14" y="20" text-anchor="middle" font-family="'DM Serif Display',serif" font-size="15" font-weight="bold" fill="#ffffff">Ɖ</text>
                </svg>`,
                iconSize: [30, 38],
                iconAnchor: [15, 36],
                popupAnchor: [0, -32]
            });
        }

        // ============================================================
        // MARKER CLUSTER GROUP
        // ============================================================
        const markers = L.markerClusterGroup({
            maxClusterRadius: 40,
            spiderfyOnMaxZoom: true,
            showCoverageOnHover: false,
            zoomToBoundsOnClick: true,
            iconCreateFunction: function(cluster) {
                const count = cluster.getChildCount();
                let size = count < 10 ? 'small' : count < 30 ? 'medium' : 'large';
                let dim = size === 'small' ? 36 : size === 'medium' ? 44 : 52;
                return L.divIcon({
                    html: '<div>' + count + '</div>',
                    className: 'marker-cluster marker-cluster-' + size,
                    iconSize: L.point(dim, dim)
                });
            }
        });

        // ============================================================
        // POPUP TEMPLATE
        // ============================================================
        function buildPopup(a) {
            const name = a.professional_name || (a.first_name + ' ' + a.last_name);
            const img = a.image_url || '/images/hero/hero_dokun.png';
            const sfBadges = (a.savoir_faires || a.savoirFaires || [])
                .map(s => `<span class="inline-block px-2 py-0.5 bg-dokun-ivory text-dokun-gold border border-dokun-gold/15 text-[10px] font-bold rounded uppercase tracking-wider">${s.name}</span>`)
                .join('');

            const artisanUrl = '/artisans/' + a.id;
            const photoSrc = a.photo_path ? '/storage/' + a.photo_path : img;

            return `
                <div style="font-family: 'Manrope', sans-serif;">
                    <div style="height: 110px; background: linear-gradient(135deg, #064E3B, #C99424); position: relative; overflow: hidden;">
                        <img src="${photoSrc}" style="width: 100%; height: 100%; object-fit: cover; mix-blend-mode: luminosity; opacity: 0.4;">
                        <div style="position: absolute; inset: 0; background: linear-gradient(to top, rgba(0,0,0,0.6) 0%, transparent 60%);"></div>
                        <div style="position: absolute; bottom: 10px; left: 14px; right: 14px;">
                            <div style="font-family: 'DM Serif Display', serif; color: white; font-size: 17px; line-height: 1.2;">${name}</div>
                        </div>
                    </div>
                    <div style="padding: 14px 16px;">
                        <div style="display: flex; gap: 4px; flex-wrap: wrap; margin-bottom: 10px;">${sfBadges}</div>
                        <p style="font-size: 12px; color: #6b7280; margin: 0 0 14px 0; display: flex; align-items: center; gap: 4px;">
                            <svg style="width:12px;height:12px;color:#C99424;flex-shrink:0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            ${a.address}
                        </p>
                        <div style="display: flex; flex-direction: column; gap: 6px;">
                            <button onclick="startRouting(${a.latitude}, ${a.longitude}, '${name.replace(/'/g, "\\'")}')" class="popup-btn popup-btn-green">
                                <svg style="width:14px;height:14px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                                Itinéraire
                            </button>
                            <a href="${artisanUrl}" class="popup-btn popup-btn-gold">
                                <svg style="width:14px;height:14px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                Voir la fiche
                            </a>
                        </div>
                    </div>
                </div>
            `;
        }

        // ============================================================
        // ADD MARKERS
        // ============================================================
        const markerMap = {};
        artisans.forEach(a => {
            if (!a.latitude || !a.longitude) return;
            const marker = L.marker([a.latitude, a.longitude], { icon: createIcon(false) });
            marker.bindPopup(buildPopup(a), { maxWidth: 280, minWidth: 280 });
            marker._artisanId = a.id;
            markers.addLayer(marker);
            markerMap[a.id] = marker;
        });
        map.addLayer(markers);

        // ============================================================
        // HAVERSINE DISTANCE
        // ============================================================
        function haversine(lat1, lng1, lat2, lng2) {
            const R = 6371;
            const dLat = (lat2 - lat1) * Math.PI / 180;
            const dLng = (lng2 - lng1) * Math.PI / 180;
            const a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                      Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
                      Math.sin(dLng / 2) * Math.sin(dLng / 2);
            return R * 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
        }

        // ============================================================
        // PROXIMITY RADIUS
        // ============================================================
        let radiusCircle = null;
        let originLat = PORTO_NOVO.lat;
        let originLng = PORTO_NOVO.lng;

        const radiusSlider = document.getElementById('radius-slider');
        const radiusLabel = document.getElementById('radius-label');

        function drawRadiusCircle() {
            const km = parseInt(radiusSlider.value);
            radiusLabel.textContent = km + ' km';
            if (radiusCircle) map.removeLayer(radiusCircle);
            radiusCircle = L.circle([originLat, originLng], {
                radius: km * 1000,
                color: '#064E3B',
                weight: 1.5,
                fillColor: '#064E3B',
                fillOpacity: 0.08,
                dashArray: '6 4'
            }).addTo(map);
        }

        radiusSlider.addEventListener('input', () => {
            drawRadiusCircle();
            applyFilters();
        });

        // ============================================================
        // SIDEBAR: SELECT ARTISAN
        // ============================================================
        let activeId = null;
        function selectArtisan(id, lat, lng) {
            if (activeId !== null) {
                const prev = document.querySelector(`.artisan-card[data-id="${activeId}"]`);
                if (prev) prev.classList.remove('active');
                if (markerMap[activeId]) markerMap[activeId].setIcon(createIcon(false));
            }

            activeId = id;
            const card = document.querySelector(`.artisan-card[data-id="${id}"]`);
            if (card) {
                card.classList.add('active');
                card.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            }
            if (markerMap[id]) {
                markers.zoomToShowLayer(markerMap[id], () => {
                    markerMap[id].setIcon(createIcon(true));
                    markerMap[id].openPopup();
                });
            }

            if (window.innerWidth < 768) {
                document.getElementById('sidebar').classList.remove('open');
            }
        }

        // ============================================================
        // SEARCH & FILTER
        // ============================================================
        const searchInput = document.getElementById('search-input');
        const chips = document.querySelectorAll('.filter-chip');
        let activeFilter = 'all';

        chips.forEach(chip => {
            chip.addEventListener('click', () => {
                chips.forEach(c => c.classList.remove('active'));
                chip.classList.add('active');
                activeFilter = chip.dataset.filter;
                applyFilters();
            });
        });

        searchInput.addEventListener('input', () => applyFilters());

        function applyFilters() {
            const query = searchInput.value.toLowerCase().trim();
            const cards = document.querySelectorAll('.artisan-card');
            const km = parseInt(radiusSlider.value);
            let visibleCount = 0;

            cards.forEach(card => {
                const cats = card.dataset.categories;
                const search = card.dataset.search;
                const cardLat = parseFloat(card.dataset.lat);
                const cardLng = parseFloat(card.dataset.lng);
                const matchFilter = activeFilter === 'all' || cats.includes(activeFilter);
                const matchSearch = !query || search.includes(query);
                const dist = haversine(originLat, originLng, cardLat, cardLng);
                const matchRadius = dist <= km;
                const visible = matchFilter && matchSearch && matchRadius;
                card.style.display = visible ? '' : 'none';
                if (visible) visibleCount++;
            });

            Object.keys(markerMap).forEach(id => {
                const card = document.querySelector(`.artisan-card[data-id="${id}"]`);
                if (card && card.style.display !== 'none') {
                    if (!markers.hasLayer(markerMap[id])) markers.addLayer(markerMap[id]);
                } else {
                    if (markers.hasLayer(markerMap[id])) markers.removeLayer(markerMap[id]);
                }
            });

            const noResults = document.getElementById('no-results');
            if (noResults) noResults.style.display = visibleCount === 0 ? 'flex' : 'none';

            const countEl = document.getElementById('sidebar-count');
            if (countEl) countEl.textContent = visibleCount + ' artisan' + (visibleCount !== 1 ? 's' : '');
        }

        // ============================================================
        // ROUTING (Leaflet Routing Machine)
        // ============================================================
        const activeRoutes = [];

        function startRouting(destLat, destLng, artisanName) {
            if (userLat === null) {
                navigator.geolocation.getCurrentPosition(pos => {
                    userLat = pos.coords.latitude;
                    userLng = pos.coords.longitude;
                    addUserMarker(userLat, userLng);
                    createRoute(userLat, userLng, destLat, destLng);
                }, () => {
                    createRoute(PORTO_NOVO.lat, PORTO_NOVO.lng, destLat, destLng);
                }, { enableHighAccuracy: true, timeout: 8000 });
            } else {
                createRoute(userLat, userLng, destLat, destLng);
            }
        }

        function createRoute(origLat, origLng, destLat, destLng) {
            const control = L.Routing.control({
                waypoints: [
                    L.latLng(origLat, origLng),
                    L.latLng(destLat, destLng)
                ],
                routeWhileDragging: false,
                addWaypoints: false,
                draggableWaypoints: false,
                fitSelectedRoutes: true,
                show: true,
                lineOptions: {
                    styles: [
                        { color: '#064E3B', weight: 5, opacity: 0.7, dashArray: '10 8' }
                    ]
                },
                createMarker: function() { return null; },
                language: 'fr'
            }).addTo(map);

            control.on('routesfound', function() {
                const altContainer = control.getContainer();
                if (altContainer) {
                    const removeBtn = document.createElement('button');
                    removeBtn.className = 'leaflet-routing-remove-btn';
                    removeBtn.textContent = "Supprimer l'itinéraire";
                    removeBtn.style.cssText = 'width:100%;margin-top:8px;';
                    removeBtn.onclick = function() {
                        map.removeControl(control);
                        const idx = activeRoutes.indexOf(control);
                        if (idx > -1) activeRoutes.splice(idx, 1);
                    };
                    altContainer.appendChild(removeBtn);
                }
            });

            activeRoutes.push(control);
            map.closePopup();
        }

        // ============================================================
        // GEOLOCATION
        // ============================================================
        let userMarker = null;

        function addUserMarker(lat, lng) {
            if (userMarker) map.removeLayer(userMarker);
            const userIcon = L.divIcon({
                className: 'user-location-marker',
                html: `<div style="width:18px;height:18px;background:#3b82f6;border:3px solid white;border-radius:50%;box-shadow:0 2px 8px rgba(59,130,246,0.4);"></div>`,
                iconSize: [18, 18],
                iconAnchor: [9, 9]
            });
            userMarker = L.marker([lat, lng], { icon: userIcon }).addTo(map);
        }

        function locateUser() {
            if (!navigator.geolocation) return;
            navigator.geolocation.getCurrentPosition(pos => {
                userLat = pos.coords.latitude;
                userLng = pos.coords.longitude;
                originLat = userLat;
                originLng = userLng;
                addUserMarker(userLat, userLng);
                map.setView([userLat, userLng], 16, { animate: true });
                drawRadiusCircle();
                applyFilters();
            }, () => {
                originLat = PORTO_NOVO.lat;
                originLng = PORTO_NOVO.lng;
                drawRadiusCircle();
            }, { enableHighAccuracy: true, timeout: 10000 });
        }

        // On page load: attempt geolocation, fall back to Porto-Novo
        (function initGeo() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(pos => {
                    userLat = pos.coords.latitude;
                    userLng = pos.coords.longitude;
                    originLat = userLat;
                    originLng = userLng;
                    addUserMarker(userLat, userLng);
                    map.setView([userLat, userLng], 15, { animate: true });
                    drawRadiusCircle();
                    applyFilters();
                }, () => {
                    originLat = PORTO_NOVO.lat;
                    originLng = PORTO_NOVO.lng;
                    drawRadiusCircle();
                }, { enableHighAccuracy: true, timeout: 8000 });
            } else {
                drawRadiusCircle();
            }
        })();

        // ============================================================
        // MOBILE SIDEBAR TOGGLE
        // ============================================================
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('open');
        }
    </script>
</body>
</html>
