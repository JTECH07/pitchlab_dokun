<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
 <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
 <title>{{ __('app.res_confirm_title') }} · ƉƆKUN</title>
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
 <span class="flex items-center gap-2 text-dokun-green"><span class="w-7 h-7 rounded-full bg-dokun-green text-white flex items-center justify-center text-xs">1</span>{{ __('app.res_step_experience') }}</span>
 <div class="flex-1 h-px bg-gray-200"></div>
 <span class="flex items-center gap-2 text-dokun-green"><span class="w-7 h-7 rounded-full bg-dokun-green text-white flex items-center justify-center text-xs">2</span>{{ __('app.res_step_details') }}</span>
 <div class="flex-1 h-px bg-gray-200"></div>
 <span class="flex items-center gap-2 text-gray-300"><span class="w-7 h-7 rounded-full bg-gray-200 flex items-center justify-center text-xs">3</span>{{ __('app.res_step_payment') }}</span>
 </div>

 <h1 class="serif text-4xl text-dokun-green mb-2">{{ __('app.res_book_with', ['name' => $artisan->first_name]) }}</h1>
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
 <h2 class="serif text-2xl text-dokun-green mb-5">① {{ __('app.res_visit_type') }}</h2>

 <div class="space-y-3" id="exp-choices">
 @foreach($artisan->experiences->where('is_published', true) as $exp)
 <label class="exp-card cursor-pointer flex gap-4 p-5 rounded-xl border-2 border-gray-200 hover:border-dokun-green transition has-[:checked]:border-dokun-green has-[:checked]:bg-emerald-50">
 <input type="radio" name="experience_id" value="{{ $exp->id }}" class="mt-1 sr-only exp-radio"
 @checked($experience?->id === $exp->id)
 data-price="{{ $exp->price }}"
 data-label="{{ $exp->title }}">
 <div class="flex-1">
 <span class="font-bold text-dokun-charcoal block"> {{ $exp->title }}</span>
 <span class="text-sm text-gray-500">{{ $exp->summary }} · {{ $exp->duration_minutes }} {{ __('app.res_min') }} · {{ __('app.res_max') }} {{ $exp->capacity }} {{ __('app.res_ppl') }}.</span>
 </div>
 <div class="text-right flex-shrink-0">
 @php
 $dispPrice = $currencyRate == 1
 ? number_format($exp->price, 0, ',', ' ') . ' FCFA'
 : $currencyInfo['symbol'] . ' ' . number_format($exp->price * $currencyRate, 2, '.', ' ');
 @endphp
 <span class="text-dokun-green font-bold serif text-lg">{{ $dispPrice }}</span>
 <span class="block text-xs text-gray-400">{{ __('app.res_per_person') }}</span>
 </div>
 </label>
 @endforeach
 </div>
 </div>

 <!-- ② Informations visiteur -->
 <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-7">
 <h2 class="serif text-2xl text-dokun-green mb-5">② {{ __('app.res_your_info') }}</h2>
 <div class="grid sm:grid-cols-2 gap-5">
 <div>
 <label class="block text-sm font-bold mb-2">{{ __('app.res_full_name') }} *</label>
 <input type="text" name="visitor_name" value="{{ old('visitor_name', auth()->user()?->name) }}" required
 class="w-full px-4 py-3 bg-dokun-ivory border border-gray-200 rounded-xl focus:ring-2 focus:ring-dokun-gold outline-none"
 placeholder="Marie Dupont">
 </div>
 <div>
 <label class="block text-sm font-bold mb-2">{{ __('app.res_phone') }} / WhatsApp *</label>
 <input type="text" name="visitor_phone" value="{{ old('visitor_phone') }}" required
 class="w-full px-4 py-3 bg-dokun-ivory border border-gray-200 rounded-xl focus:ring-2 focus:ring-dokun-gold outline-none"
 placeholder="+229 01 XX XX XX">
 </div>
 <div>
 <label class="block text-sm font-bold mb-2">{{ __('app.res_email_optional') }}</label>
 <input type="email" name="visitor_email" value="{{ old('visitor_email', auth()->user()?->email) }}"
 class="w-full px-4 py-3 bg-dokun-ivory border border-gray-200 rounded-xl focus:ring-2 focus:ring-dokun-gold outline-none"
 placeholder="votre@email.com">
 </div>
 <div>
 <label class="block text-sm font-bold mb-2">{{ __('app.res_date') }} *</label>
 <input type="date" name="requested_date" value="{{ old('requested_date') }}" required
 min="{{ date('Y-m-d', strtotime('+1 day')) }}"
 class="w-full px-4 py-3 bg-dokun-ivory border border-gray-200 rounded-xl focus:ring-2 focus:ring-dokun-gold outline-none">
 </div>
 <div>
 <label class="block text-sm font-bold mb-2">{{ __('app.res_persons') }} *</label>
 <input type="number" name="guests_count" value="{{ old('guests_count', 1) }}" min="1" max="20" required id="guests-count"
 class="w-full px-4 py-3 bg-dokun-ivory border border-gray-200 rounded-xl focus:ring-2 focus:ring-dokun-gold outline-none">
 </div>
 <div>
 <label class="block text-sm font-bold mb-2">{{ __('app.res_message_optional') }}</label>
 <textarea name="message" rows="2"
 class="w-full px-4 py-3 bg-dokun-ivory border border-gray-200 rounded-xl focus:ring-2 focus:ring-dokun-gold outline-none resize-none"
 placeholder="{{ __('app.res_message_placeholder') }}">{{ old('message') }}</textarea>
 </div>
 </div>
 </div>

 <!-- ③ Mode de paiement (unique : en ligne sur la plateforme) -->
 <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-7">
 <h2 class="serif text-2xl text-dokun-green mb-2">③ {{ __('app.res_payment_method') }}</h2>
 <p class="text-xs text-gray-400 mb-5">{{ __('app.res_redirect_note') }}</p>

 <div class="flex items-start gap-4 border-2 border-dokun-green/20 rounded-xl p-5 bg-emerald-50/50">
 <span class="text-3xl leading-none"></span>
 <div>
 <b class="block mb-1 text-dokun-green">{{ __('app.res_pay_online_title') }}</b>
 <span class="text-xs text-gray-500">{{ __('app.res_pay_online_desc') }}</span>
 </div>
 </div>

 <input type="hidden" name="payment_method" value="mobile_money">


 </div>

 <!-- Récapitulatif dynamique -->
 <div class="bg-dokun-green text-white rounded-2xl p-7" id="recap-box">
 <h3 class="font-bold text-lg mb-4 flex items-center gap-2">
 <svg class="w-5 h-5 text-dokun-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
 {{ __('app.res_summary') }}
 </h3>
 <div class="space-y-2 text-sm mb-5">
 <div class="flex justify-between"><span class="text-white/70">{{ __('app.res_type') }}</span><span id="sum-type" class="font-bold">{{ __('app.res_select_exp_first') }}</span></div>
 <div class="flex justify-between"><span class="text-white/70">{{ __('app.res_persons') }}</span><span id="sum-guests" class="font-bold">1</span></div>
 <div id="row-exp-price" class="flex justify-between hidden"><span class="text-white/70">{{ __('app.res_exp_price') }}</span><span id="sum-exp" class="font-bold">{{ __('app.res_free') }}</span></div>
 <div id="row-fee" class="flex justify-between hidden"><span class="text-white/70">{{ __('app.res_service_fee') }} (10%)</span><span id="sum-fee" class="font-bold text-dokun-gold"></span></div>
 <div id="row-divider" class="h-px bg-white/20 my-2 hidden"></div>
 <div id="row-total" class="flex justify-between text-lg hidden"><span>{{ __('app.res_pay_now') }}</span><span id="sum-feda" class="font-bold text-dokun-gold serif"></span></div>
 <p id="sum-rest" class="text-white/50 text-xs text-right hidden">{{ __('app.res_pay_atelier_rest') }}</p>
 </div>
 <button type="submit" id="submit-btn"
 class="w-full py-4 bg-dokun-gold text-dokun-charcoal font-bold text-lg rounded-xl hover:bg-dokun-gold/90 active:scale-[.98] transition shadow-xl flex items-center justify-center gap-3 disabled:opacity-50 disabled:cursor-not-allowed">
 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
 <span id="submit-label">{{ __('app.res_pay_fedapay') }}</span>
 </button>
 <p class="text-white/40 text-xs text-center mt-3">{{ __('app.res_redirect_secure') }}</p>
 </div>
 </form>
