@extends('admin.layouts.admin')
@section('title', 'Modifier Artisan')
@section('page-title', 'Modifier un Artisan')

@section('content')
<div class="max-w-3xl">
    @if($errors->any())
    <div class="bg-red-50 border border-red-200 text-red-700 rounded-2xl p-4 mb-6 text-sm font-medium">
        <ul class="list-disc list-inside space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
    @endif

    <form action="{{ route('admin.artisans.update', $artisan) }}" method="POST" class="space-y-8">
        @csrf
        @method('PUT')

        <!-- Infos Personnelles -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
            <h3 class="font-bold text-lg text-slate-900 mb-5 pb-3 border-b border-slate-100">Informations personnelles</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1.5">Prénom *</label>
                    <input type="text" name="first_name" value="{{ old('first_name', $artisan->first_name) }}" required class="w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-amber-500 outline-none bg-slate-50">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1.5">Nom *</label>
                    <input type="text" name="last_name" value="{{ old('last_name', $artisan->last_name) }}" required class="w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-amber-500 outline-none bg-slate-50">
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-sm font-bold text-slate-700 mb-1.5">Nom professionnel / Atelier</label>
                    <input type="text" name="professional_name" value="{{ old('professional_name', $artisan->professional_name) }}" placeholder="Ex: Atelier DOSSOU" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-amber-500 outline-none bg-slate-50">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1.5">Email (compte)</label>
                    <input type="email" name="email" value="{{ old('email', $artisan->user?->email) }}" required class="w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-amber-500 outline-none bg-slate-50">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1.5">Téléphone *</label>
                    <input type="text" name="phone" value="{{ old('phone', $artisan->phone) }}" required class="w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-amber-500 outline-none bg-slate-50">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1.5">WhatsApp</label>
                    <input type="text" name="whatsapp" value="{{ old('whatsapp', $artisan->whatsapp) }}" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-amber-500 outline-none bg-slate-50">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1.5">Années d'expérience</label>
                    <input type="number" name="experience_years" value="{{ old('experience_years', $artisan->experience_years) }}" min="0" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-amber-500 outline-none bg-slate-50">
                </div>
            </div>
        </div>

        <!-- Description -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
            <h3 class="font-bold text-lg text-slate-900 mb-5 pb-3 border-b border-slate-100">Description</h3>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1.5">Présentation courte</label>
                    <textarea name="description" rows="3" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-amber-500 outline-none bg-slate-50 resize-none">{{ old('description', $artisan->description) }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1.5">Histoire / Parcours</label>
                    <textarea name="history" rows="4" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-amber-500 outline-none bg-slate-50 resize-none">{{ old('history', $artisan->history) }}</textarea>
                </div>
            </div>
        </div>

        <!-- Localisation -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
            <h3 class="font-bold text-lg text-slate-900 mb-5 pb-3 border-b border-slate-100">Localisation & Statut</h3>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="sm:col-span-3">
                    <label class="block text-sm font-bold text-slate-700 mb-1.5">Quartier / Adresse (Porto-Novo) *</label>
                    <div class="flex gap-2">
                        <input type="text" id="address_input" name="address" value="{{ old('address', $artisan->address) }}" placeholder="Ex: Ouando, Porto-Novo" class="flex-1 w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-amber-500 outline-none bg-slate-50">
                        <button type="button" id="geocode_btn" class="px-4 py-2.5 bg-dokun-gold text-white font-bold rounded-xl hover:bg-yellow-600 transition-colors">
                            Localiser
                        </button>
                    </div>
                    <p id="geocode_status" class="text-xs text-slate-500 mt-2">Cliquez sur Localiser pour mettre à jour la position sur la carte.</p>
                </div>
                <!-- Hidden coordinates -->
                <input type="hidden" id="latitude_input" name="latitude" value="{{ old('latitude', $artisan->latitude) }}">
                <input type="hidden" id="longitude_input" name="longitude" value="{{ old('longitude', $artisan->longitude) }}">
                <div class="sm:col-span-1">
                    <label class="block text-sm font-bold text-slate-700 mb-1.5">Statut</label>
                    <select name="status" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-amber-500 outline-none bg-slate-50">
                        <option value="draft" {{ old('status', $artisan->status) === 'draft' ? 'selected' : '' }}>Brouillon</option>
                        <option value="published" {{ old('status', $artisan->status) === 'published' ? 'selected' : '' }}>Publié</option>
                        <option value="pending" {{ old('status', $artisan->status) === 'pending' ? 'selected' : '' }}>En attente de validation</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Savoir-Faire -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
            <h3 class="font-bold text-lg text-slate-900 mb-5 pb-3 border-b border-slate-100">Savoir-Faire</h3>
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                @php $selectedSavoirFaires = old('savoir_faires', $artisan->savoirFaires->pluck('id')->toArray()); @endphp
                @foreach($savoirFaires as $sf)
                <label class="flex items-center gap-3 p-3 rounded-xl border border-slate-200 cursor-pointer hover:border-amber-300 hover:bg-amber-50 transition-colors has-[:checked]:border-amber-400 has-[:checked]:bg-amber-50">
                    <input type="checkbox" name="savoir_faires[]" value="{{ $sf->id }}"
                        {{ in_array($sf->id, $selectedSavoirFaires) ? 'checked' : '' }}
                        class="w-4 h-4 text-amber-500 rounded">
                    <span class="text-sm font-semibold text-slate-700">{{ $sf->name }}</span>
                </label>
                @endforeach
            </div>
        </div>

        <div class="flex gap-4">
            <button type="submit" class="px-8 py-3 bg-amber-500 text-white font-black rounded-xl hover:bg-amber-600 transition-colors shadow-lg shadow-amber-500/25">
                Mettre à jour
            </button>
            <a href="{{ route('admin.artisans.index') }}" class="px-8 py-3 bg-white text-slate-600 font-bold rounded-xl border border-slate-200 hover:bg-slate-50 transition-colors">
                Annuler
            </a>
        </div>
    </form>
</div>
</div>

<script>
    document.getElementById('geocode_btn').addEventListener('click', function() {
        const address = document.getElementById('address_input').value;
        const status = document.getElementById('geocode_status');
        
        if (!address) {
            status.textContent = 'Veuillez entrer une adresse valide.';
            status.className = 'text-xs text-red-500 mt-2';
            return;
        }

        status.textContent = 'Recherche en cours...';
        status.className = 'text-xs text-slate-500 mt-2';

        const query = encodeURIComponent(address + ', Benin');
        fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${query}&limit=1`)
            .then(res => res.json())
            .then(data => {
                if (data && data.length > 0) {
                    document.getElementById('latitude_input').value = data[0].lat;
                    document.getElementById('longitude_input').value = data[0].lon;
                    status.textContent = `✅ Localisation trouvée ! (Lat: ${parseFloat(data[0].lat).toFixed(4)}, Lng: ${parseFloat(data[0].lon).toFixed(4)})`;
                    status.className = 'text-xs text-emerald-600 font-bold mt-2';
                } else {
                    status.textContent = '❌ Adresse introuvable. Essayez d\'être plus précis.';
                    status.className = 'text-xs text-amber-600 mt-2';
                }
            })
            .catch(err => {
                status.textContent = '❌ Erreur de réseau.';
                status.className = 'text-xs text-red-500 mt-2';
            });
    });
</script>
@endsection
