<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Mental Health AI') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600;700&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- ✨ ANIMATIONS -->
    <style>
        body {
            animation: fadeIn 0.8s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .float {
            animation: float 4s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        .slide-up {
            animation: slideUp 0.9s ease-out;
        }

        @keyframes slideUp {
            from {
                transform: translateY(40px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .hover-smooth {
            transition: all 0.3s ease;
        }

        .hover-smooth:hover {
            transform: scale(1.03);
        }
    </style>
</head>

<body class="font-sans antialiased">

    <!-- Background -->
    <div class="min-h-screen bg-gradient-to-br from-cyan-100 via-teal-50 to-green-100 flex items-center justify-center p-6">

        <div class="w-full max-w-6xl grid md:grid-cols-2 rounded-3xl overflow-hidden shadow-2xl">

            <!-- Left Side -->
            <div class="hidden md:flex flex-col justify-center items-center bg-gradient-to-br from-teal-600 to-cyan-500 text-white p-12 float">

                <a href="/">
                    <x-application-logo class="w-56 h-auto mx-auto mb-8 hover-smooth" />
                </a>

                <h1 class="text-4xl font-bold mb-4 text-center">
                    Mental Health AI
                </h1>

                <p class="text-lg text-center leading-8 opacity-90">
                    Your mental well-being is our priority.
                    <br><br>
                    Connect with AI support in a safe,
                    secure and caring environment.
                </p>

            </div>

            <!-- Right Side -->
            <div class="flex items-center justify-center p-8 md:p-12
                        bg-white/20 backdrop-blur-2xl border border-white/30">

                <div class="w-full max-w-md
                            bg-white/40 backdrop-blur-2xl
                            p-8 rounded-2xl shadow-xl
                            border border-white/30
                            slide-up hover-smooth">

                    {{ $slot }}

                </div>

            </div>

        </div>

    </div>

</body>
</html>