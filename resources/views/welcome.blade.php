<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Generix POS') }}</title>
    <meta name="color-scheme" content="dark light">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('site.webmanifest') }}">
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
            font-family: 'Inter', 'SF Pro Display', -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', 'Helvetica Neue', Arial, sans-serif;
            background: linear-gradient(135deg, #f8fafc 0%, #ffffff 100%);
            min-height: 100vh;
            font-feature-settings: 'kern' 1, 'liga' 1, 'calt' 1;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        /* Custom navbar styling */
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
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(22, 17, 96, 0.15);
            background: var(--navy-700);
        }
        
        .brand-logo svg {
            filter: drop-shadow(0 2px 4px rgba(22, 17, 96, 0.2));
        }

        .navbar-toggler {
            border: none;
            padding: 8px;
            border-radius: 8px;
        }

        .navbar-toggler:focus {
            box-shadow: 0 0 0 4px rgba(22, 17, 96, 0.1);
        }

        .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%2822, 17, 96, 1%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }

        .navbar-collapse {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(12px);
            border-radius: 12px;
            margin-top: 12px;
            padding: 16px;
            border: 1px solid rgba(22, 17, 96, 0.1);
            box-shadow: 0 8px 24px rgba(22, 17, 96, 0.1);
        }

        .navbar-nav .btn {
            width: 100%;
            justify-content: flex-start;
            padding: 12px 16px;
            border-radius: 12px;
            font-size: 1rem;
            margin-bottom: 8px;
            border: 1px solid rgba(22, 17, 96, 0.15);
        }

        .navbar-nav .btn:last-child {
            margin-bottom: 0;
        }

        .btn-primary {
            background: var(--navy-700);
            border-color: var(--navy-700);
        }

        .btn-primary:hover {
            background: #0f0f4a;
            border-color: #0f0f4a;
            transform: translateY(-1px);
        }

        .btn-outline-secondary {
            color: var(--navy-700);
            border-color: rgba(22, 17, 96, 0.15);
            background: rgba(22, 17, 96, 0.05);
        }

        .btn-outline-secondary:hover {
            background: rgba(22, 17, 96, 0.1);
            border-color: rgba(22, 17, 96, 0.25);
            color: var(--navy-700);
        }

        .dropdown-menu {
            border: none;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(22, 17, 96, 0.15);
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(22, 17, 96, 0.1);
        }

        .dropdown-item {
            border-radius: 8px;
            margin: 2px 8px;
            padding: 8px 12px;
            color: var(--text-muted);
            transition: all 0.2s ease;
        }

        .dropdown-item:hover {
            background: rgba(22, 17, 96, 0.05);
            color: var(--navy-700);
        }

        /* Hero section */
        .hero-section {
            position: relative;
            padding: 64px 0;
            overflow: hidden;
        }

        .hero-bg {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: 
                radial-gradient(900px 600px at 8% -10%, rgba(22, 17, 96, 0.03), transparent 60%),
                radial-gradient(700px 400px at 100% 10%, rgba(22, 17, 96, 0.02), transparent 60%);
            pointer-events: none;
        }

        .hero-card {
            background: rgba(255, 255, 255, 0.8);
            border: 1px solid rgba(22, 17, 96, 0.1);
            border-radius: 24px;
            padding: 34px;
            box-shadow: 0 20px 60px rgba(22, 17, 96, 0.15);
            backdrop-filter: blur(12px);
        }

        .eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            border-radius: 12px;
            background: rgba(22, 17, 96, 0.08);
            border: 1px solid rgba(22, 17, 96, 0.15);
            color: var(--navy-700);
            font-weight: 600;
            font-size: 0.9rem;
            margin-bottom: 24px;
        }

        .hero-title {
            font-weight: 900;
            font-size: clamp(2.5rem, 7vw, 5rem);
            color: var(--navy-700);
            line-height: 1.05;
            margin-bottom: 16px;
            letter-spacing: -0.02em;
        }

        .hero-subtitle {
            color: var(--text-muted);
            font-size: clamp(1.1rem, 2.2vw, 1.25rem);
            margin-bottom: 32px;
            line-height: 1.6;
            font-weight: 400;
        }

        .feature-chip {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 16px;
            border-radius: 12px;
            background: rgba(22, 17, 96, 0.08);
            border: 1px solid rgba(22, 17, 96, 0.15);
            color: var(--navy-700);
            font-weight: 600;
            font-size: 0.9rem;
        }

        .preview-card {
            background: linear-gradient(160deg, #ffffff, #f8fafc);
            border-radius: 16px;
            box-shadow: 0 25px 60px rgba(22, 17, 96, 0.15);
            overflow: hidden;
            aspect-ratio: 16/10;
        }

        .preview-header {
            background: #fff;
            border-bottom: 1px solid #efefef;
            padding: 16px 20px;
            font-weight: 700;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .preview-pill {
            background: var(--navy-700);
            color: white;
            padding: 4px 12px;
            border-radius: 8px;
            font-size: 0.8rem;
            font-weight: 700;
        }

        .preview-body {
            padding: 24px;
        }

        .preview-item {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 12px;
            box-shadow: 0 4px 12px rgba(22, 17, 96, 0.08);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .preview-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(22, 17, 96, 0.12);
        }

        .preview-total {
            display: flex;
            justify-content: space-between;
            font-weight: 800;
            font-size: 1.1rem;
        }

        /* Floating orbs */
        .orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(40px);
            opacity: 0.25;
            pointer-events: none;
            transform: translateZ(0);
        }

        .orb.one {
            width: 380px;
            height: 380px;
            left: -120px;
            top: 80px;
            background: radial-gradient(circle, var(--navy-500), transparent 60%);
            animation: floaty 10s ease-in-out infinite;
        }

        .orb.two {
            width: 420px;
            height: 420px;
            right: -140px;
            top: 120px;
            background: radial-gradient(circle, var(--navy-400), transparent 60%);
            animation: floaty 12s ease-in-out infinite 0.3s;
        }

        .orb.three {
            width: 300px;
            height: 300px;
            left: 50%;
            bottom: -60px;
            transform: translateX(-50%);
            background: radial-gradient(circle, rgba(22, 17, 96, 0.1), transparent 60%);
            animation: floaty 8s ease-in-out infinite 0.6s;
        }

        @keyframes floaty {
            from { transform: translateY(0) translateX(-50%); }
            50% { transform: translateY(-6px) translateX(-50%); }
            to { transform: translateY(0) translateX(-50%); }
        }

        @media (prefers-reduced-motion: reduce) {
            .orb { animation: none; }
        }

        /* Desktop navbar styling */
        @media (min-width: 992px) {
            .navbar-nav {
                flex-direction: row;
                gap: 12px;
            }
            
            .navbar-nav .btn {
                width: auto;
                padding: 8px 16px;
                margin-bottom: 0;
                font-size: 0.9rem;
            }
            
            .navbar-nav .nav-item {
                margin: 0;
            }
            
            .navbar-collapse {
                background: transparent;
                backdrop-filter: none;
                border-radius: 0;
                margin-top: 0;
                padding: 0;
                border: none;
                box-shadow: none;
            }
        }

        /* Ensure carousel controls are visible on light images */
        #posShowcase .carousel-control-prev,
        #posShowcase .carousel-control-next {
            width: 3rem;
            opacity: 1;
            z-index: 5;
        }
        #posShowcase .carousel-control-prev-icon,
        #posShowcase .carousel-control-next-icon {
            width: 2.75rem;
            height: 2.75rem;
            background-size: 100% 100%;
            filter: none;
        }
        /* Themed control icons (midnight blue) */
        #posShowcase .carousel-control-prev-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='%23161160' viewBox='0 0 16 16'%3e%3cpath fill-rule='evenodd' d='M11.354 1.646a.5.5 0 0 1 0 .708L6.707 7l4.647 4.646a.5.5 0 0 1-.708.708l-5-5a.5.5 0 0 1 0-.708l5-5a.5.5 0 0 1 .708 0z'/%3e%3c/svg%3e");
        }
        #posShowcase .carousel-control-next-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='%23161160' viewBox='0 0 16 16'%3e%3cpath fill-rule='evenodd' d='M4.646 1.646a.5.5 0 0 1 .708 0l5 5a.5.5 0 0 1 0 .708l-5 5a.5.5 0 0 1-.708-.708L9.293 7 4.646 2.354a.5.5 0 0 1 0-.708z'/%3e%3c/svg%3e");
        }

        /* Showcase images - prioritize full visibility, no letterboxing */
        #posShowcase .carousel-item img {
            width: 100%;
            height: auto;
            display: block;
        }
        /* Reduce hero padding to fit images tighter */
        .hero-section { padding: 56px 0; }
        @media (max-width: 576px) {
            .hero-section { padding: 40px 0; }
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

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    @auth
                        <li class="nav-item">
                            <a class="btn btn-primary" href="{{ url('/home') }}">Go to Dashboard</a>
                        </li>
                    @else
                        {{-- Registration disabled for clients --}}
                        @if (Route::has('login'))
                            <li class="nav-item">
                                <a class="btn btn-primary" href="{{ route('login') }}">Sign In</a>
                            </li>
                        @endif
                    @endauth
                    
                    <!-- <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            English
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#">English</a></li>
                            <li><a class="dropdown-item" href="#">Español</a></li>
                            <li><a class="dropdown-item" href="#">Français</a></li>
                        </ul>
                    </li> -->
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <main class="hero-section">
        <div class="hero-bg"></div>
        <div class="orb one" aria-hidden="true"></div>
        <div class="orb two" aria-hidden="true"></div>
        <div class="orb three" aria-hidden="true"></div>
        
        <div class="container">
            <div class="row align-items-center">
                <!-- Hero content -->
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <div class="hero-card">
                        <div class="eyebrow">
                            <i class="bi bi-grid-3x3-gap"></i>
                            Point of Sale • Ready
                        </div>
                        
                        <h1 class="hero-title">{{ config('app.name', 'Generix POS') }}</h1>
                        <p class="hero-subtitle">
                            Fast, reliable and beautifully simple POS for stores, cafés and boutiques.
                            Manage inventory, print receipts and see insights in real time.
                        </p>

                        <div class="d-flex flex-column flex-sm-row gap-3 mb-4">
                            @auth
                                <a class="btn btn-primary btn-lg" href="{{ url('/home') }}">Open Dashboard</a>
                            @else
                                {{-- Registration disabled for clients --}}
                                @if (Route::has('login'))
                                    <a class="btn btn-primary btn-lg" href="{{ route('login') }}">I already have an account</a>
                                    <a class="btn btn-warning btn-lg" href="https://generixerp.com" target="_blank" rel="noopener">Buy Now</a>
                                @endif
                            @endauth
                        </div>

                        <div class="d-flex flex-column flex-sm-row flex-wrap gap-2 mb-4">
                            <span class="feature-chip">
                                <i class="bi bi-grid-3x3-gap"></i>
                                Inventory
                            </span>
                            <span class="feature-chip">
                                <i class="bi bi-receipt"></i>
                                Billing & Receipts
                            </span>
                            <span class="feature-chip">
                                <i class="bi bi-graph-up"></i>
                                Real‑time Reports
                            </span>
                        </div>

                        <p class="text-muted mb-0">
                            <strong>Support:</strong> <a href="mailto:support@aigenerix.com" class="text-decoration-none">support@aigenerix.com</a>
                        </p>
                    </div>
                </div>
                
                <!-- Preview section -->
                <div class="col-lg-6">
                    <div id="posShowcase" class="carousel slide" data-bs-ride="carousel" data-bs-interval="4000">
                        <div class="carousel-indicators">
                            <button type="button" data-bs-target="#posShowcase" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                            <button type="button" data-bs-target="#posShowcase" data-bs-slide-to="1" aria-label="Slide 2"></button>
                            <button type="button" data-bs-target="#posShowcase" data-bs-slide-to="2" aria-label="Slide 3"></button>
                        </div>
                        <div class="carousel-inner" role="listbox" style="border-radius:12px; box-shadow: 0 8px 24px rgba(0,0,0,0.15);">
                            <div class="carousel-item active">
                                <img src="{{ asset('images/pos/pos1.png') }}" alt="POS Screenshot 1" class="d-block w-100">
                            </div>
                            <div class="carousel-item">
                                <img src="{{ asset('images/pos/pos2.png') }}" alt="POS Screenshot 2" class="d-block w-100">
                            </div>
                            <div class="carousel-item">
                                <img src="{{ asset('images/pos/pos3.png') }}" alt="POS Screenshot 3" class="d-block w-100">
                            </div>
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#posShowcase" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#posShowcase" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="py-2 text-center text-muted">
        <div class="container">
            <p class="mb-2">© {{ date('Y') }} {{ config('app.name', 'Generix POS') }}</p>
            <div class="d-flex justify-content-center gap-3">
                <a href="#" class="text-decoration-none text-muted">Privacy</a>
                <a href="#" class="text-decoration-none text-muted">Terms</a>
                <a href="#" class="text-decoration-none text-muted">Status</a>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>