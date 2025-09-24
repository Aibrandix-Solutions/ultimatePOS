<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('lang_v1.reset_password') }} - {{ config('app.name', 'Generix POS') }}</title>
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

        .reset-card {
            background: rgba(255, 255, 255, 0.8);
            border: 1px solid rgba(22, 17, 96, 0.1);
            border-radius: 22px;
            box-shadow: 0 20px 60px rgba(22, 17, 96, 0.15);
            backdrop-filter: blur(12px) saturate(1.15);
            overflow: hidden;
        }

        .reset-header {
            background: linear-gradient(135deg, var(--navy-700), var(--navy-500));
            color: white;
            padding: 2rem;
            text-align: center;
            border-radius: 22px 22px 0 0;
        }

        .reset-title {
            font-weight: 900;
            letter-spacing: -0.02em;
            margin-bottom: 0.5rem;
        }

        .reset-subtitle {
            opacity: 0.9;
            line-height: 1.6;
            margin: 0;
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

        .input-group-text {
            background: rgba(22, 17, 96, 0.05);
            border: 1px solid rgba(22, 17, 96, 0.2);
            color: var(--text-muted);
            border-radius: 12px 0 0 12px;
        }

        .input-group .form-control {
            border-radius: 0 12px 12px 0;
        }

        .btn-primary {
            background: var(--navy-700);
            border-color: var(--navy-700);
            border-radius: 12px;
            font-weight: 600;
            padding: 0.75rem 2rem;
            box-shadow: 0 8px 18px rgba(22, 17, 96, 0.15);
            transition: all 0.2s ease;
        }

        .btn-primary:hover {
            background: #0f0f4a;
            border-color: #0f0f4a;
            transform: translateY(-1px);
            filter: brightness(1.05);
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

        .alert {
            border-radius: 12px;
            border: none;
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

    <!-- Reset Password Form -->
    <div class="container-fluid d-flex align-items-center justify-content-center min-vh-100 py-5">
        <div class="row w-100 justify-content-center">
            <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                <div class="card reset-card border-0">
                    <!-- Header -->
                    <div class="reset-header">
                        <h1 class="reset-title h2">{{ config('app.name', 'Generix POS') }}</h1>
                        <p class="reset-subtitle">@lang('lang_v1.reset_password')</p>
                    </div>

                    <div class="p-4 p-md-5">
                        <form method="POST" action="{{ route('password.request') }}">
                            {{ csrf_field() }}
                            <input type="hidden" name="token" value="{{ $token }}">

                            <div class="mb-4">
                                <label class="form-label fw-semibold">@lang('Email') *</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="bi bi-envelope"></i>
                                    </span>
                                    <input id="email" type="email" class="form-control" name="email"
                                           value="{{ $email ?? old('email') }}" required autofocus 
                                           placeholder="@lang('lang_v1.email_address')">
                                </div>
                                @if ($errors->has('email'))
                                    <div class="text-danger small mt-1">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </div>
                                @endif
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-semibold">@lang('lang_v1.password') *</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="bi bi-lock"></i>
                                    </span>
                                    <input id="password" type="password" class="form-control" name="password"
                                           required placeholder="@lang('lang_v1.password')">
                                </div>
                                @if ($errors->has('password'))
                                    <div class="text-danger small mt-1">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </div>
                                @endif
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-semibold">@lang('business.confirm_password') *</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="bi bi-lock"></i>
                                    </span>
                                    <input id="password_confirmation" type="password" class="form-control"
                                           name="password_confirmation" required 
                                           placeholder="@lang('business.confirm_password')">
                                </div>
                                @if ($errors->has('password_confirmation'))
                                    <div class="text-danger small mt-1">
                                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                                    </div>
                                @endif
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="bi bi-check-circle me-2"></i>@lang('lang_v1.reset_password')
                                </button>
                            </div>
                        </form>

                        <!-- Back to Login Link -->
                        <div class="text-center mt-4">
                            <a href="{{ route('login') }}" class="text-link">
                                <i class="bi bi-arrow-left me-2"></i>Back to Login
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


