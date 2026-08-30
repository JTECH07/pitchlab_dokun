@extends('admin.layouts.admin')

@section('title', 'Utilisateurs & rôles')
@section('page-title', 'Utilisateurs & rôles')

@section('content')
<div class="max-w-7xl mx-auto">
 @if(session('success'))<div class="mb-5 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 px-5 py-3.5 font-semibold text-sm">{{ session('success') }}</div>@endif
 @if(session('error'))<div class="mb-5 rounded-xl bg-red-50 border border-red-200 text-red-700 px-5 py-3.5 font-semibold text-sm">{{ session('error') }}</div>@endif

 <section class="bg-white rounded-2xl border border-black/5 shadow-sm p-7 mb-8">
 <h2 class="font-serif text-2xl text-dokun-green mb-1">Créer un compte</h2>
 <p class="text-sm text-dokun-charcoal/55 mb-6">Pour les acteurs hors inscription publique : guides, institutions, chercheurs, partenaires.</p>

 <form method="POST" action="{{ route('admin.users.store') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 items-end">
 @csrf
 <div class="sm:col-span-2 lg:col-span-1">
 <label class="block text-xs font-bold uppercase tracking-wider text-dokun-charcoal/50 mb-1.5">Nom complet</label>
 <input type="text" name="name" required value="{{ old('name') }}"
 class="w-full rounded-xl border-gray-200 bg-[#F8F6F0] focus:border-dokun-green focus:ring-dokun-green text-sm">
 </div>
 <div class="sm:col-span-2 lg:col-span-1">
 <label class="block text-xs font-bold uppercase tracking-wider text-dokun-charcoal/50 mb-1.5">Email</label>
 <input type="email" name="email" required value="{{ old('email') }}"
 class="w-full rounded-xl border-gray-200 bg-[#F8F6F0] focus:border-dokun-green focus:ring-dokun-green text-sm">
 </div>
 <div class="sm:col-span-2 lg:col-span-1">
 <label class="block text-xs font-bold uppercase tracking-wider text-dokun-charcoal/50 mb-1.5">Mot de passe</label>
 <input type="password" name="password" required
 class="w-full rounded-xl border-gray-200 bg-[#F8F6F0] focus:border-dokun-green focus:ring-dokun-green text-sm">
 </div>
 <div class="sm:col-span-2 lg:col-span-1">
 <label class="block text-xs font-bold uppercase tracking-wider text-dokun-charcoal/50 mb-1.5">Rôle</label>
 <select name="role" required class="w-full rounded-xl border-gray-200 bg-[#F8F6F0] focus:border-dokun-green focus:ring-dokun-green text-sm font-semibold">
 @foreach(\App\Models\User::ROLES as $role)
 <option value="{{ $role }}" {{ old('role') === $role ? 'selected' : '' }}>{{ [
 'tourist' => 'Visiteur', 'artisan' => 'Artisan', 'guide' => 'Guide',
 'institution' => 'Institution / École', 'researcher' => 'Chercheur',
 'partner' => 'Partenaire', 'admin' => 'Administrateur',
 ][$role] }}</option>
 @endforeach
 </select>
 </div>
 <div class="sm:col-span-2 lg:col-span-1">
 <button type="submit" class="w-full bg-dokun-green text-white py-3 rounded-xl font-bold text-sm hover:bg-dokun-green/90 transition shadow-lg shadow-dokun-green/20">Créer</button>
 </div>
 </form>
 @if($errors->any())<p class="text-red-600 text-xs mt-3 font-semibold">{{ $errors->first() }}</p>@endif
 <p class="text-xs text-dokun-charcoal/45 mt-4">Le compte est créé avec email déjà vérifié — l'utilisateur peut se connecter directement.</p>
 </section>

 <section class="bg-white rounded-2xl border border-black/5 shadow-sm overflow-hidden">
 <div class="p-6 border-b flex flex-wrap justify-between items-center gap-3">
 <h2 class="font-serif text-2xl text-dokun-green">{{ $users->total() }} compte(s)</h2>
 </div>
 <div class="overflow-x-auto">
 <table class="w-full text-sm">
 <thead>
 <tr class="bg-[#F8F6F0] text-left text-xs font-bold uppercase tracking-wider text-dokun-charcoal/50">
 <th class="px-6 py-3.5">Utilisateur</th>
 <th class="px-4 py-3.5 hidden sm:table-cell">Rôle</th>
 <th class="px-4 py-3.5 hidden md:table-cell">Email vérifié</th>
 <th class="px-4 py-3.5 hidden md:table-cell">Réservations</th>
 <th class="px-4 py-3.5 hidden lg:table-cell">Inscrit le</th>
 <th class="px-4 py-3.5 text-right">Actions</th>
 </tr>
 </thead>
 <tbody class="divide-y">
 @foreach($users as $u)
 <tr class="hover:bg-[#F8F6F0]/60 transition">
 <td class="px-6 py-4">
 <span class="font-bold text-dokun-charcoal">{{ $u->name }}</span>
 <span class="block text-xs text-dokun-charcoal/50">{{ $u->email }}</span>
 <span class="block sm:hidden text-xs font-bold {{ ['admin'=>'text-red-700','artisan'=>'text-dokun-gold','tourist'=>'text-dokun-green'][$u->role] ?? 'text-dokun-charcoal' }} mt-1">{{ $u->role }}</span>
 </td>
 <td class="px-4 py-4 hidden sm:table-cell">
 <form method="POST" action="{{ route('admin.users.role', $u) }}" class="flex items-center gap-2" onchange="this.submit()">
 @csrf
 @method('PATCH')
 <select name="role" class="rounded-lg border-gray-200 bg-white text-xs font-bold {{ ['admin'=>'text-red-700','artisan'=>'text-dokun-gold','tourist'=>'text-dokun-green'][$u->role] ?? 'text-dokun-charcoal' }}">
 @foreach(\App\Models\User::ROLES as $role)
 <option value="{{ $role }}" {{ $u->role === $role ? 'selected' : '' }}>{{ $role }}</option>
 @endforeach
 </select>
 </form>
 </td>
 <td class="px-4 py-4 hidden md:table-cell">
 @if($u->email_verified_at)
 <span class="inline-flex items-center gap-1 text-emerald-700 font-semibold text-xs"><x-icon name="check-circle" class="w-4 h-4"/> Vérifié</span>
 @else
 <form method="POST" action="{{ route('admin.users.verify', $u) }}">
 @csrf
 <button class="text-amber-700 hover:text-amber-900 font-bold text-xs underline">Marquer vérifié</button>
 </form>
 @endif
 </td>
 <td class="px-4 py-4 text-dokun-charcoal/70 hidden md:table-cell">{{ $u->reservations_count }}</td>
 <td class="px-4 py-4 text-dokun-charcoal/50 text-xs hidden lg:table-cell">{{ $u->created_at->format('d/m/Y') }}</td>
 <td class="px-4 py-4 text-right">
 @unless($u->id === auth()->id())
 <form method="POST" action="{{ route('admin.users.destroy', $u) }}" onsubmit="return confirm('Supprimer définitivement ce compte ?')">
 @csrf
 @method('DELETE')
 <button class="text-red-500 hover:text-red-700 font-bold text-xs">Supprimer</button>
 </form>
 @endunless
 </td>
 </tr>
 @endforeach
 </tbody>
 </table>
 </div>
 <div class="p-5 border-t">{{ $users->links() }}</div>
 </section>
</div>
@endsection
