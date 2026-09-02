@extends('admin.layouts.admin')

@section('title', 'Détail demande')
@section('page-title', 'Détail demande')

@section('content')
<div class="max-w-4xl mx-auto">
 <a href="{{ route('admin.actor-requests.index') }}" class="inline-flex items-center gap-2 text-dokun-green hover:underline text-sm font-bold mb-6">
 <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
 Retour aux demandes
 </a>

 @if(session('success'))<div class="mb-5 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 px-5 py-3.5 font-semibold text-sm">{{ session('success') }}</div>@endif

 @php
 $roleLabels = ['guide'=>'Guide touristique','institution'=>'Institution culturelle','researcher'=>'Chercheur','partner'=>'Partenaire tourisme'];
 @endphp

 <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
 <div class="lg:col-span-2 space-y-6">
 <section class="bg-white rounded-2xl border border-black/5 shadow-sm p-6">
 <div class="flex items-start justify-between mb-4">
 <div>
 <h2 class="font-serif text-2xl text-dokun-green">{{ $request->name }}</h2>
 <p class="text-sm text-dokun-charcoal/60 mt-1">
 <span class="inline-block px-2.5 py-0.5 rounded-full bg-[#C99424]/10 text-[#C99424] text-xs font-bold">{{ $roleLabels[$request->role] ?? $request->role }}</span>
 </p>
 </div>
 @if($request->status === 'pending')
 <span class="inline-block px-3 py-1 rounded-full bg-amber-100 text-amber-800 text-xs font-bold">En attente</span>
 @elseif($request->status === 'approved')
 <span class="inline-block px-3 py-1 rounded-full bg-emerald-100 text-emerald-800 text-xs font-bold">Approuvée</span>
 @else
 <span class="inline-block px-3 py-1 rounded-full bg-red-100 text-red-700 text-xs font-bold">Rejetée</span>
 @endif
 </div>
 <div class="grid grid-cols-2 gap-4 text-sm">
 <div><span class="font-bold text-dokun-charcoal/50">Email :</span> {{ $request->email }}</div>
 @if($request->phone)<div><span class="font-bold text-dokun-charcoal/50">Téléphone :</span> {{ $request->phone }}</div>@endif
 @if($request->organization)<div class="col-span-2"><span class="font-bold text-dokun-charcoal/50">Organisation :</span> {{ $request->organization }}</div>@endif
 </div>
 </section>

 <section class="bg-white rounded-2xl border border-black/5 shadow-sm p-6">
 <h3 class="font-serif text-lg text-dokun-green mb-2">Motivation</h3>
 <p class="text-sm text-dokun-charcoal/70 whitespace-pre-line">{{ $request->motivation }}</p>
 </section>

 @php
 $extra = $request->extra_data ?: [];
 $extraLabels = [
 'guide_years' => "Années d'expérience", 'guide_languages' => 'Langues parlées',
 'guide_zone' => 'Zone couverte', 'guide_license' => 'Licence / carte pro',
 'inst_type' => 'Type de structure', 'inst_city' => 'Ville', 'inst_heritage' => 'Patrimoines représentés',
 'inst_partnership' => 'Collaboration recherchée',
 'res_domain' => 'Domaine de recherche', 'res_institution' => 'Institution', 'res_topic' => 'Travaux en cours',
 'res_publications' => 'Références / publications',
 'ptn_type' => "Type d'établissement", 'ptn_city' => 'Ville', 'ptn_services' => 'Prestations', 'ptn_website' => 'Site web',
 ];
 $selectLabels = [
 'inst_type' => ['musee'=>'Musée','association'=>'Association','collectivite'=>'Collectivité locale','centre'=>'Centre culturel','autre'=>'Autre'],
 'ptn_type' => ['hotel'=>'Hôtel / hébergement','agence'=>'Agence de voyage','restaurant'=>'Restauration','transport'=>'Transport','autre'=>'Autre'],
 ];
 @endphp
 @if($extra)
 <section class="bg-white rounded-2xl border border-black/5 shadow-sm p-6">
 <h3 class="font-serif text-lg text-dokun-green mb-4">Informations spécifiques au rôle</h3>
 <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
 @foreach($extra as $ekey => $evalue)
 @if($evalue === '' || $evalue === null) @continue @endif
 @php
 $label = $extraLabels[$ekey] ?? ucfirst(str_replace('_', ' ', $ekey));
 $value = $evalue;
 if (isset($selectLabels[$ekey]) && isset($selectLabels[$ekey][$evalue])) {
 $value = $selectLabels[$ekey][$evalue];
 }
 @endphp
 <div class="@if(is_string($value) && strlen($value) > 60) sm:col-span-2 @endif">
 <span class="font-bold text-dokun-charcoal/50 block mb-0.5">{{ $label }} :</span>
 <span class="text-dokun-charcoal whitespace-pre-line">{{ $value }}</span>
 </div>
 @endforeach
 </div>
 </section>
 @endif

 @if($request->admin_notes)
 <section class="bg-white rounded-2xl border border-black/5 shadow-sm p-6">
 <h3 class="font-serif text-lg text-dokun-green mb-2">Notes admin</h3>
 <p class="text-sm text-dokun-charcoal/70">{{ $request->admin_notes }}</p>
 </section>
 @endif
 </div>

 <div class="space-y-5">
 <div class="bg-white rounded-2xl border border-black/5 shadow-sm p-6">
 <p class="text-xs text-dokun-charcoal/50 mb-4">Soumise le {{ $request->created_at->format('d/m/Y à H:i') }}</p>

 @if($request->status === 'pending')
 <form method="POST" action="{{ route('admin.actor-requests.approve', $request) }}" class="space-y-3" onsubmit="return confirm('Approuver cette demande ? Un compte sera créé et un email envoyé.')">
 @csrf
 <textarea name="admin_notes" rows="2" placeholder="Notes internes (optionnel)"
 class="w-full rounded-xl border-gray-200 bg-[#F8F6F0] focus:border-dokun-green focus:ring-dokun-green text-xs"></textarea>
 <button type="submit" class="w-full py-3 bg-emerald-600 text-white font-bold rounded-xl hover:bg-emerald-700 transition text-sm">
 Approuver & créer le compte
 </button>
 </form>

 <hr class="my-4 border-gray-100">

 <form method="POST" action="{{ route('admin.actor-requests.reject', $request) }}" class="space-y-3" onsubmit="return confirm('Rejeter cette demande ?')">
 @csrf
 <textarea name="admin_notes" rows="2" placeholder="Motif du rejet (recommandé)"
 class="w-full rounded-xl border-gray-200 bg-[#F8F6F0] focus:border-red-400 focus:ring-red-400 text-xs"></textarea>
 <button type="submit" class="w-full py-3 bg-red-500 text-white font-bold rounded-xl hover:bg-red-600 transition text-sm">
 Rejeter
 </button>
 </form>
@elseif($request->status === 'approved')
  <p class="text-sm text-emerald-700 font-bold">Approuvée le {{ optional($request->reviewed_at)->format('d/m/Y') }}</p>
  <a href="{{ route('admin.users.index') }}" class="block mt-3 text-xs text-dokun-green hover:underline font-bold text-center">→ Voir les utilisateurs</a>
  @else
  <p class="text-sm text-red-600 font-bold">Rejetée le {{ optional($request->reviewed_at)->format('d/m/Y') }}</p>
 @endif
 </div>
 </div>
 </div>
</div>
@endsection
