<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
 <meta charset="utf-8">
 <meta name="viewport" content="width=device-width, initial-scale=1">
 <meta name="csrf-token" content="{{ csrf_token() }}">
 <title>ƉƆKUN Voice — {{ $artisan->first_name }} · ƉƆKUN</title>
 <link rel="preconnect" href="https://fonts.bunny.net">
 <link href="https://fonts.bunny.net/css?family=dm-serif-display:400|manrope:400,500,600,700,800&display=swap" rel="stylesheet"/>
 <script src="https://cdn.tailwindcss.com"></script>
 <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
 <script>
 tailwind.config = {
 theme: { extend: {
 colors: { dokun: { green:'#064E3B', gold:'#C99424', ivory:'#F8F6F0', charcoal:'#17201D' } },
 fontFamily: { sans:['Manrope','sans-serif'], serif:['"DM Serif Display"','serif'] },
 backgroundImage: { 'dokun-pattern':"url('data:image/svg+xml,%3Csvg width=\\'20\\' height=\\'20\\' viewBox=\\'0 0 20 20\\' xmlns=\\'http://www.w3.org/2000/svg\\'%3E%3Cg fill=\\'%23064E3B\\' fill-opacity=\\'0.05\\' fill-rule=\\'evenodd\\'%3E%3Ccircle cx=\\'3\\' cy=\\'3\\' r=\\'3\\'/%3E%3Ccircle cx=\\'13\\' cy=\\'13\\' r=\\'3\\'/%3E%3C/g%3E%3C/svg%3E')" }
 }}
 }
 </script>
 <style>
 body{font-family:'Manrope',sans-serif;}
 h1,h2,h3,h4,.font-serif{font-family:'DM Serif Display',serif;}
 @keyframes micPulse{0%,100%{box-shadow:0 0 0 0 rgba(239,68,68,.5);}50%{box-shadow:0 0 0 12px rgba(239,68,68,0);}}
 .mic-recording{animation:micPulse 1.2s infinite;}
 </style>
</head>
<body class="antialiased bg-dokun-ivory text-dokun-charcoal bg-dokun-pattern">

@include('partials.navbar', ['active' => 'artisans'])

