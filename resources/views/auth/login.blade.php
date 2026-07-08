<x-guest-layout>

    <!-- ===== META FIX: Disable zoom completely ===== -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">

    <style>
        /* ===== RESET ===== */
        * {
            -webkit-text-size-adjust: 100% !important;
            -moz-text-size-adjust: 100% !important;
            -ms-text-size-adjust: 100% !important;
            text-size-adjust: 100% !important;
            box-sizing: border-box;
        }

        /* ===== ZOOM FIX ===== */
        input,
        input[type="text"],
        input[type="email"],
        input[type="password"],
        input[type="number"],
        input[type="tel"],
        input[type="search"],
        input[type="url"],
        textarea,
        select,
        button {
            font-size: 16px !important;
            -webkit-text-size-adjust: 100% !important;
        }

        input:focus,
        textarea:focus,
        select:focus {
            font-size: 16px !important;
            transform: scale(1) !important;
            -webkit-transform: scale(1) !important;
        }

        html,
        body {
            -webkit-text-size-adjust: 100% !important;
            -moz-text-size-adjust: 100% !important;
            -ms-text-size-adjust: 100% !important;
            text-size-adjust: 100% !important;
            touch-action: manipulation;
        }

        /* ===== SKY BLUE THEME ===== */
        body {
            background: linear-gradient(145deg, #e3f2fd 0%, #bbdefb 30%, #e3f2fd 60%, #f3e5f5 100%) !important;
            background-attachment: fixed !important;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            padding: 20px;
            overflow-x: hidden;
        }

        /* ===== Card animation ===== */
        .card-enter {
            animation: floatUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            opacity: 0;
        }

        @keyframes floatUp {
            0% {
                opacity: 0;
                transform: translateY(30px) scale(0.97);
            }
            100% {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        /* ===== GLASS CARD - ANDAR WHITE ===== */
        .glass-card {
            background: rgba(255, 255, 255, 0.95) !important;
            backdrop-filter: blur(20px) saturate(180%);
            -webkit-backdrop-filter: blur(20px) saturate(180%);
            border: 1px solid rgba(255, 255, 255, 0.8);
            box-shadow: 0 30px 60px -20px rgba(13, 71, 161, 0.10), 0 10px 30px -15px rgba(0, 0, 0, 0.06);
            max-width: 580px;
            width: 100%;
            border-radius: 28px;
            padding: 1.25rem 2rem !important;
            min-height: auto;
            overflow: hidden;
            margin: 0 auto;
        }

        /* ===== Input styling ===== */
        .input-field {
            position: relative;
            width: 100%;
        }

        .input-field .input-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #1565c0;
            font-size: 16px;
            pointer-events: none;
            z-index: 2;
            opacity: 0.6;
        }

        .input-field input {
            width: 100%;
            padding: 14px 14px 14px 44px;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            border: 1.5px solid rgba(21, 101, 192, 0.10);
            border-radius: 14px;
            font-size: 16px !important;
            color: #1e293b;
            transition: all 0.25s ease;
            outline: none;
            height: 54px;
            -webkit-text-size-adjust: 100% !important;
        }

        .input-field input:focus {
            border-color: #1565c0;
            box-shadow: 0 0 0 4px rgba(21, 101, 192, 0.08), 0 0 0 2px rgba(21, 101, 192, 0.04);
            background: rgba(255, 255, 255, 1);
            transform: scale(1) !important;
        }

        .input-field input::placeholder {
            color: #90caf9;
            font-weight: 400;
        }

        /* ===== Password toggle ===== */
        .password-toggle-btn {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            background: transparent;
            border: none;
            cursor: pointer;
            padding: 8px;
            font-size: 20px;
            line-height: 1;
            color: #1565c0;
            transition: color 0.2s ease;
            z-index: 3;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            -webkit-tap-highlight-color: transparent;
            opacity: 0.6;
        }

        .password-toggle-btn:hover {
            color: #0d47a1;
            background: rgba(21, 101, 192, 0.06);
            opacity: 1;
        }

        .password-toggle-btn:active {
            transform: translateY(-50%) scale(0.92);
        }

        .input-field input[type="password"],
        .input-field input[type="text"] {
            padding-right: 54px !important;
        }

        /* ===== Button - Sky Blue gradient ===== */
        .btn-glow {
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            height: 54px;
            font-size: 16px;
            font-weight: 700;
            border: none;
            border-radius: 14px;
            background: linear-gradient(135deg, #1e88e5, #0d47a1);
            color: white;
            width: 100%;
            cursor: pointer;
            box-shadow: 0 8px 30px -8px rgba(21, 101, 192, 0.30);
            -webkit-tap-highlight-color: transparent;
            letter-spacing: 0.5px;
        }

        .btn-glow:hover {
            box-shadow: 0 12px 40px -10px rgba(21, 101, 192, 0.40);
            transform: translateY(-2px);
            background: linear-gradient(135deg, #1565c0, #0d47a1);
        }

        .btn-glow:active {
            transform: scale(0.97);
        }

        /* ===== Spacing ===== */
        .form-group {
            margin-bottom: 0.65rem;
        }

        .form-group:last-of-type {
            margin-bottom: 0;
        }

        .form-label {
            display: block;
            font-size: 0.75rem;
            font-weight: 600;
            color: #0d47a1;
            margin-bottom: 3px;
        }

        /* ===== Brand colors ===== */
        .brand-heading {
            color: #0d47a1;
            font-weight: 700;
        }

        .brand-sub {
            color: #1565c0;
            opacity: 0.8;
        }

        .divider-line {
            border-color: rgba(21, 101, 192, 0.06) !important;
        }

        .link-blue {
            color: #1565c0;
        }

        .link-blue:hover {
            color: #0d47a1;
        }

        /* ===== LOGO - SIZE INCREASED TO 110px ===== */
        .logo-wrapper {
            width: 110px;
            /* Pehle 90px tha - ab 110px */
            height: 110px;
            /* Pehle 90px tha - ab 110px */
            margin: 0 auto 0.35rem auto;
            border-radius: 50%;
            overflow: hidden;
            background: rgba(21, 101, 192, 0.04);
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid rgba(21, 101, 192, 0.06);
            padding: 14px;
            /* Padding bhi increase */
        }

        .logo-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            display: block;
        }

        .logo-glow {
            position: absolute;
            inset: -8px;
            background: radial-gradient(circle, rgba(21, 101, 192, 0.06), transparent 70%);
            border-radius: 50%;
            animation: pulseGlow 3s ease-in-out infinite;
        }

        @keyframes pulseGlow {
            0%,
            100% {
                opacity: 0.5;
                transform: scale(1);
            }
            50% {
                opacity: 1;
                transform: scale(1.05);
            }
        }

        /* ===== Heading area ===== */
        .heading-area {
            margin-bottom: 1rem;
        }

        .heading-area h1 {
            font-size: 1.5rem;
        }

        .heading-area p {
            font-size: 0.75rem;
        }

        /* ===== Session Status ===== */
        .session-status {
            margin-bottom: 1rem;
            padding: 0.75rem 1rem;
            background: rgba(21, 101, 192, 0.06);
            border-radius: 12px;
            color: #0d47a1;
            font-size: 0.875rem;
            text-align: center;
        }

        /* ===== Responsive ===== */
        @media (max-width: 480px) {
            .glass-card {
                padding: 1rem 1rem !important;
                max-width: 100%;
            }

            .input-field input {
                height: 48px;
                padding: 12px 12px 12px 38px;
                font-size: 16px !important;
            }

            .input-field .input-icon {
                left: 10px;
                font-size: 14px;
            }

            .password-toggle-btn {
                right: 8px;
                width: 34px;
                height: 34px;
                font-size: 17px;
            }

            .input-field input[type="password"],
            .input-field input[type="text"] {
                padding-right: 44px !important;
            }

            .btn-glow {
                height: 48px;
                font-size: 14px;
            }

            .logo-wrapper {
                width: 80px;
                /* Mobile par bhi bada */
                height: 80px;
                padding: 10px;
            }

            .heading-area h1 {
                font-size: 1.2rem;
            }
        }

        @media (min-width: 481px) and (max-width: 768px) {
            .glass-card {
                max-width: 520px;
                padding: 1.25rem 1.5rem !important;
            }

            .logo-wrapper {
                width: 95px;
                height: 95px;
            }
        }
    </style>

    <!-- Session Status -->
    @if (session('status'))
        <div class="session-status">
            {{ session('status') }}
        </div>
    @endif

    <!-- Main Card -->
    <div class="card-enter glass-card">

        <!-- ===== LOGO + HEADING ===== -->
        <div class="text-center heading-area">

            <div class="logo-wrapper relative mx-auto">
                <div class="logo-glow"></div>
                <img src="{{ asset('images/logo.png') }}"
                     alt="Mental Health Logo"
                     class="relative"
                />
            </div>

            <h1 class="text-2xl font-bold brand-heading tracking-tight">
                Welcome Back
            </h1>

            <p class="brand-sub mt-1 text-sm font-medium">
                Your mental health matters. Sign in to continue.
            </p>

        </div>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- ===== EMAIL ===== -->
            <div class="form-group">
                <label for="email" class="form-label">
                    Email Address
                </label>

                <div class="input-field">
                    <span class="input-icon">✉️</span>
                    <input
                        id="email"
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        autofocus
                        autocomplete="username"
                        placeholder="you@example.com"
                    />
                </div>

                <x-input-error :messages="$errors->get('email')" class="mt-1 text-sm text-blue-600" />
            </div>

            <!-- ===== PASSWORD ===== -->
            <div class="form-group">
                <label for="password" class="form-label">
                    Password
                </label>

                <div class="input-field">
                    <span class="input-icon">🔒</span>
                    <input
                        id="password"
                        type="password"
                        name="password"
                        required
                        autocomplete="current-password"
                        placeholder="Enter your password"
                    />
                    <button
                        type="button"
                        id="togglePassword"
                        class="password-toggle-btn"
                        aria-label="Toggle password visibility"
                    >
                        👁️
                    </button>
                </div>

                <x-input-error :messages="$errors->get('password')" class="mt-1 text-sm text-blue-600" />
            </div>

            <!-- ===== Remember & Forgot ===== -->
            <div class="flex items-center justify-between mt-4">

                <label class="inline-flex items-center cursor-pointer group">
                    <input
                        id="remember_me"
                        type="checkbox"
                        class="rounded border-slate-300 text-blue-500 focus:ring-blue-400 focus:ring-2 transition w-4 h-4"
                        name="remember"
                    />
                    <span class="ml-2.5 text-sm text-slate-600 group-hover:text-slate-800 transition">
                        Remember Me
                    </span>
                </label>

                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}"
                       class="text-sm text-blue-600 hover:text-blue-800 font-medium transition hover:underline underline-offset-2">
                        Forgot Password?
                    </a>
                @endif

            </div>

            <!-- ===== LOGIN BUTTON ===== -->
            <div class="mt-5">
                <button type="submit" class="btn-glow">
                    {{ __('Log In') }}
                </button>
            </div>

            <!-- ===== REGISTER LINK ===== -->
            <div class="text-center mt-4 pt-3 border-t divider-line text-sm">

                <span class="text-slate-500">
                    Don't have an account?
                </span>

                <a href="{{ route('register') }}"
                   class="link-blue font-semibold hover:underline underline-offset-2 transition ml-1">
                    Register
                </a>

            </div>

        </form>

    </div>

    <!-- ===== JAVASCRIPT ===== -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // Password Toggle
            const toggleBtn = document.getElementById('togglePassword');
            const passwordInput = document.getElementById('password');

            if (toggleBtn && passwordInput) {
                toggleBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const isPassword = passwordInput.getAttribute('type') === 'password';
                    passwordInput.setAttribute('type', isPassword ? 'text' : 'password');
                    this.textContent = isPassword ? '🙈' : '👁️';
                });
            }

            // Zoom Fix
            const allInputs = document.querySelectorAll('input, textarea, select');

            allInputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.style.fontSize = '16px';
                    this.style.transform = 'scale(1)';
                });

                input.addEventListener('touchstart', function() {
                    this.style.fontSize = '16px';
                    this.style.transform = 'scale(1)';
                });

                input.addEventListener('touchend', function(e) {
                    this.style.fontSize = '16px';
                    this.style.transform = 'scale(1)';
                    if (this !== document.activeElement) {
                        this.focus();
                    }
                });

                input.addEventListener('change', function() {
                    this.style.fontSize = '16px';
                    this.style.transform = 'scale(1)';
                });

                const observer = new MutationObserver(function() {
                    if (input.style.fontSize !== '16px') {
                        input.style.fontSize = '16px';
                    }
                    if (input.style.transform !== 'scale(1)') {
                        input.style.transform = 'scale(1)';
                    }
                });
                observer.observe(input, { attributes: true, attributeFilter: ['style'] });
            });

            // Prevent zoom gestures
            document.addEventListener('gesturestart', function(e) {
                e.preventDefault();
            });

            document.addEventListener('gesturechange', function(e) {
                e.preventDefault();
            });

            document.addEventListener('gestureend', function(e) {
                e.preventDefault();
            });

            // Prevent double-tap zoom
            let lastTouchEnd = 0;
            document.addEventListener('touchend', function(e) {
                const now = Date.now();
                if (now - lastTouchEnd <= 300) {
                    e.preventDefault();
                }
                lastTouchEnd = now;
            }, { passive: false });

            // Force viewport on resize
            window.addEventListener('resize', function() {
                const meta = document.querySelector('meta[name=viewport]');
                if (meta) {
                    meta.content = 'width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover';
                }
            });

            console.log('✅ Zoom protection active!');
        });
    </script>

</x-guest-layout>