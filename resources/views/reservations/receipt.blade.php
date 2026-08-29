<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
 <meta charset="utf-8">
 <meta name="viewport" content="width=device-width, initial-scale=1">
 <title>{{ __('app.receipt_title') }} · ƉƆKUN</title>
 <link rel="preconnect" href="https://fonts.bunny.net">
 <link href="https://fonts.bunny.net/css?family=dm-serif-display:400|manrope:400,500,600,700,800&display=swap" rel="stylesheet"/>
 <script src="https://cdn.tailwindcss.com"></script>
 <script>
 tailwind.config = {
 theme: { extend: {
 colors: { dokun: { green:'#064E3B', gold:'#C99424', ivory:'#F8F6F0', charcoal:'#17201D' } },
 fontFamily: { sans:['Manrope','sans-serif'], serif:['"DM Serif Display"','serif'] },
 }}
 }
 </script>
 <style>
 body{font-family:'Manrope',sans-serif;}
 h1,h2,h3,.font-serif{font-family:'DM Serif Display',serif;}
 @keyframes fadeUp{from{opacity:0;transform:translateY(16px);}to{opacity:1;transform:translateY(0);}}
 .fade-up{animation:fadeUp .5s ease-out both;}
 .delay-1{animation-delay:.1s;} .delay-2{animation-delay:.2s;} .delay-3{animation-delay:.3s;} .delay-4{animation-delay:.4s;}
 @media print{.no-print{display:none!important;} body{background:white;} .print-card{box-shadow:none;border:1px solid #ddd;}}
 </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-dokun-green via-dokun-green/90 to-emerald-900 flex flex-col">

@include('partials.navbar')

@if(session('success'))
<div id="flash-msg" class="fixed top-20 left-1/2 -translate-x-1/2 z-50 bg-dokun-gold text-dokun-charcoal px-8 py-4 rounded-xl shadow-2xl font-bold text-center max-w-xl w-full mx-4">
 {{ session('success') }}
</div>
<script>setTimeout(()=>{let e=document.getElementById('flash-msg');if(e){e.style.opacity='0';e.style.transition='opacity .5s';setTimeout(()=>e.remove(),500);}},6000);</script>
@endif

@if(session('info'))
<div id="flash-info" class="fixed top-20 left-1/2 -translate-x-1/2 z-50 bg-blue-600 text-white px-8 py-4 rounded-xl shadow-2xl font-bold text-center max-w-xl w-full mx-4">
 {{ session('info') }}
</div>
<script>setTimeout(()=>{let e=document.getElementById('flash-info');if(e){e.style.opacity='0';e.style.transition='opacity .5s';setTimeout(()=>e.remove(),500);}},6000);</script>
@endif

<main class="flex-1 flex items-center justify-center pt-32 pb-16 px-4">
 <div class="w-full max-w-xl">

 {{-- Titre --}}
 <div class="text-center mb-8 fade-up">
 <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-dokun-gold/20 border-2 border-dokun-gold mb-4">
 <svg class="w-10 h-10 text-dokun-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
 </svg>
 </div>
 <h1 class="font-serif text-4xl text-white mb-2">{{ __('app.receipt_confirmed') }}</h1>
 <p class="text-white/60">{{ __('app.receipt_ready') }}</p>
 </div>

 {{-- Carte billet --}}
 <div class="bg-white rounded-[2rem] shadow-2xl overflow-hidden print-card fade-up delay-1">

 {{-- Header carte --}}
 <div class="bg-dokun-green px-8 py-6 flex items-center justify-between">
 <div>
 <p class="text-dokun-gold font-bold text-xs uppercase tracking-widest mb-1">{{ __('app.receipt_ticket') }}</p>
 <h2 class="font-serif text-2xl text-white">{{ $reservation->experience_type }}</h2>
 </div>
 <div class="text-right">
 <p class="text-white/50 text-xs">{{ __('app.receipt_reference') }}</p>
 <p class="text-dokun-gold font-mono font-bold text-lg">{{ $reservation->reference }}</p>
 </div>
 </div>

 {{-- Séparateur découpé --}}
 <div class="flex items-center gap-0 px-6">
 <div class="w-6 h-6 rounded-full bg-gradient-to-br from-dokun-green to-emerald-900 -ml-9 flex-shrink-0"></div>
 <div class="flex-1 border-t-2 border-dashed border-gray-200 mx-2"></div>
 <div class="w-6 h-6 rounded-full bg-gradient-to-br from-dokun-green to-emerald-900 -mr-9 flex-shrink-0"></div>
 </div>

 {{-- Corps billet --}}
 <div class="px-8 py-7">
 <div class="grid grid-cols-2 gap-y-5 gap-x-4 mb-7">
 <div>
 <p class="text-xs text-gray-400 font-semibold uppercase tracking-wider mb-1">{{ __('app.receipt_visitor') }}</p>
 <p class="font-bold text-dokun-charcoal">{{ $reservation->visitor_name }}</p>
 </div>
 <div>
 <p class="text-xs text-gray-400 font-semibold uppercase tracking-wider mb-1">{{ __('app.receipt_date') }}</p>
 <p class="font-bold text-dokun-charcoal">{{ \Carbon\Carbon::parse($reservation->requested_date)->isoFormat('D MMMM Y') }}</p>
 </div>
 <div>
 <p class="text-xs text-gray-400 font-semibold uppercase tracking-wider mb-1">{{ __('app.receipt_artisan') }}</p>
 <p class="font-bold text-dokun-charcoal">{{ $reservation->artisan?->professional_name ?? ($reservation->artisan?->first_name . ' ' . $reservation->artisan?->last_name) }}</p>
 </div>
 <div>
 <p class="text-xs text-gray-400 font-semibold uppercase tracking-wider mb-1">{{ __('app.receipt_participants') }}</p>
 <p class="font-bold text-dokun-charcoal">{{ $reservation->guests_count }} {{ trans_choice('app.receipt_person', $reservation->guests_count, ['count' => $reservation->guests_count]) }}</p>
 </div>
 <div>
 <p class="text-xs text-gray-400 font-semibold uppercase tracking-wider mb-1">{{ __('app.receipt_payment') }}</p>
 @php
 $pmLabel = match($reservation->payment_method) {
 'mobile_money' => ' Mobile Money (FedaPay)',
 'pay_on_site' => ' ' . __('app.receipt_at_workshop'),
 default => $reservation->payment_method,
 };
 $statusLabel = match($reservation->payment_status ?? 'not_required') {
 'paid' => ['label' => __('app.receipt_paid'), 'class' => 'bg-green-100 text-green-700'],
 'pending' => ['label' => __('app.receipt_pending'), 'class' => 'bg-amber-100 text-amber-700'],
 'not_required'=> ['label' => __('app.receipt_to_pay'), 'class' => 'bg-blue-100 text-blue-700'],
 'failed' => ['label' => __('app.receipt_failed'), 'class' => 'bg-red-100 text-red-700'],
 default => ['label' => 'N/A', 'class' => 'bg-gray-100 text-gray-600'],
 };
 @endphp
 <p class="font-semibold text-dokun-charcoal text-sm">{{ $pmLabel }}</p>
 <span class="inline-block text-xs font-bold px-2 py-0.5 rounded-full {{ $statusLabel['class'] }}">{{ $statusLabel['label'] }}</span>
 </div>
 <div>
 <p class="text-xs text-gray-400 font-semibold uppercase tracking-wider mb-1">{{ __('app.receipt_res_status') }}</p>
 @php
 $stLabel = match($reservation->status) {
 'pending' => ['label' => __('app.receipt_pending_confirm'), 'class' => 'bg-amber-100 text-amber-700'],
 'accepted' => ['label' => __('app.receipt_confirmed'), 'class' => 'bg-green-100 text-green-700'],
 'completed' => ['label' => __('app.receipt_completed'), 'class' => 'bg-dokun-green/10 text-dokun-green'],
 'rejected' => ['label' => __('app.receipt_cancelled'), 'class' => 'bg-red-100 text-red-700'],
 default => ['label' => $reservation->status, 'class' => 'bg-gray-100 text-gray-600'],
 };
 @endphp
 <span class="inline-block text-xs font-bold px-2 py-0.5 rounded-full {{ $stLabel['class'] }}">{{ $stLabel['label'] }}</span>
 </div>
 </div>

 {{-- Montant --}}
 @if($reservation->total_amount)
 <div class="bg-dokun-ivory rounded-xl px-5 py-3 flex justify-between items-center mb-7">
 <span class="text-sm font-semibold text-dokun-charcoal/70">{{ __('app.receipt_total') }}</span>
 <span class="font-serif text-2xl text-dokun-green">{{ number_format($reservation->total_amount, 0, ',', ' ') }} FCFA</span>
 </div>
 @endif

 {{-- QR Code --}}
 <div class="flex flex-col items-center gap-4">
 <div class="bg-white p-3 rounded-2xl shadow-lg border border-gray-100">
 {!! $qrSvg !!}
 </div>
 <div class="text-center">
 <p class="text-xs text-gray-400 mb-1">{{ __('app.receipt_show_qr') }}</p>
 <p class="font-mono text-xs text-gray-300 select-all">{{ $reservation->qr_code_token }}</p>
 </div>
 </div>

 {{-- Info pay on site --}}
 @if($reservation->payment_method === 'pay_on_site')
 <div class="mt-5 bg-amber-50 border border-amber-200 rounded-xl p-4 text-xs text-amber-800">
 <p class="font-bold mb-1"> {{ __('app.receipt_pay_on_site') }}</p>
 <p>{!! __('app.receipt_pay_on_site_body') !!}</p>
 </div>
 @endif

 {{-- Message --}}
 @if($reservation->message)
 <div class="mt-4 bg-slate-50 rounded-xl p-4">
 <p class="text-xs text-gray-400 font-semibold mb-1">{{ __('app.receipt_your_message') }}</p>
 <p class="text-sm text-dokun-charcoal/70 italic">{{ $reservation->message }}</p>
 </div>
 @endif
 </div>

 {{-- Footer billet --}}
 <div class="bg-dokun-ivory/60 border-t border-gray-100 px-8 py-4 flex flex-wrap gap-3 justify-between items-center">
 <div class="flex items-center gap-2">
 @if($reservation->artisan?->whatsapp)
 <a href="https://wa.me/{{ preg_replace('/\D/', '', $reservation->artisan->whatsapp) }}"
 target="_blank"
 class="no-print flex items-center gap-2 bg-green-500 text-white px-4 py-2 rounded-xl text-sm font-bold hover:bg-green-600 transition">
 <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 00-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
 WhatsApp
 </a>
 @endif
 <a href="{{ route('artisans.show', $reservation->artisan_id) }}"
 class="no-print flex items-center gap-2 border border-dokun-green text-dokun-green px-4 py-2 rounded-xl text-sm font-bold hover:bg-dokun-green hover:text-white transition">
 {{ __('app.receipt_view_artisan') }}
 </a>
 @if($reservation->status === 'completed')
 <a href="{{ route('reviews.create', $reservation->qr_code_token) }}"
 class="no-print flex items-center gap-2 bg-dokun-gold text-dokun-charcoal px-4 py-2 rounded-xl text-sm font-bold hover:bg-yellow-500 transition shadow-md">
 ⭐ {{ __('app.receipt_leave_review') }}
 </a>
 <a href="{{ route('moments.create', $reservation->qr_code_token) }}"
 class="no-print flex items-center gap-2 bg-dokun-green text-white px-4 py-2 rounded-xl text-sm font-bold hover:bg-emerald-700 transition shadow-md">
 {{ __('app.moments_publish') }}
 </a>
 @endif
 </div>
 <button onclick="window.print()" class="no-print flex items-center gap-2 text-gray-400 hover:text-dokun-charcoal text-sm font-semibold transition">
 <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
 {{ __('app.receipt_print') }}
 </button>
 </div>
 </div>

 {{-- Action artisan — scan --}}
 @auth
 @if(auth()->user()->id === $reservation->artisan?->user_id && $reservation->status !== 'completed')
 <div class="mt-6 fade-up delay-3">
 <form action="{{ route('reservations.scan', $reservation->qr_code_token) }}" method="POST">
 @csrf
 <button type="submit"
 class="w-full py-4 bg-dokun-gold text-dokun-charcoal font-bold text-lg rounded-2xl hover:bg-dokun-gold/90 active:scale-[.98] transition shadow-xl flex items-center justify-center gap-3">
 <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
 {{ __('app.receipt_mark_completed') }}
 </button>
 </form>
 <p class="text-center text-white/40 text-xs mt-2">{{ __('app.receipt_confirm_after') }}</p>
 </div>
 @endif
 @endauth

 {{-- Lien retour --}}
 <div class="text-center mt-8 no-print fade-up delay-4">
 <a href="{{ route('experiences.index') }}" class="text-white/60 hover:text-white text-sm font-semibold transition">
 ← {{ __('app.receipt_more_experiences') }}
 </a>
 </div>

 </div>
</main>

@include('partials.footer')
</body>
</html>
