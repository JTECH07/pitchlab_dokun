<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('app.contact_title') }}</title>
    <link href="https://fonts.bunny.net/css?family=dm-serif-display:400|manrope:400,600,700,800&display=swap" rel="stylesheet"/>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>tailwind.config={theme:{extend:{colors:{dokun:{green:'#064E3B',gold:'#C99424',ivory:'#F8F6F0',charcoal:'#17201D'}},fontFamily:{sans:['Manrope','sans-serif'],serif:['"DM Serif Display"','serif']}}}}</script>
    <style>
        body { font-family: 'Manrope', sans-serif; }
        h1, h2, h3, .serif { font-family: 'DM Serif Display', serif; }
        .wax-pattern{background-image:url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' stroke='%23C99424' stroke-opacity='0.25'%3E%3Ccircle cx='30' cy='30' r='12'/%3E%3Ccircle cx='0' cy='0' r='8'/%3E%3Ccircle cx='60' cy='0' r='8'/%3E%3Ccircle cx='0' cy='60' r='8'/%3E%3Ccircle cx='60' cy='60' r='8'/%3E%3Cpath d='M30 18l10 12-10 12-10-12z'/%3E%3C/g%3E%3C/svg%3E");}
        @keyframes fadeUp{from{opacity:0;transform:translateY(24px)}to{opacity:1;transform:translateY(0)}}
        .fade-up{animation:fadeUp .7s ease both}
    </style>
</head>
<body class="antialiased bg-dokun-ivory text-dokun-charcoal">
@include('partials.navbar', ['active' => ''])

<main class="pt-28">

    {{-- ══════════ HERO ══════════ --}}
    <section class="relative overflow-hidden">
        <img src="{{ url('images/dokun_bg2.jpg') }}" class="absolute inset-0 w-full h-full object-cover opacity-10" alt="" loading="lazy">
        <div class="absolute inset-0 wax-pattern opacity-50"></div>
        <div class="absolute inset-0 bg-gradient-to-b from-transparent to-dokun-ivory"></div>
        <div class="relative z-10 max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-16 md:py-20 text-center">
            <span class="fade-up inline-block mb-5 px-5 py-2 rounded-full border border-dokun-gold/40 bg-dokun-gold/10 text-dokun-gold text-xs font-bold uppercase tracking-[0.2em]">{{ __('app.contact_hero_badge') }}</span>
            <h1 class="fade-up text-4xl md:text-5xl text-dokun-green" style="animation-delay:.15s">{{ __('app.contact_hero_title') }}</h1>
            <p class="fade-up mt-4 text-gray-600 text-lg max-w-xl mx-auto" style="animation-delay:.3s">
                {{ __('app.contact_hero_sub') }}
            </p>
        </div>
    </section>

    {{-- ══════════ INFOS + FORMULAIRE ══════════ --}}
    <section class="pb-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-start">

                {{-- Colonne gauche : infos --}}
                <div class="space-y-5 fade-up">
                    <div class="fade-up bg-white rounded-2xl p-6 shadow-sm flex items-start gap-4">
                        <div class="w-11 h-11 rounded-full bg-dokun-green flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 text-dokun-gold" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-dokun-green">{{ __('app.contact_address') }}</h3>
                            <p class="mt-1 text-gray-600 text-sm leading-relaxed">{{ __('app.contact_address_value') }}</p>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl p-6 shadow-sm flex items-start gap-4" style="animation-delay:.1s">
                        <div class="w-11 h-11 rounded-full bg-dokun-green flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 text-dokun-gold" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-dokun-green">{{ __('app.contact_email') }}</h3>
                            <a href="mailto:{{ env('MAIL_FROM_ADDRESS') }}" class="mt-1 block text-gray-600 hover:text-dokun-gold transition-colors text-sm">{{ env('MAIL_FROM_ADDRESS') }}</a>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl p-6 shadow-sm flex items-start gap-4" style="animation-delay:.2s">
                        <div class="w-11 h-11 rounded-full bg-dokun-green flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 text-dokun-gold" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-dokun-green">{{ __('app.contact_phone') }}</h3>
                            <p class="mt-1 text-gray-600 text-sm">+229 0１ XX XX XX XX</p>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl p-6 shadow-sm flex items-start gap-4" style="animation-delay:.3s">
                        <div class="w-11 h-11 rounded-full bg-dokun-green flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 text-dokun-gold" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-dokun-green">{{ __('app.contact_hours') }}</h3>
                            <p class="mt-1 text-gray-600 text-sm">{{ __('app.contact_hours_value') }}</p>
                        </div>
                    </div>
                </div>

                {{-- Colonne droite : formulaire --}}
                <div class="fade-up bg-white rounded-2xl p-7 md:p-9 shadow-sm" style="animation-delay:.15s">
                    @if(session('contact_success'))
                    <div class="mb-4 px-5 py-4 bg-emerald-50 border border-emerald-200 rounded-xl text-emerald-800 text-sm font-semibold">{{ __('app.contact_success') }}</div>
                    @endif
                    @if($errors->any())
                    <div class="mb-4 px-5 py-4 bg-red-50 border border-red-200 rounded-xl text-red-700 text-sm font-semibold">@foreach($errors->all() as $e)<p>{{ $e }}</p>@endforeach</div>
                    @endif

                    <h2 class="text-2xl text-dokun-green mb-1">{{ __('app.contact_form_title') }}</h2>
                    <p class="text-gray-500 text-sm mb-7">{{ __('app.contact_required') }}</p>
                    <form action="{{ route('contact.send') }}" method="POST" class="space-y-5">
                        @csrf
                        <div>
                            <label for="name" class="block text-sm font-bold mb-2 text-dokun-charcoal">{{ __('app.contact_full_name') }} *</label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}" required
                                   placeholder="{{ __('app.contact_name_placeholder') }}"
                                   class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-dokun-ivory/50 focus:bg-white focus:border-dokun-green focus:ring-2 focus:ring-dokun-green/20 outline-none transition">
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-bold mb-2 text-dokun-charcoal">Email *</label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" required
                                   placeholder="vous@exemple.com"
                                   class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-dokun-ivory/50 focus:bg-white focus:border-dokun-green focus:ring-2 focus:ring-dokun-green/20 outline-none transition">
                        </div>
                        <div>
                            <label for="subject" class="block text-sm font-bold mb-2 text-dokun-charcoal">{{ __('app.contact_subject') }} *</label>
                            <select id="subject" name="subject" required
                                    class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-dokun-ivory/50 focus:bg-white focus:border-dokun-green focus:ring-2 focus:ring-dokun-green/20 outline-none transition {{ old('subject') ? '' : 'text-gray-500' }}">
                                <option value="" disabled {{ old('subject') ? '' : 'selected' }}>{{ __('app.contact_subject_choose') }}</option>
                                <option value="Question générale" {{ old('subject') === 'Question générale' ? 'selected' : '' }}>{{ __('app.contact_subj_general') }}</option>
                                <option value="Devenir artisan partenaire" {{ old('subject') === 'Devenir artisan partenaire' ? 'selected' : '' }}>{{ __('app.contact_subj_partner') }}</option>
                                <option value="Partenariat" {{ old('subject') === 'Partenariat' ? 'selected' : '' }}>{{ __('app.contact_subj_partnership') }}</option>
                                <option value="Presse" {{ old('subject') === 'Presse' ? 'selected' : '' }}>{{ __('app.contact_subj_press') }}</option>
                                <option value="Autre" {{ old('subject') === 'Autre' ? 'selected' : '' }}>{{ __('app.contact_subj_other') }}</option>
                            </select>
                        </div>
                        <div>
                            <label for="message" class="block text-sm font-bold mb-2 text-dokun-charcoal">{{ __('app.contact_message') }} *</label>
                            <textarea id="message" name="message" rows="5" required
                                      placeholder="{{ __('app.contact_message_placeholder') }}"
                                      class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-dokun-ivory/50 focus:bg-white focus:border-dokun-green focus:ring-2 focus:ring-dokun-green/20 outline-none transition resize-y">{{ old('message') }}</textarea>
                        </div>
                        <button type="submit"
                                class="w-full inline-flex items-center justify-center gap-2 px-8 py-4 bg-dokun-green text-white font-bold rounded-full hover:bg-dokun-green/90 transition shadow-lg shadow-dokun-green/20">
                            {{ __('app.contact_send') }}
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                        </button>
                        <p class="text-xs text-gray-400 text-center pt-1">{{ __('app.contact_response_time') }}</p>
                    </form>
                </div>

            </div>
        </div>
    </section>

    {{-- ══════════ BANDEAU CARTE ══════════ --}}
    <section class="pb-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <a href="{{ route('carte') }}" class="group relative block overflow-hidden rounded-2xl bg-dokun-charcoal text-white fade-up">
                <img src="{{ url('images/dokun_carte.jpg') }}" class="absolute inset-0 w-full h-full object-cover opacity-25" alt="" loading="lazy">
                <div class="absolute inset-0 wax-pattern opacity-30 group-hover:opacity-50 transition-opacity"></div>
                <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-6 px-8 py-10 md:px-14">
                    <div>
                        <span class="text-dokun-gold text-xs font-bold uppercase tracking-[0.2em]">Carte interactive</span>
                        <h2 class="mt-2 text-2xl md:text-3xl">{{ __('app.contact_map_title') }}</h2>
                        <p class="mt-2 text-white/60 text-sm">{{ __('app.contact_map_sub') }}</p>
                    </div>
                    <span class="shrink-0 inline-flex items-center gap-2 px-8 py-4 bg-dokun-gold text-dokun-charcoal font-bold rounded-full group-hover:bg-yellow-500 transition shadow-lg">
                        {{ __('app.contact_open_map') }}
                        <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </span>
                </div>
            </a>
        </div>
    </section>

</main>

@include('partials.footer')
</body>
</html>
