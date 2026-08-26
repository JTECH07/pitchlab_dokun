<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mon voyage — ƉƆKUN</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=dm-serif-display:400|manrope:400,600,700,800&display=swap" rel="stylesheet"/>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>tailwind.config={theme:{extend:{colors:{dokun:{green:'#064E3B',gold:'#C99424',ivory:'#F8F6F0',charcoal:'#17201D'}},fontFamily:{sans:['Manrope','sans-serif'],serif:['"DM Serif Display"','serif']}}}}</script>
    <style>
        body{font-family:'Manrope',sans-serif;}
        h1,h2,h3,.font-serif{font-family:'DM Serif Display',serif;}
        .wax-pattern{background-image:url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' stroke='%23C99424' stroke-opacity='0.25'%3E%3Ccircle cx='30' cy='30' r='12'/%3E%3Ccircle cx='0' cy='0' r='8'/%3E%3Ccircle cx='60' cy='0' r='8'/%3E%3Ccircle cx='0' cy='60' r='8'/%3E%3Ccircle cx='60' cy='60' r='8'/%3E%3Cpath d='M30 18l10 12-10 12-10-12z'/%3E%3C/g%3E%3C/svg%3E");}
        @keyframes fadeUp{from{opacity:0;transform:translateY(24px)}to{opacity:1;transform:translateY(0)}}
        .fade-up{animation:fadeUp .6s ease both;}
        .fav-card:hover{transform:translateY(-4px);}
        .fav-card{transition:all .25s ease;}
    </style>
</head>
<body class="antialiased bg-dokun-ivory text-dokun-charcoal">

@include('partials.navbar')

<main class="pt-28 pb-24">
    {{-- Hero --}}
    <section class="bg-dokun-charcoal relative overflow-hidden mb-14">
        <img src="{{ url('images/dokun_bg3.jpg') }}" class="absolute inset-0 w-full h-full object-cover opacity-15" alt="" loading="lazy">
        <div class="absolute inset-0 wax-pattern opacity-30"></div>
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-14 relative z-10 fade-up">
            <span class="inline-block px-4 py-1.5 bg-dokun-gold/15 text-dokun-gold border border-dokun-gold/30 rounded-full text-xs font-bold uppercase tracking-widest mb-4">Mon voyage</span>
            <h1 class="font-serif text-3xl md:text-5xl text-white mb-2">Kwabɔ, {{ $upcoming->isEmpty() && $past->isEmpty() ? 'explorateur' : auth()->user()->name }} !</h1>
            <p class="text-white/65">Tes réservations, tes artisans favoris et ta progression d'apprentissage — tout ton séjour à Porto-Novo au même endroit.</p>
        </div>
    </section>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-14">

        {{-- Statistiques rapides --}}
        <section class="grid grid-cols-2 md:grid-cols-4 gap-4 fade-up">
            @php
                $stats = [
                    ['label' => 'À venir', 'value' => $upcoming->count(), 'icon' => 'calendar'],
                    ['label' => 'Favoris', 'value' => $favorites->count(), 'icon' => 'heart'],
                    ['label' => 'Leçons Learn', 'value' => $learnStats['lessons_done'], 'icon' => 'book-open'],
                    ['label' => 'Avis donnés', 'value' => $reviewsCount, 'icon' => 'star'],
                ];
            @endphp
            @foreach($stats as $s)
            <div class="bg-white rounded-2xl border border-black/5 p-5 text-center">
                <x-icon :name="$s['icon']" class="w-7 h-7 mx-auto text-dokun-gold"/>
                <p class="font-serif text-3xl text-dokun-green mt-1">{{ $s['value'] }}</p>
                <p class="text-[11px] font-bold uppercase tracking-wider text-dokun-charcoal/45 mt-0.5">{{ $s['label'] }}</p>
            </div>
            @endforeach
        </section>

        {{-- Fidélisation --}}
        <section class="fade-up" style="animation-delay:.08s">
            <div class="bg-dokun-charcoal rounded-2xl relative overflow-hidden">
                <div class="absolute inset-0 wax-pattern opacity-20"></div>
                <div class="relative z-10 p-7">
                    <h2 class="font-serif text-2xl text-white mb-1">Programme fidélité</h2>
                    <p class="text-white/50 text-sm mb-6">Chaque action compte — gagne des points, monte de niveau, débloque des badges.</p>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-7">
                        {{-- Niveau + progression --}}
                        <div class="md:col-span-2 bg-white/5 border border-white/10 rounded-xl p-5">
                            <div class="flex items-center justify-between mb-3">
                                <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-dokun-gold/15 text-dokun-gold text-xs font-bold uppercase tracking-wider">
                                    <x-icon name="gem" class="w-4 h-4"/>
                                    {{ app()->getLocale()==='en' ? $level['en'] : $level['fr'] }}
                                </span>
                                <span class="text-white font-bold">{{ number_format($totalPoints, 0, ',', ' ') }} <span class="text-white/40 text-sm font-semibold">pts</span></span>
                            </div>
                            @if($nextLevel)
                            @php $pct = min(100, round(($totalPoints - $level['threshold']) / max(1, $nextLevel['threshold'] - $level['threshold']) * 100)); @endphp
                            <div class="h-2 bg-white/10 rounded-full overflow-hidden">
                                <div class="h-full bg-gradient-to-r from-dokun-gold to-yellow-400 rounded-full transition-all duration-700" style="width:{{ $pct }}%"></div>
                            </div>
                            <p class="text-white/40 text-xs mt-2">{{ number_format($nextLevel['threshold'] - $totalPoints, 0, ',', ' ') }} pts pour devenir
                                <strong class="text-dokun-gold/90">{{ app()->getLocale()==='en' ? $nextLevel['en'] : $nextLevel['fr'] }}</strong>
                            </p>
                            @else
                            <p class="text-dokun-gold/90 text-xs font-bold mt-1">Niveau maximum atteint — ayibobo !</p>
                            @endif
                        </div>

                        {{-- Streak --}}
                        <div class="bg-white/5 border border-white/10 rounded-xl p-5 flex flex-col items-center justify-center text-center">
                            <x-icon name="flame" class="w-8 h-8 {{ $summary->streak_days > 0 ? 'text-orange-500' : 'text-white/25' }}"/>
                            <p class="font-serif text-3xl text-white mt-2">{{ $summary->streak_days }}</p>
                            <p class="text-[11px] font-bold uppercase tracking-wider text-white/40 mt-0.5">jour(s) de suite</p>
                            <p class="text-[10px] text-white/30 mt-1">+5 pts par jour de visite</p>
                        </div>
                    </div>

                    {{-- Badges --}}
                    <p class="text-white/60 text-xs font-bold uppercase tracking-wider mb-3">Badges</p>
                    <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-8 gap-3">
                        @foreach($allBadges as $b)
                        @php $earned = isset($earnedBadgeIds[$b->id]); @endphp
                        <div title="{{ app()->getLocale()==='en' ? $b->desc_en : $b->desc_fr }}"
                             class="rounded-xl p-3 text-center border transition {{ $earned ? 'bg-dokun-gold/15 border-dokun-gold/40' : 'bg-white/5 border-white/10 opacity-45 grayscale' }}">
                            <x-icon name="{{ $b->icon }}" class="w-6 h-6 mx-auto {{ $earned ? 'text-dokun-gold' : 'text-white/40' }}"/>
                            <p class="text-[9px] font-bold uppercase tracking-wide mt-2 {{ $earned ? 'text-dokun-gold' : 'text-white/35' }}">{{ app()->getLocale()==='en' ? $b->name_en : $b->name_fr }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>

        {{-- Réservations à venir --}}
        <section class="fade-up" style="animation-delay:.1s">
            <h2 class="font-serif text-2xl text-dokun-green mb-5 flex items-center gap-3">
                <x-icon name="calendar" class="w-6 h-6 text-dokun-green"/> Réservations à venir
                <span class="text-sm font-sans font-bold text-dokun-charcoal/40">{{ $upcoming->count() }}</span>
            </h2>
            @forelse($upcoming as $r)
            <div class="bg-white rounded-2xl border border-black/5 p-6 mb-3 flex flex-col md:flex-row md:items-center gap-5">
                <div class="w-14 h-14 rounded-xl bg-dokun-green flex items-center justify-center flex-shrink-0 overflow-hidden">
                    @if($r->artisan?->photo_path)
                        <img src="{{ asset('storage/'.$r->artisan->photo_path) }}" class="w-full h-full object-cover" alt="">
                    @else
                        <span class="text-dokun-gold font-serif text-xl">Ɖ</span>
                    @endif
                </div>
                <div class="flex-1 min-w-0">
                    <h3 class="font-bold text-dokun-charcoal truncate">{{ $r->experience?->name ?? ($r->artisan?->professional_name ?? 'Expérience') }}</h3>
                    <p class="text-sm text-dokun-charcoal/50 mt-0.5">
                        {{ $r->artisan?->first_name }} {{ $r->artisan?->last_name }}
                        · {{ \Carbon\Carbon::parse($r->requested_date)->locale(app()->getLocale())->isoFormat('ddd D MMM YYYY') }}
                        · {{ $r->guests_count }} pers.
                    </p>
                </div>
                <div class="flex items-center gap-3 flex-shrink-0">
                    @php $badge = match($r->status) {
                        'pending' => ['bg-amber-50 text-amber-600 border-amber-200', __('app.res_status_pending')],
                        'accepted', 'confirmed' => ['bg-emerald-50 text-emerald-600 border-emerald-200', __('app.res_status_confirmed')],
                        default => ['bg-slate-50 text-slate-500 border-slate-200', ucfirst($r->status)],
                    }; @endphp
                    <span class="px-3 py-1.5 rounded-full text-[11px] font-bold border {{ $badge[0] }}">{{ $badge[1] }}</span>
                    <a href="{{ route('reservations.receipt', $r->qr_code_token) }}" class="px-4 py-2 bg-dokun-green hover:bg-dokun-green/90 text-white text-xs font-bold rounded-lg transition">
                        QR / Reçu
                    </a>
                </div>
            </div>
            @empty
            <div class="bg-white rounded-2xl border border-dashed border-black/10 p-10 text-center">
                <p class="text-dokun-charcoal/40 text-sm mb-4">Aucune réservation à venir pour le moment.</p>
                <a href="{{ route('experiences.index') }}" class="inline-flex px-6 py-3 bg-dokun-green text-white text-sm font-bold rounded-xl hover:bg-dokun-green/90 transition">Découvrir les expériences</a>
            </div>
            @endforelse
        </section>

        {{-- Historique --}}
        @if($past->isNotEmpty())
        <section class="fade-up" style="animation-delay:.15s">
            <h2 class="font-serif text-2xl text-dokun-green mb-5 flex items-center gap-3">
                <x-icon name="history" class="w-6 h-6 text-dokun-green"/> Historique
                <span class="text-sm font-sans font-bold text-dokun-charcoal/40">{{ $past->count() }}</span>
            </h2>
            @foreach($past as $r)
            <div class="bg-white rounded-2xl border border-black/5 p-5 mb-3 flex flex-col md:flex-row md:items-center gap-4 opacity-90">
                <div class="flex-1 min-w-0">
                    <h3 class="font-bold text-dokun-charcoal/80 truncate">{{ $r->experience?->name ?? 'Expérience' }}</h3>
                    <p class="text-xs text-dokun-charcoal/45 mt-0.5">
                        {{ \Carbon\Carbon::parse($r->requested_date)->locale(app()->getLocale())->isoFormat('D MMM YYYY') }}
                        · {{ number_format($r->total_amount) }} {{ $r->currency ?? 'XOF' }}
                    </p>
                </div>
                <div class="flex items-center gap-2 flex-shrink-0">
                    @php $b = match($r->status) {
                        'completed' => ['bg-emerald-50 text-emerald-600 border-emerald-200', __('app.res_status_completed')],
                        'cancelled', 'rejected' => ['bg-red-50 text-red-500 border-red-200', __('app.res_status_cancelled')],
                        default => ['bg-slate-50 text-slate-500 border-slate-200', ucfirst($r->status)],
                    }; @endphp
                    <span class="px-3 py-1.5 rounded-full text-[11px] font-bold border {{ $b[0] }}">{{ $b[1] }}</span>
                    @if($r->status === 'completed')
                        <a href="{{ route('reviews.create', $r->id) }}" class="px-4 py-2 bg-dokun-gold/10 hover:bg-dokun-gold/20 text-dokun-gold text-xs font-bold rounded-lg transition">Laisser un avis</a>
                    @endif
                </div>
            </div>
            @endforeach
        </section>
        @endif

        {{-- Favoris --}}
        <section class="fade-up" style="animation-delay:.2s">
            <h2 class="font-serif text-2xl text-dokun-green mb-5 flex items-center gap-3">
                <x-icon name="heart" class="w-6 h-6 text-dokun-green"/> Mes favoris
                <span class="text-sm font-sans font-bold text-dokun-charcoal/40">{{ $favorites->count() }}</span>
            </h2>
            @forelse($favorites as $a)
            <a href="{{ route('artisans.show', $a->id) }}" class="fav-card bg-white rounded-2xl border border-black/5 p-5 mb-3 flex items-center gap-5 block hover:shadow-lg">
                <div class="w-16 h-16 rounded-xl overflow-hidden bg-dokun-green flex items-center justify-center flex-shrink-0">
                    @if($a->photo_path)
                        <img src="{{ asset('storage/'.$a->photo_path) }}" class="w-full h-full object-cover" alt="">
                    @else
                        <img src="{{ $a->image_url }}" class="w-full h-full object-cover" alt="">
                    @endif
                </div>
                <div class="flex-1 min-w-0">
                    <h3 class="font-bold text-dokun-charcoal truncate">{{ $a->professional_name ?? ($a->first_name.' '.$a->last_name) }}</h3>
                    <p class="text-xs text-dokun-charcoal/50 mt-0.5 truncate">{{ $a->address }}</p>
                    <div class="flex gap-1.5 mt-2 flex-wrap">
                        @foreach($a->savoirFaires->take(3) as $sf)
                            <span class="px-2 py-0.5 bg-dokun-ivory text-dokun-gold text-[10px] font-bold rounded uppercase tracking-wider border border-dokun-gold/15">{{ $sf->name }}</span>
                        @endforeach
                    </div>
                </div>
                <svg class="w-5 h-5 text-red-500 fill-current flex-shrink-0" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
            </a>
            @empty
            <div class="bg-white rounded-2xl border border-dashed border-black/10 p-10 text-center">
                <p class="text-dokun-charcoal/40 text-sm mb-4">Aucun favori pour le moment — ajoute des artisans depuis le catalogue.</p>
                <a href="{{ route('artisans.index') }}" class="inline-flex px-6 py-3 bg-dokun-green text-white text-sm font-bold rounded-xl hover:bg-dokun-green/90 transition">Explorer les artisans</a>
            </div>
            @endforelse
        </section>

        {{-- Progression Learn --}}
        <section class="fade-up" style="animation-delay:.25s">
            <h2 class="font-serif text-2xl text-dokun-green mb-5"><x-icon name="graduation" class="w-6 h-6 text-dokun-green inline-block align-middle mr-2"/> Ma progression Learn</h2>
            <div class="bg-white rounded-2xl border border-black/5 p-7 relative overflow-hidden">
                <div class="absolute inset-0 wax-pattern opacity-[0.05]"></div>
                <div class="relative z-10 grid grid-cols-1 sm:grid-cols-3 gap-6 mb-6">
                    <div class="text-center">
                        <p class="font-serif text-4xl text-dokun-green">{{ $learnStats['lessons_done'] }}</p>
                        <p class="text-[11px] font-bold uppercase tracking-wider text-dokun-charcoal/45 mt-1">Leçons terminées</p>
                    </div>
                    <div class="text-center">
                        <p class="font-serif text-4xl text-dokun-gold">{{ $learnStats['avg_score'] }}<span class="text-lg">%</span></p>
                        <p class="text-[11px] font-bold uppercase tracking-wider text-dokun-charcoal/45 mt-1">Score moyen</p>
                    </div>
                    <div class="text-center">
                        <p class="font-serif text-4xl text-dokun-charcoal">{{ $learnStats['best_score'] }}<span class="text-lg">%</span></p>
                        <p class="text-[11px] font-bold uppercase tracking-wider text-dokun-charcoal/45 mt-1">Meilleur score</p>
                    </div>
                </div>
                @if($learnStats['recent']->isNotEmpty())
                <div class="relative z-10 pt-5 border-t border-black/5 space-y-2">
                    @foreach($learnStats['recent'] as $p)
                    <a href="{{ route('learn.play', [$p->lesson->course, $p->lesson]) }}" class="flex items-center justify-between px-4 py-3 rounded-xl hover:bg-dokun-ivory transition group">
                        <span class="text-sm font-semibold text-dokun-charcoal/75 group-hover:text-dokun-green transition">
                            {{ $p->lesson->course->icon }} {{ app()->getLocale()==='en' ? $p->lesson->title_en : $p->lesson->title_fr }}
                        </span>
                        <span class="text-xs font-bold {{ $p->best_score >= 70 ? 'text-emerald-600' : 'text-amber-600' }}">{{ $p->best_score }}%</span>
                    </a>
                    @endforeach
                </div>
                @endif
                <div class="relative z-10 mt-5 text-center">
                    <a href="{{ route('learn.index') }}" class="inline-flex px-6 py-3 bg-dokun-gold text-white text-sm font-bold rounded-xl hover:bg-yellow-600 transition shadow-lg shadow-dokun-gold/20">Continuer à apprendre →</a>
                </div>
            </div>
        </section>

    </div>
</main>

@include('partials.footer')
</body>
</html>
