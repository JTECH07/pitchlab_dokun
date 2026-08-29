<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
 <meta charset="utf-8">
 <meta name="viewport" content="width=device-width, initial-scale=1">
 <meta name="csrf-token" content="{{ csrf_token() }}">
 <title>ƉƆKUN Bridge — {{ $artisan->first_name }} · ƉƆKUN</title>
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
 @keyframes fadeUp{from{opacity:0;transform:translateY(10px);}to{opacity:1;transform:translateY(0);}}
 .chat-bubble{animation:fadeUp .3s ease-out;}
 .typing-dot{width:8px;height:8px;border-radius:50%;background:#064E3B;display:inline-block;animation:bounce .8s infinite alternate;}
 .typing-dot:nth-child(2){animation-delay:.15s;}
 .typing-dot:nth-child(3){animation-delay:.3s;}
 @keyframes bounce{from{transform:translateY(0);}to{transform:translateY(-8px);}}
 @keyframes micPulse{0%,100%{box-shadow:0 0 0 0 rgba(239,68,68,.5);}50%{box-shadow:0 0 0 12px rgba(239,68,68,0);}}
 .mic-recording{animation:micPulse 1.2s infinite;}
 @keyframes greenPulse{0%,100%{box-shadow:0 0 0 0 rgba(6,78,59,.3);}50%{box-shadow:0 0 0 10px rgba(6,78,59,0);}}
 .typing-pulse{animation:greenPulse 1.5s infinite;}
 .suggestion-chip{transition:all .2s ease;}
 .suggestion-chip:hover{transform:translateY(-2px);box-shadow:0 4px 12px rgba(6,78,59,.15);}
 .suggestion-chip:active{transform:scale(.97);}
 @keyframes slideUp{from{opacity:0;transform:translateY(20px);}to{opacity:1;transform:translateY(0);}}
 .slide-up{animation:slideUp .4s ease-out;}
 @keyframes starPop{0%{transform:scale(0);}50%{transform:scale(1.3);}100%{transform:scale(1);}}
 .star-pop{animation:starPop .3s ease-out forwards;}
 </style>
</head>
<body class="antialiased bg-dokun-ivory text-dokun-charcoal bg-dokun-pattern">

@include('partials.navbar', ['active' => 'artisans'])

<main class="pt-32 pb-24 max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

 <nav class="mb-10 text-sm font-semibold text-dokun-charcoal/50 flex items-center gap-2 flex-wrap">
 <a href="{{ route('home') }}" class="hover:text-dokun-gold transition-colors">{{ __('app.nav_home') }}</a>
 <svg class="w-3.5 h-3.5 text-dokun-charcoal/30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
 <a href="{{ route('artisans.index') }}" class="hover:text-dokun-gold transition-colors">{{ __('app.nav_artisans') }}</a>
 <svg class="w-3.5 h-3.5 text-dokun-charcoal/30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
 <a href="{{ route('artisans.show', $artisan->id) }}" class="hover:text-dokun-gold transition-colors">{{ $artisan->first_name }}</a>
 <svg class="w-3.5 h-3.5 text-dokun-charcoal/30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
 <span class="text-dokun-green">{{ __('app.feat_bridge_title') }}</span>
 </nav>

 <div class="mb-10 flex flex-col sm:flex-row sm:items-center gap-5">
 <div class="flex-1">
 <h1 class="text-3xl md:text-5xl font-serif text-dokun-green tracking-tight mb-4 leading-tight">
 {{ __('app.feat_bridge_title') }} — {{ $artisan->first_name }}
 </h1>
 <p class="text-dokun-charcoal/70 text-[15px] leading-relaxed max-w-2xl">
 {{ __('app.feature_bridge_intro', ['name' => $artisan->first_name]) }}
 </p>
 </div>
 <a href="{{ route('payment.confirm', $artisan->id) }}"
 class="inline-flex items-center gap-2 px-6 py-3 bg-dokun-gold text-white font-bold rounded-xl hover:bg-dokun-gold/90 active:scale-[.98] transition shadow-lg shadow-dokun-gold/20 text-sm whitespace-nowrap flex-shrink-0">
 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
 {{ __('app.feature_bridge_book') }}
 </a>
 </div>

 @guest
 <div class="bg-white rounded-2xl border border-emerald-100 shadow-lg overflow-hidden p-10 text-center">
 <div class="w-20 h-20 mx-auto mb-5 rounded-full bg-emerald-50 flex items-center justify-center">
 <svg class="w-10 h-10 text-dokun-green" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/></svg>
 </div>
 <h3 class="font-serif text-2xl text-dokun-green mb-3">{{ __('app.feature_bridge_guest_title', ['name' => $artisan->first_name]) }}</h3>
 <p class="text-dokun-charcoal/60 max-w-md mx-auto mb-6 leading-relaxed">
 {{ __('app.feature_bridge_guest_sub') }}
 </p>
 <a href="{{ route('login') }}"
 class="inline-flex items-center gap-2 px-8 py-4 bg-dokun-green text-white font-bold rounded-xl hover:bg-dokun-green/90 active:scale-[.98] transition shadow-lg shadow-dokun-green/20 text-lg">
 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
 {{ __('app.feature_bridge_guest_login') }}
 </a>
 </div>
 @else
 <div class="bg-white rounded-2xl border border-emerald-100 shadow-lg overflow-hidden">

 {{-- Header --}}
 <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4 p-5 border-b border-emerald-50 bg-gradient-to-r from-emerald-50/50 to-white">
 <div class="w-11 h-11 rounded-full bg-dokun-green text-white flex items-center justify-center flex-shrink-0 text-sm font-bold">
 {{ mb_substr($artisan->first_name, 0, 1) }}
 </div>
 <div class="flex-1 min-w-0">
 <h3 class="font-bold text-lg text-dokun-charcoal">{{ $artisan->first_name }}</h3>
 <p class="text-xs text-dokun-charcoal/50">Fon/Gun · {{ __('app.feature_bridge_instant') }}</p>
 </div>
 <div class="flex items-center gap-2 flex-shrink-0">
 <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
 <span class="text-xs text-emerald-600 font-semibold">{{ __('app.feature_bridge_online') }}</span>
 </div>
 </div>

 {{-- Toolbar: modes + langue --}}
 <div class="flex items-center gap-2 px-5 py-3 border-b border-slate-100 bg-slate-50/50 flex-wrap">
 <span class="text-xs font-bold text-dokun-charcoal/40 uppercase tracking-wider mr-1">{{ __('app.feature_bridge_mode') }} :</span>
 <button onclick="bridgeSetMode('text')" id="mode-text" class="bridge-mode-btn px-3 py-1.5 rounded-lg text-xs font-bold bg-dokun-green text-white transition">
 ⌨ {{ __('app.feature_bridge_text') }}
 </button>
 <button onclick="bridgeSetMode('voice_to_text')" id="mode-voice-to-text" class="bridge-mode-btn px-3 py-1.5 rounded-lg text-xs font-bold bg-white text-dokun-charcoal border border-slate-200 hover:border-dokun-green transition">
 <x-icon name="mic" class="inline-block w-4 h-4 mr-2" /> {{ __('app.feature_bridge_voice_to_text') }}
 </button>
 <button onclick="bridgeSetMode('text_to_voice')" id="mode-text-to-voice" class="bridge-mode-btn px-3 py-1.5 rounded-lg text-xs font-bold bg-white text-dokun-charcoal border border-slate-200 hover:border-dokun-green transition">
 {{ __('app.feature_bridge_text_to_voice') }}
 </button>
 <div class="ml-auto flex items-center gap-1">
 <button onclick="bridgeSetLang('fr')" id="lang-fr"
 class="px-3 py-1.5 rounded-lg text-xs font-bold bg-dokun-green text-white transition"> FR</button>
 <button onclick="bridgeSetLang('en')" id="lang-en"
 class="px-3 py-1.5 rounded-lg text-xs font-bold bg-white text-dokun-charcoal border border-slate-200 hover:border-dokun-green transition"> EN</button>
 <input type="hidden" id="bridge-lang" value="fr">
 </div>
 </div>

 {{-- Messages area --}}
 <div id="bridge-messages" class="h-96 sm:h-[500px] overflow-y-auto p-5 space-y-4 bg-gradient-to-b from-slate-50/30 to-white relative">

 {{-- Load older button --}}
 <div id="load-older-wrap" class="hidden text-center py-2">
 <button onclick="loadOlderMessages()" id="load-older-btn"
 class="text-xs text-dokun-green font-semibold hover:underline">
 ↑ {{ __('app.feature_bridge_load_older') }}
 </button>
 </div>

 {{-- Welcome + suggestion bubbles --}}
 <div class="flex gap-3 chat-bubble">
 <div class="w-8 h-8 rounded-full bg-dokun-green text-white flex items-center justify-center text-xs font-bold flex-shrink-0">{{ mb_substr($artisan->first_name, 0, 1) }}</div>
 <div class="max-w-[80%]">
 <p class="text-xs text-dokun-charcoal/50 mb-1 font-semibold">{{ $artisan->first_name }} · Fon/Gun</p>
 <div class="bg-white rounded-2xl rounded-tl-sm px-4 py-3 shadow-sm border border-gray-100">
 <p class="text-sm font-medium text-emerald-900">"Kouabo! Welcomez nan atelier mǐtɔn..."</p>
 <p class="text-xs text-dokun-charcoal/60 mt-1.5 italic">({{ __('app.feature_bridge_welcome') }})</p>
 </div>
 </div>
 </div>

 {{-- Suggestion chips --}}
 <div id="suggestion-chips" class="flex flex-wrap gap-2 pl-11 slide-up">
 <button onclick="bridgeSend('Parlez-moi de votre métier')" class="suggestion-chip px-3 py-1.5 bg-emerald-50 text-emerald-800 text-xs font-semibold rounded-full border border-emerald-200 hover:bg-emerald-100">
 {{ __('app.feature_bridge_job') }}
 </button>
 <button onclick="bridgeSend('Comment avez-vous appris ?')" class="suggestion-chip px-3 py-1.5 bg-emerald-50 text-emerald-800 text-xs font-semibold rounded-full border border-emerald-200 hover:bg-emerald-100">
 {{ __('app.feature_bridge_learning') }}
 </button>
 <button onclick="bridgeSend('Quelles sont vos créations préférées ?')" class="suggestion-chip px-3 py-1.5 bg-emerald-50 text-emerald-800 text-xs font-semibold rounded-full border border-emerald-200 hover:bg-emerald-100">
 {{ __('app.feature_bridge_fav_creations') }}
 </button>
 </div>
 </div>

 {{-- Satisfaction survey (hidden by default) --}}
 <div id="satisfaction-survey" class="hidden border-t border-slate-100 bg-gradient-to-r from-emerald-50 to-amber-50 p-4 slide-up">
 <div class="flex items-center gap-3">
 <p class="text-sm font-semibold text-dokun-charcoal flex-1">⭐ {{ __('app.feature_bridge_rate') }}</p>
 <div class="flex gap-1" id="survey-stars">
 <button onclick="submitRating(1)" class="text-2xl hover:scale-125 transition" aria-label="1">⭐</button>
 <button onclick="submitRating(2)" class="text-2xl hover:scale-125 transition" aria-label="2">⭐</button>
 <button onclick="submitRating(3)" class="text-2xl hover:scale-125 transition" aria-label="3">⭐</button>
 <button onclick="submitRating(4)" class="text-2xl hover:scale-125 transition" aria-label="4">⭐</button>
 <button onclick="submitRating(5)" class="text-2xl hover:scale-125 transition" aria-label="5">⭐</button>
 </div>
 <button onclick="dismissSurvey()" class="text-xs text-dokun-charcoal/40 hover:text-dokun-charcoal/60 ml-2" aria-label="Close"></button>
 </div>
 <p id="survey-thanks" class="hidden text-sm text-dokun-green font-semibold mt-2">{{ __('app.feature_bridge_thanks') }} </p>
 </div>

 {{-- Input area --}}
 <div class="p-4 border-t border-slate-100 bg-white">
 <div class="flex gap-3 items-end">
 <button id="bridge-mic-btn" onclick="bridgeToggleMic()" title="Microphone"
 class="hidden w-11 h-11 bg-slate-100 text-slate-500 rounded-xl hover:bg-red-50 hover:text-red-500 transition flex items-center justify-center flex-shrink-0">
 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"/></svg>
 </button>
 <div class="flex-1">
 <textarea id="bridge-input" rows="2"
 placeholder="{{ __('app.feature_bridge_write') }}"
 class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:border-dokun-green focus:ring-1 focus:ring-dokun-green outline-none transition resize-none"></textarea>
 </div>
 <button id="bridge-send-btn" onclick="bridgeSend()" title="{{ __('app.feature_bridge_send') }}"
 class="w-11 h-11 bg-dokun-green text-white rounded-xl hover:bg-dokun-green/90 active:scale-95 transition flex items-center justify-center flex-shrink-0 shadow-lg shadow-dokun-green/20">
 <svg class="w-5 h-5" id="bridge-send-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
 <svg class="w-5 h-5 hidden animate-spin" id="bridge-loading-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
 </button>
 </div>
 </div>
 </div>
 @endguest

</main>

@include('partials.footer')

<script>
document.addEventListener('DOMContentLoaded', function () {
 const CSRF = document.querySelector('meta[name="csrf-token"]').content;
 const ARTISAN_ID = {{ $artisan->id }};
 const IS_AUTH = {{ Auth::check() ? 'true' : 'false' }};

 const ui = {
 loading: {!! json_encode(__('app.feature_bridge_loading')) !!},
 loadOlder: {!! json_encode(__('app.feature_bridge_load_older')) !!},
 you: {!! json_encode(__('app.feature_bridge_you')) !!},
 thinking: {!! json_encode(__('app.feature_bridge_thinking')) !!},
 connError: {!! json_encode(__('app.feature_bridge_conn_error')) !!},
 noVoice: {!! json_encode(__('app.feature_bridge_no_voice')) !!},
 listening: {!! json_encode(__('app.feature_bridge_listening')) !!},
 writeMsg: {!! json_encode(__('app.feature_bridge_write')) !!},
 speakMicro: {!! json_encode(__('app.feature_bridge_speak_micro')) !!},
 };

 if (!IS_AUTH) return;

 const messagesDiv = document.getElementById('bridge-messages');
 const bridgeInput = document.getElementById('bridge-input');
 const bridgeSendBtn = document.getElementById('bridge-send-btn');
 const bridgeSendIco = document.getElementById('bridge-send-icon');
 const bridgeLoadIco = document.getElementById('bridge-loading-icon');
 const bridgeLang = document.getElementById('bridge-lang');
 const bridgeMicBtn = document.getElementById('bridge-mic-btn');
 const artisanInitial = @json(mb_substr($artisan->first_name, 0, 1));
 const artisanName = @json($artisan->first_name);

 let bridgeBusy = false;
 let bridgeMode = 'text';
 let bridgeRecognition = null;
 let bridgeIsRecording = false;
 let messageCount = 0;
 let historyOffset = 0;
 let hasMoreHistory = false;
 let surveyShown = false;

 // ─── Language toggle ───────────────────────────────
 window.bridgeSetLang = function(lang) {
 bridgeLang.value = lang;
 document.getElementById('lang-fr').className = lang === 'fr'
 ? 'px-3 py-1.5 rounded-lg text-xs font-bold bg-dokun-green text-white transition'
 : 'px-3 py-1.5 rounded-lg text-xs font-bold bg-white text-dokun-charcoal border border-slate-200 hover:border-dokun-green transition';
 document.getElementById('lang-en').className = lang === 'en'
 ? 'px-3 py-1.5 rounded-lg text-xs font-bold bg-dokun-green text-white transition'
 : 'px-3 py-1.5 rounded-lg text-xs font-bold bg-white text-dokun-charcoal border border-slate-200 hover:border-dokun-green transition';
 };

 // ─── Load history on page load ─────────────────────
 async function loadHistory() {
 try {
 const res = await fetch(@json(route('features.bridge.history', $artisan->id)) + '?limit=50&offset=0', {
 headers: {'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF}
 });
 if (!res.ok) return;
 const data = await res.json();
 if (data.status === 'success' && data.messages.length > 0) {
 messagesDiv.innerHTML = '';
 data.messages.forEach(msg => {
 addMessage(msg.sender_type === 'visitor' ? 'user' : 'artisan', msg.original_text, msg.translated_text, true);
 });
 messageCount = data.messages.filter(m => m.sender_type === 'visitor').length;
 hasMoreHistory = data.has_more;
 historyOffset = data.messages.length;
 if (hasMoreHistory) {
 document.getElementById('load-older-wrap').classList.remove('hidden');
 }
 if (messageCount >= 5 && !surveyShown) {
 showSurvey();
 }
 }
 } catch (e) { /* silent */ }
 }
 loadHistory();

 // ─── Load older messages ───────────────────────────
 window.loadOlderMessages = async function() {
 const btn = document.getElementById('load-older-btn');
 btn.textContent = ui.loading;
 btn.disabled = true;
 try {
 const res = await fetch(@json(route('features.bridge.history', $artisan->id)) + `?limit=20&offset=${historyOffset}`, {
 headers: {'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF}
 });
 if (!res.ok) return;
 const data = await res.json();
 if (data.status === 'success' && data.messages.length > 0) {
 const scrollBefore = messagesDiv.scrollHeight;
 data.messages.reverse().forEach(msg => {
 const wrapper = document.createElement('div');
 wrapper.innerHTML = createMessageHtml(msg.sender_type === 'visitor' ? 'user' : 'artisan', msg.original_text, msg.translated_text);
 const firstMsg = messagesDiv.querySelector('.chat-bubble');
 if (firstMsg) {
 messagesDiv.insertBefore(wrapper.firstElementChild, firstMsg);
 } else {
 messagesDiv.appendChild(wrapper.firstElementChild);
 }
 });
 historyOffset += data.messages.length;
 hasMoreHistory = data.has_more;
 messagesDiv.scrollTop = messagesDiv.scrollHeight - scrollBefore;
 }
 if (!hasMoreHistory) {
 document.getElementById('load-older-wrap').classList.add('hidden');
 }
 } catch (e) { /* silent */ }
 btn.textContent = '↑ ' + ui.loadOlder;
 btn.disabled = false;
 };

 // ─── Mode toggle ───────────────────────────────────
 window.bridgeSetMode = function(mode) {
 bridgeMode = mode;
 document.querySelectorAll('.bridge-mode-btn').forEach(btn => {
 btn.className = 'bridge-mode-btn px-3 py-1.5 rounded-lg text-xs font-bold bg-white text-dokun-charcoal border border-slate-200 hover:border-dokun-green transition';
 });
 const activeBtn = document.getElementById('mode-' + mode.replace('_', '-'));
 if (activeBtn) {
 activeBtn.className = 'bridge-mode-btn px-3 py-1.5 rounded-lg text-xs font-bold bg-dokun-green text-white transition';
 }
 bridgeMicBtn.classList.toggle('hidden', mode === 'text');
 };

 // ─── Message rendering ─────────────────────────────
 function escHtml(str) {
 const d = document.createElement('div');
 d.textContent = str;
 return d.innerHTML;
 }

 function createMessageHtml(type, localText, translatedText) {
 if (type === 'user') {
 return `<div class="flex gap-3 justify-end chat-bubble">
 <div class="max-w-[80%]">
 <div class="bg-dokun-green text-white rounded-2xl rounded-tr-sm px-4 py-3 shadow-sm">
 <p class="text-sm">${escHtml(localText)}</p>
 </div>
 </div>
 <div class="w-8 h-8 rounded-full bg-slate-300 text-white flex items-center justify-center text-xs font-bold flex-shrink-0">${ui.you}</div>
 </div>`;
 }
 return `<div class="flex gap-3 chat-bubble">
 <div class="w-8 h-8 rounded-full bg-dokun-green text-white flex items-center justify-center text-xs font-bold flex-shrink-0 typing-pulse">${artisanInitial}</div>
 <div class="max-w-[80%]">
 <p class="text-xs text-dokun-charcoal/50 mb-1 font-semibold">${escHtml(artisanName)} · Fon/Gun</p>
 <div class="bg-white rounded-2xl rounded-tl-sm px-4 py-3 shadow-sm border border-gray-100">
 <p class="text-sm font-medium text-emerald-900">"${escHtml(localText)}"</p>
 ${translatedText ? `<p class="text-xs text-dokun-charcoal/60 mt-1.5 italic border-t border-gray-100 pt-1.5">${escHtml(translatedText)}</p>` : ''}
 </div>
 </div>
 </div>`;
 }

 function addMessage(type, localText, translatedText, silent) {
 messagesDiv.insertAdjacentHTML('beforeend', createMessageHtml(type, localText, translatedText));
 if (!silent) messagesDiv.scrollTop = messagesDiv.scrollHeight;
 }

 function addTypingIndicator() {
 messagesDiv.insertAdjacentHTML('beforeend', `
 <div class="flex gap-3 chat-bubble" id="typing-indicator">
 <div class="w-8 h-8 rounded-full bg-dokun-green text-white flex items-center justify-center text-xs font-bold flex-shrink-0 typing-pulse">${artisanInitial}</div>
 <div class="bg-white rounded-2xl rounded-tl-sm px-4 py-3 shadow-sm border border-gray-100 flex items-center gap-2">
 <div class="flex gap-1"><span class="typing-dot"></span><span class="typing-dot"></span><span class="typing-dot"></span></div>
 <span class="text-xs text-dokun-charcoal/40 ml-1">{{ $artisan->first_name }} {{ __('app.feature_bridge_thinking') }}...</span>
 </div>
 </div>
 `);
 messagesDiv.scrollTop = messagesDiv.scrollHeight;
 }

 function removeTypingIndicator() {
 const t = document.getElementById('typing-indicator');
 if (t) t.remove();
 }

 // ─── Satisfaction survey ───────────────────────────
 function showSurvey() {
 surveyShown = true;
 document.getElementById('satisfaction-survey').classList.remove('hidden');
 }

 window.submitRating = function(rating) {
 const stars = document.querySelectorAll('#survey-stars button');
 stars.forEach((s, i) => {
 if (i < rating) {
 s.classList.add('star-pop');
 s.style.opacity = '1';
 } else {
 s.style.opacity = '0.3';
 }
 });
 document.getElementById('survey-thanks').classList.remove('hidden');
 // Could POST to /api/bridge/{artisan}/rating
 setTimeout(() => {
 document.getElementById('satisfaction-survey').classList.add('hidden');
 }, 3000);
 };

 window.dismissSurvey = function() {
 document.getElementById('satisfaction-survey').classList.add('hidden');
 };

 // ─── Send message ──────────────────────────────────
 async function bridgeSend(textOverride) {
 if (bridgeBusy) return;
 const msg = textOverride || bridgeInput.value.trim();
 if (!msg) return;
 const lang = bridgeLang.value;

 bridgeBusy = true;
 bridgeSendIco.classList.add('hidden');
 bridgeLoadIco.classList.remove('hidden');
 if (!textOverride) bridgeInput.value = '';
 bridgeInput.disabled = true;

 addMessage('user', msg);
 addTypingIndicator();

 // Hide suggestion chips after first real message
 const chips = document.getElementById('suggestion-chips');
 if (chips) chips.remove();

 try {
 const res = await fetch(@json(route('features.bridge', $artisan->id)), {
 method: 'POST',
 headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json'},
 body: JSON.stringify({message: msg, language: lang}),
 });

 if (!res.ok) throw new Error('HTTP ' + res.status);
 const data = await res.json();
 removeTypingIndicator();
 addMessage('artisan', data.local_reply, data.translated_reply);

 messageCount = data.message_count || (messageCount + 1);
 if (messageCount >= 5 && !surveyShown) {
 showSurvey();
 }

 if (bridgeMode === 'text_to_voice' && 'speechSynthesis' in window && data.translated_reply) {
 const utt = new SpeechSynthesisUtterance(data.translated_reply);
 utt.lang = lang === 'fr' ? 'fr-FR' : 'en-US';
 utt.rate = 0.9;
 window.speechSynthesis.speak(utt);
 }
 } catch (e) {
 removeTypingIndicator();
 messagesDiv.insertAdjacentHTML('beforeend', `<p class="text-red-400 text-xs text-center py-2">${ui.connError}</p>`);
 }

 bridgeBusy = false;
 bridgeSendIco.classList.remove('hidden');
 bridgeLoadIco.classList.add('hidden');
 bridgeInput.disabled = false;
 bridgeInput.focus();
 }
 window.bridgeSend = bridgeSend;

 bridgeSendBtn.addEventListener('click', () => bridgeSend());
 bridgeInput.addEventListener('keydown', e => { if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); bridgeSend(); } });

 // ─── Voice recognition ─────────────────────────────
 window.bridgeToggleMic = function() {
 if (!('webkitSpeechRecognition' in window) && !('SpeechRecognition' in window)) {
 alert(ui.noVoice);
 return;
 }
 if (bridgeIsRecording) {
 bridgeRecognition.stop();
 return;
 }
 const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
 bridgeRecognition = new SpeechRecognition();
 bridgeRecognition.lang = bridgeLang.value === 'fr' ? 'fr-FR' : 'en-US';
 bridgeRecognition.interimResults = true;
 bridgeRecognition.maxAlternatives = 1;

 bridgeMicBtn.classList.add('bg-red-500', 'text-white', 'mic-recording');
 bridgeMicBtn.classList.remove('bg-slate-100', 'text-slate-500');
 bridgeIsRecording = true;
 bridgeInput.placeholder = ui.listening;

 bridgeRecognition.onresult = function(event) {
 let transcript = '';
 for (let i = event.resultIndex; i < event.results.length; i++) {
 transcript += event.results[i][0].transcript;
 }
 bridgeInput.value = transcript;
 if (event.results[event.results.length - 1].isFinal) {
 bridgeRecognition.stop();
 bridgeSend(transcript);
 }
 };

 bridgeRecognition.onerror = function() {
 bridgeIsRecording = false;
 bridgeMicBtn.classList.remove('bg-red-500', 'text-white', 'mic-recording');
 bridgeMicBtn.classList.add('bg-slate-100', 'text-slate-500');
 bridgeInput.placeholder = ui.writeMsg;
 };

 bridgeRecognition.onend = function() {
 bridgeIsRecording = false;
 bridgeMicBtn.classList.remove('bg-red-500', 'text-white', 'mic-recording');
 bridgeMicBtn.classList.add('bg-slate-100', 'text-slate-500');
 bridgeInput.placeholder = bridgeMode === 'text' ? ui.writeMsg : ui.speakMicro;
 };

 bridgeRecognition.start();
 };

});
</script>
</body>
</html>
