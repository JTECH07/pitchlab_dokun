@extends('admin.layouts.admin')
@section('title', 'Carte des Artisans')
@section('page-title', 'Carte des Artisans')

@section('content')
<div class="space-y-6">
 <!-- Filter Header -->
 <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
 <div>
 <h2 class="text-2xl font-extrabold text-slate-900">Vue Cartographique</h2>
 <p class="text-sm text-slate-500 font-medium mt-1">Localisation et statut de tous les artisans enregistrés.</p>
 </div>
 <div class="flex items-center gap-2 flex-wrap">
 <button data-admin-filter="all" class="admin-map-chip active px-4 py-2 rounded-full text-xs font-bold border border-slate-200 bg-dokun-green text-white transition-all">
 Tous <span class="ml-1 opacity-75">{{ $artisans->count() }}</span>
 </button>
 <button data-admin-filter="published" class="admin-map-chip px-4 py-2 rounded-full text-xs font-bold border border-slate-200 bg-white text-slate-700 transition-all">
 <span class="inline-block w-2 h-2 bg-emerald-500 rounded-full mr-1"></span> Publiés <span class="ml-1 opacity-75">{{ $artisans->where('status', 'published')->count() }}</span>
 </button>
 <button data-admin-filter="pending" class="admin-map-chip px-4 py-2 rounded-full text-xs font-bold border border-slate-200 bg-white text-slate-700 transition-all">
 <span class="inline-block w-2 h-2 bg-amber-500 rounded-full mr-1"></span> En attente <span class="ml-1 opacity-75">{{ $artisans->where('status', 'pending')->count() }}</span>
 </button>
 <button data-admin-filter="draft" class="admin-map-chip px-4 py-2 rounded-full text-xs font-bold border border-slate-200 bg-white text-slate-700 transition-all">
 <span class="inline-block w-2 h-2 bg-slate-400 rounded-full mr-1"></span> Brouillons <span class="ml-1 opacity-75">{{ $artisans->where('status', 'draft')->count() }}</span>
 </button>
 </div>
 </div>

 <!-- Map Container -->
 <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden" style="height: calc(100vh - 14rem); min-height: 500px;">
 <div id="admin-map" style="width: 100%; height: 100%;"></div>
 </div>
</div>

