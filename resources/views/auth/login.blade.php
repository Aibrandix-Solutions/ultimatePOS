<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('lang_v1.login') }} - {{ config('app.name', 'Generix POS') }}</title>
    <meta name="color-scheme" content="dark light">
    <link rel="icon" href="{{ asset('favicon.ico') }}">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    
    <style>
        :root {
            --navy-700: #161160;
            --navy-600: #1a1a70;
            --navy-500: #1e1e80;
            --navy-400: #222290;
            --text: #1e293b;
            --text-muted: rgba(30, 41, 59, 0.7);
        }

        body {
            font-family: 'Inter', 'SF Pro Display', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #f8fafc 0%, #ffffff 100%);
            min-height: 100vh;
            font-feature-settings: 'kern' 1, 'liga' 1, 'calt' 1;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        .navbar {
            background: rgba(255, 255, 255, 0.95) !important;
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(22, 17, 96, 0.1);
        }

        .navbar-brand {
            font-weight: 800;
            letter-spacing: -0.01em;
            color: var(--navy-700) !important;
            text-decoration: none;
            font-size: 1.25rem;
        }
        
        .brand-logo {
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            box-shadow: 0 8px 24px rgba(22, 17, 96, 0.2);
            background: conic-gradient(from 200deg at 50% 50%, var(--navy-400), var(--navy-700));
        }
        
        .brand-logo svg {
            filter: drop-shadow(0 3px 8px rgba(22, 17, 96, 0.3));
        }

        .login-card {
            background: rgba(255, 255, 255, 0.8);
            border: 1px solid rgba(22, 17, 96, 0.1);
            border-radius: 22px;
            box-shadow: 0 20px 60px rgba(22, 17, 96, 0.15);
            backdrop-filter: blur(12px) saturate(1.15);
        }

        .login-title {
            font-weight: 900;
            letter-spacing: -0.02em;
            color: var(--text);
        }

        .login-subtitle {
            color: var(--text-muted);
            line-height: 1.6;
        }

        .form-control {
            border-radius: 12px;
            border: 1px solid rgba(22, 17, 96, 0.2);
            padding: 0.75rem 1rem;
            font-size: 1rem;
            transition: all 0.2s ease;
        }

        .form-control:focus {
            border-color: var(--navy-700);
            box-shadow: 0 0 0 4px rgba(22, 17, 96, 0.1);
        }

        .form-control::placeholder {
            color: var(--text-muted);
        }

        .btn-primary {
            background: var(--navy-700);
            border-color: var(--navy-700);
            border-radius: 12px;
            font-weight: 600;
            padding: 0.75rem 1rem;
            box-shadow: 0 8px 18px rgba(22, 17, 96, 0.15);
            transition: all 0.2s ease;
        }

        .btn-primary:hover {
            background: #0f0f4a;
            border-color: #0f0f4a;
            transform: translateY(-1px);
            filter: brightness(1.05);
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        .password-toggle {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--text-muted);
            transition: color 0.2s ease;
            padding: 4px;
        }

        .password-toggle:hover {
            color: var(--navy-700);
        }

        .form-check-input:checked {
            background-color: var(--navy-700);
            border-color: var(--navy-700);
        }

        .demo-card {
            background: rgba(255, 255, 255, 0.8);
            border: 1px solid rgba(22, 17, 96, 0.1);
            border-radius: 16px;
            backdrop-filter: blur(12px) saturate(1.15);
        }

        .demo-btn {
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.8rem;
            transition: all 0.2s ease;
            border: none;
            color: white;
        }

        .demo-btn:hover {
            transform: translateY(-1px);
            filter: brightness(1.1);
        }

        .text-link {
            color: var(--navy-700);
            text-decoration: none;
            font-weight: 600;
        }

        .text-link:hover {
            text-decoration: underline;
            color: var(--navy-700);
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
                <div class="brand-logo me-2">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none">
                        <path d="M5 7h14M7 11h10M9 15h6" stroke="#fff" stroke-width="1.7" stroke-linecap="round"/>
                        <rect x="3" y="4" width="18" height="16" rx="3" stroke="#fff" stroke-width="1.5" fill="none" opacity=".9"/>
                    </svg>
                </div>
                {{ config('app.name', 'Generix POS') }}
            </a>
        </div>
    </nav>

    <!-- Login Form -->
    <div class="container-fluid d-flex align-items-center justify-content-center min-vh-100 py-5">
        <div class="row w-100 justify-content-center">
            <div class="col-12 col-sm-10 col-md-8 col-lg-6 col-xl-5">
                <div class="card login-card border-0 p-4 p-md-5">
                    <div class="card-body">
                        <div class="text-center mb-4">
                            <h1 class="login-title h2 mb-2">@lang('lang_v1.welcome_back')</h1>
                            <p class="login-subtitle">@lang('lang_v1.login_to_your') {{ config('app.name', 'Generix POS') }}</p>
                        </div>

                        @php
                            $username = old('username');
                            $password = null;
                            if (config('app.env') == 'demo') {
                                $username = 'admin';
                                $password = '123456';

                                $demo_types = [
                                    'all_in_one' => 'admin',
                                    'super_market' => 'admin',
                                    'pharmacy' => 'admin-pharmacy',
                                    'electronics' => 'admin-electronics',
                                    'services' => 'admin-services',
                                    'restaurant' => 'admin-restaurant',
                                    'superadmin' => 'superadmin',
                                    'woocommerce' => 'woocommerce_user',
                                    'essentials' => 'admin-essentials',
                                    'manufacturing' => 'manufacturer-demo',
                                ];

                                if (!empty($_GET['demo_type']) && array_key_exists($_GET['demo_type'], $demo_types)) {
                                    $username = $demo_types[$_GET['demo_type']];
                                }
                            }
                        @endphp

                        @if (config('app.env') == 'demo')
                        <div class="card demo-card mb-4">
                            <div class="card-body">
                                <h5 class="card-title text-center mb-3">Demo Shops</h5>
                                <p class="text-center text-muted small mb-3">
                                    Demos are for example purpose only, this application can be used in many other similar businesses.
                                </p>
                                <div class="row g-2">
                                    <div class="col-6 col-md-4">
                                        <button class="btn demo-btn w-100" style="background:#28a745;" data-admin="admin">All In One</button>
                                    </div>
                                    <div class="col-6 col-md-4">
                                        <button class="btn demo-btn w-100" style="background:#dc3545;" data-admin="admin-pharmacy">Pharmacy</button>
                                    </div>
                                    <div class="col-6 col-md-4">
                                        <button class="btn demo-btn w-100" style="background:#fd7e14;" data-admin="admin-services">Multi-Service</button>
                                    </div>
                                    <div class="col-6 col-md-4">
                                        <button class="btn demo-btn w-100" style="background:#6f42c1;" data-admin="admin-electronics">Electronics</button>
                                    </div>
                                    <div class="col-6 col-md-4">
                                        <button class="btn demo-btn w-100" style="background:#161160;" data-admin="admin">Super Market</button>
                                    </div>
                                    <div class="col-6 col-md-4">
                                        <button class="btn demo-btn w-100" style="background:#dc3545;" data-admin="admin-restaurant">Restaurant</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        <form method="POST" action="{{ route('login') }}" id="login-form">
                            {{ csrf_field() }}

                            <div class="mb-3">
                                <label for="username" class="form-label fw-semibold">@lang('lang_v1.username')</label>
                                <input type="text" class="form-control" name="username" id="username" 
                                       placeholder="@lang('lang_v1.username')" value="{{ $username }}" required autofocus>
                                @if ($errors->has('username'))
                                    <div class="text-danger small mt-1">{{ $errors->first('username') }}</div>
                                @endif
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label fw-semibold">@lang('lang_v1.password')</label>
                                <div class="position-relative">
                                    <input type="password" class="form-control" name="password" id="password" 
                                           placeholder="@lang('lang_v1.password')" value="{{ $password }}" required>
                                    <button type="button" class="password-toggle" id="show_hide_icon">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                                @if ($errors->has('password'))
                                    <div class="text-danger small mt-1">{{ $errors->first('password') }}</div>
                                @endif
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                    <label class="form-check-label small text-muted" for="remember">
                                        @lang('lang_v1.remember_me')
                                    </label>
                                </div>
                                @if (config('app.env') != 'demo')
                                    <a href="{{ route('password.request') }}" class="text-link small">
                                        @lang('lang_v1.forgot_your_password')
                                    </a>
                                @endif
                            </div>

                            @if(config('constants.enable_recaptcha'))
                                <div class="mb-3">
                                    <div class="g-recaptcha" data-sitekey="{{ config('constants.google_recaptcha_key') }}"></div>
                                    @if ($errors->has('g-recaptcha-response'))
                                        <div class="text-danger small mt-1">{{ $errors->first('g-recaptcha-response') }}</div>
                                    @endif
                                </div>
                            @endif

                            <button type="submit" class="btn btn-primary w-100 mb-3">
                                @lang('lang_v1.login')
                            </button>
                        </form>

                        {{-- Registration disabled for clients --}}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Demo login functionality
        document.querySelectorAll('.demo-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                document.getElementById('username').value = this.dataset.admin;
                document.getElementById('password').value = '{{ $password }}';
                document.getElementById('login-form').submit();
            });
        });

        // Password toggle functionality
        const passwordToggle = document.getElementById('show_hide_icon');
        const passwordInput = document.getElementById('password');
        
        passwordToggle.addEventListener('click', function(e) {
            e.preventDefault();
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            
            // Toggle icon
            if (type === 'text') {
                this.innerHTML = '<i class="bi bi-eye-slash"></i>';
            } else {
                this.innerHTML = '<i class="bi bi-eye"></i>';
            }
        });
    });
    </script>
</body>
</html>