<main class="pt-32 pb-24 max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

 <nav class="mb-10 text-sm font-semibold text-dokun-charcoal/50 flex items-center gap-2 flex-wrap">
 <a href="{{ route('home') }}" class="hover:text-dokun-gold transition-colors">Accueil</a>
 <svg class="w-3.5 h-3.5 text-dokun-charcoal/30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
 <a href="{{ route('artisans.index') }}" class="hover:text-dokun-gold transition-colors">Artisans</a>
 <svg class="w-3.5 h-3.5 text-dokun-charcoal/30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
 <a href="{{ route('artisans.show', $artisan->id) }}" class="hover:text-dokun-gold transition-colors">{{ $artisan->first_name }}</a>
 <svg class="w-3.5 h-3.5 text-dokun-charcoal/30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
 <span class="text-dokun-green">Voix</span>
 </nav>

 <div class="mb-10">
 <h1 class="text-3xl md:text-5xl font-serif text-dokun-green tracking-tight mb-4 leading-tight">
 @if(app()->getLocale()==='en')
 <x-icon name="volume" class="inline-block w-8 h-8 mr-2 text-dokun-green align-text-bottom" /> ƉƆKUN Voice — The voice as a living archive
 @else
 <x-icon name="volume" class="inline-block w-8 h-8 mr-2 text-dokun-green align-text-bottom" /> ƉƆKUN Voice — La voix comme archive vivante
 @endif
 </h1>
 <p class="text-dokun-charcoal/70 text-[15px] leading-relaxed max-w-2xl">
 @if(app()->getLocale()==='en')
 Listen to {{ $artisan->first_name }} talk about their craft in their mother tongue. Each voice archive comes with
 a Fon/Gun transcription and an English translation, preserving living heritage for future generations.
 @else
 Écoutez {{ $artisan->first_name }} parler de son art dans sa langue maternelle. Chaque archive vocale est accompagnée
 d'une transcription en Fon/Gun et d'une traduction française, préservant ainsi un patrimoine vivant pour les générations futures.
 @endif
 </p>
 </div>

 <div class="bg-dokun-charcoal rounded-2xl border border-dokun-gold/20 shadow-lg overflow-hidden">
 @auth
 @if(Auth::id() === $artisan->user_id || Auth::user()->role === 'admin')
 <div class="p-6 border-b border-white/10 bg-white/5">
 <div class="flex items-center gap-4">
 <button id="voice-record-btn" onclick="voiceToggleRecord()" class="relative w-14 h-14 rounded-full bg-dokun-gold text-white flex items-center justify-center hover:bg-yellow-500 transition shadow-lg shadow-dokun-gold/30 flex-shrink-0">
 <svg class="w-6 h-6" id="voice-record-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"/></svg>
 <div class="hidden absolute inset-0 rounded-full bg-red-500 items-center justify-center" id="voice-record-stop">
 <div class="w-4 h-4 bg-white rounded-sm"></div>
 </div>
 </button>
 <div class="flex-1">
 <p class="text-white/80 text-sm font-semibold" id="voice-record-label">{{ __('app.feature_voice_record_label') }}</p>
 <p class="text-white/40 text-xs" id="voice-record-status">{{ __('app.feature_voice_record_status') }}</p>
 </div>
 <div class="text-dokun-gold font-mono text-sm font-bold hidden" id="voice-record-timer">00:00</div>
 </div>
 <div class="mt-3 hidden" id="voice-upload-status">
 <div class="flex items-center gap-2 text-dokun-gold text-xs">
 <svg class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
 {{ __('app.feature_voice_transcribing') }}
 </div>
 </div>
 </div>
 @endif
 @endauth

 <div class="p-6">
 <p class="text-white/70 text-sm mb-4">
 @if(app()->getLocale()==='en')
 Listen to {{ $artisan->first_name }} talk about their art in their mother tongue.
 @else
 Écoutez {{ $artisan->first_name }} parler de son art dans sa langue maternelle.
 @endif
 </p>

 <div id="voice-archives-list" class="space-y-2 min-h-[60px]">
 <p class="text-white/40 text-sm text-center py-4" id="voice-loading">
 <svg class="w-5 h-5 mx-auto animate-spin mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
 {{ __('app.feature_voice_loading') }}
 </p>
 </div>

 <div id="voice-player" class="hidden mt-5 space-y-3">
 <audio id="voice-audio" controls class="w-full h-10 rounded-lg"></audio>
 <div class="bg-white/5 rounded-xl p-4 border border-white/10 space-y-3">
 <div>
 <p class="text-dokun-gold text-xs font-bold mb-1 uppercase tracking-wider">{{ __('app.feature_voice_transcription') }} (Fon/Gun)</p>
 <p id="voice-transcription" class="text-white/90 text-sm italic leading-relaxed"></p>
 </div>
 <div class="h-px bg-white/10"></div>
 <div>
 @if(app()->getLocale()==='en')
 <p class="text-dokun-gold text-xs font-bold mb-1 uppercase tracking-wider">English translation</p>
 @else
 <p class="text-dokun-gold text-xs font-bold mb-1 uppercase tracking-wider">Traduction française</p>
 @endif
 <p id="voice-translation" class="text-white/75 text-sm leading-relaxed"></p>
 </div>
 </div>
 </div>
 </div>
 </div>

</main>

@include('partials.footer')

