<x-guest-layout>
 <div class="text-center mb-8">
 <h1 class="font-serif text-3xl text-[#064E3B] mb-2">{{ __('app.auth_confirm_hero') }}</h1>
 <p class="text-[#17201D]/60 text-sm">{{ __('app.auth_confirm_text') }}</p>
 </div>

 <form method="POST" action="{{ route('password.confirm') }}" class="space-y-5">
 @csrf

 <div>
 <label for="password" class="block text-sm font-bold text-[#17201D] mb-2">{{ __('app.auth_password') }}</label>
 <input id="password" type="password" name="password" required autocomplete="current-password"
 class="w-full px-4 py-3.5 bg-[#F8F6F0] border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#064E3B] outline-none transition font-semibold"
 placeholder="••••••••">
 <x-input-error :messages="$errors->get('password')" class="mt-2" />
 </div>

 <button type="submit" class="w-full py-4 bg-[#064E3B] text-white font-bold rounded-xl hover:bg-[#064E3B]/90 transition-all shadow-lg text-base">
 {{ __('app.auth_confirm_hero') }}
 </button>
 </form>
</x-guest-layout>
