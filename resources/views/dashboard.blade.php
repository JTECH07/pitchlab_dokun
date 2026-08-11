<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-slate-800 leading-tight">
            {{ __('Tableau de Bord ƉƆKUN') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Welcome Banner -->
            <div class="bg-gradient-to-r from-amber-500 to-orange-500 rounded-3xl p-8 text-white shadow-lg mb-8">
                <h3 class="text-3xl font-black mb-2">Bonjour, {{ Auth::user()->name }} 👋</h3>
                <p class="text-amber-100 font-medium">
                    @if(Auth::user()->role === 'admin')
                        Vous êtes sur l'interface d'administration de la plateforme ƉƆKUN.
                    @else
                        Bienvenue dans votre espace artisan. Gérez vos expériences et réservations ici.
                    @endif
                </p>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 flex items-center gap-4">
                    <div class="w-14 h-14 rounded-full bg-blue-50 flex items-center justify-center text-blue-500">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                    <div>
                        <div class="text-3xl font-black text-slate-900">{{ $stats['artisans_count'] }}</div>
                        <div class="text-sm font-bold text-slate-500">Artisans inscrits</div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 flex items-center gap-4">
                    <div class="w-14 h-14 rounded-full bg-amber-50 flex items-center justify-center text-amber-500">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                    </div>
                    <div>
                        <div class="text-3xl font-black text-slate-900">{{ $stats['categories_count'] }}</div>
                        <div class="text-sm font-bold text-slate-500">Catégories</div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 flex items-center gap-4">
                    <div class="w-14 h-14 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-500">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    <div>
                        <div class="text-3xl font-black text-slate-900">{{ $stats['reservations_count'] }}</div>
                        <div class="text-sm font-bold text-slate-500">Réservations Totales</div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 flex items-center gap-4">
                    <div class="w-14 h-14 rounded-full bg-red-50 flex items-center justify-center text-red-500">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <div class="text-3xl font-black text-slate-900">{{ $stats['pending_reservations'] }}</div>
                        <div class="text-sm font-bold text-slate-500">Demandes en attente</div>
                    </div>
                </div>
            </div>

            <!-- Actions Pro -->
            <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-slate-100">
                <div class="p-8">
                    <h4 class="text-xl font-bold text-slate-800 mb-6">Actions Rapides</h4>
                    <div class="flex flex-wrap gap-4">
                        @if(Auth::user()->role === 'admin')
                            <a href="{{ route('admin.artisans.create') }}" class="px-6 py-3 bg-slate-900 text-white font-bold rounded-xl shadow-lg hover:bg-slate-800 transition-colors">
                                + Ajouter un artisan
                            </a>
                            <a href="{{ route('admin.artisans.index') }}" class="px-6 py-3 bg-white text-slate-700 font-bold rounded-xl border border-slate-200 shadow-sm hover:bg-slate-50 transition-colors">
                                Gérer les artisans
                            </a>
                            <a href="{{ route('admin.reservations.index') }}" class="px-6 py-3 bg-amber-500 text-white font-bold rounded-xl shadow-lg hover:bg-amber-600 transition-colors">
                                Voir les réservations
                                @if($stats['pending_reservations'] > 0)
                                    <span class="ml-2 bg-white text-amber-600 text-xs font-black px-2 py-0.5 rounded-full">{{ $stats['pending_reservations'] }}</span>
                                @endif
                            </a>
                        @else
                            <button class="px-6 py-3 bg-amber-500 text-white font-bold rounded-xl shadow-lg hover:bg-amber-600 transition-colors">
                                Mettre à jour mon profil
                            </button>
                            <button class="px-6 py-3 bg-white text-slate-700 font-bold rounded-xl border border-slate-200 shadow-sm hover:bg-slate-50 transition-colors">
                                Gérer mes expériences
                            </button>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
