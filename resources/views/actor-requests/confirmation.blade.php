<x-guest-layout>
    <div class="text-center">
        <div class="w-16 h-16 bg-[#064E3B]/10 rounded-full flex items-center justify-center mx-auto mb-5">
            <svg viewBox="0 0 24 24" fill="none" stroke="#064E3B" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-8 h-8">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>
            </svg>
        </div>
        <h1 class="font-serif text-3xl text-[#064E3B] mb-3">{{ __('app.actor_conf_title') }}</h1>
        <p class="text-[#17201D]/60 text-sm mb-2">{{ __('app.actor_conf_thanks') }}</p>
        <p class="text-[#17201D]/60 text-sm mb-8">{{ __('app.actor_conf_body') }}</p>
        <a href="/" class="inline-block text-sm font-bold text-[#C99424] hover:underline">{{ __('app.actor_conf_home') }}</a>
    </div>
</x-guest-layout>
