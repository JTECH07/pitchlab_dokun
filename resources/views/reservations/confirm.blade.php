<!DOCTYPE html>
<html lang="fr" class="scroll-smooth">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Confirmer la réservation · ƉƆKUN</title>
    <link href="https://fonts.bunny.net/css?family=dm-serif-display:400|manrope:400,600,700,800&display=swap" rel="stylesheet"/>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>tailwind.config={theme:{extend:{colors:{dokun:{green:'#064E3B',gold:'#C99424',ivory:'#F8F6F0',charcoal:'#17201D'}},fontFamily:{sans:['Manrope','sans-serif'],serif:['"DM Serif Display"','serif']}}}}</script>
    <style>body{font-family:Manrope,sans-serif}h1,h2,h3,.serif{font-family:'DM Serif Display',serif}</style>
</head>
<body class="bg-dokun-ivory text-dokun-charcoal min-h-screen">
@include('partials.navbar',['active'=>''])

@if(session('error'))
<div class="fixed top-20 left-1/2 -translate-x-1/2 z-50 bg-red-600 text-white px-8 py-4 rounded-xl shadow-2xl font-bold max-w-xl w-full mx-4 text-center">{{ session('error') }}</div>
@endif

<main class="pt-32 pb-20 max-w-2xl mx-auto px-4">

    <!-- Étapes -->
    <div class="flex items-center gap-3 mb-10 text-sm font-bold">
        <span class="flex items-center gap-2 text-dokun-green"><span class="w-7 h-7 rounded-full bg-dokun-green text-white flex items-center justify-center text-xs">1</span>Expérience</span>
        <div class="flex-1 h-px bg-gray-200"></div>
        <span class="flex items-center gap-2 text-dokun-green"><span class="w-7 h-7 rounded-full bg-dokun-green text-white flex items-center justify-center text-xs">2</span>Coordonnées</span>
        <div class="flex-1 h-px bg-gray-200"></div>
        <span class="flex items-center gap-2 text-gray-300"><span class="w-7 h-7 rounded-full bg-gray-200 flex items-center justify-center text-xs">3</span>Paiement</span>
    </div>

    <h1 class="serif text-4xl text-dokun-green mb-2">Réserver avec {{ $artisan->first_name }}</h1>
    <p class="text-gray-500 mb-8">{{ $artisan->professional_name ?? ($artisan->first_name . ' ' . $artisan->last_name) }} · {{ $artisan->address }}</p>

    @if($errors->any())
    <div class="bg-red-50 border border-red-200 text-red-700 rounded-xl p-5 mb-6 text-sm">
        <ul class="list-disc list-inside space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
    @endif

    <form action="{{ route('payment.initiate', $artisan->id) }}" method="POST" id="res-form" class="space-y-6">
        @csrf

        <!-- ① Sélection expérience -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-7">
            <h2 class="serif text-2xl text-dokun-green mb-5">① Type de visite</h2>

            <div class="space-y-3" id="exp-choices">
                <!-- Visite libre -->
                <label class="exp-card cursor-pointer flex gap-4 p-5 rounded-xl border-2 border-gray-200 hover:border-dokun-green transition has-[:checked]:border-dokun-green has-[:checked]:bg-emerald-50">
                    <input type="radio" name="experience_id" value="" class="mt-1 sr-only exp-radio" @checked(!$experience) data-price="0" data-label="Visite d'atelier libre">
                    <div class="flex-1">
                        <span class="font-bold text-dokun-charcoal block">🏺 Visite d'atelier libre</span>
                        <span class="text-sm text-gray-500">Observer l'artisan dans son atelier, sans activité pratique guidée.</span>
                    </div>
                    <div class="text-right flex-shrink-0">
                        <span class="text-dokun-green font-bold">Gratuit</span>
                        <span class="block text-xs text-gray-400">+ frais de service (5%)</span>
                    </div>
                </label>

                @foreach($artisan->experiences->where('is_published', true) as $exp)
                <label class="exp-card cursor-pointer flex gap-4 p-5 rounded-xl border-2 border-gray-200 hover:border-dokun-green transition has-[:checked]:border-dokun-green has-[:checked]:bg-emerald-50">
                    <input type="radio" name="experience_id" value="{{ $exp->id }}" class="mt-1 sr-only exp-radio"
                        @checked($experience?->id === $exp->id)
                        data-price="{{ $exp->price }}"
                        data-label="{{ $exp->title }}">
                    <div class="flex-1">
                        <span class="font-bold text-dokun-charcoal block">🎨 {{ $exp->title }}</span>
                        <span class="text-sm text-gray-500">{{ $exp->summary }} · {{ $exp->duration_minutes }} min · max {{ $exp->capacity }} pers.</span>
                    </div>
                    <div class="text-right flex-shrink-0">
                        @php
                            $dispPrice = $currencyRate == 1
                                ? number_format($exp->price, 0, ',', ' ') . ' FCFA'
                                : $currencyInfo['symbol'] . ' ' . number_format($exp->price * $currencyRate, 2, '.', ' ');
                        @endphp
                        <span class="text-dokun-green font-bold serif text-lg">{{ $dispPrice }}</span>
                        <span class="block text-xs text-gray-400">/ personne</span>
                    </div>
                </label>
                @endforeach
            </div>
        </div>

        <!-- ② Informations visiteur -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-7">
            <h2 class="serif text-2xl text-dokun-green mb-5">② Vos informations</h2>
            <div class="grid sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-bold mb-2">Nom complet *</label>
                    <input type="text" name="visitor_name" value="{{ old('visitor_name', auth()->user()?->name) }}" required
                        class="w-full px-4 py-3 bg-dokun-ivory border border-gray-200 rounded-xl focus:ring-2 focus:ring-dokun-gold outline-none"
                        placeholder="Marie Dupont">
                </div>
                <div>
                    <label class="block text-sm font-bold mb-2">Téléphone / WhatsApp *</label>
                    <input type="text" name="visitor_phone" value="{{ old('visitor_phone') }}" required
                        class="w-full px-4 py-3 bg-dokun-ivory border border-gray-200 rounded-xl focus:ring-2 focus:ring-dokun-gold outline-none"
                        placeholder="+229 01 XX XX XX">
                </div>
                <div>
                    <label class="block text-sm font-bold mb-2">Email (optionnel)</label>
                    <input type="email" name="visitor_email" value="{{ old('visitor_email', auth()->user()?->email) }}"
                        class="w-full px-4 py-3 bg-dokun-ivory border border-gray-200 rounded-xl focus:ring-2 focus:ring-dokun-gold outline-none"
                        placeholder="votre@email.com">
                </div>
                <div>
                    <label class="block text-sm font-bold mb-2">Date souhaitée *</label>
                    <input type="date" name="requested_date" value="{{ old('requested_date') }}" required
                        min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                        class="w-full px-4 py-3 bg-dokun-ivory border border-gray-200 rounded-xl focus:ring-2 focus:ring-dokun-gold outline-none">
                </div>
                <div>
                    <label class="block text-sm font-bold mb-2">Nombre de personnes *</label>
                    <input type="number" name="guests_count" value="{{ old('guests_count', 1) }}" min="1" max="20" required id="guests-count"
                        class="w-full px-4 py-3 bg-dokun-ivory border border-gray-200 rounded-xl focus:ring-2 focus:ring-dokun-gold outline-none">
                </div>
                <div>
                    <label class="block text-sm font-bold mb-2">Message (optionnel)</label>
                    <textarea name="message" rows="2"
                        class="w-full px-4 py-3 bg-dokun-ivory border border-gray-200 rounded-xl focus:ring-2 focus:ring-dokun-gold outline-none resize-none"
                        placeholder="Questions, allergies, besoins particuliers...">{{ old('message') }}</textarea>
                </div>
            </div>
        </div>

        <!-- ③ Mode de paiement -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-7">
            <h2 class="serif text-2xl text-dokun-green mb-2">③ Mode de paiement</h2>
            <p class="text-xs text-gray-400 mb-5">Dans les deux cas, vous êtes redirigé vers FedaPay (paiement sécurisé).</p>

            <div class="grid sm:grid-cols-2 gap-4 mb-4">
                <label class="cursor-pointer border-2 border-gray-200 rounded-xl p-5 hover:border-dokun-green transition has-[:checked]:border-dokun-green has-[:checked]:bg-emerald-50">
                    <input type="radio" name="payment_method" value="pay_on_site" @checked(old('payment_method','pay_on_site')==='pay_on_site') class="sr-only">
                    <b class="block mb-1">🏺 Payer l'expérience à l'atelier</b>
                    <span class="text-xs text-gray-500">Sécurisez votre créneau en payant uniquement les <strong>frais de réservation (5%, min. 500 FCFA)</strong> maintenant. Vous réglez le reste sur place.</span>
                </label>
                <label class="cursor-pointer border-2 border-gray-200 rounded-xl p-5 hover:border-dokun-green transition has-[:checked]:border-dokun-green has-[:checked]:bg-emerald-50">
                    <input type="radio" name="payment_method" value="mobile_money" @checked(old('payment_method')==='mobile_money') class="sr-only">
                    <b class="block mb-1">💳 Tout payer maintenant</b>
                    <span class="text-xs text-gray-500">Payez l'expérience complète + <strong>frais de service (5%, min. 500 FCFA)</strong> via Mobile Money (MTN, Moov, Celtiis).</span>
                </label>
            </div>

            @if(config('services.fedapay.environment', 'sandbox') === 'sandbox')
            <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 text-xs text-amber-800 flex items-start gap-3">
                <span class="text-base leading-none">💡</span>
                <div>
                    <strong class="block mb-0.5">Mode Test Sandbox FedaPay Actif</strong>
                    <span>Sur la page de paiement FedaPay, choisissez l'opérateur <strong>Momo Test</strong>. Le numéro de test pré-rempli <strong>64000001</strong> ou <strong>66000001</strong> simulera un paiement réussi. (Tout autre numéro simule un échec).</span>
                </div>
            </div>
            @endif
        </div>

        <!-- Récapitulatif dynamique -->
        <div class="bg-dokun-green text-white rounded-2xl p-7" id="recap-box">
            <h3 class="font-bold text-lg mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-dokun-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                Récapitulatif
            </h3>
            <div class="space-y-2 text-sm mb-5">
                <div class="flex justify-between"><span class="text-white/70">Type</span><span id="sum-type" class="font-bold">Visite d'atelier libre</span></div>
                <div class="flex justify-between"><span class="text-white/70">Personnes</span><span id="sum-guests" class="font-bold">1</span></div>
                <div class="flex justify-between"><span class="text-white/70">Prix expérience</span><span id="sum-exp" class="font-bold">Gratuit</span></div>
                <div class="flex justify-between"><span class="text-white/70">Frais de service ƉƆKUN (5%)</span><span id="sum-fee" class="font-bold text-dokun-gold">500 FCFA</span></div>
                <div class="h-px bg-white/20 my-2"></div>
                <div class="flex justify-between text-lg"><span>À payer maintenant</span><span id="sum-feda" class="font-bold text-dokun-gold serif">1 000 FCFA</span></div>
                <p id="sum-rest" class="text-white/50 text-xs text-right hidden">+ reste à régler à l'atelier</p>
            </div>
            <button type="submit" id="submit-btn"
                class="w-full py-4 bg-dokun-gold text-dokun-charcoal font-bold text-lg rounded-xl hover:bg-dokun-gold/90 active:scale-[.98] transition shadow-xl flex items-center justify-center gap-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                <span id="submit-label">Payer 1 000 FCFA via FedaPay →</span>
            </button>
            <p class="text-white/40 text-xs text-center mt-3">Vous serez redirigé vers FedaPay · Paiement 100% sécurisé</p>
        </div>
    </form>
