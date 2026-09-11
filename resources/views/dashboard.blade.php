<x-app-layout>
 <x-slot name="header"><h1 class="font-serif text-3xl text-dokun-green">{{ $mode === 'admin' ? 'Pilotage ƉƆKUN' : 'Mon espace' }}</h1></x-slot>
 @if($mode === 'visitor')
 <div class="min-h-screen bg-dokun-ivory py-8">
  <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

   {{-- Hero --}}
   <section class="rounded-3xl bg-dokun-charcoal text-white p-8 md:p-10 border-t-4 border-dokun-gold shadow-2xl relative overflow-hidden">
    <div class="absolute top-0 right-0 w-64 h-64 bg-dokun-gold rounded-full -mr-20 -mt-20 opacity-20 blur-3xl z-0"></div>
    <div class="relative z-10">
     <p class="text-dokun-gold text-xs font-bold tracking-[.18em] uppercase">Votre carnet de voyage</p>
     <h2 class="font-serif text-4xl mt-2">Bonjour, <span class="text-dokun-gold">{{ Auth::user()->name }}</span></h2>
     <p class="mt-3 text-white/75">Retrouvez vos réservations et continuez l'exploration de Porto-Novo.</p>
     <a href="{{ route('experiences.index') }}" class="inline-block bg-dokun-gold text-white hover:bg-yellow-600 transition rounded-xl px-5 py-3 mt-6 font-bold shadow-lg shadow-dokun-gold/20">Découvrir une expérience</a>
    </div>
   </section>

   {{-- Réservations --}}
   <section class="bg-white rounded-2xl border border-black/5 mt-8 overflow-hidden shadow-sm">
    <div class="p-6 border-b">
     <h2 class="font-serif text-2xl text-dokun-green">Mes réservations</h2>
    </div>
    <div class="divide-y">
     @forelse($reservations as $reservation)
     <article class="p-6 flex flex-wrap justify-between gap-4">
      <div class="flex-1 min-w-0">
       <div class="flex items-center gap-3 mb-1">
        <h3 class="font-bold text-lg text-dokun-charcoal">{{ $reservation->experience_type }}</h3>
        @php
         $statusColors = ['pending'=>'bg-amber-100 text-amber-700','accepted'=>'bg-emerald-100 text-emerald-700','completed'=>'bg-blue-100 text-blue-700','rejected'=>'bg-red-100 text-red-700'];
         $statusLabels = ['pending'=>'En attente','accepted'=>'Acceptée','completed'=>'Réalisée','rejected'=>'Rejetée'];
        @endphp
        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $statusColors[$reservation->status] ?? '' }}">{{ $statusLabels[$reservation->status] ?? $reservation->status }}</span>
       </div>
       <p class="text-sm text-dokun-charcoal/60 capitalize">{{ \Carbon\Carbon::parse($reservation->requested_date)->locale('fr')->translatedFormat('l d F Y') }} · {{ $reservation->guests_count }} personne(s)</p>
       <p class="text-xs mt-1 text-dokun-charcoal/50">Référence {{ $reservation->reference }}</p>

       {{-- Boutons d'action --}}
       <div class="flex flex-wrap gap-2 mt-4">
        {{-- QR Code : toujours visible --}}
        <a href="{{ route('reservations.receipt', $reservation->qr_code_token) }}"
           class="inline-flex items-center gap-1.5 px-4 py-2 bg-dokun-green text-white text-xs font-bold rounded-xl hover:bg-dokun-green/90 transition shadow-sm">
         <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
         Voir mon billet QR
        </a>

        @if($reservation->status === 'completed')
        {{-- Avis --}}
        <a href="{{ route('reviews.create', $reservation->qr_code_token) }}"
           class="inline-flex items-center gap-1.5 px-4 py-2 bg-dokun-gold text-white text-xs font-bold rounded-xl hover:bg-yellow-500 transition shadow-sm">
         <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
         Donner un avis
        </a>
        {{-- Moment --}}
        <a href="{{ route('moments.create', $reservation->qr_code_token) }}"
           class="inline-flex items-center gap-1.5 px-4 py-2 bg-dokun-charcoal text-white text-xs font-bold rounded-xl hover:bg-dokun-charcoal/90 transition shadow-sm">
         <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
         Publier un moment
        </a>
        @else
        <span class="inline-flex items-center gap-1.5 px-4 py-2 bg-gray-100 text-gray-400 text-xs font-bold rounded-xl cursor-default">
         <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
         Avis & Moment (après l'expérience)
        </span>
        @endif
       </div>
      </div>
     </article>
     @empty
     <div class="p-10 text-center text-dokun-charcoal/40">
      <svg class="w-12 h-12 mx-auto mb-3 text-dokun-charcoal/20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
      <p class="font-semibold">Aucune réservation pour le moment</p>
      <p class="text-sm mt-1">Découvrez nos expériences culturelles !</p>
      <a href="{{ route('experiences.index') }}" class="inline-block mt-4 bg-dokun-gold text-white px-5 py-2.5 rounded-xl font-bold text-sm hover:bg-yellow-600 transition">Explorer les expériences</a>
     </div>
     @endforelse
    </div>
   </section>

   {{-- Devenir artisan --}}
   <a href="{{ route('artisan.apply') }}" class="mt-8 block bg-white rounded-2xl border border-[#C99424]/20 p-6 flex items-center gap-5 hover:border-[#C99424]/50 hover:shadow-md transition group">
    <div class="w-14 h-14 bg-[#C99424]/10 rounded-2xl flex items-center justify-center flex-shrink-0 group-hover:bg-[#C99424]/20 transition">
     <svg class="w-7 h-7 text-[#C99424]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M14.7 6.3a1 1 0 000 1.4l1.6 1.6a1 1 0 001.4 0l3.77-3.77a6 6 0 01-7.94 7.94l-6.91 6.91a2.12 2.12 0 01-3-3l6.91-6.91a6 6 0 017.94-7.94l-3.76 3.76z"/></svg>
    </div>
    <div>
     <p class="font-serif text-lg text-dokun-charcoal group-hover:text-[#C99424] transition">Devenir artisan ƉƆKUN</p>
     <p class="text-sm text-dokun-charcoal/50">Présentez votre savoir-faire et recevez des visiteurs du monde entier.</p>
    </div>
    <svg class="w-5 h-5 text-dokun-charcoal/30 ml-auto group-hover:text-[#C99424] transition" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7"/></svg>
   </a>

  </div>
 </div>
 @else
 {{-- Admin dashboard (unchanged) --}}
 <div class="min-h-screen bg-dokun-ivory py-8"><div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
  <section class="rounded-3xl bg-dokun-charcoal text-white p-8 md:p-10 border-t-4 border-dokun-gold shadow-2xl relative overflow-hidden"><img src="{{ url('images/hero/tourisme_porto_novo.png') }}" alt="" class="absolute inset-0 w-full h-full object-cover opacity-10 mix-blend-overlay"><div class="absolute top-0 right-0 w-64 h-64 bg-dokun-gold rounded-full -mr-20 -mt-20 opacity-20 blur-3xl z-0"></div><div class="relative z-10"><p class="text-dokun-gold text-xs font-bold tracking-[.18em] uppercase">Espace administration</p><h2 class="font-serif text-4xl mt-2">Bonjour, <span class="text-dokun-gold">{{ Auth::user()->name }}</span></h2><p class="mt-3 text-white/75 max-w-2xl">Gardez une vue simple sur les savoir-faire, les détenteurs et les demandes de visite.</p></div></section>
  <div class="grid grid-cols-2 lg:grid-cols-4 gap-5 my-7">@foreach([['Artisans',$stats['artisans_count'] ?? 0],['Savoir-faire',$stats['categories_count'] ?? 0],['Réservations',$stats['reservations_count'] ?? 0],['À traiter',$stats['pending_reservations'] ?? 0]] as [$label,$value])<div class="bg-white rounded-2xl p-6 border border-black/5 shadow-sm"><p class="text-sm font-semibold text-dokun-charcoal/55">{{ $label }}</p><p class="font-serif text-4xl text-dokun-gold mt-2">{{ $value }}</p></div>@endforeach</div>
  <section class="bg-white rounded-2xl p-7 border border-black/5 shadow-sm"><h2 class="font-serif text-2xl text-dokun-green">Actions rapides</h2><div class="flex flex-wrap gap-3 mt-5"><a href="{{ route('admin.applications.index') }}" class="bg-[#C99424] hover:bg-[#b3831f] transition text-white px-5 py-3 rounded-xl font-bold shadow-lg shadow-dokun-gold/20 flex items-center gap-2"><svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>Candidatures artisans</a><a href="{{ route('admin.actor-requests.index') }}" class="border-2 border-[#C99424] text-[#C99424] hover:bg-[#C99424] hover:text-white transition px-5 py-3 rounded-xl font-bold flex items-center gap-2"><svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>Demandes acteurs</a><a href="{{ route('admin.artisans.create') }}" class="bg-dokun-green hover:bg-dokun-green/90 transition text-white px-5 py-3 rounded-xl font-bold shadow-lg shadow-dokun-green/20">Ajouter un artisan</a><a href="{{ route('admin.experiences.index') }}" class="bg-dokun-green hover:bg-dokun-green/90 transition text-white px-5 py-3 rounded-xl font-bold shadow-lg shadow-dokun-green/20">Expériences</a><a href="{{ route('admin.savoir-faires.index') }}" class="border-2 border-dokun-green text-dokun-green hover:bg-dokun-green hover:text-white transition px-5 py-3 rounded-xl font-bold">Gérer les contenus</a><a href="{{ route('admin.reservations.index') }}" class="bg-dokun-gold hover:bg-yellow-600 transition text-white px-5 py-3 rounded-xl font-bold shadow-lg shadow-dokun-gold/20">Voir les réservations</a><a href="{{ route('admin.users.index') }}" class="border-2 border-dokun-gold text-dokun-gold hover:bg-dokun-gold hover:text-white transition px-5 py-3 rounded-xl font-bold">Utilisateurs</a><a href="{{ route('admin.reviews.index') }}" class="border-2 border-dokun-green text-dokun-green hover:bg-dokun-green hover:text-white transition px-5 py-3 rounded-xl font-bold">Avis</a><a href="{{ route('admin.admin.moments.index') }}" class="border-2 border-dokun-green text-dokun-green hover:bg-dokun-green hover:text-white transition px-5 py-3 rounded-xl font-bold">Moments</a></div></section>
 </div></div>
 @endif
</x-app-layout>
