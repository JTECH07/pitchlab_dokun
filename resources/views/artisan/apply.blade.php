<x-app-layout>
    <x-slot name="header">
        <h1 class="font-serif text-3xl text-dokun-green">Devenir artisan ƉƆKUN</h1>
    </x-slot>

    <div class="py-8 max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

        @if(session('error'))
            <div class="mb-6 rounded-xl bg-red-50 border border-red-200 text-red-700 px-5 py-3.5 font-semibold text-sm">{{ session('error') }}</div>
        @endif

        @if($existing && $existing->status === 'pending')
            <div class="rounded-2xl bg-amber-50 border border-amber-200 p-6 text-center">
                <p class="font-serif text-xl text-amber-800 mb-2">Candidature en cours d'examen</p>
                <p class="text-sm text-amber-700">Notre équipe examine votre dossier. Vous recevrez un email dès que la décision sera prise.</p>
            </div>
        @else
            <form method="POST" action="{{ route('artisan.apply.submit') }}" class="space-y-8">
                @csrf

                {{-- Identité --}}
                <section class="bg-white rounded-2xl border border-black/5 shadow-sm p-6">
                    <h2 class="font-serif text-xl text-dokun-green mb-1">Votre identité</h2>
                    <p class="text-xs text-dokun-charcoal/50 mb-5">Qui êtes-vous ?</p>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-dokun-charcoal/50 mb-1.5">Prénom</label>
                            <input type="text" name="first_name" value="{{ old('first_name', $existing?->first_name) }}" required
                                class="w-full rounded-xl border-gray-200 bg-[#F8F6F0] focus:border-dokun-green focus:ring-dokun-green text-sm">
                            <x-input-error :messages="$errors->get('first_name')" class="mt-1.5"/>
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-dokun-charcoal/50 mb-1.5">Nom</label>
                            <input type="text" name="last_name" value="{{ old('last_name', $existing?->last_name) }}" required
                                class="w-full rounded-xl border-gray-200 bg-[#F8F6F0] focus:border-dokun-green focus:ring-dokun-green text-sm">
                            <x-input-error :messages="$errors->get('last_name')" class="mt-1.5"/>
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-dokun-charcoal/50 mb-1.5">Nom professionnel <span class="text-dokun-charcoal/30 normal-case">(optionnel)</span></label>
                            <input type="text" name="professional_name" value="{{ old('professional_name', $existing?->professional_name) }}"
                                class="w-full rounded-xl border-gray-200 bg-[#F8F6F0] focus:border-dokun-green focus:ring-dokun-green text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-dokun-charcoal/50 mb-1.5">Catégorie</label>
                            <select name="category_id" required class="w-full rounded-xl border-gray-200 bg-[#F8F6F0] focus:border-dokun-green focus:ring-dokun-green text-sm">
                                <option value="">Choisir…</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ old('category_id', $existing?->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('category_id')" class="mt-1.5"/>
                        </div>
                    </div>
                </section>

                {{-- Contact --}}
                <section class="bg-white rounded-2xl border border-black/5 shadow-sm p-6">
                    <h2 class="font-serif text-xl text-dokun-green mb-1">Contact</h2>
                    <p class="text-xs text-dokun-charcoal/50 mb-5">Comment vous joindre ?</p>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-dokun-charcoal/50 mb-1.5">Téléphone</label>
                            <input type="text" name="phone" value="{{ old('phone', $existing?->phone) }}" required placeholder="+229 97 00 00 00"
                                class="w-full rounded-xl border-gray-200 bg-[#F8F6F0] focus:border-dokun-green focus:ring-dokun-green text-sm">
                            <x-input-error :messages="$errors->get('phone')" class="mt-1.5"/>
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-dokun-charcoal/50 mb-1.5">WhatsApp <span class="text-dokun-charcoal/30 normal-case">(optionnel)</span></label>
                            <input type="text" name="whatsapp" value="{{ old('whatsapp', $existing?->whatsapp) }}" placeholder="+229 97 00 00 00"
                                class="w-full rounded-xl border-gray-200 bg-[#F8F6F0] focus:border-dokun-green focus:ring-dokun-green text-sm">
                        </div>
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-bold uppercase tracking-wider text-dokun-charcoal/50 mb-1.5">Adresse / Atelier</label>
                            <input type="text" name="address" value="{{ old('address', $existing?->address) }}" required
                                class="w-full rounded-xl border-gray-200 bg-[#F8F6F0] focus:border-dokun-green focus:ring-dokun-green text-sm">
                            <x-input-error :messages="$errors->get('address')" class="mt-1.5"/>
                        </div>
                    </div>
                </section>

                {{-- Savoir-faire --}}
                <section class="bg-white rounded-2xl border border-black/5 shadow-sm p-6">
                    <h2 class="font-serif text-xl text-dokun-green mb-1">Votre activité</h2>
                    <p class="text-xs text-dokun-charcoal/50 mb-5">Parlez-nous de vous et de votre métier.</p>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-dokun-charcoal/50 mb-1.5">Années d'expérience</label>
                            <input type="number" name="experience_years" value="{{ old('experience_years', $existing?->experience_years ?? 0) }}" min="0" max="80" required
                                class="w-28 rounded-xl border-gray-200 bg-[#F8F6F0] focus:border-dokun-green focus:ring-dokun-green text-sm">
                            <x-input-error :messages="$errors->get('experience_years')" class="mt-1.5"/>
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-dokun-charcoal/50 mb-1.5">Description <span class="text-dokun-charcoal/30 normal-case">(1-2 phrases)</span></label>
                            <textarea name="description" rows="2" required maxlength="2000" placeholder="Décrivez brièvement votre activité…"
                                class="w-full rounded-xl border-gray-200 bg-[#F8F6F0] focus:border-dokun-green focus:ring-dokun-green text-sm">{{ old('description', $existing?->description) }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-1.5"/>
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-dokun-charcoal/50 mb-1.5">Histoire <span class="text-dokun-charcoal/30 normal-case">(optionnel)</span></label>
                            <textarea name="history" rows="3" maxlength="3000" placeholder="Votre parcours, la transmission…"
                                class="w-full rounded-xl border-gray-200 bg-[#F8F6F0] focus:border-dokun-green focus:ring-dokun-green text-sm">{{ old('history', $existing?->history) }}</textarea>
                        </div>
                    </div>
                </section>

                <button type="submit" class="w-full py-3.5 bg-[#064E3B] text-white font-bold rounded-xl hover:bg-[#064E3B]/90 transition shadow-lg text-sm">
                    Soumettre ma candidature
                </button>
            </form>
        @endif
    </div>
</x-app-layout>
