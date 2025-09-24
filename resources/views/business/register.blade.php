<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('lang_v1.register') }} - {{ config('app.name', 'Generix POS') }}</title>
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
    <!-- Bootstrap Datepicker CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" integrity="sha512-mSYUmp1HYZDFaVKK//63EcZq4iFWFjxSL+Z3T/aCt4IO9Cejm03q3NKKYN6pF9YbY7F6XfU2QOKXAsl3zn6u2g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
    <style>
        :root {
            --navy-700: #161160;
            --navy-600: #1a1a70;
            --navy-500: #1e1e80;
            --navy-400: #222290;
            --text: #1e293b;
            --text-muted: rgba(30, 41, 59, 0.7);
            --success: #10b981;
            --warning: #f59e0b;
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

        .register-card {
            background: rgba(255, 255, 255, 0.8);
            border: 1px solid rgba(22, 17, 96, 0.1);
            border-radius: 22px;
            box-shadow: 0 20px 60px rgba(22, 17, 96, 0.15);
            backdrop-filter: blur(12px) saturate(1.15);
            overflow: hidden;
        }

        .register-header {
            background: linear-gradient(135deg, var(--navy-700), var(--navy-500));
            color: white;
            padding: 2rem;
            text-align: center;
            border-radius: 22px 22px 0 0;
        }

        .register-title {
            font-weight: 900;
            letter-spacing: -0.02em;
            margin-bottom: 0.5rem;
        }

        .register-subtitle {
            opacity: 0.9;
            line-height: 1.6;
            margin: 0;
        }

        .register-title {
            font-weight: 900;
            letter-spacing: -0.02em;
            color: var(--text);
        }

        .register-subtitle {
            color: var(--text-muted);
            line-height: 1.6;
        }

        .section-title {
            font-weight: 700;
            color: var(--text);
            border-bottom: 2px solid var(--navy-700);
            padding-bottom: 0.5rem;
        }

        .form-control, .form-select {
            border-radius: 12px;
            border: 1px solid rgba(22, 17, 96, 0.2);
            padding: 0.75rem 1rem;
            font-size: 1rem;
            transition: all 0.2s ease;
        }

        .form-control:focus, .form-select:focus {
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

        .form-check-input:checked {
            background-color: var(--navy-700);
            border-color: var(--navy-700);
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

        .input-group-text {
            background: rgba(22, 17, 96, 0.05);
            border: 1px solid rgba(22, 17, 96, 0.2);
            color: var(--text-muted);
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

    <!-- Register Form -->
    <div class="container-fluid d-flex align-items-center justify-content-center min-vh-100 py-5">
        <div class="row w-100 justify-content-center">
            <div class="col-12 col-lg-10 col-xl-8">
                <div class="card register-card border-0">
                    <!-- Header -->
                    <div class="register-header">
                        <h1 class="register-title h2 text-white">{{ config('app.name', 'Generix POS') }}</h1>
                        <p class="register-subtitle text-white">@lang('business.register_and_get_started_in_minutes')</p>
                    </div>

                    <div class="p-4 p-md-5">

            {!! Form::open([
                'url' => route('business.postRegister'),
                'method' => 'post',
                'id' => 'business_register_form',
                'files' => true,
            ]) !!}
                        
            @include('business.partials.register_form', ['is_register' => true])
            {!! Form::hidden('package_id', $package_id) !!}
                        
                        <button type="submit" class="btn btn-primary w-100 mt-3">
                            @lang('business.register')
                        </button>
                        
            {!! Form::close() !!}

                        <div class="text-center mt-4">
                            <a href="{{ route('login') }}" class="text-link">
                                Already have account? <strong>@lang('lang_v1.login')</strong>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery (required for bootstrap-datepicker) -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Bootstrap Datepicker JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js" integrity="sha512-T/tUfKSV1bihCnd+MxKD0Hm1uBBroVY1TT8C2fZCqF4PgtC6W/1ZC6rXrXHxN1vQKu3Sj7r3azrRtAM8s7Wukg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Language change functionality
        document.querySelectorAll('.change_lang').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                window.location = "{{ route('business.getRegister') }}?lang=" + this.getAttribute('value');
            });
        });

        // Initialize start date picker if plugin is available
        const $startInputs = document.querySelectorAll('.start-date-picker');

        if (typeof $ !== 'undefined' && $.fn.datepicker) {
            $('.start-date-picker').datepicker({
                format: 'mm/dd/yyyy',
                autoclose: true,
                todayHighlight: true
            }).on('click', function(){
                $(this).datepicker('show');
            });
        } else {
            // Fallback to native date input
            $startInputs.forEach(function(input){
                try {
                    input.setAttribute('type', 'date');
                } catch(e) {
                    // ignore
                }
            });
        }
    });
    </script>
</body>
</html>