<x-guest-layout>
 <div class="text-center mb-8">
 <h1 class="font-serif text-3xl text-[#064E3B] mb-2">{{ __('app.auth_reset_hero') }}</h1>
 </div>

 <form method="POST" action="{{ route('password.store') }}" class="space-y-5">
 @csrf

 <input type="hidden" name="token" value="{{ $request->route('token') }}">

 <div>
 <label for="email" class="block text-sm font-bold text-[#17201D] mb-2">{{ __('app.auth_email') }}</label>
 <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username"
 class="w-full px-4 py-3.5 bg-[#F8F6F0] border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#064E3B] outline-none transition font-semibold"
 placeholder="{{ __('app.auth_email_placeholder') }}">
 <x-input-error :messages="$errors->get('email')" class="mt-2" />
 </div>

 <div>
 <label for="password" class="block text-sm font-bold text-[#17201D] mb-2">{{ __('app.auth_new_password') }}</label>
 <input id="password" type="password" name="password" required autocomplete="new-password"
 class="w-full px-4 py-3.5 bg-[#F8F6F0] border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#064E3B] outline-none transition font-semibold"
 placeholder="{{ __('app.auth_password_min') }}">
 <x-input-error :messages="$errors->get('password')" class="mt-2" />
 </div>

 <div>
 <label for="password_confirmation" class="block text-sm font-bold text-[#17201D] mb-2">{{ __('app.auth_password_confirm') }}</label>
 <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
 class="w-full px-4 py-3.5 bg-[#F8F6F0] border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#064E3B] outline-none transition font-semibold"
 placeholder="{{ __('app.auth_confirm_placeholder') }}">
 <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
 </div>

 <button type="submit" class="w-full py-4 bg-[#064E3B] text-white font-bold rounded-xl hover:bg-[#064E3B]/90 transition-all shadow-lg text-base">
 {{ __('app.auth_reset_submit') }}
 </button>
 </form>
</x-guest-layout>
