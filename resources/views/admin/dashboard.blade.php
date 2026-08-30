@extends('admin.layouts.admin')

@section('title', 'Tableau de bord')
@section('page-title', 'Pilotage ƉƆKUN')

@section('content')
<div class="max-w-7xl mx-auto">
 <section class="rounded-3xl bg-dokun-charcoal text-white p-8 md:p-10 border-t-4 border-dokun-gold shadow-2xl relative overflow-hidden">
 <img src="{{ asset('images/dokun_bg3.jpg') }}" alt="" class="absolute inset-0 w-full h-full object-cover opacity-10 mix-blend-overlay">
 <div class="absolute top-0 right-0 w-64 h-64 bg-dokun-gold rounded-full -mr-20 -mt-20 opacity-20 blur-3xl z-0"></div>
 <div class="relative z-10">
 <p class="text-dokun-gold text-xs font-bold tracking-[.18em] uppercase">Espace administration</p>
 <h2 class="font-serif text-3xl md:text-4xl mt-2">Bonjour, <span class="text-dokun-gold">{{ Auth::user()->name }}</span></h2>
 <p class="mt-3 text-white/75 max-w-2xl">Gardez une vue simple sur les savoir-faire, les détenteurs et les demandes de visite.</p>
 </div>
 </section>

 <div class="grid grid-cols-2 lg:grid-cols-4 gap-5 my-7">
 @foreach([['Artisans', $stats['artisans_count'] ?? 0, 'admin.artisans.index', 'admin.artisans.*'], ['Savoir-faire', $stats['categories_count'] ?? 0, 'admin.savoir-faires.index', 'admin.savoir-faires.*'], ['Réservations', $stats['reservations_count'] ?? 0, 'admin.reservations.index', 'admin.reservations.*'], ['À traiter', $stats['pending_reservations'] ?? 0, 'admin.applications.index', 'admin.applications.*']] as [$label, $value, $route, $routeIs])
 <a href="{{ route($route) }}" class="bg-white rounded-2xl p-6 border border-black/5 shadow-sm hover:shadow-md hover:border-dokun-gold/30 transition group">
 <p class="text-sm font-semibold text-dokun-charcoal/55">{{ $label }}</p>
 <p class="font-serif text-4xl text-dokun-gold mt-2 group-hover:scale-105 transition-transform origin-left">{{ $value }}</p>
 </a>
 @endforeach
 </div>

 <section class="bg-white rounded-2xl p-7 border border-black/5 shadow-sm">
 <h2 class="font-serif text-2xl text-dokun-green">Actions rapides</h2>
 <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 mt-5">
 <a href="{{ route('admin.applications.index') }}" class="bg-[#C99424] hover:bg-[#b3831f] transition text-white px-5 py-3 rounded-xl font-bold shadow-lg shadow-dokun-gold/20 flex items-center gap-2">
 <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
 <span class="truncate">Candidatures artisans</span>
 </a>
 <a href="{{ route('admin.actor-requests.index') }}" class="border-2 border-[#C99424] text-[#C99424] hover:bg-[#C99424] hover:text-white transition px-5 py-3 rounded-xl font-bold flex items-center gap-2">
 <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
 <span class="truncate">Demandes acteurs</span>
 </a>
 <a href="{{ route('admin.artisans.create') }}" class="bg-dokun-green hover:bg-dokun-green/90 transition text-white px-5 py-3 rounded-xl font-bold shadow-lg shadow-dokun-green/20 flex items-center gap-2">
 <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4"/></svg>
 Ajouter un artisan
 </a>
 <a href="{{ route('admin.experiences.index') }}" class="bg-dokun-green hover:bg-dokun-green/90 transition text-white px-5 py-3 rounded-xl font-bold shadow-lg shadow-dokun-green/20 flex items-center gap-2">
 <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
 Expériences
 </a>
 <a href="{{ route('admin.savoir-faires.index') }}" class="border-2 border-dokun-green text-dokun-green hover:bg-dokun-green hover:text-white transition px-5 py-3 rounded-xl font-bold flex items-center gap-2">
 <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
 Gérer les contenus
 </a>
 <a href="{{ route('admin.reservations.index') }}" class="bg-dokun-gold hover:bg-yellow-600 transition text-white px-5 py-3 rounded-xl font-bold shadow-lg shadow-dokun-gold/20 flex items-center gap-2">
 <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
 Voir les réservations
 </a>
 <a href="{{ route('admin.users.index') }}" class="border-2 border-dokun-charcoal text-dokun-charcoal hover:bg-dokun-charcoal hover:text-white transition px-5 py-3 rounded-xl font-bold flex items-center gap-2">
 <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
 Utilisateurs
 </a>
 </div>
 </section>
</div>
@endsection