</main>

@include('partials.footer')

<script>
const RATE = {{ $currencyRate }};
const SYMBOL = "{{ $currencyInfo['symbol'] }}";
const IS_XOF = (RATE === 1);
const L = {
 free: @json(__('app.res_free')),
 persons: @json(__('app.res_persons')),
 freeVisit: @json(__('app.res_free_visit')),
 payEnd: '→',
 fee: @json(__('app.res_fee')),
 atelierRest: @json(__('app.res_pay_atelier_rest')),
 processing: @json(__('app.res_processing')),
 payFeda: @json(__('app.res_pay_fedapay')),
 redirectSecure: @json(__('app.res_redirect_secure')),
};

function calculateServiceFee(experienceTotal) {
 return Math.max(Math.ceil(experienceTotal * 0.10), 500);
}

function fmt(xof) {
 if (IS_XOF) return xof.toLocaleString('fr-FR') + ' FCFA';
 const v = (xof * RATE).toFixed(2);
 return SYMBOL + ' ' + parseFloat(v).toLocaleString('fr-FR');
}

function update() {
 const radio = document.querySelector('.exp-radio:checked');
 const guests = parseInt(document.getElementById('guests-count').value) || 1;

 if (!radio) {
  document.getElementById('sum-type').textContent = {{ json_encode(__('app.res_select_exp_first')) }};
  document.getElementById('sum-guests').textContent = guests + ' ' + L.persons;
  ['row-exp-price','row-fee','row-divider','row-total'].forEach(id => document.getElementById(id).classList.add('hidden'));
  document.getElementById('submit-label').textContent = {{ json_encode(__('app.res_pay_fedapay')) }};
  document.getElementById('submit-btn').disabled = true;
  return;
 }

 const price = parseFloat(radio.dataset.price || 0);
 const label = radio.dataset.label || L.freeVisit;
 const expTotal = price * guests;
 const fee = calculateServiceFee(expTotal);
 const fedaAmt = expTotal + fee;

 document.getElementById('sum-type').textContent = label;
 document.getElementById('sum-guests').textContent = guests + ' ' + L.persons;
 document.getElementById('sum-exp').textContent = price > 0 ? fmt(expTotal) : L.free;
 document.getElementById('sum-fee').textContent = fmt(fee);
 document.getElementById('sum-feda').textContent = fmt(fedaAmt);
 ['row-exp-price','row-fee','row-divider','row-total'].forEach(id => document.getElementById(id).classList.remove('hidden'));
 document.getElementById('sum-rest').classList.add('hidden');
 document.getElementById('submit-label').textContent = L.payFeda + ' ' + fmt(fedaAmt) + ' ' + L.payEnd;
 document.getElementById('submit-btn').disabled = false;
}

document.querySelectorAll('.exp-radio').forEach(r => r.addEventListener('change', update));
document.getElementById('guests-count').addEventListener('input', update);
document.getElementById('res-form').addEventListener('submit', e => {
 if (!document.querySelector('.exp-radio:checked')) {
  e.preventDefault();
  document.getElementById('submit-btn').disabled = false;
  return;
 }
 document.getElementById('submit-btn').disabled = true;
 document.getElementById('submit-label').textContent = L.processing + '…';
});
update();
</script>
</body>
</html>
