<x-guest-layout>
    <div class="text-center mb-8">
        <h1 class="font-serif text-3xl text-[#064E3B] mb-2">Créer un compte</h1>
        <p class="text-[#17201D]/60 text-sm">Rejoignez la communauté ƉƆKUN</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf
        <div>
            <label for="role" class="block text-sm font-bold text-[#17201D] mb-2">Je rejoins ƉƆKUN en tant que</label>
            <select id="role" name="role" required class="w-full px-4 py-3.5 bg-[#F8F6F0] border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#064E3B] outline-none transition font-semibold">
                <option value="tourist" @selected(old('role', 'tourist') === 'tourist')>Visiteur ou touriste</option>
                <option value="artisan" @selected(old('role') === 'artisan')>Détenteur de savoir-faire</option>
                <option value="guide" @selected(old('role') === 'guide')>Guide touristique</option>
                <option value="institution" @selected(old('role') === 'institution')>École ou institution</option>
                <option value="researcher" @selected(old('role') === 'researcher')>Étudiant ou chercheur</option>
                <option value="partner" @selected(old('role') === 'partner')>Partenaire culturel ou touristique</option>
            </select>
            <p class="mt-2 text-xs text-[#17201D]/55">Vous pourrez compléter votre profil après l’inscription.</p>
            <x-input-error :messages="$errors->get('role')" class="mt-2"/>
        </div>
        <div>
            <label for="name" class="block text-sm font-bold text-[#17201D] mb-2">Votre nom complet</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                class="w-full px-4 py-3.5 bg-[#F8F6F0] border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#064E3B] outline-none transition font-semibold placeholder:font-normal placeholder:text-gray-400"
                placeholder="Ex: Koffi Dossou">
            <x-input-error :messages="$errors->get('name')" class="mt-2"/>
        </div>
        <div>
            <label for="email" class="block text-sm font-bold text-[#17201D] mb-2">Adresse email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required
                class="w-full px-4 py-3.5 bg-[#F8F6F0] border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#064E3B] outline-none transition font-semibold placeholder:font-normal placeholder:text-gray-400"
                placeholder="vous@exemple.com">
            <x-input-error :messages="$errors->get('email')" class="mt-2"/>
        </div>
        <div>
            <label for="password" class="block text-sm font-bold text-[#17201D] mb-2">Mot de passe</label>
            <input id="password" type="password" name="password" required
                class="w-full px-4 py-3.5 bg-[#F8F6F0] border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#064E3B] outline-none transition font-semibold"
                placeholder="Minimum 8 caractères">
            <x-input-error :messages="$errors->get('password')" class="mt-2"/>
        </div>
        <div>
            <label for="password_confirmation" class="block text-sm font-bold text-[#17201D] mb-2">Confirmer le mot de passe</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required
                class="w-full px-4 py-3.5 bg-[#F8F6F0] border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#064E3B] outline-none transition font-semibold"
                placeholder="Répétez le mot de passe">
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2"/>
        </div>

        <button type="submit" class="w-full py-4 bg-[#064E3B] text-white font-bold rounded-xl hover:bg-[#064E3B]/90 transition-all shadow-lg text-base">
            Créer mon compte
        </button>
    </form>

    <div class="mt-6 text-center text-sm text-[#17201D]/60">
        Déjà inscrit ?
        <a href="{{ route('login') }}" class="text-[#C99424] font-bold hover:underline ml-1">Se connecter</a>
    </div>
</x-guest-layout>
