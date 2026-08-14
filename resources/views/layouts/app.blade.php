<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'ƉƆKUN') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=dm-serif-display:400|manrope:400,600,700,800&display=swap" rel="stylesheet" />

        <!-- Tailwind CDN -->
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        colors: {
                            dokun: {
                                green: '#064E3B',
                                gold: '#C99424',
                                ivory: '#F8F6F0',
                                charcoal: '#17201D',
                            }
                        },
                        fontFamily: {
                            sans: ['Manrope', 'sans-serif'],
                            serif: ['"DM Serif Display"', 'serif'],
                        }
                    }
                }
            }
        </script>

        <style>
            body { font-family: 'Manrope', sans-serif; }
            h1, h2, h3, h4, .font-serif { font-family: 'DM Serif Display', serif; }
        </style>

        <!-- Scripts -->
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-dokun-ivory">
            @include('partials.navbar')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white border-b border-black/5 pt-20">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
            @include('partials.footer')
        </div>
    </body>
</html>
