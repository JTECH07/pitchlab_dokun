<x-guest-layout>
    <div class="text-center mb-8">
        <div class="w-16 h-16 bg-[#064E3B] rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg">
            <svg viewBox="0 0 24 24" fill="none" stroke="#C99424" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-8 h-8">
                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/>
            </svg>
        </div>
        <h1 class="font-serif text-3xl text-[#064E3B] mb-2">{{ __('app.auth_verify_hero') }}</h1>
        <p class="text-[#17201D]/60 text-sm">{{ __('app.auth_verify_sub') }}</p>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-6 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3.5 font-semibold text-sm flex items-start gap-2">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-5 h-5 flex-shrink-0 mt-0.5"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
            {{ __('app.auth_verify_sent_banner') }}
        </div>
    @endif

    @if (session('error'))
        <div class="mb-6 rounded-xl bg-red-50 border border-red-200 text-red-700 px-4 py-3.5 font-semibold text-sm">{{ session('error') }}</div>
    @endif

    {{-- 3 étapes illustrées --}}
    <div class="grid grid-cols-3 gap-3 mb-8">
        <div class="text-center">
            <div class="w-12 h-12 bg-[#F8F6F0] rounded-full flex items-center justify-center mx-auto mb-2 border border-[#C99424]/30">
                <span class="font-serif text-xl text-[#C99424] font-bold">1</span>
            </div>
            <p class="text-[11px] font-bold text-[#17201D]/70 leading-tight">{{ __('app.auth_verify_step1') }}</p>
        </div>
        <div class="text-center">
            <div class="w-12 h-12 bg-[#F8F6F0] rounded-full flex items-center justify-center mx-auto mb-2 border border-[#C99424]/30">
                <span class="font-serif text-xl text-[#C99424] font-bold">2</span>
            </div>
            <p class="text-[11px] font-bold text-[#17201D]/70 leading-tight">{{ __('app.auth_verify_step2') }}</p>
        </div>
        <div class="text-center">
            <div class="w-12 h-12 bg-[#F8F6F0] rounded-full flex items-center justify-center mx-auto mb-2 border border-[#C99424]/30">
                <span class="font-serif text-xl text-[#C99424] font-bold">3</span>
            </div>
            <p class="text-[11px] font-bold text-[#17201D]/70 leading-tight">{{ __('app.auth_verify_step3') }}</p>
        </div>
    </div>

    <p class="text-center text-sm text-[#17201D]/60 mb-6">
        {{ __('app.auth_verify_footer') }}
    </p>

    {{-- Renvoyer l'email --}}
    <form method="POST" action="{{ route('verification.send') }}" class="mb-6">
        @csrf
        <button type="submit" class="w-full py-3.5 bg-[#064E3B] text-white font-bold rounded-xl hover:bg-[#064E3B]/90 transition-all shadow-lg text-sm flex items-center justify-center gap-2">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-4 h-4"><path d="M21 2l-2 2m-7.61 7.61a5.5 5.5 0 1 1-7.778 7.778 5.5 5.5 0 0 1 7.777-7.777zm0 0L15.5 7.5m0 0l3 3L22 7l-3-3m-3.5 3.5L19 4"/></svg>
            {{ __('app.auth_verify_resend_btn') }}
        </button>
    </form>

    {{-- Corriger l'email si faute de frappe --}}
    <details class="mb-6 group">
        <summary class="cursor-pointer text-sm text-[#C99424] font-bold hover:underline text-center flex items-center justify-center gap-1.5">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-4 h-4"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
            {{ __('app.auth_verify_typo') }}
        </summary>
        <form method="POST" action="{{ route('email.update') }}" class="mt-4 space-y-3">
            @csrf
            @method('PATCH')
            <div>
                <label for="email" class="block text-xs font-bold text-[#17201D]/60 mb-1.5">{{ __('app.auth_verify_new_email') }}</label>
                <input id="email" type="email" name="email" value="{{ auth()->user()->email }}" required
                    class="w-full px-4 py-3 bg-[#F8F6F0] border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#064E3B] outline-none transition font-semibold text-sm">
                <x-input-error :messages="$errors->get('email')" class="mt-2"/>
            </div>
            <button type="submit" class="w-full py-3 bg-[#C99424] text-white font-bold rounded-xl hover:bg-[#b3831f] transition text-sm">
                {{ __('app.auth_verify_fix_submit') }}
            </button>
        </form>
    </details>

    <div class="text-center">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="text-sm text-[#17201D]/40 hover:text-[#17201D]/70 transition underline">
                {{ __('app.auth_logout') }}
            </button>
        </form>
    </div>
</x-guest-layout>