</main>

@include('partials.footer')

<script>
const RATE   = {{ $currencyRate }};
const SYMBOL = "{{ $currencyInfo['symbol'] }}";
const IS_XOF = (RATE === 1);

function calculateServiceFee(experienceTotal) {
    return Math.max(Math.ceil(experienceTotal * 0.05), 500);
}

function fmt(xof) {
    if (IS_XOF) return xof.toLocaleString('fr-FR') + ' FCFA';
    const v = (xof * RATE).toFixed(2);
    return SYMBOL + ' ' + parseFloat(v).toLocaleString('fr-FR');
}

function update() {
    const radio   = document.querySelector('.exp-radio:checked');
    const guests  = parseInt(document.getElementById('guests-count').value) || 1;
    const method  = document.querySelector('input[name="payment_method"]:checked')?.value ?? 'pay_on_site';

    const price   = parseFloat(radio?.dataset.price || 0);
    const label   = radio?.dataset.label || "Visite d'atelier libre";
    const expTotal= price * guests;
    const fee     = calculateServiceFee(expTotal);

    document.getElementById('sum-type').textContent   = label;
    document.getElementById('sum-guests').textContent = guests + ' personne(s)';
    document.getElementById('sum-exp').textContent    = price > 0 ? fmt(expTotal) : 'Gratuit';
    document.getElementById('sum-fee').textContent    = fmt(fee);

    let fedaAmt, submitText;
    if (method === 'mobile_money') {
        fedaAmt = expTotal + fee;
        submitText = 'Payer ' + fmt(fedaAmt) + ' via FedaPay →';
        document.getElementById('sum-rest').classList.add('hidden');
    } else {
        fedaAmt = fee;
        submitText = 'Payer ' + fmt(fee) + ' (frais) via FedaPay →';
        if (price > 0) document.getElementById('sum-rest').classList.remove('hidden');
        else document.getElementById('sum-rest').classList.add('hidden');
    }

    document.getElementById('sum-feda').textContent  = fmt(fedaAmt);
    document.getElementById('submit-label').textContent = submitText;
}

document.querySelectorAll('.exp-radio').forEach(r => r.addEventListener('change', update));
document.querySelectorAll('input[name="payment_method"]').forEach(r => r.addEventListener('change', update));
document.getElementById('guests-count').addEventListener('input', update);
document.getElementById('res-form').addEventListener('submit', e => {
    document.getElementById('submit-btn').disabled = true;
    document.getElementById('submit-label').textContent = 'Traitement…';
});
update();
</script>
</body>
</html>
