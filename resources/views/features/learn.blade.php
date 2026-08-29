<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
 <meta charset="utf-8">
 <meta name="viewport" content="width=device-width, initial-scale=1">
 <meta name="csrf-token" content="{{ csrf_token() }}">
 <title>ƉƆKUN Learn — {{ $artisan->first_name }} · ƉƆKUN</title>
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
 .card-3d{perspective:800px;}
 .card-inner{position:relative;width:100%;height:100%;transition:transform .6s cubic-bezier(.4,0,.2,1);transform-style:preserve-3d;}
 .card-3d.flipped .card-inner{transform:rotateY(180deg);}
 .card-face{position:absolute;width:100%;height:100%;backface-visibility:hidden;border-radius:1rem;display:flex;flex-direction:column;align-items:center;justify-content:center;padding:1.25rem;}
 .card-back{transform:rotateY(180deg);}
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
 <span class="text-dokun-green">{{ __('app.feat_learn_title') }}</span>
 </nav>

 <div class="mb-10">
 <h1 class="text-3xl md:text-5xl font-serif text-dokun-green tracking-tight mb-4 leading-tight">
 <x-icon name="book-open" class="inline-block w-10 h-10 mr-3 text-dokun-green align-text-bottom" />{{ __('app.feature_learn_title') }}
 </h1>
 <p class="text-dokun-charcoal/70 text-[15px] leading-relaxed max-w-2xl">
 {{ __('app.feature_learn_intro') }}
 </p>
 </div>

 <div class="bg-amber-50 rounded-2xl border border-amber-200 shadow-lg p-7 flex flex-col gap-5">
 <div class="flex items-center justify-between">
 <div class="flex items-center gap-4">
 <div class="w-11 h-11 rounded-full bg-amber-200 text-amber-700 flex items-center justify-center">
 <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
 </div>
 <div>
 <h3 class="font-bold text-lg text-amber-900">{{ __('app.feature_learn_word_game') }}</h3>
 <p class="text-xs text-amber-900/60">Fon/Gun · {{ $artisan->first_name }}</p>
 </div>
 </div>
 <div class="text-right">
 <p class="text-xs text-amber-700 font-semibold uppercase tracking-wider">{{ __('app.feature_learn_score') }}</p>
 <p class="text-2xl font-bold text-dokun-green font-serif" id="game-score">0</p>
 </div>
 </div>

 <div id="game-loading" class="text-center py-8 text-amber-900/50">
 <svg class="w-8 h-8 mx-auto animate-spin mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
 {{ __('app.feature_learn_loading') }}
 </div>

 <div id="game-area" class="hidden space-y-4">
 <div class="flex items-center gap-3">
 <div class="flex-1 h-2.5 bg-amber-200 rounded-full overflow-hidden">
 <div id="game-progress-bar" class="h-full bg-dokun-green rounded-full transition-all duration-500" style="width:0%"></div>
 </div>
 <span id="game-counter" class="text-xs text-amber-700 font-bold w-14 text-right">0 / 0</span>
 </div>

 <div class="card-3d cursor-pointer mx-auto" id="flashcard" style="height:160px;max-width:400px;" title="Cliquez pour retourner">
 <div class="card-inner">
 <div class="card-face bg-white border-2 border-amber-200 shadow-md">
 <p class="text-xs text-amber-600 font-bold uppercase mb-2 tracking-wider">Fon / Gun</p>
 <p class="text-3xl font-serif text-dokun-green text-center" id="card-local"></p>
 <p class="text-xs text-slate-400 mt-3">↺ {{ __('app.feature_learn_click_flip') }}</p>
 </div>
 <div class="card-back bg-dokun-green text-white border-2 border-dokun-green shadow-md">
 <p class="text-xs text-white/60 font-bold uppercase mb-2 tracking-wider">Français</p>
 <p class="text-xl font-bold text-center" id="card-french"></p>
 <p class="text-xs text-white/50 mt-1" id="card-context"></p>
 <button id="card-speak-btn" class="mt-3 text-xs bg-white/20 hover:bg-white/30 px-4 py-1.5 rounded-full transition flex items-center gap-1.5">
 <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M3 9v6h4l5 5V4L7 9H3zm13.5 3c0-1.77-1.02-3.29-2.5-4.03v8.05c1.48-.73 2.5-2.25 2.5-4.02z"/></svg>
 {{ __('app.feature_learn_pronounce') }}
 </button>
 </div>
 </div>
 </div>

 <div id="quiz-area" class="space-y-2.5">
 <p class="text-xs text-amber-800 font-semibold text-center uppercase tracking-wider">{{ __('app.feature_learn_question') }}</p>
 <div id="quiz-choices" class="grid grid-cols-2 gap-2.5"></div>
 </div>

 <div id="quiz-feedback" class="hidden text-center text-sm font-bold py-2.5 rounded-xl"></div>

 <button id="game-next-btn" onclick="gameNext()" class="hidden w-full py-3 bg-dokun-green text-white font-bold text-sm rounded-xl hover:bg-dokun-green/90 active:scale-[.98] transition shadow-lg shadow-dokun-green/20">
 {{ __('app.learn_next') }} →
 </button>
 </div>

 <div id="game-end" class="hidden text-center space-y-4 py-4">
 <div class="text-4xl"><x-icon name="trophy" class="w-16 h-16 mx-auto text-amber-500" /></div>
 <p class="font-bold text-amber-900 text-lg">{{ __('app.feature_learn_game_done') }}</p>
 <p class="text-sm text-amber-700">
 {{ __('app.feature_learn_final_score') }} : <span id="end-score" class="font-bold text-dokun-green text-xl font-serif"></span>
 </p>
 <button onclick="gameRestart()" class="w-full py-3 bg-amber-600 text-white font-bold text-sm rounded-xl hover:bg-amber-700 active:scale-[.98] transition">
 {{ __('app.feature_learn_replay') }} <x-icon name="history" class="inline-block w-4 h-4 ml-2" />
 </button>
 </div>
 </div>

