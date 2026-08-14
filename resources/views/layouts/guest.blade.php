<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'ƉƆKUN') }} - Authentification</title>

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
                        },
                        backgroundImage: {
                            'dokun-pattern': "url('data:image/svg+xml,%3Csvg width=\\'20\\' height=\\'20\\' viewBox=\\'0 0 20 20\\' xmlns=\\'http://www.w3.org/2000/svg\\'%3E%3Cg fill=\\'%23064E3B\\' fill-opacity=\\'0.05\\' fill-rule=\\'evenodd\\'%3E%3Ccircle cx=\\'3\\' cy=\\'3\\' r=\\'3\\'/%3E%3Ccircle cx=\\'13\\' cy=\\'13\\' r=\\'3\\'/%3E%3C/g%3E%3C/svg%3E')",
                        }
                    }
                }
            }
        </script>
        <style>
            body { font-family: 'Manrope', sans-serif; }
            h1, h2, h3, h4, .font-serif { font-family: 'DM Serif Display', serif; }
        </style>
    </head>
    <body class="font-sans text-dokun-charcoal antialiased bg-dokun-ivory bg-dokun-pattern">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
            <div>
                <a href="/" class="flex flex-col items-center gap-2">
                    <div class="w-16 h-16 bg-dokun-green rounded-xl flex items-center justify-center text-dokun-gold font-bold text-3xl shadow-xl">Ɖ</div>
                    <span class="font-serif text-3xl tracking-wide text-dokun-green mt-2">ƆKUN</span>
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-8 px-8 py-10 bg-white shadow-2xl border border-dokun-green/10 sm:rounded-2xl">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
