<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('lang_v1.register') }} - {{ config('app.name', 'Ultimate POS') }}</title>
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

        /* ---------- REGISTER FORM ---------- */
        .register-container{
            min-height:100vh; display:flex; align-items:center; justify-content:center;
            padding:40px 20px;
        }
        .register-card{
            position:relative;
            background:var(--card-bg);
            border:1px solid var(--card-border);
            border-radius:22px;
            padding:48px clamp(24px, 4vw, 56px);
            box-shadow:var(--shadow-lg);
            backdrop-filter:blur(12px) saturate(1.15);
            max-width:800px; width:100%;
        }
        @supports not (backdrop-filter: blur(1px)){
            .register-card{background:rgba(255,255,255,.9)}
        }

        .register-header{
            text-align:center; margin-bottom:2rem;
        }
        .register-title{
            font-size:2rem; font-weight:800; margin:0 0 .5rem;
            color:var(--text);
        }
        .register-subtitle{
            color:var(--text-muted); font-size:1rem; margin:0;
        }

        .form-section{
            margin-bottom:2rem;
        }
        .section-title{
            font-size:1.25rem; font-weight:700; margin:0 0 1rem;
            color:var(--text); border-bottom:2px solid var(--navy-700);
            padding-bottom:.5rem;
        }

        .form-row{
            display:grid; grid-template-columns:repeat(auto-fit, minmax(250px, 1fr));
            gap:1rem; margin-bottom:1rem;
        }
        .form-group{
            margin-bottom:1rem;
        }
        .form-label{
            display:block; font-weight:600; margin-bottom:.5rem;
            color:var(--text); font-size:.9rem;
        }
        .form-input, .form-select{
            width:100%; padding:.75rem 1rem; border-radius:12px;
            border:1px solid rgba(22,17,96,.2); background:#fff;
            color:var(--text); font-size:1rem; transition:all .2s ease;
        }
        .form-input:focus, .form-select:focus{
            outline:none; border-color:var(--navy-700);
            box-shadow:var(--focus-ring);
        }
        .form-input::placeholder{
            color:var(--text-muted);
        }

        .input-group{
            position:relative; display:flex; align-items:center;
        }
        .input-icon{
            position:absolute; left:12px; color:var(--text-muted);
            z-index:1; pointer-events:none;
        }
        .input-group .form-input{
            padding-left:40px;
        }

        .register-btn{
            width:100%; padding:.75rem 1rem; border-radius:12px;
            background:var(--navy-700); color:#fff; border:none;
            font-weight:600; font-size:1rem; cursor:pointer;
            transition:all .2s ease; box-shadow:0 8px 18px rgba(22,17,96,.15);
            margin-top:1rem;
        }
        .register-btn:hover{
            filter:brightness(1.05); transform:translateY(-1px);
        }
        .register-btn:active{
            transform:translateY(0);
        }

        .login-link{
            text-align:center; margin-top:1.5rem;
        }
        .login-link a{
            color:var(--navy-700); text-decoration:none; font-weight:600;
        }
        .login-link a:hover{
            text-decoration:underline;
        }

        .checkbox-container{
            display:flex; align-items:center; gap:.5rem; margin-bottom:1rem;
        }
        .checkbox-input{
            width:16px; height:16px; accent-color:var(--navy-700);
        }
        .checkbox-label{
            font-size:.9rem; color:var(--text-muted);
        }

        /* ---------- RESPONSIVE ---------- */
        @media (max-width: 768px){
            .register-container{padding:20px 16px}
            .register-card{padding:32px 24px}
            .form-row{grid-template-columns:1fr}
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

<div class="register-container">
    <div class="register-card">
        <div class="register-header">
            <h1 class="register-title">{{ config('app.name', 'Ultimate POS') }}</h1>
            <p class="register-subtitle">@lang('business.register_and_get_started_in_minutes')</p>
        </div>

        {!! Form::open([
            'url' => route('business.postRegister'),
            'method' => 'post',
            'id' => 'business_register_form',
            'files' => true,
        ]) !!}
        
        @include('business.partials.register_form', ['is_register' => true])
        {!! Form::hidden('package_id', $package_id) !!}
        
        <button type="submit" class="register-btn">
            @lang('business.register')
        </button>
        
        {!! Form::close() !!}

        <div class="login-link">
            <a href="{{ route('login') }}">
                Already have account? <strong>@lang('lang_v1.login')</strong>
            </a>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Language change functionality
    document.querySelectorAll('.change_lang').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            window.location = "{{ route('business.getRegister') }}?lang=" + this.getAttribute('value');
        });
    });
});
</script>
</body>
</html>