</main>

@include('partials.footer')

<script>
document.addEventListener('DOMContentLoaded', function () {
 const ARTISAN_ID = {{ $artisan->id }};

 const ui = {
 noWords: {!! json_encode(__('app.feature_learn_no_words')) !!},
 loadError: {!! json_encode(__('app.feature_learn_load_error')) !!},
 correct: {!! json_encode(__('app.feature_learn_correct')) !!},
 wrong: {!! json_encode(__('app.feature_learn_wrong')) !!},
 };

 const gameLoading = document.getElementById('game-loading');
 const gameArea = document.getElementById('game-area');
 const gameEnd = document.getElementById('game-end');
 const flashcard = document.getElementById('flashcard');
 const cardLocal = document.getElementById('card-local');
 const cardFrench = document.getElementById('card-french');
 const cardContext = document.getElementById('card-context');
 const cardSpeakBtn = document.getElementById('card-speak-btn');
 const quizChoices = document.getElementById('quiz-choices');
 const quizFeedback = document.getElementById('quiz-feedback');
 const gameNextBtn = document.getElementById('game-next-btn');
 const gameScoreEl = document.getElementById('game-score');
 const gameCounter = document.getElementById('game-counter');
 const gameProgressBar= document.getElementById('game-progress-bar');
 const endScore = document.getElementById('end-score');

 let allWords = [];
 let wordQueue = [];
 let currentWord = null;
 let score = 0;
 let totalPlayed = 0;

 const badgeMap = { greeting:'bg-emerald-100 text-emerald-700', commerce:'bg-amber-100 text-amber-700', craft:'bg-purple-100 text-purple-700', basic:'bg-sky-100 text-sky-700' };

 fetch(@json(route('features.learn', $artisan->id)))
 .then(r => r.json())
 .then(data => {
 gameLoading.classList.add('hidden');
 if (!data.words || data.words.length === 0) {
 gameArea.innerHTML = `<p class="text-amber-900/50 text-sm text-center py-4">${ui.noWords}</p>`;
 gameArea.classList.remove('hidden');
 return;
 }
 allWords = data.words;
 gameStart();
 })
 .catch(() => {
 gameLoading.classList.add('hidden');
 gameArea.innerHTML = `<p class="text-red-500 text-sm text-center py-4">${ui.loadError}</p>`;
 gameArea.classList.remove('hidden');
 });

 function shuffle(arr) { return [...arr].sort(() => Math.random() - .5); }

 function gameStart() {
 wordQueue = shuffle(allWords);
 score = 0;
 totalPlayed= 0;
 gameScoreEl.textContent = '0';
 gameEnd.classList.add('hidden');
 gameArea.classList.remove('hidden');
 gameShowNext();
 }
 window.gameRestart = gameStart;

 function gameShowNext() {
 if (wordQueue.length === 0) { gameEnd_(); return; }
 currentWord = wordQueue.pop();
 totalPlayed++;

 const pct = ((totalPlayed - 1) / allWords.length) * 100;
 gameProgressBar.style.width = pct + '%';
 gameCounter.textContent = `${totalPlayed} / ${allWords.length}`;

 flashcard.classList.remove('flipped');
 cardLocal.textContent = currentWord.local_word;
 cardFrench.textContent = currentWord.french_translation;
 const ctx = currentWord.context || 'craft';
 cardContext.innerHTML = `<span class="px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider rounded-full ${badgeMap[ctx] || badgeMap.craft}">${ctx}</span>`;

 const wrongs = shuffle(allWords.filter(w => w.id !== currentWord.id)).slice(0, 3);
 const choices = shuffle([currentWord, ...wrongs]);
 quizChoices.innerHTML = '';
 choices.forEach(ch => {
 const btn = document.createElement('button');
 btn.textContent = ch.french_translation;
 btn.className = 'w-full text-left px-4 py-3 text-sm bg-white border-2 border-amber-100 rounded-xl hover:border-dokun-green hover:bg-emerald-50 transition font-medium text-amber-900 active:scale-[.98]';
 btn.dataset.correct = (ch.id === currentWord.id) ? '1' : '0';
 btn.addEventListener('click', gameOnChoice);
 quizChoices.appendChild(btn);
 });

 quizFeedback.classList.add('hidden');
 gameNextBtn.classList.add('hidden');
 }

 function gameOnChoice(e) {
 const btn = e.currentTarget;
 const isRight = btn.dataset.correct === '1';
 quizChoices.querySelectorAll('button').forEach(b => b.disabled = true);
 flashcard.classList.add('flipped');

 if (isRight) {
 score++;
 gameScoreEl.textContent = score;
 btn.classList.add('border-dokun-green','bg-emerald-50','text-dokun-green');
 quizFeedback.textContent = ' ' + ui.correct;
 quizFeedback.className = 'text-center text-sm font-bold py-2.5 rounded-xl bg-emerald-50 text-dokun-green';
 } else {
 btn.classList.add('border-red-400','bg-red-50','text-red-600');
 quizChoices.querySelectorAll('button').forEach(b => {
 if (b.dataset.correct === '1') b.classList.add('border-dokun-green','bg-emerald-50','text-dokun-green');
 });
 quizFeedback.textContent = ` ${ui.wrong}"${currentWord.french_translation}"`;
 quizFeedback.className = 'text-center text-sm font-bold py-2.5 rounded-xl bg-red-50 text-red-600';
 }
 quizFeedback.classList.remove('hidden');
 gameNextBtn.classList.remove('hidden');
 }
 window.gameNext = function() {
 quizChoices.querySelectorAll('button').forEach(b => {
 b.disabled = false;
 b.className = 'w-full text-left px-4 py-3 text-sm bg-white border-2 border-amber-100 rounded-xl hover:border-dokun-green hover:bg-emerald-50 transition font-medium text-amber-900 active:scale-[.98]';
 });
 gameShowNext();
 };

 function gameEnd_() {
 gameProgressBar.style.width = '100%';
 gameArea.classList.add('hidden');
 gameEnd.classList.remove('hidden');
 endScore.textContent = `${score} / ${allWords.length}`;
 }

 flashcard.addEventListener('click', () => flashcard.classList.toggle('flipped'));

 cardSpeakBtn.addEventListener('click', (e) => {
 e.stopPropagation();
 if (!currentWord || !('speechSynthesis' in window)) return;
 const utt = new SpeechSynthesisUtterance(currentWord.local_word);
 utt.lang = 'fr-FR';
 utt.rate = 0.75;
 window.speechSynthesis.speak(utt);
 });

});
</script>
</body>
</html>
