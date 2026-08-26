<x-guest-layout>
    <div class="text-center mb-8">
        <h1 class="font-serif text-3xl text-[#064E3B] mb-2">Rejoindre ƉƆKUN</h1>
        <p class="text-[#17201D]/60 text-sm">Vous êtes guide, chercheur, institution ou partenaire ? Proposez-vous !</p>
    </div>

    <form method="POST" action="{{ route('actor-requests.submit') }}" class="space-y-5" x-data="{ role: '{{ old('role') }}' }">
        @csrf

        {{-- Sélection du rôle --}}
        <div class="grid grid-cols-2 gap-3">
            @foreach($roles as $key => $meta)
                <label class="relative cursor-pointer rounded-2xl border-2 p-3.5 text-center transition-all duration-200"
                       :class="role === '{{ $key }}' ? 'border-[#C99424] bg-[#C99424]/5 shadow-md' : 'border-gray-200 hover:border-gray-300'">
                    <input type="radio" name="role" value="{{ $key }}" class="sr-only" x-model="role">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"
                         class="w-7 h-7 mx-auto mb-1.5 transition-colors" :class="role === '{{ $key }}' ? 'text-[#C99424]' : 'text-gray-400'">
                        <path d="{{ $meta['icon'] }}"/>
                    </svg>
                    <span class="block font-bold text-xs" :class="role === '{{ $key }}' ? 'text-[#C99424]' : 'text-[#17201D]/60'">{{ $meta['label'] }}</span>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" class="w-4 h-4 absolute top-1.5 right-1.5 text-[#C99424]" x-show="role === '{{ $key }}'" x-cloak><path d="M20 6L9 17l-5-5"/></svg>
                </label>
            @endforeach
        </div>
        <x-input-error :messages="$errors->get('role')" class="mt-1"/>

        {{-- Infos personnelles --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-bold text-[#17201D] mb-2">Nom complet</label>
                <input type="text" name="name" value="{{ old('name') }}" required
                    class="w-full px-4 py-3 bg-[#F8F6F0] border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#064E3B] outline-none transition font-semibold text-sm"
                    placeholder="Ex: Koffi Dossou">
                <x-input-error :messages="$errors->get('name')" class="mt-1.5"/>
            </div>
            <div>
                <label class="block text-sm font-bold text-[#17201D] mb-2">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required
                    class="w-full px-4 py-3 bg-[#F8F6F0] border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#064E3B] outline-none transition font-semibold text-sm"
                    placeholder="vous@exemple.com">
                <x-input-error :messages="$errors->get('email')" class="mt-1.5"/>
            </div>
            <div>
                <label class="block text-sm font-bold text-[#17201D] mb-2">Téléphone <span class="text-[#17201D]/30 normal-case">(optionnel)</span></label>
                <input type="text" name="phone" value="{{ old('phone') }}"
                    class="w-full px-4 py-3 bg-[#F8F6F0] border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#064E3B] outline-none transition font-semibold text-sm"
                    placeholder="+229 97 00 00 00">
            </div>
            <div>
                <label class="block text-sm font-bold text-[#17201D] mb-2">Organisation <span class="text-[#17201D]/30 normal-case">(optionnel)</span></label>
                <input type="text" name="organization" value="{{ old('organization') }}"
                    class="w-full px-4 py-3 bg-[#F8F6F0] border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#064E3B] outline-none transition font-semibold text-sm"
                    placeholder="Nom de votre structure">
            </div>
        </div>

        {{-- Motivation --}}
        <div>
            <label class="block text-sm font-bold text-[#17201D] mb-2">Pourquoi rejoindre ƉƆKUN ?</label>
            <textarea name="motivation" rows="3" required maxlength="2000"
                class="w-full px-4 py-3 bg-[#F8F6F0] border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#064E3B] outline-none transition font-semibold text-sm"
                placeholder="Décrivez votre activité et ce que vous souhaitez apporter à la communauté…">{{ old('motivation') }}</textarea>
            <x-input-error :messages="$errors->get('motivation')" class="mt-1.5"/>
        </div>

        <button type="submit" class="w-full py-4 bg-[#064E3B] text-white font-bold rounded-xl hover:bg-[#064E3B]/90 transition-all shadow-lg text-base">
            Envoyer ma demande
        </button>
    </form>

    <div class="mt-6 text-center text-sm text-[#17201D]/60">
        Déjà inscrit ?
        <a href="{{ route('login') }}" class="text-[#C99424] font-bold hover:underline ml-1">Se connecter</a>
    </div>
</x-guest-layout>
