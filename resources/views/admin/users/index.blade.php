<x-app-layout>
    <x-slot name="header">
        <h1 class="font-serif text-3xl text-dokun-green">Utilisateurs & rôles</h1>
    </x-slot>

    <div class="py-8 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if(session('success'))<div class="mb-5 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 px-5 py-3.5 font-semibold text-sm">{{ session('success') }}</div>@endif
        @if(session('error'))<div class="mb-5 rounded-xl bg-red-50 border border-red-200 text-red-700 px-5 py-3.5 font-semibold text-sm">{{ session('error') }}</div>@endif

        {{-- Créer un compte (tous rôles) --}}
        <section class="bg-white rounded-2xl border border-black/5 shadow-sm p-7 mb-8">
            <h2 class="font-serif text-2xl text-dokun-green mb-1">Créer un compte</h2>
            <p class="text-sm text-dokun-charcoal/55 mb-6">Pour les acteurs hors inscription publique : guides, institutions, chercheurs, partenaires.</p>

            <form method="POST" action="{{ route('admin.users.store') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
                @csrf
                <div class="md:col-span-1">
                    <label class="block text-xs font-bold uppercase tracking-wider text-dokun-charcoal/50 mb-1.5">Nom complet</label>
                    <input type="text" name="name" required value="{{ old('name') }}"
                        class="w-full rounded-xl border-gray-200 bg-[#F8F6F0] focus:border-dokun-green focus:ring-dokun-green text-sm">
                </div>
                <div class="md:col-span-1">
                    <label class="block text-xs font-bold uppercase tracking-wider text-dokun-charcoal/50 mb-1.5">Email</label>
                    <input type="email" name="email" required value="{{ old('email') }}"
                        class="w-full rounded-xl border-gray-200 bg-[#F8F6F0] focus:border-dokun-green focus:ring-dokun-green text-sm">
                </div>
                <div class="md:col-span-1">
                    <label class="block text-xs font-bold uppercase tracking-wider text-dokun-charcoal/50 mb-1.5">Mot de passe</label>
                    <input type="password" name="password" required
                        class="w-full rounded-xl border-gray-200 bg-[#F8F6F0] focus:border-dokun-green focus:ring-dokun-green text-sm">
                </div>
                <div class="md:col-span-1">
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
                <div class="md:col-span-1">
                    <button type="submit" class="w-full bg-dokun-green text-white py-3 rounded-xl font-bold text-sm hover:bg-dokun-green/90 transition shadow-lg shadow-dokun-green/20">Créer</button>
                </div>
            </form>
            @if($errors->any())<p class="text-red-600 text-xs mt-3 font-semibold">{{ $errors->first() }}</p>@endif
            <p class="text-xs text-dokun-charcoal/45 mt-4">Le compte est créé avec email déjà vérifié — l'utilisateur peut se connecter directement.</p>
        </section>

        {{-- Liste des utilisateurs --}}
        <section class="bg-white rounded-2xl border border-black/5 shadow-sm overflow-hidden">
            <div class="p-6 border-b flex flex-wrap justify-between items-center gap-3">
                <h2 class="font-serif text-2xl text-dokun-green">{{ $users->total() }} compte(s)</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-[#F8F6F0] text-left text-xs font-bold uppercase tracking-wider text-dokun-charcoal/50">
                            <th class="px-6 py-3.5">Utilisateur</th>
                            <th class="px-4 py-3.5">Rôle</th>
                            <th class="px-4 py-3.5">Email vérifié</th>
                            <th class="px-4 py-3.5">Réservations</th>
                            <th class="px-4 py-3.5">Inscrit le</th>
                            <th class="px-4 py-3.5 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @foreach($users as $u)
                        <tr class="hover:bg-[#F8F6F0]/60 transition">
                            <td class="px-6 py-4">
                                <span class="font-bold text-dokun-charcoal">{{ $u->name }}</span>
                                <span class="block text-xs text-dokun-charcoal/50">{{ $u->email }}</span>
                            </td>
                            <td class="px-4 py-4">
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
                            <td class="px-4 py-4">
                                @if($u->email_verified_at)
                                <span class="inline-flex items-center gap-1 text-emerald-700 font-semibold text-xs"><x-icon name="check-circle" class="w-4 h-4"/> Vérifié</span>
                                @else
                                <form method="POST" action="{{ route('admin.users.verify', $u) }}">
                                    @csrf
                                    <button class="text-amber-700 hover:text-amber-900 font-bold text-xs underline">Marquer vérifié</button>
                                </form>
                                @endif
                            </td>
                            <td class="px-4 py-4 text-dokun-charcoal/70">{{ $u->reservations_count }}</td>
                            <td class="px-4 py-4 text-dokun-charcoal/50 text-xs">{{ $u->created_at->format('d/m/Y') }}</td>
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
</x-app-layout>
