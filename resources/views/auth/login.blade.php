<x-guest-layout>
    <x-auth-session-status class="mb-4" :status="session('status')"/>

    <div class="text-center mb-8">
        <h1 class="font-serif text-3xl text-[#064E3B] mb-2">{{ __('app.auth_login_hero') }}</h1>
        <p class="text-[#17201D]/60 text-sm">{{ __('app.auth_login_sub') }}</p>
    </div>

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf
        <div>
            <label for="email" class="block text-sm font-bold text-[#17201D] mb-2">{{ __('app.auth_email') }}</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                class="w-full px-4 py-3.5 bg-[#F8F6F0] border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#064E3B] focus:border-transparent outline-none transition text-[#17201D] font-semibold placeholder:text-gray-400 placeholder:font-normal"
                placeholder="{{ __('app.auth_email_placeholder') }}">
            <x-input-error :messages="$errors->get('email')" class="mt-2"/>
        </div>
        <div>
            <label for="password" class="block text-sm font-bold text-[#17201D] mb-2">{{ __('app.auth_password') }}</label>
            <input id="password" type="password" name="password" required
                class="w-full px-4 py-3.5 bg-[#F8F6F0] border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#064E3B] focus:border-transparent outline-none transition text-[#17201D] font-semibold"
                placeholder="••••••••">
            <x-input-error :messages="$errors->get('password')" class="mt-2"/>
        </div>
        <div class="flex items-center justify-between">
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="remember" id="remember_me" class="w-4 h-4 rounded border-gray-300 text-[#064E3B] focus:ring-[#064E3B]">
                <span class="text-sm text-[#17201D]/70">{{ __('app.auth_remember') }}</span>
            </label>
            @if(Route::has('password.request'))
            <a href="{{ route('password.request') }}" class="text-sm text-[#064E3B] hover:text-[#C99424] font-semibold transition-colors">
                {{ __('app.auth_forgot') }}
            </a>
            @endif
        </div>
        <button type="submit" class="w-full py-4 bg-[#064E3B] text-white font-bold rounded-xl hover:bg-[#064E3B]/90 transition-all shadow-lg shadow-[#064E3B]/20 text-base">
            {{ __('app.auth_submit_login') }}
        </button>
    </form>

    <div class="mt-6 text-center text-sm text-[#17201D]/60">
        {{ __('app.auth_no_account') }}
        <a href="{{ route('register') }}" class="text-[#C99424] font-bold hover:underline ml-1">{{ __('app.auth_sign_up') }}</a>
    </div>
</x-guest-layout>
