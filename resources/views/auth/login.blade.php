<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('lang_v1.login') }} - {{ config('app.name', 'Ultimate POS') }}</title>
    <meta name="color-scheme" content="dark light">
    <link rel="icon" href="{{ asset('favicon.ico') }}">
    <style>
        /* ---------- THEME ---------- */
        :root{
            --navy-900:#0a0a3a;
            --navy-800:#0f0f4a;
            --navy-700:#161160;
            --navy-600:#1a1a70;
            --navy-500:#1e1e80;
            --navy-400:#222290;
            --text:#1e293b;
            --text-muted:rgba(30,41,59,.7);
            --card-bg:rgba(255,255,255,.8);
            --card-border:rgba(22,17,96,.1);
            --focus-ring:0 0 0 4px rgba(22,17,96,.1);
            --shadow-lg:0 20px 60px rgba(22,17,96,.15);
        }

        /* ---------- RESET ---------- */
        *{box-sizing:border-box}
        html,body{height:100%}
        body{
            margin:0;
            color:var(--text);
            font-family:ui-sans-serif, -apple-system, BlinkMacSystemFont, "Segoe UI",
                         Roboto, "Helvetica Neue", Arial, "Noto Sans", "Apple Color Emoji",
                         "Segoe UI Emoji", "Segoe UI Symbol";
            line-height:1.5;
            background:
                radial-gradient(900px 600px at 8% -10%, rgba(22,17,96,.03), transparent 60%),
                radial-gradient(700px 400px at 100% 10%, rgba(22,17,96,.02), transparent 60%),
                linear-gradient(135deg, #ffffff, #f8fafc);
            overflow-x:hidden;
        }
        /* soft grain / texture */
        body::before{
            content:"";
            position:fixed; inset:0; pointer-events:none;
            background-image:
                radial-gradient(1px 1px at 10% 20%, rgba(22,17,96,.02) 0, transparent 50%),
                radial-gradient(1px 1px at 80% 70%, rgba(22,17,96,.015) 0, transparent 50%),
                radial-gradient(1px 1px at 50% 40%, rgba(22,17,96,.01) 0, transparent 50%);
            background-size:8px 8px, 8px 8px, 8px 8px;
            mix-blend-mode:overlay;
        }

        a{color:inherit; text-decoration:none}
        .container{
            max-width:1120px; margin-inline:auto; padding-inline:24px;
        }

        /* ---------- HEADER ---------- */
        .site-header{
            position:sticky; top:0; z-index:10;
            backdrop-filter:saturate(1.2) blur(6px);
            background:linear-gradient(to bottom,
                rgba(255,255,255,.8), rgba(255,255,255,0));
        }
        .navbar{
            display:flex; align-items:center; justify-content:space-between;
            padding:18px 0;
        }
        .brand{
            display:flex; align-items:center; gap:.75rem; font-weight:800; letter-spacing:.2px;
        }
        .brand-logo{
            width:36px; height:36px; display:grid; place-items:center;
            border-radius:10px; box-shadow:0 8px 24px rgba(22,17,96,.2);
            background:conic-gradient(from 200deg at 50% 50%, var(--navy-400), var(--navy-700));
        }
        .brand svg{filter:drop-shadow(0 3px 8px rgba(22,17,96,.3))}
        .brand-name{font-size:1.05rem}

        /* ---------- LOGIN FORM ---------- */
        .login-container{
            min-height:100vh; display:flex; align-items:center; justify-content:center;
            padding:40px 20px;
        }
        .login-card{
            position:relative;
            background:var(--card-bg);
            border:1px solid var(--card-border);
            border-radius:22px;
            padding:48px clamp(24px, 4vw, 56px);
            box-shadow:var(--shadow-lg);
            backdrop-filter:blur(12px) saturate(1.15);
            max-width:480px; width:100%;
        }
        @supports not (backdrop-filter: blur(1px)){
            .login-card{background:rgba(255,255,255,.9)}
        }

        .login-header{
            text-align:center; margin-bottom:2rem;
        }
        .login-title{
            font-size:2rem; font-weight:800; margin:0 0 .5rem;
            color:var(--text);
        }
        .login-subtitle{
            color:var(--text-muted); font-size:1rem; margin:0;
        }

        .form-group{
            margin-bottom:1.5rem;
        }
        .form-label{
            display:block; font-weight:600; margin-bottom:.5rem;
            color:var(--text); font-size:.9rem;
        }
        .form-input{
            width:100%; padding:.75rem 1rem; border-radius:12px;
            border:1px solid rgba(22,17,96,.2); background:#fff;
            color:var(--text); font-size:1rem; transition:all .2s ease;
        }
        .form-input:focus{
            outline:none; border-color:var(--navy-700);
            box-shadow:var(--focus-ring);
        }
        .form-input::placeholder{
            color:var(--text-muted);
        }

        .password-container{
            position:relative;
        }
        .password-toggle{
            position:absolute; right:12px; top:50%; transform:translateY(-50%);
            background:none; border:none; cursor:pointer; padding:4px;
            color:var(--text-muted); transition:color .2s ease;
        }
        .password-toggle:hover{
            color:var(--navy-700);
        }

        .remember-container{
            display:flex; align-items:center; gap:.5rem; margin-bottom:1.5rem;
        }
        .remember-checkbox{
            width:16px; height:16px; accent-color:var(--navy-700);
        }
        .remember-label{
            font-size:.9rem; color:var(--text-muted);
        }

        .login-btn{
            width:100%; padding:.75rem 1rem; border-radius:12px;
            background:var(--navy-700); color:#fff; border:none;
            font-weight:600; font-size:1rem; cursor:pointer;
            transition:all .2s ease; box-shadow:0 8px 18px rgba(22,17,96,.15);
        }
        .login-btn:hover{
            filter:brightness(1.05); transform:translateY(-1px);
        }
        .login-btn:active{
            transform:translateY(0);
        }

        .register-link{
            text-align:center; margin-top:1.5rem;
        }
        .register-link a{
            color:var(--navy-700); text-decoration:none; font-weight:600;
        }
        .register-link a:hover{
            text-decoration:underline;
        }

        .forgot-link{
            color:var(--navy-700); text-decoration:none; font-size:.9rem;
        }
        .forgot-link:hover{
            text-decoration:underline;
        }

        /* ---------- DEMO SECTION ---------- */
        .demo-section{
            background:var(--card-bg); border:1px solid var(--card-border);
            border-radius:16px; padding:24px; margin-bottom:24px;
            backdrop-filter:blur(12px) saturate(1.15);
        }
        .demo-title{
            font-size:1.1rem; font-weight:700; margin:0 0 1rem;
            color:var(--text); text-align:center;
        }
        .demo-grid{
            display:grid; grid-template-columns:repeat(auto-fit, minmax(120px, 1fr));
            gap:12px; margin-bottom:16px;
        }
        .demo-btn{
            padding:.5rem .75rem; border-radius:8px; border:none;
            color:#fff; font-weight:600; font-size:.8rem; cursor:pointer;
            transition:all .2s ease; text-align:center;
        }
        .demo-btn:hover{
            transform:translateY(-1px); filter:brightness(1.1);
        }

        /* ---------- RESPONSIVE ---------- */
        @media (max-width: 768px){
            .login-container{padding:20px 16px}
            .login-card{padding:32px 24px}
            .demo-grid{grid-template-columns:repeat(2, 1fr)}
        }
    </style>
</head>
<body>
<header class="site-header">
    <div class="container navbar">
        <a class="brand" href="{{ url('/') }}" aria-label="Home">
            <span class="brand-logo" aria-hidden="true">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none">
                    <path d="M5 7h14M7 11h10M9 15h6" stroke="#fff" stroke-width="1.7" stroke-linecap="round"/>
                    <rect x="3" y="4" width="18" height="16" rx="3" stroke="#fff" stroke-width="1.5" fill="none" opacity=".9"/>
                </svg>
            </span>
            <span class="brand-name">{{ config('app.name', 'Ultimate POS') }}</span>
        </a>
    </div>
</header>

<div class="login-container">
    <div class="login-card">
        <div class="login-header">
            <h1 class="login-title">@lang('lang_v1.welcome_back')</h1>
            <p class="login-subtitle">@lang('lang_v1.login_to_your') {{ config('app.name', 'Ultimate POS') }}</p>
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
        <div class="demo-section">
            <h3 class="demo-title">Demo Shops</h3>
            <p style="text-align:center; color:var(--text-muted); font-size:.9rem; margin-bottom:16px;">
                Demos are for example purpose only, this application can be used in many other similar businesses.
            </p>
            <div class="demo-grid">
                <button class="demo-btn" style="background:#28a745;" data-admin="admin">All In One</button>
                <button class="demo-btn" style="background:#dc3545;" data-admin="admin-pharmacy">Pharmacy</button>
                <button class="demo-btn" style="background:#fd7e14;" data-admin="admin-services">Multi-Service</button>
                <button class="demo-btn" style="background:#6f42c1;" data-admin="admin-electronics">Electronics</button>
                <button class="demo-btn" style="background:#161160;" data-admin="admin">Super Market</button>
                <button class="demo-btn" style="background:#dc3545;" data-admin="admin-restaurant">Restaurant</button>
            </div>
        </div>
        @endif

        <form method="POST" action="{{ route('login') }}" id="login-form">
            {{ csrf_field() }}
            
            <div class="form-group">
                <label class="form-label">@lang('lang_v1.username')</label>
                <input class="form-input" name="username" required autofocus 
                       placeholder="@lang('lang_v1.username')" id="username" type="text" 
                       value="{{ $username }}" />
                @if ($errors->has('username'))
                    <span style="color:#dc3545; font-size:.8rem; margin-top:.25rem; display:block;">
                        {{ $errors->first('username') }}
                    </span>
                @endif
            </div>

            <div class="form-group">
                <label class="form-label">@lang('lang_v1.password')</label>
                <div class="password-container">
                    <input class="form-input" id="password" type="password" name="password" 
                           value="{{ $password }}" required placeholder="@lang('lang_v1.password')" />
                    <button type="button" class="password-toggle" id="show_hide_icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                            <circle cx="12" cy="12" r="3"/>
                        </svg>
                    </button>
                </div>
                @if ($errors->has('password'))
                    <span style="color:#dc3545; font-size:.8rem; margin-top:.25rem; display:block;">
                        {{ $errors->first('password') }}
                    </span>
                @endif
            </div>

            <div class="remember-container">
                <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }} 
                       class="remember-checkbox" id="remember">
                <label for="remember" class="remember-label">@lang('lang_v1.remember_me')</label>
                @if (config('app.env') != 'demo')
                    <a href="{{ route('password.request') }}" class="forgot-link" style="margin-left:auto;">
                        @lang('lang_v1.forgot_your_password')
                    </a>
                @endif
            </div>

            @if(config('constants.enable_recaptcha'))
            <div class="form-group">
                <div class="g-recaptcha" data-sitekey="{{ config('constants.google_recaptcha_key') }}"></div>
                @if ($errors->has('g-recaptcha-response'))
                    <span style="color:#dc3545; font-size:.8rem; margin-top:.25rem; display:block;">
                        {{ $errors->first('g-recaptcha-response') }}
                    </span>
                @endif
            </div>
            @endif

            <button type="submit" class="login-btn">
                @lang('lang_v1.login')
            </button>
        </form>

        @if (!(request()->segment(1) == 'business' && request()->segment(2) == 'register'))
            @if (config('constants.allow_registration'))
                <div class="register-link">
                    <a href="{{ route('business.getRegister') }}@if (!empty(request()->lang)) {{ '?lang=' . request()->lang }} @endif">
                        {{ __('business.not_yet_registered') }} <strong>{{ __('business.register_now') }}</strong>
                    </a>
                </div>
            @endif
        @endif
    </div>
</div>

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
            this.innerHTML = '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>';
        } else {
            this.innerHTML = '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>';
        }
    });
});
</script>
</body>
</html>
