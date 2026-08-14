<!DOCTYPE html>
<html lang="fr" class="scroll-smooth">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Expériences culturelles · ƉƆKUN</title>
    <link rel="preconnect" href="https://fonts.bunny.net"><link href="https://fonts.bunny.net/css?family=dm-serif-display:400|manrope:400,600,700,800&display=swap" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script>tailwind.config={theme:{extend:{colors:{dokun:{green:'#064E3B',gold:'#C99424',ivory:'#F8F6F0',charcoal:'#17201D'}},fontFamily:{sans:['Manrope','sans-serif'],serif:['DM Serif Display','serif']}}}}</script>
    <style>body{font-family:Manrope,sans-serif}h1,h2,h3,.font-serif{font-family:'DM Serif Display',serif}</style>
</head>
<body class="bg-dokun-ivory text-dokun-charcoal">
@include('partials.navbar', ['active' => 'experiences'])
<main class="pt-28 pb-20">
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-12">
        <div class="rounded-[2rem] overflow-hidden bg-dokun-green text-white grid md:grid-cols-2 shadow-xl">
            <div class="p-8 md:p-14 flex flex-col justify-center">
                <span class="text-dokun-gold text-xs font-bold tracking-[.2em] uppercase mb-4">Porto-Novo, Bénin</span>
                <h1 class="font-serif text-4xl md:text-5xl leading-tight">Des gestes à découvrir, des histoires à vivre.</h1>
                <p class="mt-5 text-white/75 text-lg">Choisissez une expérience, une date, puis échangez directement avec l’artisan.</p>
                <form class="mt-7 flex bg-white rounded-xl p-1.5 max-w-md" method="GET">
                    <label class="sr-only" for="q">Rechercher une expérience</label>
                    <input id="q" name="q" value="{{ request('q') }}" class="min-w-0 flex-1 bg-transparent border-0 focus:ring-0 text-dokun-charcoal px-3" placeholder="Poterie, tissage, atelier…">
                    <button class="bg-dokun-gold text-white px-5 py-3 rounded-lg font-bold">Rechercher</button>
                </form>
            </div>
            <img class="h-72 md:h-full w-full object-cover" src="{{ asset('images/reel_marche_arts.png') }}" alt="Marché d'art et culture béninoise">
        </div>
    </section>
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-end mb-8"><div><h2 class="font-serif text-3xl text-dokun-green">Expériences à réserver</h2><p class="text-dokun-charcoal/60 mt-2">Les tarifs sont affichés en francs CFA.</p></div><span class="hidden sm:block text-sm text-dokun-charcoal/60">{{ $experiences->total() }} proposition(s)</span></div>
        @if($experiences->isEmpty())
            <div class="bg-white rounded-2xl p-12 text-center border border-dokun-gold/20"><h3 class="font-serif text-2xl text-dokun-green">Aucune expérience trouvée</h3><p class="mt-2 text-dokun-charcoal/60">Essayez un autre mot ou découvrez nos artisans.</p><a class="inline-block mt-6 text-dokun-green font-bold underline" href="{{ route('artisans.index') }}">Voir les artisans</a></div>
        @else
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-7">
            @foreach($experiences as $experience)
            <article class="bg-white rounded-2xl overflow-hidden border border-black/5 shadow-sm hover:shadow-xl transition-shadow">
                <img class="h-52 w-full object-cover" src="{{ asset($experience->image_path ?: 'images/hero/hero_dokun.png') }}" alt="{{ $experience->title }}">
                <div class="p-6"><p class="text-xs font-bold uppercase tracking-wider text-dokun-gold">{{ $experience->artisan->professional_name }}</p><h3 class="font-serif text-2xl text-dokun-green mt-2">{{ $experience->title }}</h3><p class="text-sm leading-relaxed text-dokun-charcoal/65 mt-3 line-clamp-2">{{ $experience->summary }}</p>
                    <dl class="flex gap-5 text-sm mt-5 text-dokun-charcoal/70"><div><dt class="sr-only">Durée</dt><dd>{{ $experience->duration_minutes }} min</dd></div><div><dt class="sr-only">Capacité</dt><dd>{{ $experience->capacity }} pers. max</dd></div><div><dt class="sr-only">Langue</dt><dd>{{ $experience->language }}</dd></div></dl>
                    <div class="mt-6 flex justify-between gap-3 items-center"><strong class="text-dokun-green text-lg">{{ number_format($experience->price, 0, ',', ' ') }} FCFA</strong><a href="{{ route('artisans.show', $experience->artisan_id) }}#reservation-form" class="bg-dokun-green text-white px-4 py-3 rounded-xl font-bold text-sm">Réserver</a></div>
                </div>
            </article>
            @endforeach
        </div><div class="mt-10">{{ $experiences->links() }}</div>
        @endif
    </section>
</main>
@include('partials.footer')
</body></html>
