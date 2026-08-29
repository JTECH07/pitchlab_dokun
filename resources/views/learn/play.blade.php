<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
 <meta charset="utf-8">
 <meta name="viewport" content="width=device-width, initial-scale=1">
 <meta name="csrf-token" content="{{ csrf_token() }}">
 <title>{{ app()->getLocale()==='en' ? $lesson->title_en : $lesson->title_fr }} — ƉƆKUN Learn</title>
 <link rel="preconnect" href="https://fonts.bunny.net">
 <link href="https://fonts.bunny.net/css?family=dm-serif-display:400|manrope:400,600,700,800&display=swap" rel="stylesheet"/>
 <script src="https://cdn.tailwindcss.com"></script>
 <script>tailwind.config={theme:{extend:{colors:{dokun:{green:'#064E3B',gold:'#C99424',ivory:'#F8F6F0',charcoal:'#17201D'}},fontFamily:{sans:['Manrope','sans-serif'],serif:['"DM Serif Display"','serif']}}}}</script>
 <style>
 body{font-family:'Manrope',sans-serif;}
 h1,h2,h3,.font-serif{font-family:'DM Serif Display',serif;}
 .wax-pattern{background-image:url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' stroke='%23C99424' stroke-opacity='0.25'%3E%3Ccircle cx='30' cy='30' r='12'/%3E%3Ccircle cx='0' cy='0' r='8'/%3E%3Ccircle cx='60' cy='0' r='8'/%3E%3Ccircle cx='0' cy='60' r='8'/%3E%3Ccircle cx='60' cy='60' r='8'/%3E%3Cpath d='M30 18l10 12-10 12-10-12z'/%3E%3C/g%3E%3C/svg%3E");}
 @keyframes fadeUp{from{opacity:0;transform:translateY(24px)}to{opacity:1;transform:translateY(0)}}
 .fade-up{animation:fadeUp .5s ease both;}
 .flashcard{perspective:1000px;cursor:pointer;}
 .flashcard-inner{position:relative;width:100%;height:100%;transition:transform .55s cubic-bezier(.4,.2,.2,1);transform-style:preserve-3d;}
 .flashcard.flipped .flashcard-inner{transform:rotateY(180deg);}
 .flashcard-face{position:absolute;inset:0;backface-visibility:hidden;border-radius:1.25rem;display:flex;flex-direction:column;align-items:center;justify-content:center;padding:2rem;}
 .flashcard-back{transform:rotateY(180deg);}
 @keyframes popIn{from{opacity:0;transform:scale(.9)}to{opacity:1;transform:scale(1)}}
 .pop-in{animation:popIn .35s ease both;}
 @keyframes shake{0%,100%{transform:translateX(0)}20%,60%{transform:translateX(-8px)}40%,80%{transform:translateX(8px)}}
 .shake{animation:shake .4s ease;}
 </style>
</head>
<body class="antialiased bg-dokun-ivory text-dokun-charcoal">

@include('partials.navbar')

<main class="pt-28 pb-16 min-h-screen">
 <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">

 {{-- Fil d'Ariane --}}
 <nav class="mb-8 text-sm font-semibold text-dokun-charcoal/45 flex items-center gap-2 flex-wrap">
 <a href="{{ route('learn.index') }}" class="hover:text-dokun-gold transition">Learn</a>
 <svg class="w-3.5 h-3.5 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
 <a href="{{ route('learn.course', $course) }}" class="hover:text-dokun-gold transition">{{ app()->getLocale()==='en' ? $course->title_en : $course->title_fr }}</a>
 <svg class="w-3.5 h-3.5 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
 <span class="text-dokun-green">{{ app()->getLocale()==='en' ? $lesson->title_en : $lesson->title_fr }}</span>
 </nav>

 {{-- Barre de progression globale --}}
 <div class="flex items-center gap-3 mb-8">
 <div class="flex-1 h-2 bg-black/5 rounded-full overflow-hidden"><div id="global-progress" class="h-full bg-gradient-to-r from-dokun-green to-dokun-gold rounded-full transition-all duration-500" style="width:0%"></div></div>
 <span id="phase-label" class="text-xs font-bold text-dokun-charcoal/50 uppercase tracking-wider whitespace-nowrap">Flashcards</span>
 </div>

 {{-- PHASE 1 : FLASHCARDS --}}
 <section id="phase-cards" class="fade-up">
 <p class="text-center text-sm text-dokun-charcoal/55 mb-6">
 {{ __('app.learn_flip_hint') }} <span id="card-counter" class="font-bold text-dokun-green">1 / {{ count($words) }}</span>
 </p>

 <div class="flashcard h-64 mb-8 select-none" id="flashcard" onclick="flipCard()">
 <div class="flashcard-inner shadow-xl">
 <div class="flashcard-face bg-white border border-black/5">
 <span class="absolute top-4 left-5 text-[10px] font-bold uppercase tracking-widest text-dokun-gold" id="card-lang-label">Fon / Gun</span>
 <button type="button" onclick="event.stopPropagation(); speakWord()" title="{{ app()->getLocale()==='en' ? 'Listen' : 'Écouter' }}"
 class="absolute top-3 right-4 w-10 h-10 rounded-full bg-dokun-green/5 hover:bg-dokun-green/15 flex items-center justify-center transition group">
 <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5 text-dokun-green"><polygon points="11 5 6 9 2 9 2 15 6 15 11 19"/><path d="M15.54 8.46a5 5 0 0 1 0 7.07"/><path d="M19.07 4.93a10 10 0 0 1 0 14.14"/></svg>
 </button>
 <span id="card-front" class="font-serif text-4xl md:text-5xl text-dokun-green"></span>
 <span class="mt-6 text-[11px] text-dokun-charcoal/30 font-semibold uppercase tracking-wider">Cliquer pour retourner</span>
 </div>
 <div class="flashcard-back bg-dokun-charcoal wax-pattern">
 <span class="absolute top-4 left-5 text-[10px] font-bold uppercase tracking-widest text-dokun-gold">{{ app()->getLocale()==='en' ? 'English' : 'Français' }}</span>
 <span id="card-back" class="font-serif text-3xl md:text-4xl text-white text-center"></span>
 </div>
 </div>
 </div>

 <div class="flex items-center justify-between gap-4">
 <button onclick="prevCard()" id="btn-prev" class="px-6 py-3 rounded-xl font-bold text-sm bg-white border border-black/10 hover:bg-white/70 transition disabled:opacity-30 disabled:cursor-not-allowed">
 ← Précédent
 </button>
 <div class="flex gap-1.5" id="dots"></div>
 <button onclick="nextCard()" id="btn-card-next" class="px-6 py-3 rounded-xl font-bold text-sm text-white transition shadow-lg" style="background:#064E3B">
 Suivant →
 </button>
 </div>

 <button onclick="startQuiz()" class="mt-10 w-full py-4 rounded-xl font-bold text-white transition shadow-lg flex items-center justify-center gap-2" style="background:#C99424">
 <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-5 h-5"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="6"/><circle cx="12" cy="12" r="2"/></svg>
 {{ __('app.learn_start_quiz') }}
 </button>
 </section>

 {{-- PHASE 2 : QUIZ --}}
 <section id="phase-quiz" class="hidden fade-up">
 <div class="bg-white rounded-2xl border border-black/5 p-8 relative overflow-hidden">
 <div class="absolute inset-0 wax-pattern opacity-[0.04]"></div>
 <div class="relative z-10">
 <div class="flex items-center justify-between mb-6">
 <span class="text-[11px] font-bold uppercase tracking-widest text-dokun-gold" id="quiz-question-label"></span>
 <span class="text-xs font-bold text-dokun-charcoal/40"><span id="quiz-current">1</span> / <span id="quiz-total">10</span></span>
 </div>
 <h2 id="quiz-prompt" class="font-serif text-2xl md:text-3xl text-dokun-green mb-8 text-center"></h2>
 <div id="quiz-options" class="grid grid-cols-1 sm:grid-cols-2 gap-3"></div>
 </div>
 </div>
 <div id="quiz-feedback" class="hidden mt-5 px-5 py-4 rounded-xl font-bold text-sm"></div>
 </section>

 {{-- PHASE 3 : RÉSULTAT --}}
 <section id="phase-result" class="hidden fade-up text-center">
 <div class="bg-white rounded-2xl border border-black/5 p-10 relative overflow-hidden">
 <div class="absolute inset-0 wax-pattern opacity-[0.06]"></div>
 <div class="relative z-10">
 <span class="text-6xl mb-4 inline-block pop-in" id="result-emoji"></span>
 <h2 class="font-serif text-3xl text-dokun-green mb-2">{{ __('app.learn_score') }} : <span id="result-score" class="text-dokun-gold"></span>/100</h2>
 <p id="result-message" class="text-dokun-charcoal/60 mb-8"></p>
 <div class="flex flex-col sm:flex-row gap-3 justify-center">
 <button onclick="location.reload()" class="px-7 py-3.5 rounded-xl font-bold text-sm bg-dokun-ivory border border-black/10 hover:bg-black/5 transition">{{ __('app.learn_restart') }}</button>
 <a href="{{ route('learn.course', $course) }}" class="px-7 py-3.5 rounded-xl font-bold text-sm text-white transition shadow-lg" style="background:#064E3B">{{ __('app.learn_back_course') }} →</a>
 </div>
 <p id="save-status" class="text-xs mt-6 font-semibold text-dokun-charcoal/40"></p>
 </div>
 </div>
 </section>
 </div>
</main>

<script>
@php
$wordsJson = $words->map(fn($w) => [
 'fon' => $w->local_word,
 'fr' => $w->french_translation,
 'en' => $w->english_translation,
 'audio' => $w->audio_path ? asset('storage/' . $w->audio_path) : null,
])->values()->toJson();
@endphp
const WORDS = @json($wordsJson);
const IS_EN = {{ $isEn ? 'true' : 'false' }};
const CSRF = document.querySelector('meta[name="csrf-token"]').content;
const COMPLETE_URL = '{{ route("learn.complete", $lesson) }}';
const IS_AUTH = {{ Auth::check() ? 'true' : 'false' }};
const LESSON_KEY = 'dokun_learn_{{ $lesson->id }}';

let cardIndex = 0;
let quiz = [], quizIndex = 0, quizScore = 0;

// ---------- PRONONCIATION ----------
function speakWord() {
 const w = WORDS[cardIndex];
 if (!w) return;
 // 1. Audio réel si disponible (upload admin)
 if (w.audio) {
 try {
 const a = new Audio(w.audio);
 a.play();
 return;
 } catch (e) {}
 }
 // 2. Synthèse vocale du navigateur (approximation FR)
 if ('speechSynthesis' in window) {
 speechSynthesis.cancel();
 const u = new SpeechSynthesisUtterance(w.fon);
 u.lang = 'fr-FR';
 u.rate = 0.85;
 u.pitch = 1;
 speechSynthesis.speak(u);
 }
}

// ---------- FLASHCARDS ----------
function renderCard() {
 const w = WORDS[cardIndex];
 const front = document.getElementById('card-front');
 const back = document.getElementById('card-back');
 front.textContent = w.fon;
 back.textContent = IS_EN ? w.en : w.fr;
 document.getElementById('card-lang-label').textContent = 'Fon / Gun';
 document.getElementById('card-counter').textContent = (cardIndex + 1) + ' / ' + WORDS.length;
 document.getElementById('btn-prev').disabled = cardIndex === 0;
 document.getElementById('btn-card-next').textContent = cardIndex === WORDS.length - 1 ? 'Quiz →' : 'Suivant →';
 renderDots();
}

function renderDots() {
 const dots = document.getElementById('dots');
 dots.innerHTML = '';
 WORDS.forEach((_, i) => {
 const d = document.createElement('span');
 d.className = 'w-2 h-2 rounded-full transition-all ' + (i <= cardIndex ? 'bg-dokun-gold' : 'bg-black/10');
 dots.appendChild(d);
 });
}

function flipCard() { document.getElementById('flashcard').classList.toggle('flipped'); }

function prevCard() {
 if (cardIndex === 0) return;
 cardIndex--;
 unflip(); renderCard();
}

function nextCard() {
 if (cardIndex === WORDS.length - 1) { startQuiz(); return; }
 cardIndex++;
 unflip(); renderCard();
}

function unflip() { document.getElementById('flashcard').classList.remove('flipped'); }

// ---------- QUIZ ----------
function shuffle(arr) { return arr.sort(() => Math.random() - 0.5); }

function buildQuiz() {
 // Une question par mot, direction alternée fon→langue et langue→fon
 const questions = WORDS.map((w, i) => ({
 word: w,
 dir: i % 2 === 0 ? 'toLang' : 'toFon',
 }));
 return shuffle(questions);
}

function startQuiz() {
 quiz = buildQuiz();
 quizIndex = 0; quizScore = 0;
 document.getElementById('phase-cards').classList.add('hidden');
 document.getElementById('phase-quiz').classList.remove('hidden');
 document.getElementById('phase-label').textContent = 'Quiz';
 document.getElementById('global-progress').style.width = '50%';
 document.getElementById('quiz-total').textContent = quiz.length;
 renderQuestion();
}

function renderQuestion() {
 const q = quiz[quizIndex];
 document.getElementById('quiz-current').textContent = quizIndex + 1;
 const promptEl = document.getElementById('quiz-prompt');
 const labelEl = document.getElementById('quiz-question-label');

 if (q.dir === 'toLang') {
 labelEl.textContent = IS_EN ? 'Traduire en anglais' : 'Traduire en français';
 promptEl.textContent = q.word.fon;
 } else {
 labelEl.textContent = 'Trouve le mot en Fon/Gun';
 promptEl.textContent = IS_EN ? q.word.en : q.word.fr;
 }

 // Options : bonne réponse + 3 distracteurs
 const correct = q.dir === 'toLang' ? (IS_EN ? q.word.en : q.word.fr) : q.word.fon;
 const pool = WORDS.map(w => q.dir === 'toLang' ? (IS_EN ? w.en : w.fr) : w.fon).filter(v => v !== correct);
 const options = shuffle([correct, ...shuffle(pool).slice(0, Math.min(3, pool.length))]);

 const container = document.getElementById('quiz-options');
 container.innerHTML = '';
 const feedback = document.getElementById('quiz-feedback');
 feedback.classList.add('hidden');

 options.forEach(opt => {
 const btn = document.createElement('button');
 btn.className = 'px-5 py-4 rounded-xl border-2 border-black/10 bg-white font-bold text-sm text-left hover:border-dokun-gold hover:bg-dokun-gold/5 transition-all pop-in';
 btn.textContent = opt;
 btn.onclick = () => answer(btn, opt, correct);
 container.appendChild(btn);
 });
}

function answer(btn, chosen, correct) {
 const buttons = document.querySelectorAll('#quiz-options button');
 buttons.forEach(b => b.disabled = true);

 const isCorrect = chosen === correct;
 if (isCorrect) { quizScore++; btn.style.borderColor = '#10b981'; btn.style.background = '#ecfdf5'; btn.style.color = '#059669'; }
 else { btn.classList.add('shake'); btn.style.borderColor = '#ef4444'; btn.style.background = '#fef2f2'; btn.style.color = '#dc2626'; }

 buttons.forEach(b => { if (b.textContent === correct) { b.style.borderColor = '#10b981'; } });

 setTimeout(() => {
 quizIndex++;
 if (quizIndex >= quiz.length) finishQuiz();
 else renderQuestion();
 }, isCorrect ? 650 : 1200);
}

// ---------- RÉSULTAT ----------
function finishQuiz() {
 const score = Math.round(quizScore / quiz.length * 100);
 document.getElementById('phase-quiz').classList.add('hidden');
 const res = document.getElementById('phase-result');
 res.classList.remove('hidden');
 document.getElementById('phase-label').textContent = IS_EN ? 'Result' : 'Résultat';
 document.getElementById('global-progress').style.width = '100%';

 const iconMap = {
 trophy: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-16 h-16 text-dokun-gold"><path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6"/><path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18"/><path d="M4 22h16"/><path d="M10 14.66V17c0 .55-.47.98-.97 1.21C7.85 18.75 7 20.24 7 22"/><path d="M14 14.66V17c0 .55.47.98.97 1.21C16.15 18.75 17 20.24 17 22"/><path d="M18 2H6v7a6 6 0 0 0 12 0V2Z"/></svg>',
 sparkles:'<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-16 h-16 text-emerald-500"><path d="m12 3-1.9 5.8a2 2 0 0 1-1.287 1.288L3 12l5.8 1.9a2 2 0 0 1 1.288 1.287L12 21l1.9-5.8a2 2 0 0 1 1.287-1.288L21 12l-5.8-1.9a2 2 0 0 1-1.288-1.287Z"/><path d="M5 3v4M19 17v4M3 5h4M17 19h4"/></svg>',
 bolt: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-16 h-16 text-amber-500"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10"/></svg>',
 book: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-16 h-16 text-dokun-green"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>'
 };
 const icon = score >= 90 ? 'trophy' : score >= 70 ? 'sparkles' : score >= 50 ? 'bolt' : 'book';
 const msg = score >= 90 ? (IS_EN ? 'Outstanding! You master this lesson.' : 'Exceptionnel ! Cette leçon est la tienne.')
 : score >= 70 ? (IS_EN ? 'Very well! Keep going.' : 'Très bien ! Continue comme ça.')
 : score >= 50 ? (IS_EN ? 'Good effort. Review and try again!' : 'Bon effort. Revois les cartes et réessaie !')
 : (IS_EN ? 'No worries — repetition is the mother of learning.' : "Pas grave — la répétition est la mère de l'apprentissage.");

 document.getElementById('result-emoji').innerHTML = iconMap[icon];
 document.getElementById('result-score').textContent = score;
 document.getElementById('result-message').textContent = msg;

 saveProgress(score);
}

function saveProgress(score) {
 const status = document.getElementById('save-status');
 try { localStorage.setItem(LESSON_KEY, JSON.stringify({ best: Math.max(score, JSON.parse(localStorage.getItem(LESSON_KEY) || '{"best":0}').best), at: Date.now() })); } catch (e) {}

 if (!IS_AUTH) {
 status.textContent = IS_EN ? 'Progress saved on this device. Sign in to sync it.' : 'Progression sauvegardée sur cet appareil. Connecte-toi pour la synchroniser.';
 return;
 }

 fetch(COMPLETE_URL, {
 method: 'POST',
 headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
 body: JSON.stringify({ score }),
 })
 .then(r => r.json())
 .then(d => { if (d.status === 'saved') status.textContent = IS_EN ? 'Progress saved to your account.' : 'Progression enregistrée dans ton compte.'; })
 .catch(() => {});
}

// ---------- INIT ----------
renderCard();

document.addEventListener('keydown', e => {
 const cardsVisible = !document.getElementById('phase-cards').classList.contains('hidden');
 if (!cardsVisible) return;
 if (e.key === 'ArrowRight') nextCard();
 if (e.key === 'ArrowLeft') prevCard();
 if (e.key === ' ') { e.preventDefault(); flipCard(); }
});
</script>
</body>
</html>
