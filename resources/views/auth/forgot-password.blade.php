<x-guest-layout>
    <div class="text-center mb-8">
        <h1 class="font-serif text-3xl text-[#064E3B] mb-2">{{ __('app.auth_forgot_hero') }}</h1>
        <p class="text-[#17201D]/60 text-sm">{{ __('app.auth_forgot_text') }}</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
        @csrf

        <div>
            <label for="email" class="block text-sm font-bold text-[#17201D] mb-2">{{ __('app.auth_email') }}</label>
            <input id="email" type="email" name="email" :value="old('email')" required autofocus
                class="w-full px-4 py-3.5 bg-[#F8F6F0] border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#064E3B] outline-none transition font-semibold placeholder:font-normal placeholder:text-gray-400"
                placeholder="{{ __('app.auth_email_placeholder') }}">
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <button type="submit" class="w-full py-4 bg-[#064E3B] text-white font-bold rounded-xl hover:bg-[#064E3B]/90 transition-all shadow-lg text-base">
            {{ __('app.auth_forgot_submit') }}
        </button>

        <div class="text-center text-sm">
            <a href="{{ route('login') }}" class="text-[#C99424] font-bold hover:underline">{{ __('app.auth_back_login') }}</a>
        </div>
    </form>
</x-guest-layout>
