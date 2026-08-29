<x-guest-layout>
 <div class="text-center mb-8">
 <h1 class="font-serif text-3xl text-[#064E3B] mb-2">{{ __('app.auth_register_hero') }}</h1>
 <p class="text-[#17201D]/60 text-sm">{{ __('app.auth_register_sub') }}</p>
 </div>

 <form method="POST" action="{{ route('register') }}" class="space-y-5" x-data="{ role: '{{ old('role', 'tourist') }}' }">
 @csrf
 <div>
 <label class="block text-sm font-bold text-[#17201D] mb-3">{{ __('app.auth_join_as') }}</label>
 <div class="grid grid-cols-2 gap-3">
 <label class="relative cursor-pointer rounded-2xl border-2 p-4 text-center transition-all duration-200"
 :class="role === 'tourist' ? 'border-[#064E3B] bg-[#064E3B]/5 shadow-md' : 'border-gray-200 hover:border-gray-300'">
 <input type="radio" name="role" value="tourist" class="sr-only" x-model="role">
 <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
 class="w-8 h-8 mx-auto mb-2 transition-colors" :class="role === 'tourist' ? 'text-[#064E3B]' : 'text-gray-400'">
 <circle cx="12" cy="12" r="10"/><path d="M2 12h20"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/>
 </svg>
 <span class="block font-bold text-sm" :class="role === 'tourist' ? 'text-[#064E3B]' : 'text-[#17201D]/60'">{{ __('app.auth_role_visitor') }}</span>
 <span class="block text-[11px] text-[#17201D]/45 mt-1 leading-snug">{{ __('app.auth_role_visitor_desc') }}</span>
 <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" class="w-5 h-5 absolute top-2 right-2 text-[#064E3B]" x-show="role === 'tourist'" x-cloak><path d="M20 6L9 17l-5-5"/></svg>
 </label>
 <label class="relative cursor-pointer rounded-2xl border-2 p-4 text-center transition-all duration-200"
 :class="role === 'artisan' ? 'border-[#C99424] bg-[#C99424]/5 shadow-md' : 'border-gray-200 hover:border-gray-300'">
 <input type="radio" name="role" value="artisan" class="sr-only" x-model="role">
 <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
 class="w-8 h-8 mx-auto mb-2 transition-colors" :class="role === 'artisan' ? 'text-[#C99424]' : 'text-gray-400'">
 <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/>
 </svg>
 <span class="block font-bold text-sm" :class="role === 'artisan' ? 'text-[#C99424]' : 'text-[#17201D]/60'">{{ __('app.auth_role_artisan') }}</span>
 <span class="block text-[11px] text-[#17201D]/45 mt-1 leading-snug">{{ __('app.auth_role_artisan_desc') }}</span>
 <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" class="w-5 h-5 absolute top-2 right-2 text-[#C99424]" x-show="role === 'artisan'" x-cloak><path d="M20 6L9 17l-5-5"/></svg>
 </label>
 </div>
 <p x-show="role === 'artisan'" x-cloak class="mt-3 text-xs text-[#C99424]/80 bg-[#C99424]/5 rounded-lg px-3 py-2 flex items-start gap-1.5">
 <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-3.5 h-3.5 flex-shrink-0 mt-0.5"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/></svg>
 <span>{{ __('app.auth_artisan_note') }}</span>
 </p>
 <p class="mt-3 text-xs text-[#17201D]/55 flex items-center gap-1.5">
 <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-3.5 h-3.5 flex-shrink-0"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/></svg>
 {{ __('app.auth_actor_link') }} <a href="{{ route('actor-requests.form') }}" class="text-[#C99424] font-bold hover:underline">{{ __('app.auth_actor_link_cta') }}</a>.
 </p>
 <x-input-error :messages="$errors->get('role')" class="mt-2"/>
 </div>
 <div>
 <label for="name" class="block text-sm font-bold text-[#17201D] mb-2">{{ __('app.auth_name') }}</label>
 <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
 class="w-full px-4 py-3.5 bg-[#F8F6F0] border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#064E3B] outline-none transition font-semibold placeholder:font-normal placeholder:text-gray-400"
 placeholder="{{ __('app.auth_name_placeholder') }}">
 <x-input-error :messages="$errors->get('name')" class="mt-2"/>
 </div>
 <div>
 <label for="email" class="block text-sm font-bold text-[#17201D] mb-2">{{ __('app.auth_email') }}</label>
 <input id="email" type="email" name="email" value="{{ old('email') }}" required
 class="w-full px-4 py-3.5 bg-[#F8F6F0] border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#064E3B] outline-none transition font-semibold placeholder:font-normal placeholder:text-gray-400"
 placeholder="{{ __('app.auth_email_placeholder') }}">
 <x-input-error :messages="$errors->get('email')" class="mt-2"/>
 </div>
 <div>
 <label for="password" class="block text-sm font-bold text-[#17201D] mb-2">{{ __('app.auth_password') }}</label>
 <input id="password" type="password" name="password" required
 class="w-full px-4 py-3.5 bg-[#F8F6F0] border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#064E3B] outline-none transition font-semibold"
 placeholder="{{ __('app.auth_password_min') }}">
 <x-input-error :messages="$errors->get('password')" class="mt-2"/>
 </div>
 <div>
 <label for="password_confirmation" class="block text-sm font-bold text-[#17201D] mb-2">{{ __('app.auth_password_confirm') }}</label>
 <input id="password_confirmation" type="password" name="password_confirmation" required
 class="w-full px-4 py-3.5 bg-[#F8F6F0] border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#064E3B] outline-none transition font-semibold"
 placeholder="{{ __('app.auth_password_confirm_placeholder') }}">
 <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2"/>
 </div>

 <button type="submit" class="w-full py-4 bg-[#064E3B] text-white font-bold rounded-xl hover:bg-[#064E3B]/90 transition-all shadow-lg text-base">
 {{ __('app.auth_submit_register') }}
 </button>
 </form>

 <div class="mt-6 text-center text-sm text-[#17201D]/60">
 {{ __('app.auth_has_account') }}
 <a href="{{ route('login') }}" class="text-[#C99424] font-bold hover:underline ml-1">{{ __('app.auth_sign_in') }}</a>
 </div>
</x-guest-layout>
