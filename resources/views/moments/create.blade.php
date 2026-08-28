<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('app.moments_create_title') }} · ƉƆKUN</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=dm-serif-display:400|manrope:400,500,600,700,800&display=swap" rel="stylesheet"/>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>tailwind.config={theme:{extend:{colors:{dokun:{green:'#064E3B',gold:'#C99424',ivory:'#F8F6F0',charcoal:'#17201D'}},fontFamily:{sans:['Manrope','sans-serif'],serif:['"DM Serif Display"','serif']}}}}</script>
    <style>
        body{font-family:'Manrope',sans-serif;}
        h1,h2,h3,.font-serif{font-family:'DM Serif Display',serif;}
        @keyframes fadeUp{from{opacity:0;transform:translateY(20px)}to{opacity:1;transform:translateY(0)}}
        .fade-up{animation:fadeUp .55s ease both;}
    </style>
</head>
<body class="antialiased bg-dokun-ivory text-dokun-charcoal">
@include('partials.navbar')

<main class="pt-28 pb-20">
    <div class="max-w-xl mx-auto px-4 sm:px-6 lg:px-8">

        @if(session('success'))
            <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 font-semibold text-center">{{ session('success') }}</div>
        @endif

        <div class="text-center mb-10 fade-up">
            <span class="inline-block mb-4 px-5 py-2 rounded-full border border-dokun-gold/50 bg-dokun-gold/10 text-dokun-gold text-xs font-bold uppercase tracking-[0.2em]">🎬 {{ __('app.moments_badge') }}</span>
            <h1 class="font-serif text-4xl text-dokun-green mb-3">{{ __('app.moments_create_title') }}</h1>
            <p class="text-gray-500">{{ __('app.moments_create_sub') }}</p>
        </div>

        <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden fade-up">

            <div class="bg-dokun-green px-6 py-4">
                <p class="text-dokun-gold text-xs uppercase tracking-widest font-bold mb-1">{{ __('app.moments_experience') }}</p>
                <h2 class="font-serif text-xl text-white">{{ $reservation->experience_type }}</h2>
                <p class="text-white/60 text-xs mt-1">{{ $reservation->artisan?->professional_name ?? (($reservation->artisan?->first_name ?? '') . ' ' . ($reservation->artisan?->last_name ?? '')) }}</p>
            </div>

            <form action="{{ route('moments.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-5">
                @csrf
                <input type="hidden" name="reservation_request_id" value="{{ $reservation->id }}">

                <div>
                    <label class="block text-sm font-bold text-dokun-charcoal mb-2">{{ __('app.moments_title_label') }} <span class="text-red-500">*</span></label>
                    <input type="text" name="title" required maxlength="120" value="{{ old('title') }}"
                        class="w-full rounded-xl border border-gray-200 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-dokun-gold" placeholder="{{ __('app.moments_title_ph') }}">
                    @error('title')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-dokun-charcoal mb-2">{{ __('app.moments_desc_label') }}</label>
                    <textarea name="description" rows="3" maxlength="1000" class="w-full rounded-xl border border-gray-200 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-dokun-gold" placeholder="{{ __('app.moments_desc_ph') }}">{{ old('description') }}</textarea>
                    @error('description')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-dokun-charcoal mb-2">{{ __('app.moments_video_label') }}</label>
                    <input type="file" name="video" accept="video/mp4,video/webm,video/ogg"
                        class="w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-lg file:border-0 file:bg-dokun-ivory file:text-dokun-green file:font-bold hover:file:bg-dokun-green/10">
                    <p class="text-xs text-gray-400 mt-1">{{ __('app.moments_video_hint') }}</p>
                    @error('video')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-dokun-charcoal mb-2">{{ __('app.moments_cover_label') }}</label>
                    <input type="file" name="cover" accept="image/*"
                        class="w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-lg file:border-0 file:bg-dokun-ivory file:text-dokun-green file:font-bold hover:file:bg-dokun-green/10">
                    @error('cover')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <button type="submit"
                    class="w-full py-4 bg-dokun-gold text-dokun-charcoal rounded-2xl font-bold text-lg hover:bg-yellow-500 active:scale-[.98] transition shadow-lg">
                    🎬 {{ __('app.moments_publish') }}
                </button>
            </form>
        </div>

        <div class="text-center mt-8">
            <a href="{{ route('reservations.receipt', $reservation->qr_code_token) }}" class="text-dokun-gold font-semibold hover:underline text-sm">← {{ __('app.moments_back_receipt') }}</a>
        </div>
    </div>
</main>

@include('partials.footer')
</body>
</html>
