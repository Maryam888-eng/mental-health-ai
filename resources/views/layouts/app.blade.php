<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Font Awesome for Icons -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Custom CSS for Emergency Alert Banner -->
        <style>
            .emergency-banner {
                background: linear-gradient(135deg, #dc3545, #fd7e14);
                color: white;
                padding: 10px 20px;
                text-align: center;
                position: sticky;
                top: 0;
                z-index: 999;
                animation: slideDown 0.5s ease-in-out;
            }
            .emergency-banner a {
                color: white;
                font-weight: bold;
                text-decoration: underline;
                margin-left: 10px;
            }
            .emergency-banner a:hover {
                color: #ffd700;
            }
            .emergency-banner .badge-count {
                background: white;
                color: #dc3545;
                padding: 2px 12px;
                border-radius: 50px;
                font-weight: bold;
                margin: 0 5px;
            }
            @keyframes slideDown {
                from {
                    transform: translateY(-100%);
                    opacity: 0;
                }
                to {
                    transform: translateY(0);
                    opacity: 1;
                }
            }
            @keyframes pulse {
                0% { transform: scale(1); }
                50% { transform: scale(1.1); }
                100% { transform: scale(1); }
            }
            .pulse-icon {
                animation: pulse 1.5s infinite;
                display: inline-block;
            }
        </style>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            <!-- 🚨 EMERGENCY ALERT BANNER - Doctor/Admin ke liye -->
            @auth
                @php
                    $pendingAlerts = App\Models\EmergencyAlert::unresolved()->count();
                    $userRole = auth()->user()->role ?? 'user';
                    
                    // Role ke hisaab se route select karo
                    $alertsRoute = $userRole === 'admin' 
                        ? route('admin.emergency-alerts.index') 
                        : route('doctor.emergency-alerts.index');
                @endphp

                @if(($userRole === 'doctor' || $userRole === 'admin') && $pendingAlerts > 0)
                    <div class="emergency-banner">
                        <div class="container">
                            <i class="fas fa-exclamation-triangle pulse-icon"></i>
                            <strong>
                                <span class="badge-count">{{ $pendingAlerts }}</span>
                                emergency alert(s) pending!
                            </strong>
                            <a href="{{ $alertsRoute }}">
                                View Alerts <i class="fas fa-arrow-right"></i>
                            </a>
                            <span class="ms-3 text-light" style="font-size: 0.8rem;">
                                <i class="far fa-clock"></i> 
                                {{ now()->format('h:i A') }}
                            </span>
                        </div>
                    </div>
                @endif
            @endauth

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>

            <!-- Footer -->
            <footer class="bg-white border-top mt-auto py-4">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-muted">
                    <small>
                        © {{ date('Y') }} {{ config('app.name', 'Mental Health AI') }}. 
                        All rights reserved.
                    </small>
                </div>
            </footer>
        </div>

        <!-- Scripts -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>