<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
 .leaflet-popup-content-wrapper { border-radius: 0.75rem !important; overflow: hidden !important; padding: 0 !important; box-shadow: 0 12px 32px rgba(0,0,0,0.15) !important; }
 .leaflet-popup-content { margin: 0 !important; font-family: 'Manrope', sans-serif !important; }
 .leaflet-popup-tip { display: none !important; }
 .admin-map-chip.active { background: #064E3B !important; color: white !important; border-color: #064E3B !important; }
 .admin-map-chip:hover { background: #064E3B !important; color: white !important; border-color: #064E3B !important; }
 .leaflet-control-zoom { border: none !important; box-shadow: 0 4px 14px rgba(0,0,0,0.25) !important; border-radius: 10px !important; overflow: hidden; }
 .leaflet-control-zoom a { background: #1a1a1a !important; color: #ffffff !important; width: 34px !important; height: 34px !important; line-height: 34px !important; font-size: 17px !important; font-weight: 700 !important; }
 .leaflet-control-zoom a:hover { background: #333333 !important; }
 .drop-marker { transition: transform 0.15s ease; }
 .drop-marker:hover { transform: scale(1.12); }
</style>
@endsection

@section('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
 (function() {
 const artisans = @json($artisans);

 const map = L.map('admin-map', { zoomControl: false }).setView([6.4969, 2.6289], 13);
 L.control.zoom({ position: 'topleft' }).addTo(map);

 // OpenStreetMap standard tiles — même design que l'accueil
 L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
 attribution: '&copy; OpenStreetMap',
 maxZoom: 19
 }).addTo(map);

 // Status color config (bande du marqueur goutte)
 const statusColors = {
 published: '#2563EB',
 pending: '#f59e0b',
 draft: '#9ca3af',
 default: '#9ca3af'
 };
 const statusLabels = {
 published: 'Publié', pending: 'En attente', draft: 'Brouillon', default: 'Inconnu'
 };

 // Marqueur goutte bleu roi avec Ɖ (même design que partout)
 function createAdminIcon(status) {
 const color = statusColors[status] || statusColors.default;
 return L.divIcon({
 className: 'drop-marker',
 html: `<svg width="30" height="38" viewBox="0 0 28 36" xmlns="http://www.w3.org/2000/svg">
 <path d="M14 0C6.3 0 0 6.3 0 14c0 10.5 14 22 14 22s14-11.5 14-22C28 6.3 21.7 0 14 0z" fill="${color}" stroke="#ffffff" stroke-width="2"/>
 <text x="14" y="20" text-anchor="middle" font-family="'DM Serif Display',serif" font-size="15" font-weight="bold" fill="#ffffff">Ɖ</text>
 </svg>`,
 iconSize: [30, 38],
 iconAnchor: [15, 36],
 popupAnchor: [0, -32]
 });
 }

 function buildAdminPopup(a) {
 const name = a.professional_name || (a.first_name + ' ' + a.last_name);
 const label = statusLabels[a.status] || statusLabels.default;
 const sfs = (a.savoir_faires || a.savoirFaires || []).map(s => s.name).join(', ') || '—';
 const editUrl = '/admin/artisans/' + a.id + '/edit';
 return `
 <div style="padding:16px;min-width:220px;">
 <div style="display:flex;align-items:center;gap:10px;margin-bottom:10px;">
 <svg width="26" height="33" viewBox="0 0 28 36"><path d="M14 0C6.3 0 0 6.3 0 14c0 10.5 14 22 14 22s14-11.5 14-22C28 6.3 21.7 0 14 0z" fill="#2563EB"/><text x="14" y="20" text-anchor="middle" font-family="'DM Serif Display',serif" font-size="14" font-weight="bold" fill="#fff">Ɖ</text></svg>
 <div>
 <div style="font-weight:700;font-size:14px;color:#17201D;">${name}</div>
 <div style="font-size:11px;color:#6b7280;margin-top:1px;">${a.address || '—'}</div>
 </div>
 </div>
 <div style="display:inline-block;padding:3px 10px;border-radius:20px;font-size:10px;font-weight:700;background:#2563EB15;color:#2563EB;border:1px solid #2563EB30;margin-bottom:8px;">${label}</div>
 <div style="font-size:11px;color:#6b7280;margin-bottom:12px;">Savoir-faire : ${sfs}</div>
 <a href="${editUrl}" style="display:block;text-align:center;padding:8px 16px;border-radius:8px;background:#064E3B;color:white;font-size:12px;font-weight:700;text-decoration:none;">Voir / Modifier</a>
 </div>
 `;
 }

 // Add markers by status
 let allMarkers = [];
 artisans.forEach(a => {
 if (!a.latitude || !a.longitude) return;
 const marker = L.marker([a.latitude, a.longitude], { icon: createAdminIcon(a.status) });
 marker.bindPopup(buildAdminPopup(a), { maxWidth: 300, minWidth: 240 });
 marker._status = a.status;
 marker.addTo(map);
 allMarkers.push(marker);
 });

 // Filter chips
 const chips = document.querySelectorAll('.admin-map-chip');
 chips.forEach(chip => {
 chip.addEventListener('click', () => {
 chips.forEach(c => c.classList.remove('active'));
 chip.classList.add('active');
 const filter = chip.dataset.adminFilter;

 allMarkers.forEach(m => {
 if (filter === 'all' || m._status === filter) {
 if (!map.hasLayer(m)) m.addTo(map);
 } else {
 if (map.hasLayer(m)) map.removeLayer(m);
 }
 });
 });
 });
 })();
</script>
@endsection
