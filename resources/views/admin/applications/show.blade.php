<x-app-layout>
 <x-slot name="header">
 <div class="flex items-center gap-3">
 <a href="{{ route('admin.applications.index') }}" class="text-dokun-green hover:underline text-sm font-bold">← Candidatures</a>
 <h1 class="font-serif text-3xl text-dokun-green">Détail candidature</h1>
 </div>
 </x-slot>

 <div class="py-8 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
 @if(session('success'))<div class="mb-5 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 px-5 py-3.5 font-semibold text-sm">{{ session('success') }}</div>@endif

 <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
 {{-- Colonne infos --}}
 <div class="lg:col-span-2 space-y-6">
 <section class="bg-white rounded-2xl border border-black/5 shadow-sm p-6">
 <div class="flex items-start justify-between mb-4">
 <div>
 <h2 class="font-serif text-2xl text-dokun-green">{{ $application->first_name }} {{ $application->last_name }}</h2>
 @if($application->professional_name)<p class="text-sm text-dokun-charcoal/60 italic">"{{ $application->professional_name }}"</p>@endif
 </div>
 @if($application->status === 'pending')
 <span class="inline-block px-3 py-1 rounded-full bg-amber-100 text-amber-800 text-xs font-bold">En attente</span>
 @elseif($application->status === 'approved')
 <span class="inline-block px-3 py-1 rounded-full bg-emerald-100 text-emerald-800 text-xs font-bold">Approuvée</span>
 @else
 <span class="inline-block px-3 py-1 rounded-full bg-red-100 text-red-700 text-xs font-bold">Rejetée</span>
 @endif
 </div>
 <div class="grid grid-cols-2 gap-4 text-sm">
 <div><span class="font-bold text-dokun-charcoal/50">Email :</span> {{ $application->user->email }}</div>
 <div><span class="font-bold text-dokun-charcoal/50">Téléphone :</span> {{ $application->phone }}</div>
 @if($application->whatsapp)<div><span class="font-bold text-dokun-charcoal/50">WhatsApp :</span> {{ $application->whatsapp }}</div>@endif
 <div><span class="font-bold text-dokun-charcoal/50">Expérience :</span> {{ $application->experience_years }} ans</div>
 <div class="col-span-2"><span class="font-bold text-dokun-charcoal/50">Adresse :</span> {{ $application->address }}</div>
 </div>
 </section>

 <section class="bg-white rounded-2xl border border-black/5 shadow-sm p-6">
 <h3 class="font-serif text-lg text-dokun-green mb-2">Description</h3>
 <p class="text-sm text-dokun-charcoal/70">{{ $application->description }}</p>
 </section>

 @if($application->history)
 <section class="bg-white rounded-2xl border border-black/5 shadow-sm p-6">
 <h3 class="font-serif text-lg text-dokun-green mb-2">Histoire</h3>
 <p class="text-sm text-dokun-charcoal/70 whitespace-pre-line">{{ $application->history }}</p>
 </section>
 @endif

 @if($application->admin_notes)
 <section class="bg-white rounded-2xl border border-black/5 shadow-sm p-6">
 <h3 class="font-serif text-lg text-dokun-green mb-2">Notes admin</h3>
 <p class="text-sm text-dokun-charcoal/70">{{ $application->admin_notes }}</p>
 </section>
 @endif
 </div>

 {{-- Colonne actions --}}
 <div class="space-y-5">
 <div class="bg-white rounded-2xl border border-black/5 shadow-sm p-6">
 <h3 class="font-serif text-lg text-dokun-green mb-1">{{ $application->category->name ?? 'Sans catégorie' }}</h3>
 <p class="text-xs text-dokun-charcoal/50 mb-4">Soumise le {{ $application->created_at->format('d/m/Y à H:i') }}</p>

 @if($application->status === 'pending')
 <form method="POST" action="{{ route('admin.applications.approve', $application) }}" class="space-y-3" onsubmit="return confirm('Approuver cette candidature ? Un email sera envoyé à l\'artisan.')">
 @csrf
 <textarea name="admin_notes" rows="2" placeholder="Notes internes (optionnel)"
 class="w-full rounded-xl border-gray-200 bg-[#F8F6F0] focus:border-dokun-green focus:ring-dokun-green text-xs"></textarea>
 <button type="submit" class="w-full py-3 bg-emerald-600 text-white font-bold rounded-xl hover:bg-emerald-700 transition text-sm">
 Approuver
 </button>
 </form>

 <hr class="my-4 border-gray-100">

 <form method="POST" action="{{ route('admin.applications.reject', $application) }}" class="space-y-3" onsubmit="return confirm('Rejeter cette candidature ? Un email sera envoyé.')">
 @csrf
 <textarea name="admin_notes" rows="2" placeholder="Motif du rejet (recommandé)"
 class="w-full rounded-xl border-gray-200 bg-[#F8F6F0] focus:border-red-400 focus:ring-red-400 text-xs"></textarea>
 <button type="submit" class="w-full py-3 bg-red-500 text-white font-bold rounded-xl hover:bg-red-600 transition text-sm">
 Rejeter
 </button>
 </form>
 @elseif($application->status === 'approved')
 <p class="text-sm text-emerald-700 font-bold">Approuvée le {{ \Carbon\Carbon::parse($application->reviewed_at)->format('d/m/Y') }}</p>
 @else
 <p class="text-sm text-red-600 font-bold">Rejetée le {{ \Carbon\Carbon::parse($application->reviewed_at)->format('d/m/Y') }}</p>
 @endif
 </div>

 <a href="{{ route('admin.users.index') }}" class="block text-center text-xs text-dokun-charcoal/50 hover:text-dokun-green transition">Voir les utilisateurs →</a>
 </div>
 </div>
 </div>
</x-app-layout>
