<x-app-layout>
    <x-slot name="header">
        <h1 class="font-serif text-3xl text-dokun-green">Modifier mon profil</h1>
    </x-slot>

    <div class="min-h-screen bg-dokun-ivory py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

            <a href="{{ route('artisan-space.index') }}" class="inline-flex items-center gap-2 text-dokun-charcoal/60 hover:text-dokun-green font-semibold text-sm mb-6 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Retour à mon atelier
            </a>

            @if(session('success'))
                <div class="mb-6 bg-emerald-50 text-emerald-800 rounded-xl p-4 font-semibold">{{ session('success') }}</div>
            @endif

            @if($errors->any())
                <div class="mb-6 bg-red-50 text-red-700 rounded-xl p-4 text-sm font-medium">
                    <ul class="list-disc list-inside space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                </div>
            @endif

            <form action="{{ route('artisan-space.update-profile') }}" method="POST" class="space-y-8">
                @csrf
                @method('PUT')

                {{-- Photo de profil --}}
                <div class="bg-white rounded-2xl border border-black/5 p-6">
                    <h2 class="font-serif text-xl text-dokun-green mb-5 pb-3 border-b border-black/5">Photo de profil</h2>
                    <div class="flex items-center gap-6">
                        <div class="relative group w-24 h-24 rounded-full overflow-hidden bg-dokun-charcoal/5 border-2 border-dokun-charcoal/10 flex-shrink-0">
                            @if($artisan->photo_path)
                                <img src="{{ asset('storage/' . $artisan->photo_path) }}" alt="" class="w-full h-full object-cover" id="edit-profile-photo-preview">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-4xl text-dokun-charcoal/20" id="edit-profile-photo-preview-placeholder">👤</div>
                                <img src="" alt="" class="w-full h-full object-cover hidden" id="edit-profile-photo-preview">
                            @endif
                            <label class="absolute inset-0 bg-black/0 group-hover:bg-black/40 transition flex items-center justify-center cursor-pointer opacity-0 group-hover:opacity-100">
                                <span class="bg-white text-dokun-green w-8 h-8 rounded-full flex items-center justify-center text-sm shadow">📷</span>
                                <input
                                    type="file"
                                    accept="image/jpeg,image/png,image/webp,image/gif"
                                    class="hidden"
                                    id="edit-photo-input"
                                    onchange="uploadEditProfilePhoto(this)"
                                >
                            </label>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-dokun-charcoal/80">Photo de profil</p>
                            <p class="text-xs text-dokun-charcoal/50 mt-0.5">JPEG, PNG, WebP ou GIF · Max 5 Mo</p>
                            <div id="edit-photo-status" class="text-xs mt-1 font-semibold"></div>
                        </div>
                    </div>
                </div>

                {{-- Section 1 — Informations personnelles --}}
                <div class="bg-white rounded-2xl border border-black/5 p-6">
                    <h2 class="font-serif text-xl text-dokun-green mb-5 pb-3 border-b border-black/5">Informations personnelles</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-bold text-dokun-charcoal/80 mb-1.5">Prénom *</label>
                            <input type="text" name="first_name" value="{{ old('first_name', $artisan->first_name) }}" required
                                class="w-full px-4 py-2.5 border border-black/10 rounded-xl focus:ring-2 focus:ring-dokun-green/30 focus:border-dokun-green outline-none transition">
                            @error('first_name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-dokun-charcoal/80 mb-1.5">Nom *</label>
                            <input type="text" name="last_name" value="{{ old('last_name', $artisan->last_name) }}" required
                                class="w-full px-4 py-2.5 border border-black/10 rounded-xl focus:ring-2 focus:ring-dokun-green/30 focus:border-dokun-green outline-none transition">
                            @error('last_name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div class="sm:col-span-2">
                            <label class="block text-sm font-bold text-dokun-charcoal/80 mb-1.5">Nom professionnel / Atelier</label>
                            <input type="text" name="professional_name" value="{{ old('professional_name', $artisan->professional_name) }}" placeholder="Ex: Atelier DOSSOU"
                                class="w-full px-4 py-2.5 border border-black/10 rounded-xl focus:ring-2 focus:ring-dokun-green/30 focus:border-dokun-green outline-none transition">
                            @error('professional_name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-dokun-charcoal/80 mb-1.5">Téléphone *</label>
                            <input type="text" name="phone" value="{{ old('phone', $artisan->phone) }}" required
                                class="w-full px-4 py-2.5 border border-black/10 rounded-xl focus:ring-2 focus:ring-dokun-green/30 focus:border-dokun-green outline-none transition">
                            @error('phone') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-dokun-charcoal/80 mb-1.5">WhatsApp</label>
                            <input type="text" name="whatsapp" value="{{ old('whatsapp', $artisan->whatsapp) }}"
                                class="w-full px-4 py-2.5 border border-black/10 rounded-xl focus:ring-2 focus:ring-dokun-green/30 focus:border-dokun-green outline-none transition">
                            @error('whatsapp') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                {{-- Section 2 — Mon histoire --}}
                <div class="bg-white rounded-2xl border border-black/5 p-6">
                    <h2 class="font-serif text-xl text-dokun-green mb-5 pb-3 border-b border-black/5">Mon histoire</h2>
                    <div class="space-y-5">
                        <div>
                            <label class="block text-sm font-bold text-dokun-charcoal/80 mb-1.5">Présentation courte</label>
                            <textarea name="description" rows="3"
                                class="w-full px-4 py-2.5 border border-black/10 rounded-xl focus:ring-2 focus:ring-dokun-green/30 focus:border-dokun-green outline-none transition resize-none">{{ old('description', $artisan->description) }}</textarea>
                            @error('description') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-dokun-charcoal/80 mb-1.5">Mon parcours / Histoire</label>
                            <textarea name="history" rows="5"
                                class="w-full px-4 py-2.5 border border-black/10 rounded-xl focus:ring-2 focus:ring-dokun-green/30 focus:border-dokun-green outline-none transition resize-none">{{ old('history', $artisan->history) }}</textarea>
                            @error('history') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-dokun-charcoal/80 mb-1.5">Années d'expérience</label>
                            <input type="number" name="experience_years" value="{{ old('experience_years', $artisan->experience_years) }}" min="0"
                                class="w-full px-4 py-2.5 border border-black/10 rounded-xl focus:ring-2 focus:ring-dokun-green/30 focus:border-dokun-green outline-none transition">
                            @error('experience_years') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                {{-- Section 3 — Localisation --}}
                <div class="bg-white rounded-2xl border border-black/5 p-6">
                    <h2 class="font-serif text-xl text-dokun-green mb-5 pb-3 border-b border-black/5">Localisation</h2>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-bold text-dokun-charcoal/80 mb-1.5">Quartier *</label>
                            <select id="quartier_select" onchange="applyQuartier()"
                                class="w-full px-4 py-2.5 border border-black/10 rounded-xl focus:ring-2 focus:ring-dokun-green/30 focus:border-dokun-green outline-none transition bg-white">
                                <option value="">— Sélectionnez votre quartier —</option>
                                @foreach($quartiers as $q)
                                    <option value="{{ $q->slug }}"
                                        data-lat="{{ $q->lat }}" data-lng="{{ $q->lng }}" data-name="{{ $q->name }}"
                                        {{ old('quartier', $artisan->address && str_contains(strtolower($artisan->address), strtolower($q->name)) ? $q->slug : '') }}>
                                        {{ $q->name }}
                                    </option>
                                @endforeach
                            </select>
                            <p class="text-xs text-dokun-charcoal/50 mt-1.5">Choisissez votre quartier : les coordonnées se remplissent automatiquement.</p>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-dokun-charcoal/80 mb-1.5">Adresse précise</label>
                            <div class="flex gap-2">
                                <input type="text" id="address_input" name="address"
                                    value="{{ old('address', $artisan->address) }}" placeholder="Ex: Rue 12, près du marché, Ouando"
                                    class="flex-1 px-4 py-2.5 border border-black/10 rounded-xl focus:ring-2 focus:ring-dokun-green/30 focus:border-dokun-green outline-none transition">
                                <button type="button" id="geocode_btn" onclick="geocode()"
                                    class="px-5 py-2.5 bg-dokun-gold text-white font-bold rounded-xl hover:bg-yellow-600 transition whitespace-nowrap">
                                    Géocoder l'adresse
                                </button>
                            </div>
                            <p id="geocode_status" class="text-xs text-dokun-charcoal/50 mt-2">
                                Le géocodage affine la position au-delà du centre du quartier.
                            </p>
                        </div>

                        <input type="hidden" id="latitude_input" name="latitude" value="{{ old('latitude', $artisan->latitude) }}">
                        <input type="hidden" id="longitude_input" name="longitude" value="{{ old('longitude', $artisan->longitude) }}">

                        @error('latitude') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                        @error('longitude') <p class="text-xs text-red-500">{{ $message }}</p> @enderror

                        {{-- Mini map preview --}}
                        <div class="bg-dokun-ivory rounded-xl p-4 border border-black/5">
                            <p class="text-xs font-bold text-dokun-charcoal/60 mb-2">Aperçu des coordonnées</p>
                            <div class="flex items-center gap-6 text-sm text-dokun-charcoal/70">
                                <span>Lat: <strong id="lat_display">{{ $artisan->latitude ? number_format($artisan->latitude, 6) : '—' }}</strong></span>
                                <span>Lng: <strong id="lng_display">{{ $artisan->longitude ? number_format($artisan->longitude, 6) : '—' }}</strong></span>
                            </div>
                            <div id="map_container" class="mt-3 rounded-xl overflow-hidden border border-black/5 hidden">
                                <iframe id="map_iframe" width="100%" height="200" style="border:0" loading="lazy"></iframe>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Section 4 — Savoir-faire --}}
                <div class="bg-white rounded-2xl border border-black/5 p-6">
                    <h2 class="font-serif text-xl text-dokun-green mb-5 pb-3 border-b border-black/5">Savoir-faire</h2>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                        @foreach($savoirFaires as $sf)
                            <label class="flex items-center gap-3 p-3 rounded-xl border border-black/5 cursor-pointer hover:border-dokun-gold/50 hover:bg-dokun-gold/5 transition-colors has-[:checked]:border-dokun-gold has-[:checked]:bg-dokun-gold/10">
                                <input type="checkbox" name="savoir_faires[]" value="{{ $sf->id }}"
                                    {{ $artisan->savoirFaires->contains($sf->id) ? 'checked' : '' }}
                                    class="w-4 h-4 text-dokun-gold rounded">
                                <span class="text-sm font-semibold text-dokun-charcoal/80">{{ $sf->name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                {{-- Save --}}
                <div class="flex gap-4">
                    <button type="submit"
                        class="px-8 py-3 bg-dokun-green text-white font-black rounded-xl hover:bg-dokun-green/90 transition shadow-lg shadow-dokun-green/20">
                        Enregistrer les modifications
                    </button>
                    <a href="{{ route('artisan-space.index') }}"
                        class="px-8 py-3 bg-white text-dokun-charcoal/70 font-bold rounded-xl border border-black/10 hover:bg-dokun-ivory transition">
                        Annuler
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        async function uploadEditProfilePhoto(input) {
            const file = input.files[0];
            if (!file) return;
            const status = document.getElementById('edit-photo-status');
            status.textContent = 'Envoi…';
            status.className = 'text-xs mt-1 font-semibold text-dokun-gold';

            const formData = new FormData();
            formData.append('photo', file);

            try {
                const res = await fetch('{{ route("artisan-space.photo.upload") }}', {
                    method: 'POST',
                    body: formData,
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
                });
                if (!res.ok) {
                    const err = await res.json().catch(() => ({ message: 'Erreur' }));
                    status.textContent = err.message || 'Erreur lors de l\'envoi.';
                    status.className = 'text-xs mt-1 font-semibold text-red-500';
                    return;
                }
                const data = await res.json();
                if (data.status === 'success') {
                    const img = document.getElementById('edit-profile-photo-preview');
                    const placeholder = document.getElementById('edit-profile-photo-preview-placeholder');
                    if (img) { img.src = data.url; img.classList.remove('hidden'); }
                    if (placeholder) placeholder.classList.add('hidden');
                    status.textContent = '✓ Photo envoyée !';
                    status.className = 'text-xs mt-1 font-semibold text-emerald-600';
                    setTimeout(() => { status.textContent = ''; }, 3000);
                }
            } catch (e) {
                status.textContent = 'Erreur réseau.';
                status.className = 'text-xs mt-1 font-semibold text-red-500';
            } finally {
                input.value = '';
            }
        }

        function applyQuartier() {
            const sel = document.getElementById('quartier_select');
            const opt = sel.options[sel.selectedIndex];
            if (!opt.value) return;
            const lat = opt.dataset.lat, lng = opt.dataset.lng, name = opt.dataset.name;
            document.getElementById('latitude_input').value = lat;
            document.getElementById('longitude_input').value = lng;
            document.getElementById('lat_display').textContent = parseFloat(lat).toFixed(6);
            document.getElementById('lng_display').textContent = parseFloat(lng).toFixed(6);
            // Préfixe l'adresse avec le quartier si pas déjà présent
            const addr = document.getElementById('address_input');
            if (!addr.value.toLowerCase().includes(name.toLowerCase())) {
                addr.value = name + ', ' + (addr.value ? addr.value.replace(/^[^,]+,\s*/, '') : 'Porto-Novo');
            }
            const status = document.getElementById('geocode_status');
            status.textContent = `Position réglée sur le centre de ${name}. Géocodez votre adresse pour affiner.`;
            status.className = 'text-xs text-emerald-600 font-bold mt-2';
        }

        // Pré-sélectionne le quartier au chargement si l'adresse en contient un
        (function initQuartier() {
            const sel = document.getElementById('quartier_select');
            if (sel.value) applyQuartier();
        })();

        function geocode() {
            const address = document.getElementById('address_input').value;
            const status = document.getElementById('geocode_status');

            if (!address) {
                status.textContent = 'Veuillez entrer une adresse valide.';
                status.className = 'text-xs text-red-500 mt-2';
                return;
            }

            status.textContent = 'Recherche en cours…';
            status.className = 'text-xs text-dokun-charcoal/50 mt-2';

            const query = encodeURIComponent(address + ', Benin');
            fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${query}&limit=1`)
                .then(res => res.json())
                .then(data => {
                    if (data && data.length > 0) {
                        const lat = data[0].lat;
                        const lon = data[0].lon;
                        document.getElementById('latitude_input').value = lat;
                        document.getElementById('longitude_input').value = lon;
                        document.getElementById('lat_display').textContent = parseFloat(lat).toFixed(6);
                        document.getElementById('lng_display').textContent = parseFloat(lon).toFixed(6);

                        document.getElementById('map_container').classList.remove('hidden');
                        document.getElementById('map_iframe').src =
                            `https://www.openstreetmap.org/export/embed.html?bbox=${parseFloat(lon)-0.01},${parseFloat(lat)-0.01},${parseFloat(lon)+0.01},${parseFloat(lat)+0.01}&layer=mapnik&marker=${lat},${lon}`;

                        status.textContent = `Localisation trouvée ! (Lat: ${parseFloat(lat).toFixed(4)}, Lng: ${parseFloat(lon).toFixed(4)})`;
                        status.className = 'text-xs text-emerald-600 font-bold mt-2';
                    } else {
                        status.textContent = 'Adresse introuvable. Essayez d\'être plus précis (ex: "Ouando, Porto-Novo").';
                        status.className = 'text-xs text-amber-600 mt-2';
                    }
                })
                .catch(() => {
                    status.textContent = 'Erreur de réseau lors de la localisation.';
                    status.className = 'text-xs text-red-500 mt-2';
                });
        }
    </script>
</x-app-layout>