<script>
document.addEventListener('DOMContentLoaded', function () {
 const CSRF = document.querySelector('meta[name="csrf-token"]').content;
 const ARTISAN_ID = {{ $artisan->id }};
 const IS_AUTH = {{ Auth::check() ? 'true' : 'false' }};
 const IS_OWNER = {{ (Auth::check() && (Auth::id() === $artisan->user_id || Auth::user()->role === 'admin')) ? 'true' : 'false' }};

 const ui = {
 noArchives: {!! json_encode(app()->getLocale() === 'en' ? 'No voice archives available yet.' : 'Aucune archive vocale disponible pour le moment.') !!},
 loadError: {!! json_encode(app()->getLocale() === 'en' ? 'Unable to load archives.' : 'Impossible de charger les archives.') !!},
 noTrans: {!! json_encode(app()->getLocale() === 'en' ? '(transcription unavailable)' : '(transcription indisponible)') !!},
 noTranslFr: {!! json_encode('(traduction indisponible)') !!},
 noTranslEn: {!! json_encode('(translation unavailable)') !!},
 recording: {!! json_encode(app()->getLocale() === 'en' ? 'Recording...' : 'Enregistrement en cours...') !!},
 stopToFinish: {!! json_encode(app()->getLocale() === 'en' ? 'Press again to stop' : 'Appuyez à nouveau pour arrêter') !!},
 recordLabel: {!! json_encode(__('app.feature_voice_record_label')) !!},
 recordStatus: {!! json_encode(__('app.feature_voice_record_status')) !!},
 micDenied: {!! json_encode(app()->getLocale() === 'en' ? 'Microphone access denied.' : 'Accès au microphone refusé.') !!},
 uploadError: {!! json_encode(app()->getLocale() === 'en' ? 'Error during upload. Try again.' : 'Erreur lors de l\'envoi. Réessayez.') !!},
 };

 const archivesList = document.getElementById('voice-archives-list');
 const voiceLoadingEl = document.getElementById('voice-loading');
 const voicePlayer = document.getElementById('voice-player');
 const voiceAudio = document.getElementById('voice-audio');
 const voiceTrans = document.getElementById('voice-transcription');
 const voiceTransl = document.getElementById('voice-translation');

 const abortCtrl = new AbortController();
 const fetchTimer = setTimeout(() => abortCtrl.abort(), 15000);
 fetch(@json(route('features.voice.archives', $artisan->id)), { signal: abortCtrl.signal })
 .then(r => { clearTimeout(fetchTimer); return r.json(); })
 .then(data => {
 voiceLoadingEl && voiceLoadingEl.remove();
 if (!data.archives || data.archives.length === 0) {
 archivesList.innerHTML = `<p class="text-white/40 text-sm text-center py-4">${ui.noArchives}</p>`;
 return;
 }
 archivesList.innerHTML = '';
 data.archives.forEach((arc, i) => {
 const dur = arc.duration_seconds > 0
 ? `${Math.floor(arc.duration_seconds/60)}:${String(arc.duration_seconds%60).padStart(2,'0')}`
 : '';
 const btn = document.createElement('button');
 btn.className = 'w-full flex items-center gap-3 bg-white/5 hover:bg-white/10 border border-white/10 rounded-xl px-4 py-3 transition text-left group';
 btn.innerHTML = `
 <div class="w-10 h-10 rounded-full bg-dokun-gold/80 text-white flex items-center justify-center flex-shrink-0 group-hover:bg-dokun-gold transition">
 <svg class="w-4 h-4 ml-0.5" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
 </div>
 <div class="min-w-0 flex-1">
 <p class="text-white/90 text-sm font-semibold truncate">Archive #${i+1}</p>
 <p class="text-white/40 text-xs">${arc.language ? arc.language.toUpperCase() : 'FON'}${dur ? ' · ' + dur : ''}</p>
 </div>
 ${arc.transcription ? '<svg class="w-4 h-4 text-white/30 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>' : ''}
 `;
 btn.addEventListener('click', () => {
 voiceAudio.src = arc.audio_url;
 const isEn = {{ app()->getLocale() === 'en' ? 'true' : 'false' }};
 voiceTrans.textContent = arc.transcription || (isEn ? ui.noTrans : ui.noTrans);
 const transl = isEn ? (arc.translation_en || arc.translation_fr || ui.noTranslEn) : (arc.translation_fr || ui.noTranslFr);
 voiceTransl.textContent = transl;
 voicePlayer.classList.remove('hidden');
 voiceAudio.play();
 archivesList.querySelectorAll('button').forEach(b => b.classList.remove('ring-2','ring-dokun-gold'));
 btn.classList.add('ring-2','ring-dokun-gold');
 });
 archivesList.appendChild(btn);
 });
 })
 .catch(() => {
 clearTimeout(fetchTimer);
 if (voiceLoadingEl) voiceLoadingEl.remove();
 archivesList.innerHTML = `<p class="text-white/40 text-sm text-center py-4">${ui.loadError}</p>`;
 });

 if (!IS_OWNER) return;

 let voiceMediaRecorder = null;
 let voiceChunks = [];
 let voiceTimer = null;
 let voiceSeconds = 0;

 window.voiceToggleRecord = function() {
 if (voiceMediaRecorder && voiceMediaRecorder.state === 'recording') {
 voiceMediaRecorder.stop();
 return;
 }
 navigator.mediaDevices.getUserMedia({ audio: true }).then(stream => {
 voiceMediaRecorder = new MediaRecorder(stream);
 voiceChunks = [];
 voiceSeconds = 0;

 voiceMediaRecorder.ondataavailable = e => { if (e.data.size > 0) voiceChunks.push(e.data); };
 voiceMediaRecorder.onstop = () => {
 stream.getTracks().forEach(t => t.stop());
 clearInterval(voiceTimer);
 const blob = new Blob(voiceChunks, { type: 'audio/webm' });
 voiceUploadAudio(blob);
 };

 voiceMediaRecorder.start();
 document.getElementById('voice-record-icon').classList.add('hidden');
 document.getElementById('voice-record-stop').classList.remove('hidden');
 document.getElementById('voice-record-stop').classList.add('flex');
 document.getElementById('voice-record-btn').classList.add('mic-recording');
 document.getElementById('voice-record-label').textContent = ui.recording;
 document.getElementById('voice-record-status').textContent = ui.stopToFinish;
 const timerEl = document.getElementById('voice-record-timer');
 timerEl.classList.remove('hidden');
 voiceTimer = setInterval(() => {
 voiceSeconds++;
 const m = String(Math.floor(voiceSeconds / 60)).padStart(2, '0');
 const s = String(voiceSeconds % 60).padStart(2, '0');
 timerEl.textContent = m + ':' + s;
 }, 1000);
 }).catch(() => {
 alert(ui.micDenied);
 });
 };

 function voiceUploadAudio(blob) {
 const btn = document.getElementById('voice-record-btn');
 btn.classList.remove('mic-recording');
 document.getElementById('voice-record-icon').classList.remove('hidden');
 document.getElementById('voice-record-stop').classList.add('hidden');
 document.getElementById('voice-record-stop').classList.remove('flex');
 document.getElementById('voice-record-timer').classList.add('hidden');
 document.getElementById('voice-record-label').textContent = ui.recordLabel;
 document.getElementById('voice-record-status').textContent = ui.recordStatus;

 document.getElementById('voice-upload-status').classList.remove('hidden');

 const fd = new FormData();
 fd.append('audio', blob, 'voice.webm');
 fd.append('language', 'fon');

 fetch(@json(route('artisan-space.voice.upload', $artisan->id)), {
 method: 'POST',
 headers: { 'X-CSRF-TOKEN': CSRF },
 body: fd,
 })
 .then(r => r.json())
 .then(data => {
 document.getElementById('voice-upload-status').classList.add('hidden');
 if (data.status === 'success') {
 const i = archivesList.querySelectorAll('button').length + 1;
 const dur = data.duration_seconds > 0 ? Math.floor(data.duration_seconds/60) + ':' + String(data.duration_seconds%60).padStart(2,'0') : '';
 const newBtn = document.createElement('button');
 newBtn.className = 'w-full flex items-center gap-3 bg-white/5 hover:bg-white/10 border border-white/10 rounded-xl px-4 py-3 transition text-left group';
 newBtn.innerHTML = `
 <div class="w-10 h-10 rounded-full bg-dokun-gold/80 text-white flex items-center justify-center flex-shrink-0 group-hover:bg-dokun-gold transition">
 <svg class="w-4 h-4 ml-0.5" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
 </div>
 <div class="min-w-0 flex-1">
 <p class="text-white/90 text-sm font-semibold truncate">Archive #${i}</p>
 <p class="text-white/40 text-xs">FON${dur ? ' · ' + dur : ''}</p>
 </div>
 `;
 newBtn.addEventListener('click', () => {
 voiceAudio.src = data.audio_url;
 voiceTrans.textContent = data.transcription || '';
 voiceTransl.textContent = data.translation || '';
 voicePlayer.classList.remove('hidden');
 voiceAudio.play();
 });
 if (archivesList.querySelector('p')) archivesList.innerHTML = '';
 archivesList.insertBefore(newBtn, archivesList.firstChild);
 }
 })
 .catch(() => {
 document.getElementById('voice-upload-status').classList.add('hidden');
 alert(ui.uploadError);
 });
 }

});
</script>
<script>
if ('serviceWorker' in navigator) {
 navigator.serviceWorker.register('/sw.js').catch(() => {});
}
</script>
</body>
</html>
