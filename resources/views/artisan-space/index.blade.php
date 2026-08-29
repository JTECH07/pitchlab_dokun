<x-app-layout>
 <x-slot name="header">
 <h1 class="font-serif text-3xl text-dokun-green">Mon atelier</h1>
 </x-slot>

 <div class="min-h-screen bg-dokun-ivory py-8" x-data="artisanDashboard()">
 <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

 @if(session('success'))
 <div class="mb-6 bg-emerald-50 text-emerald-800 rounded-xl p-4 font-semibold">{{ session('success') }}</div>
 @endif

 @if(!$artisan)
 <div class="bg-white rounded-2xl p-10 text-center">
 <h2 class="font-serif text-3xl text-dokun-green">Bienvenue sur votre espace professionnel</h2>
 <p class="mt-3 text-dokun-charcoal/65">Complétez votre profil pour commencer à recevoir des réservations.</p>
 <a href="{{ route('profile.edit') }}" class="inline-block mt-6 bg-dokun-green text-white px-6 py-3 rounded-xl font-bold">Compléter mon compte</a>
 </div>
 @else

 {{-- Hero Banner --}}
 <section class="rounded-3xl bg-dokun-green text-white p-7 md:p-10 relative overflow-hidden">
 <img src="{{ $artisan->image_url }}" alt="" class="absolute inset-0 h-full w-full object-cover opacity-20">
 <div class="relative flex items-center gap-6">
 <img src="{{ $artisan->image_url }}" alt="{{ $artisan->professional_name }}" class="w-20 h-20 md:w-28 md:h-28 rounded-2xl object-cover border-2 border-white/30">
 <div>
 <p class="text-dokun-gold uppercase font-bold tracking-wider text-xs">Espace professionnel</p>
 <h2 class="font-serif text-3xl md:text-4xl mt-1">{{ $artisan->professional_name ?: $artisan->first_name.' '.$artisan->last_name }}</h2>
 @if($artisan->professional_name)
 <p class="text-white/60 text-sm mt-1">{{ $artisan->first_name }} {{ $artisan->last_name }}</p>
 @endif
 </div>
 </div>
 <div class="relative mt-6 flex gap-3">
 <a href="{{ route('artisans.show', $artisan) }}" class="bg-white text-dokun-green px-5 py-2.5 rounded-xl font-bold text-sm hover:bg-white/90 transition">Voir ma fiche publique</a>
 <a href="{{ route('artisan-space.edit-profile') }}" class="border border-white/30 text-white px-5 py-2.5 rounded-xl font-bold text-sm hover:bg-white/10 transition">Modifier mon profil</a>
 </div>
 </section>

 {{-- Stats --}}
 <div class="grid sm:grid-cols-3 gap-5 my-7">
 <div class="bg-white rounded-2xl p-6 border border-black/5">
 <p class="text-dokun-charcoal/55 text-sm font-semibold">À répondre</p>
 <p class="font-serif text-4xl text-dokun-green mt-2" x-text="stats.pending">0</p>
 </div>
 <div class="bg-white rounded-2xl p-6 border border-black/5">
 <p class="text-dokun-charcoal/55 text-sm font-semibold">À préparer</p>
 <p class="font-serif text-4xl text-dokun-green mt-2" x-text="stats.accepted">0</p>
 </div>
 <div class="bg-white rounded-2xl p-6 border border-black/5">
 <p class="text-dokun-charcoal/55 text-sm font-semibold">Réalisées</p>
 <p class="font-serif text-4xl text-dokun-green mt-2" x-text="stats.completed">0</p>
 </div>
 </div>

 {{-- Tabs --}}
 <div class="bg-white rounded-2xl border border-black/5 overflow-hidden">
 <div class="flex border-b border-black/5 overflow-x-auto">
 @php
 $tabs = [
 ['key' => 'reservations', 'icon' => '', 'label' => 'Réservations'],
 ['key' => 'gallery', 'icon' => '', 'label' => 'Ma Galerie'],
 ['key' => 'voice', 'icon' => '', 'label' => 'Ma Voix'],
 ['key' => 'profile', 'icon' => '', 'label' => 'Mon Profil'],
 ];
 @endphp
 @foreach($tabs as $tab)
 <button
 @click="activeTab = '{{ $tab['key'] }}'"
 :class="activeTab === '{{ $tab['key'] }}' ? 'border-dokun-green text-dokun-green' : 'border-transparent text-dokun-charcoal/50 hover:text-dokun-charcoal/80'"
 class="flex items-center gap-2 px-5 py-4 text-sm font-bold border-b-2 whitespace-nowrap transition"
 >
 <span>{{ $tab['icon'] }}</span>
 <span class="hidden sm:inline">{{ $tab['label'] }}</span>
 </button>
 @endforeach
 </div>

 {{-- ═══════════════════════════════════════════════════════ --}}
 {{-- Tab 1: Reservations (AJAX-filtered tabs) --}}
 {{-- ═══════════════════════════════════════════════════════ --}}
 <div x-show="activeTab === 'reservations'" x-cloak>
 {{-- Filter sub-tabs --}}
 <div class="flex border-b border-black/5 overflow-x-auto px-2">
 @foreach([
 ['key' => 'all', 'label' => 'Toutes'],
 ['key' => 'pending', 'label' => 'À répondre'],
 ['key' => 'accepted', 'label' => 'À préparer'],
 ['key' => 'completed','label' => 'Réalisées'],
 ['key' => 'rejected', 'label' => 'Rejetées'],
 ] as $f)
 <button
 @click="resFilter = '{{ $f['key'] }}'"
 :class="resFilter === '{{ $f['key'] }}' ? 'border-dokun-green text-dokun-green' : 'border-transparent text-dokun-charcoal/50 hover:text-dokun-charcoal/80'"
 class="flex items-center gap-2 px-4 py-3 text-xs font-bold border-b-2 whitespace-nowrap transition"
 >
 <span>{{ $f['label'] }}</span>
 <span
 x-show="'{{ $f['key'] }}' !== 'all'"
 :class="resFilter === '{{ $f['key'] }}' ? 'bg-dokun-green text-white' : 'bg-dokun-charcoal/10 text-dokun-charcoal/50'"
 class="inline-flex items-center justify-center min-w-[20px] h-5 px-1.5 rounded-full text-[10px] font-bold transition"
 x-text="resCount('{{ $f['key'] }}')"
 ></span>
 </button>
 @endforeach
 </div>

 {{-- Reservation cards --}}
 <div class="divide-y">
 <template x-for="(res, idx) in filteredReservations" :key="res.id">
 <article class="p-6 flex flex-col md:flex-row md:items-center gap-5 justify-between">
 <div class="flex-1 min-w-0">
 <div class="flex items-center gap-2.5 mb-1.5">
 <h3 class="font-bold text-dokun-charcoal" x-text="res.experience ? res.experience.title : res.experience_type"></h3>
 <span
 :class="{
 'bg-amber-50 text-amber-700 border-amber-200': res.status === 'pending',
 'bg-emerald-50 text-emerald-700 border-emerald-200': res.status === 'accepted',
 'bg-blue-50 text-blue-700 border-blue-200': res.status === 'completed',
 'bg-red-50 text-red-700 border-red-200': res.status === 'rejected'
 }"
 class="inline-flex items-center gap-1 px-2 py-0.5 font-bold text-[10px] rounded-full border"
 >
 <span class="w-1 h-1 rounded-full"
 :class="{
 'bg-amber-500': res.status === 'pending',
 'bg-emerald-500': res.status === 'accepted',
 'bg-blue-500': res.status === 'completed',
 'bg-red-500': res.status === 'rejected'
 }"
 ></span>
 <span x-text="statusLabel(res.status)"></span>
 </span>
 </div>
 <p class="text-sm text-dokun-charcoal/60">
 <span x-text="res.visitor_name"></span> ·
 <span x-text="formatDate(res.requested_date)"></span> ·
 <span x-text="res.guests_count + ' personne(s)'"></span>
 </p>
 <p class="text-sm text-dokun-charcoal/60" x-text="res.visitor_phone"></p>
 <p x-show="res.message" class="text-sm text-dokun-charcoal/45 mt-1 italic" x-text="res.message"></p>
 </div>

 {{-- Action buttons --}}
 <div class="flex gap-2 flex-shrink-0" x-show="res.status === 'pending'">
 <button
 @click="updateStatus(res, 'accepted')"
 :disabled="res._updating"
 class="flex items-center gap-1.5 px-4 py-2 bg-emerald-500 hover:bg-emerald-600 text-white rounded-xl font-bold text-sm transition shadow-sm disabled:opacity-50"
 >
 <span></span> Accepter
 </button>
 <button
 @click="updateStatus(res, 'rejected')"
 :disabled="res._updating"
 class="flex items-center gap-1.5 px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-xl font-bold text-sm transition shadow-sm disabled:opacity-50"
 >
 <span></span> Refuser
 </button>
 </div>
 <div class="flex gap-2 flex-shrink-0" x-show="res.status === 'accepted'">
 <button
 @click="updateStatus(res, 'completed')"
 :disabled="res._updating"
 class="flex items-center gap-1.5 px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-xl font-bold text-sm transition shadow-sm disabled:opacity-50"
 >
 <span></span> Terminée
 </button>
 </div>
 </article>
 </template>

 <div x-show="filteredReservations.length === 0" class="p-10 text-center text-dokun-charcoal/55">
 Aucune réservation dans cette catégorie.
 </div>
 </div>
 </div>

 {{-- Tab 2: Gallery --}}
 <div x-show="activeTab === 'gallery'" x-cloak>
 {{-- Upload Zone --}}
 <div class="p-6">
 <form
 id="gallery-form"
 action="{{ route('artisan-space.media.store') }}"
 method="POST"
 enctype="multipart/form-data"
 class="relative"
 >
 @csrf
 <input type="file" name="file" id="gallery-file-input" accept="image/*" class="hidden" x-ref="galleryInput" @change="handleGalleryUpload($event)">

 <div
 @click="$refs.galleryInput.click()"
 @dragover.prevent="dragOver = true"
 @dragleave.prevent="dragOver = false"
 @drop.prevent="dragOver = false; handleGalleryDrop($event)"
 :class="dragOver ? 'border-dokun-green bg-dokun-green/5' : 'border-dokun-charcoal/15 hover:border-dokun-gold'"
 class="border-2 border-dashed rounded-2xl p-10 text-center cursor-pointer transition"
 >
 <div x-show="!uploading">
 <div class="text-4xl mb-3"></div>
 <p class="font-bold text-dokun-charcoal/70">Glissez une photo ici ou cliquez pour sélectionner</p>
 <p class="text-sm text-dokun-charcoal/45 mt-1">JPEG, PNG, GIF ou WebP · Max 5 Mo</p>
 </div>
 <div x-show="uploading" class="flex items-center justify-center gap-3">
 <div class="w-5 h-5 border-2 border-dokun-green border-t-transparent rounded-full animate-spin"></div>
 <span class="text-dokun-charcoal/60 font-semibold">Envoi en cours…</span>
 </div>
 </div>
 </form>
 </div>

 @php
 $galleryMedia = $artisan->media()->where('type', 'image')->latest()->get();
 @endphp

 {{-- Gallery Grid --}}
 <div x-show="galleryPhotos.length > 0" class="px-6 pb-6">
 <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
 <template x-for="(photo, index) in galleryPhotos" :key="photo.id">
 <div class="relative group rounded-2xl overflow-hidden aspect-square bg-dokun-charcoal/5">
 <img :src="photo.url" alt="" class="w-full h-full object-cover">
 <div class="absolute inset-0 bg-black/0 group-hover:bg-black/40 transition flex items-center justify-center opacity-0 group-hover:opacity-100">
 <button
 @click="confirmDeletePhoto(photo.id, index)"
 class="bg-red-500 text-white w-10 h-10 rounded-full flex items-center justify-center font-bold text-lg shadow-lg hover:bg-red-600 transition"
 ></button>
 </div>
 </div>
 </template>
 </div>
 </div>

 {{-- Empty State --}}
 <div x-show="galleryPhotos.length === 0 && !uploading" class="px-6 pb-10">
 @if($galleryMedia->isEmpty())
 <div class="text-center py-12 text-dokun-charcoal/40">
 <div class="text-5xl mb-4"></div>
 <p class="font-semibold text-lg">Aucune photo</p>
 <p class="text-sm mt-1">Ajoutez des photos de votre atelier et de vos créations</p>
 </div>
 @endif
 </div>
 </div>

 {{-- Tab 3: Voice --}}
 <div x-show="activeTab === 'voice'" x-cloak>
 {{-- Recorder --}}
 <div class="p-6 border-b border-black/5">
 <div class="flex flex-col sm:flex-row items-center gap-4">
 {{-- Record Button --}}
 <button
 @click="toggleRecording()"
 :class="isRecording ? 'bg-red-500 hover:bg-red-600 animate-pulse' : 'bg-dokun-green hover:bg-dokun-green/90'"
 class="flex items-center gap-3 px-6 py-3 rounded-xl text-white font-bold text-sm transition w-full sm:w-auto justify-center"
 >
 <span class="relative flex h-3 w-3" x-show="isRecording">
 <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-300 opacity-75"></span>
 <span class="relative inline-flex rounded-full h-3 w-3 bg-white"></span>
 </span>
 <span x-text="isRecording ? 'Arrêter l\'enregistrement' : ' Enregistrer'"></span>
 </button>

 {{-- Upload existing file --}}
 <input type="file" id="voice-file-input" accept="audio/*" class="hidden" x-ref="voiceInput" @change="handleVoiceUpload($event)">
 <button
 @click="$refs.voiceInput.click()"
 class="border border-dokun-charcoal/15 text-dokun-charcoal/70 px-5 py-3 rounded-xl font-bold text-sm hover:border-dokun-gold hover:text-dokun-charcoal transition w-full sm:w-auto justify-center flex items-center gap-2"
 >
 Choisir un fichier audio
 </button>
 </div>

 {{-- Recording timer --}}
 <div x-show="isRecording" class="mt-4 flex items-center gap-2 text-red-500 font-mono text-sm">
 <span class="w-2 h-2 bg-red-500 rounded-full animate-pulse"></span>
 <span x-text="recordingTime"></span>
 </div>
 </div>

 {{-- Voice archives --}}
 <div class="p-6">
 <h3 class="font-bold text-dokun-charcoal/80 mb-4">Archives vocales</h3>

 <div x-show="voiceUploading" class="mb-4 flex items-center gap-3 text-dokun-gold font-semibold text-sm">
 <div class="w-4 h-4 border-2 border-dokun-gold border-t-transparent rounded-full animate-spin"></div>
 Envoi en cours…
 </div>

 <div x-show="voiceArchives.length > 0" class="space-y-3">
 <template x-for="(archive, index) in voiceArchives" :key="index">
 <div class="flex items-center gap-4 p-4 bg-dokun-ivory rounded-xl">
 <button
 @click="playArchive(archive.audio_url, $event)"
 class="w-10 h-10 rounded-full bg-dokun-green text-white flex items-center justify-center flex-shrink-0 hover:bg-dokun-green/90 transition"
 >
 <span x-text="playingIndex === index ? '⏸' : '▶'"></span>
 </button>
 <div class="flex-1 min-w-0">
 <input
 type="text"
 :value="archive.title || 'Archive vocale'"
 @blur="updateArchiveTitle(archive.id, archive, $event.target.value)"
 @keydown.enter="$event.target.blur()"
 class="font-bold text-sm bg-transparent border-b border-transparent hover:border-dokun-charcoal/20 focus:border-dokun-green outline-none w-full transition px-0 py-0"
 >
 <p class="text-xs text-dokun-charcoal/50 mt-0.5">
 <span x-text="archive.language || 'fon'"></span>
 <span x-show="archive.duration_seconds"> · <span x-text="formatDuration(archive.duration_seconds)"></span></span>
 </p>
 </div>
 <button
 @click="deleteArchive(archive.id, index)"
 class="w-8 h-8 rounded-lg text-red-400 hover:bg-red-50 hover:text-red-600 flex items-center justify-center flex-shrink-0 transition text-sm"
 title="Supprimer"
 ></button>
 </div>
 </template>
 </div>

 <div x-show="voiceArchives.length === 0 && !voiceLoading" class="text-center py-10 text-dokun-charcoal/40">
 <div class="text-4xl mb-3"></div>
 <p class="font-semibold">Aucune archive vocale</p>
 <p class="text-sm mt-1">Enregistrez votre voix ou téléchargez un fichier audio</p>
 </div>
 </div>
 </div>

 {{-- Tab 4: Profile --}}
 <div x-show="activeTab === 'profile'" x-cloak>
 <div class="p-8">
 <div class="text-center mb-6">
 <h3 class="font-serif text-2xl text-dokun-green">Mon profil artisan</h3>
 <p class="text-dokun-charcoal/55 mt-1 max-w-md mx-auto text-sm">Modifiez vos informations, vos savoir-faire, votre description et votre photo de profil.</p>
 </div>

 {{-- Photo de profil --}}
 <div class="max-w-sm mx-auto mb-8">
 <div class="relative group mx-auto w-40 h-40 rounded-full overflow-hidden bg-dokun-charcoal/5 border-4 border-white shadow-lg">
 @if($artisan && $artisan->photo_path)
 <img src="{{ asset('storage/' . $artisan->photo_path) }}" alt="" class="w-full h-full object-cover" id="profile-photo-preview">
 @else
 <div class="w-full h-full flex items-center justify-center text-5xl text-dokun-charcoal/20" id="profile-photo-preview-placeholder"></div>
 <img src="" alt="" class="w-full h-full object-cover hidden" id="profile-photo-preview">
 @endif
 <label class="absolute inset-0 bg-black/0 group-hover:bg-black/40 transition flex items-center justify-center cursor-pointer opacity-0 group-hover:opacity-100">
 <span class="bg-white text-dokun-green px-3 py-1.5 rounded-lg font-bold text-xs shadow-lg"> Modifier</span>
 <input
 type="file"
 accept="image/jpeg,image/png,image/webp,image/gif"
 class="hidden"
 @change="uploadProfilePhoto($event)"
 >
 </label>
 </div>
 <div x-show="photoUploading" class="mt-3 flex items-center justify-center gap-2 text-dokun-gold text-sm font-semibold">
 <div class="w-4 h-4 border-2 border-dokun-gold border-t-transparent rounded-full animate-spin"></div>
 Envoi…
 </div>
 @if($artisan && $artisan->pending_photo_path && $artisan->pending_photo_path !== $artisan->photo_path)
 <p class="mt-2 text-xs text-amber-600 font-semibold text-center">⏳ En attente de validation</p>
 @endif
 <div x-show="photoSuccess" class="mt-2 text-xs text-emerald-600 font-semibold text-center" x-text="photoSuccess"></div>
 </div>

 <div class="text-center">
 <a href="{{ route('artisan-space.edit-profile') }}" class="inline-block bg-dokun-green text-white px-6 py-3 rounded-xl font-bold hover:bg-dokun-green/90 transition">Modifier mon profil</a>
 </div>
 </div>
 </div>
 </div>

 <script>
 function artisanDashboard() {
 const allReservations = @js($reservations->map(fn($r) => [
 'id' => $r->id,
 'status' => $r->status,
 'visitor_name' => $r->visitor_name,
 'visitor_phone' => $r->visitor_phone,
 'visitor_email' => $r->visitor_email,
 'requested_date' => $r->requested_date,
 'guests_count' => $r->guests_count,
 'experience_type' => $r->experience_type,
 'message' => $r->message,
 'experience' => $r->experience ? ['id' => $r->experience->id, 'title' => $r->experience->title] : null,
 ]));

 return {
 activeTab: 'reservations',
 resFilter: 'all',
 reservations: allReservations,
 stats: {
 pending: allReservations.filter(r => r.status === 'pending').length,
 accepted: allReservations.filter(r => r.status === 'accepted').length,
 completed: allReservations.filter(r => r.status === 'completed').length,
 },
 dragOver: false,
 uploading: false,
 galleryPhotos: @js($artisan ? $artisan->media()->where('type', 'image')->latest()->get()->map(fn($m) => ['id' => $m->id, 'url' => $m->url]) : []),
 isRecording: false,
 recordingTime: '00:00',
 recordingTimer: null,
 recordingSeconds: 0,
 voiceArchives: [],
 voiceLoading: false,
 voiceUploading: false,
 playingIndex: null,
 currentAudio: null,
 photoUploading: false,
 photoSuccess: '',

 get filteredReservations() {
 if (this.resFilter === 'all') return this.reservations;
 return this.reservations.filter(r => r.status === this.resFilter);
 },

 resCount(status) {
 return this.reservations.filter(r => r.status === status).length;
 },

 statusLabel(status) {
 return {
 pending: 'En attente',
 accepted: 'Acceptée',
 completed: 'Réalisée',
 rejected: 'Rejetée',
 }[status] || status;
 },

 formatDate(dateStr) {
 if (!dateStr) return '';
 const d = new Date(dateStr);
 return d.toLocaleDateString('fr-FR', { weekday: 'long', day: 'numeric', month: 'long' });
 },

 async updateStatus(res, newStatus) {
 res._updating = true;
 try {
 const res2 = await fetch('{{ route("artisan-space.reservations.status-json", ":id") }}'.replace(':id', res.id), {
 method: 'PATCH',
 headers: {
 'X-CSRF-TOKEN': '{{ csrf_token() }}',
 'Accept': 'application/json',
 'Content-Type': 'application/json',
 },
 body: JSON.stringify({ status: newStatus }),
 });
 const data = await res2.json();
 if (data.status === 'success') {
 res.status = data.reservation.status;
 this.recalcStats();
 }
 } catch (e) {
 console.error('Status update failed', e);
 }
 res._updating = false;
 },

 recalcStats() {
 this.stats.pending = this.reservations.filter(r => r.status === 'pending').length;
 this.stats.accepted = this.reservations.filter(r => r.status === 'accepted').length;
 this.stats.completed = this.reservations.filter(r => r.status === 'completed').length;
 },

 init() {
 this.loadVoiceArchives();
 },

 async loadVoiceArchives() {
 if (!{{ $artisan ? $artisan->id : 'null' }}) return;
 this.voiceLoading = true;
 try {
 const res = await fetch(`{{ url('/features/voice/' . ($artisan?->id ?? 0) . '/archives') }}`);
 const data = await res.json();
 this.voiceArchives = data.archives || [];
 } catch (e) {
 console.error('Failed to load voice archives', e);
 }
 this.voiceLoading = false;
 },

 handleGalleryDrop(event) {
 const file = event.dataTransfer.files[0];
 if (file && file.type.startsWith('image/')) {
 this.uploadGalleryFile(file);
 }
 },

 handleGalleryUpload(event) {
 const file = event.target.files[0];
 if (file) this.uploadGalleryFile(file);
 },

 async uploadGalleryFile(file) {
 this.uploading = true;
 const formData = new FormData();
 formData.append('file', file);
 formData.append('_token', '{{ csrf_token() }}');

 try {
 const res = await fetch('{{ route("artisan-space.media.store") }}', {
 method: 'POST',
 body: formData,
 headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
 });
 const data = await res.json();
 if (data.status === 'success') {
 this.galleryPhotos.unshift({ id: data.media.id, url: data.url });
 }
 } catch (e) {
 console.error('Upload failed', e);
 }
 this.uploading = false;
 document.getElementById('gallery-file-input').value = '';
 },

 async confirmDeletePhoto(id, index) {
 if (!confirm('Supprimer cette photo ?')) return;
 try {
 await fetch('{{ route("artisan-space.media.destroy", ":id") }}'.replace(':id', id), {
 method: 'DELETE',
 headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json', 'Content-Type': 'application/json' }
 });
 this.galleryPhotos.splice(index, 1);
 } catch (e) {
 console.error('Delete failed', e);
 }
 },

 async toggleRecording() {
 if (this.isRecording) {
 this.stopRecording();
 } else {
 this.startRecording();
 }
 },

 async startRecording() {
 try {
 const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
 this.mediaRecorder = new MediaRecorder(stream, { mimeType: 'audio/webm' });
 this.audioChunks = [];

 this.mediaRecorder.ondataavailable = (e) => {
 if (e.data.size > 0) this.audioChunks.push(e.data);
 };

 this.mediaRecorder.onstop = async () => {
 const blob = new Blob(this.audioChunks, { type: 'audio/webm' });
 const file = new File([blob], 'enregistrement_' + Date.now() + '.webm', { type: 'audio/webm' });
 stream.getTracks().forEach(t => t.stop());
 await this.uploadVoiceFile(file);
 };

 this.mediaRecorder.start();
 this.isRecording = true;
 this.recordingSeconds = 0;
 this.recordingTimer = setInterval(() => {
 this.recordingSeconds++;
 const m = String(Math.floor(this.recordingSeconds / 60)).padStart(2, '0');
 const s = String(this.recordingSeconds % 60).padStart(2, '0');
 this.recordingTime = `${m}:${s}`;
 }, 1000);
 } catch (e) {
 alert('Impossible d\'accéder au micro. Vérifiez les permissions de votre navigateur.');
 }
 },

 stopRecording() {
 if (this.mediaRecorder && this.isRecording) {
 this.mediaRecorder.stop();
 this.isRecording = false;
 clearInterval(this.recordingTimer);
 }
 },

 handleVoiceUpload(event) {
 const file = event.target.files[0];
 if (file) this.uploadVoiceFile(file);
 },

 async uploadVoiceFile(file) {
 this.voiceUploading = true;
 const formData = new FormData();
 formData.append('audio', file);
 formData.append('title', 'Archive vocale');
 formData.append('language', 'fon');

 try {
 const artisanId = {{ $artisan?->id ?? 'null' }};
 const res = await fetch(`{{ url('/mon-atelier/voice/') }}/${artisanId}/upload`, {
 method: 'POST',
 body: formData,
 headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
 });
 if (!res.ok) {
 if (res.status === 413) {
 alert('Fichier trop volumineux. Taille maximale : 30 Mo. Utilisez la commande : php -c php.ini artisan serve');
 } else {
 const err = await res.json().catch(() => ({ message: 'Erreur lors de l\'envoi.' }));
 alert(err.message || 'Erreur lors de l\'envoi du fichier audio.');
 }
 return;
 }
 const data = await res.json();
 if (data.status === 'success') {
 this.voiceArchives.unshift({
 id: data.archive_id,
 audio_url: data.audio_url,
 title: data.title,
 language: 'fon',
 duration_seconds: 0,
 });
 }
 } catch (e) {
 console.error('Voice upload failed', e);
 alert('Erreur réseau lors de l\'envoi du fichier audio.');
 } finally {
 this.voiceUploading = false;
 const input = document.getElementById('voice-file-input');
 if (input) input.value = '';
 }
 },

 playArchive(url, event) {
 if (this.currentAudio) {
 this.currentAudio.pause();
 this.currentAudio = null;
 if (this.playingIndex !== null) {
 this.playingIndex = null;
 return;
 }
 }
 const audio = new Audio(url);
 this.currentAudio = audio;
 const idx = this.voiceArchives.findIndex(a => a.audio_url === url);
 this.playingIndex = idx;
 audio.play();
 audio.onended = () => { this.playingIndex = null; this.currentAudio = null; };
 },

 formatDuration(seconds) {
 if (!seconds) return '';
 const m = Math.floor(seconds / 60);
 const s = seconds % 60;
 return `${m}:${String(s).padStart(2, '0')}`;
 },

 async deleteArchive(id, index) {
 if (!confirm('Supprimer cette archive vocale ?')) return;
 try {
 const res = await fetch(`{{ url('/mon-atelier/voice/') }}/${id}`, {
 method: 'DELETE',
 headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json', 'Content-Type': 'application/json' }
 });
 if (res.ok) {
 this.voiceArchives.splice(index, 1);
 } else {
 alert('Erreur lors de la suppression.');
 }
 } catch (e) {
 console.error('Delete archive failed', e);
 alert('Erreur réseau lors de la suppression.');
 }
 },

 async updateArchiveTitle(id, archive, newTitle) {
 const trimmed = (newTitle || '').trim();
 if (trimmed === archive.title || (!trimmed && !archive.title)) return;
 try {
 const res = await fetch(`{{ url('/mon-atelier/voice/') }}/${id}`, {
 method: 'PATCH',
 headers: {
 'X-CSRF-TOKEN': '{{ csrf_token() }}',
 'Accept': 'application/json',
 'Content-Type': 'application/json',
 },
 body: JSON.stringify({ title: trimmed || 'Archive vocale' }),
 });
 if (res.ok) {
 const data = await res.json();
 archive.title = data.archive.title;
 }
 } catch (e) {
 console.error('Update title failed', e);
 }
 },

 async uploadProfilePhoto(event) {
 const file = event.target.files[0];
 if (!file) return;
 this.photoUploading = true;
 this.photoSuccess = '';
 const formData = new FormData();
 formData.append('photo', file);
 try {
 const res = await fetch('{{ route("artisan-space.photo.upload") }}', {
 method: 'POST',
 body: formData,
 headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
 });
 if (!res.ok) {
 const err = await res.json().catch(() => ({ message: 'Erreur lors de l\'envoi.' }));
 alert(err.message || 'Erreur lors de l\'envoi de la photo.');
 return;
 }
 const data = await res.json();
 if (data.status === 'success') {
 const img = document.getElementById('profile-photo-preview');
 const placeholder = document.getElementById('profile-photo-preview-placeholder');
 if (img) { img.src = data.url; img.classList.remove('hidden'); }
 if (placeholder) placeholder.classList.add('hidden');
 this.photoSuccess = 'Photo envoyée !';
 setTimeout(() => { this.photoSuccess = ''; }, 3000);
 }
 } catch (e) {
 console.error('Photo upload failed', e);
 alert('Erreur réseau lors de l\'envoi de la photo.');
 } finally {
 this.photoUploading = false;
 event.target.value = '';
 }
 },
 }
 }
 </script>

 @endif
 </div>
 </div>
</x-app-layout>
