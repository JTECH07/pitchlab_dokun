<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
 <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
 <title>{{ __('app.receipt_title') }} · ƉƆKUN</title>
 <link href="https://fonts.bunny.net/css?family=dm-serif-display:400|manrope:400,500,600,700,800&display=swap" rel="stylesheet"/>
 <script src="https://cdn.tailwindcss.com"></script>
 <script>tailwind.config={theme:{extend:{colors:{dokun:{green:'#064E3B',gold:'#C99424',ivory:'#F8F6F0',charcoal:'#17201D'}},fontFamily:{sans:['Manrope'],serif:['"DM Serif Display"']}}}}</script>
 <style>
 body{font-family:'Manrope',sans-serif}
 h1,h2,h3,.font-serif{font-family:'DM Serif Display',serif}
 @keyframes fadeUp{from{opacity:0;transform:translateY(16px)}to{opacity:1;transform:translateY(0)}}
 .fade-up{animation:fadeUp .5s ease-out both}
 .delay-1{animation-delay:.1s}.delay-2{animation-delay:.2s}.delay-3{animation-delay:.3s}
 @media print{.no-print{display:none!important}body{background:white}.print-card{box-shadow:none;border:1px solid #ddd}}
 </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-dokun-green via-dokun-green/90 to-emerald-900">

 {{-- Header minimal --}}
 <header class="fixed top-0 w-full z-50 bg-dokun-green/95 backdrop-blur-sm border-b border-white/10">
 <div class="max-w-xl mx-auto px-4 h-14 flex items-center justify-between">
 <a href="{{ route('home') }}" class="flex items-center gap-2">
 <img src="{{ url('images/dokun_logo_final.jpeg') }}" alt="ƉƆKUN" class="w-8 h-9 rounded-lg">
 <span class="font-serif text-white text-lg">ƉƆKUN</span>
 </a>
 <button onclick="window.print()" class="no-print flex items-center gap-1.5 text-white/70 hover:text-white text-xs font-semibold transition">
 <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
 {{ __('app.receipt_print') }}
 </button>
 </div>
 </header>

<main class="pt-24 pb-10 px-4 flex justify-center">
 <div class="w-full max-w-lg">

 {{-- Flash --}}
 @if(session('success'))
 <div class="mb-4 bg-dokun-gold text-dokun-charcoal px-5 py-3 rounded-xl font-bold text-sm text-center shadow-lg">{{ session('success') }}</div>
 @endif

 {{-- Confirmation --}}
 <div class="text-center mb-6 fade-up">
 <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-white/10 border-2 border-dokun-gold mb-3">
 <svg class="w-8 h-8 text-dokun-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
 </div>
 <h1 class="font-serif text-3xl text-white mb-1">{{ __('app.receipt_confirmed') }}</h1>
 <p class="text-white/50 text-sm">{{ __('app.receipt_ready') }}</p>
 </div>

 {{-- Carte billet --}}
 <div class="bg-white rounded-2xl shadow-2xl overflow-hidden print-card fade-up delay-1">

 {{-- Header --}}
 <div class="bg-dokun-green px-6 py-5 flex items-center justify-between">
 <div>
 <p class="text-dokun-gold font-bold text-[10px] uppercase tracking-widest mb-0.5">{{ __('app.receipt_ticket') }}</p>
 <h2 class="font-serif text-xl text-white">{{ $reservation->experience_type }}</h2>
 </div>
 <div class="text-right">
 <p class="text-white/40 text-[10px]">{{ __('app.receipt_reference') }}</p>
 <p class="text-dokun-gold font-mono font-bold text-sm">{{ $reservation->reference }}</p>
 </div>
 </div>

 {{-- Découpé --}}
 <div class="flex items-center gap-0 px-5">
 <div class="w-5 h-5 rounded-full bg-gradient-to-br from-dokun-green to-emerald-900 -ml-7 flex-shrink-0"></div>
 <div class="flex-1 border-t-2 border-dashed border-gray-200 mx-2"></div>
 <div class="w-5 h-5 rounded-full bg-gradient-to-br from-dokun-green to-emerald-900 -mr-7 flex-shrink-0"></div>
 </div>

 {{-- Corps --}}
 <div class="px-6 py-5">
 <div class="grid grid-cols-2 gap-y-4 gap-x-3 text-sm mb-5">
 <div>
 <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider mb-0.5">{{ __('app.receipt_visitor') }}</p>
 <p class="font-bold text-dokun-charcoal">{{ $reservation->visitor_name }}</p>
 </div>
 <div>
 <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider mb-0.5">{{ __('app.receipt_date') }}</p>
 <p class="font-bold text-dokun-charcoal">{{ \Carbon\Carbon::parse($reservation->requested_date)->isoFormat('D MMMM Y') }}</p>
 </div>
 <div>
 <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider mb-0.5">{{ __('app.receipt_artisan') }}</p>
 <p class="font-bold text-dokun-charcoal">{{ $reservation->artisan?->professional_name ?? ($reservation->artisan?->first_name . ' ' . $reservation->artisan?->last_name) }}</p>
 </div>
 <div>
 <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider mb-0.5">{{ __('app.receipt_participants') }}</p>
 <p class="font-bold text-dokun-charcoal">{{ $reservation->guests_count }} {{ trans_choice('app.receipt_person', $reservation->guests_count, ['count' => $reservation->guests_count]) }}</p>
 </div>
 </div>

 {{-- Montant --}}
 @if($reservation->total_amount)
 <div class="bg-dokun-ivory rounded-xl px-4 py-2.5 flex justify-between items-center mb-5">
 <span class="text-xs font-semibold text-dokun-charcoal/60">{{ __('app.receipt_total') }}</span>
 <span class="font-serif text-xl text-dokun-green">{{ number_format($reservation->total_amount, 0, ',', ' ') }} FCFA</span>
 </div>
 @endif

 {{-- QR Code — GRAND et scannable --}}
 <div class="flex flex-col items-center gap-3 mb-5">
 <div class="bg-white p-4 rounded-2xl shadow-lg border-2 border-dokun-green/20">
 {!! $qrSvg !!}
 </div>
 <p class="text-xs text-gray-400 text-center">{{ __('app.receipt_show_qr') }}</p>
 <p class="font-mono text-xs text-gray-300 select-all bg-gray-50 px-3 py-1 rounded-lg">{{ $reservation->qr_code_token }}</p>
 </div>

 {{-- Actions post-expérience --}}
 @if($reservation->status === 'completed')
 <div class="border-t border-gray-100 pt-4 space-y-2">
 <a href="{{ route('reviews.create', $reservation->qr_code_token) }}"
 class="flex items-center gap-3 w-full py-3 bg-dokun-gold/10 border border-dokun-gold/30 text-dokun-gold rounded-xl font-bold text-sm hover:bg-dokun-gold/20 transition text-center justify-center">
 <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
 {{ __('app.receipt_leave_review') }}
 </a>
 <a href="{{ route('moments.create', $reservation->qr_code_token) }}"
 class="flex items-center gap-3 w-full py-3 bg-dokun-green/10 border border-dokun-green/30 text-dokun-green rounded-xl font-bold text-sm hover:bg-dokun-green/20 transition text-center justify-center">
 <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
 {{ __('app.moments_publish') }}
 </a>
 </div>
 @endif

 {{-- WhatsApp artisan --}}
 @if($reservation->artisan?->whatsapp)
 <a href="https://wa.me/{{ preg_replace('/\D/', '', $reservation->artisan->whatsapp) }}" target="_blank"
 class="no-print flex items-center gap-2 w-full py-3 bg-green-500 text-white rounded-xl font-bold text-sm hover:bg-green-600 transition mt-3 justify-center">
 <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 00-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
 Contacter l'artisan via WhatsApp
 </a>
 @endif
 </div>
 </div>

 {{-- Scan artisan --}}
 @auth
 @if(auth()->user()->id === $reservation->artisan?->user_id && $reservation->status !== 'completed')
 <div class="mt-4 fade-up delay-2">
 <form action="{{ route('reservations.scan', $reservation->qr_code_token) }}" method="POST">
 @csrf
 <button type="submit" class="no-print w-full py-3.5 bg-dokun-gold text-dokun-charcoal font-bold rounded-xl hover:bg-dokun-gold/90 active:scale-[.98] transition shadow-lg flex items-center justify-center gap-2 text-sm">
 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
 {{ __('app.receipt_mark_completed') }}
 </button>
 </form>
 </div>
 @endif
 @endauth

 {{-- Retour --}}
 <div class="text-center mt-6 no-print fade-up delay-3">
 <a href="{{ route('experiences.index') }}" class="text-white/50 hover:text-white text-sm font-semibold transition">
 ← {{ __('app.receipt_more_experiences') }}
 </a>
 </div>

 </div>
</main>

 {{-- Footer minimal --}}
 <footer class="text-center py-4 text-white/30 text-xs">
 <p>&copy; {{ date('Y') }} ƉƆKUN — {{ __('app.brand_tagline') }}</p>
 </footer>
</body>
</html>
