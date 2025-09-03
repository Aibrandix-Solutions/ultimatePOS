<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Ultimate POS') }}</title>
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

        .nav-actions{display:flex; align-items:center; gap:.75rem}
        .btn{
            --_bg:transparent; --_fg:var(--text); --_bd:rgba(22,17,96,.2);
            display:inline-flex; align-items:center; justify-content:center; gap:.5rem;
            padding:.7rem 1rem; border-radius:999px; border:1px solid var(--_bd);
            background:var(--_bg); color:var(--_fg); font-weight:600;
            transition:all .2s ease; box-shadow:none; text-decoration:none;
        }
        .btn:hover{transform:translateY(-1px)}
        .btn:active{transform:translateY(0)}
        .btn:focus-visible{outline:none; box-shadow:var(--focus-ring)}
        .btn-primary{
            --_bg:var(--navy-700); --_fg:#fff; --_bd:transparent;
            box-shadow:0 8px 18px rgba(22,17,96,.15);
        }
        .btn-primary:hover{filter:brightness(1.05)}
        .btn-ghost{background:rgba(22,17,96,.05)}
        .btn-ghost:hover{background:rgba(22,17,96,.1)}

        /* language menu (no JS) */
        details.lang{position:relative}
        details.lang>summary{
            list-style:none; cursor:pointer; border:1px solid rgba(22,17,96,.2);
            padding:.65rem 1rem; border-radius:999px;
        }
        details.lang>summary::-webkit-details-marker{display:none}
        details.lang[open] > summary{box-shadow:var(--focus-ring)}
        .lang-menu{
            position:absolute; right:0; margin-top:.5rem; min-width:180px;
            background:rgba(255,255,255,.9); border:1px solid var(--card-border);
            border-radius:14px; padding:.35rem; backdrop-filter:blur(10px) saturate(1.2);
            box-shadow:var(--shadow-lg);
        }
        .lang-menu a{
            display:block; padding:.6rem .75rem; border-radius:10px; color:var(--text);
        }
        .lang-menu a:hover{background:rgba(22,17,96,.05)}

        /* ---------- HERO ---------- */
        .hero{
            position:relative; padding:80px 0 96px;
        }
        /* floating orbs */
        .orb{
            position:absolute; border-radius:50%; filter:blur(40px); opacity:.25; pointer-events:none;
            transform:translateZ(0);
        }
        .orb.one{width:380px; height:380px; left:-120px; top:80px; background:radial-gradient(circle, var(--navy-500), transparent 60%)}
        .orb.two{width:420px; height:420px; right:-140px; top:120px; background:radial-gradient(circle, var(--navy-400), transparent 60%)}
        .orb.three{width:300px; height:300px; left:50%; bottom:-60px; transform:translateX(-50%); background:radial-gradient(circle, rgba(22,17,96,.1), transparent 60%)}

        .card{
            position:relative;
            background:var(--card-bg);
            border:1px solid var(--card-border);
            border-radius:22px;
            padding:48px clamp(24px, 4vw, 56px);
            box-shadow:var(--shadow-lg);
            backdrop-filter:blur(12px) saturate(1.15);
        }
        @supports not (backdrop-filter: blur(1px)){
            .card{background:rgba(255,255,255,.9)}
        }

        .eyebrow{
            display:inline-flex; align-items:center; gap:.5rem;
            padding:.4rem .7rem; border-radius:999px;
            background:rgba(22,17,96,.05); border:1px solid var(--card-border);
            color:var(--text-muted); font-weight:600; letter-spacing:.2px; margin-bottom:1rem;
        }
        .title{
            margin:0 0 .35rem;
            font-weight:800; line-height:1.08;
            font-size:clamp(2.2rem, 7vw, 4.8rem);
            text-shadow:0 16px 40px rgba(22,17,96,.1);
        }
        .subtitle{
            color:var(--text-muted);
            font-size:clamp(1.02rem, 2.2vw, 1.15rem);
            margin:0 0 1.4rem;
        }
        .cta{display:flex; flex-wrap:wrap; gap:.75rem; margin:1.25rem 0 1.75rem}
        .chips{display:flex; flex-wrap:wrap; gap:.5rem}
        .chip{
            display:inline-flex; align-items:center; gap:.5rem;
            padding:.5rem .7rem; border-radius:999px;
            background:rgba(22,17,96,.05); border:1px solid var(--card-border);
            color:var(--text-muted); font-weight:600;
        }
        .chip svg{opacity:.9}

        /* ---------- FOOTER ---------- */
        footer{color:var(--text-muted); font-size:.95rem; padding:36px 0 60px; text-align:center}
        .meta{opacity:.8}
        .links{display:flex; gap:1rem; justify-content:center; margin-top:.5rem}
        .links a{opacity:.9}
        .links a:hover{opacity:1; text-decoration:underline}

        /* ---------- MOTION ---------- */
        @keyframes floaty{from{transform:translateY(0)}50%{transform:translateY(-6px)}to{transform:translateY(0)}}}
        .orb.one{animation:floaty 10s ease-in-out infinite}
        .orb.two{animation:floaty 12s ease-in-out infinite .3s}
        .orb.three{animation:floaty 8s ease-in-out infinite .6s}
        @media (prefers-reduced-motion:reduce){
            .orb{animation:none}
        }

        /* ---------- RESPONSIVE ---------- */
        .hero-grid{
            display:grid; gap:24px;
            grid-template-columns: 1.05fr;
        }
        @media (min-width: 900px){
            .hero-grid{grid-template-columns: 1.1fr .9fr; align-items:center}
            .preview{min-height:380px}
        }
        .preview{
            position:relative; isolation:isolate;
        }
        .preview-frame{
            position:relative; margin:auto; max-width:520px;
            aspect-ratio:16/10; border-radius:16px;
            background:linear-gradient(160deg, #ffffff, #f8fafc);
            box-shadow:0 25px 60px rgba(22,17,96,.15);
            overflow:hidden; color:var(--text);
        }
        .preview-header{
            display:flex; align-items:center; justify-content:space-between;
            padding:.8rem 1rem; background:#fff; border-bottom:1px solid #efefef;
            font-weight:700;
        }
        .preview-body{padding:1.2rem}
        .preview-pill{
            display:inline-flex; align-items:center; gap:.4rem;
            border-radius:999px; padding:.35rem .65rem;
            background:#f1f5f9; font-weight:700; color:var(--navy-700);
        }
        .preview-grid{
            margin-top:.9rem; display:grid; grid-template-columns:repeat(3,1fr); gap:.6rem;
        }
        .preview-card{
            background:#fff; border:1px solid #e2e8f0; border-radius:10px; padding:.7rem;
            box-shadow:0 6px 14px rgba(22,17,96,.05);
        }
        .preview-total{
            margin-top:1rem; display:flex; justify-content:space-between; font-weight:800;
        }
    </style>
</head>
<body>
<header class="site-header">
    <div class="container navbar">
        <a class="brand" href="{{ url('/') }}" aria-label="Home">
            <span class="brand-logo" aria-hidden="true">
                <!-- Simple POS mark -->
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none">
                    <path d="M5 7h14M7 11h10M9 15h6" stroke="#fff" stroke-width="1.7" stroke-linecap="round"/>
                    <rect x="3" y="4" width="18" height="16" rx="3" stroke="#fff" stroke-width="1.5" fill="none" opacity=".9"/>
                </svg>
            </span>
            <span class="brand-name">{{ config('app.name', 'Ultimate POS') }}</span>
        </a>

        <nav class="nav-actions" aria-label="Primary">
            @auth
                <a class="btn btn-primary" href="{{ url('/home') }}">Go to Dashboard</a>
            @else
                @if (Route::has('business.getRegister'))
                    <a class="btn btn-primary" href="{{ route('business.getRegister') }}">Register</a>
                @endif
                @if (Route::has('login'))
                    <a class="btn btn-ghost" href="{{ route('login') }}">Sign In</a>
                @endif
            @endauth

            <details class="lang">
                <summary aria-label="Change language">English ▾</summary>
                <div class="lang-menu" role="menu" aria-label="Language">
                    <!-- Wire these to your localization routes if you have them -->
                    <a href="#" role="menuitem">English</a>
                    <a href="#" role="menuitem">Español</a>
                    <a href="#" role="menuitem">Français</a>
                </div>
            </details>
        </nav>
    </div>
</header>

<main class="hero">
    <div class="orb one" aria-hidden="true"></div>
    <div class="orb two" aria-hidden="true"></div>
    <div class="orb three" aria-hidden="true"></div>

    <div class="container hero-grid">
        <!-- LEFT: Hero card -->
        <section class="card" aria-label="Welcome">
            <span class="eyebrow">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="M4 7h16M4 12h16M4 17h16" stroke="#fff" stroke-width="1.4" stroke-linecap="round"/></svg>
                Point of Sale • Ready
            </span>
            <h1 class="title">{{ config('app.name', 'Ultimate POS') }}</h1>
            <p class="subtitle">
                Fast, reliable and beautifully simple POS for stores, cafés and boutiques.
                Manage inventory, print receipts and see insights in real time.
            </p>

            <div class="cta">
                @auth
                    <a class="btn btn-primary" href="{{ url('/home') }}">Open Dashboard</a>
                @else
                    @if (Route::has('business.getRegister'))
                        <a class="btn btn-primary" href="{{ route('business.getRegister') }}">Create an account</a>
                    @endif
                    @if (Route::has('login'))
                        <a class="btn btn-ghost" href="{{ route('login') }}">I already have an account</a>
                    @endif
                @endauth
            </div>

            <div class="chips" aria-label="Highlights">
                <span class="chip">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><rect x="3" y="3" width="7" height="7" rx="1.2" stroke="#fff" stroke-width="1.4"/><rect x="14" y="3" width="7" height="7" rx="1.2" stroke="#fff" stroke-width="1.4"/><rect x="3" y="14" width="7" height="7" rx="1.2" stroke="#fff" stroke-width="1.4"/><rect x="14" y="14" width="7" height="7" rx="1.2" stroke="#fff" stroke-width="1.4"/></svg>
                    Inventory
                </span>
                <span class="chip">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="M6 7h12M6 11h12M6 15h7" stroke="#fff" stroke-width="1.4" stroke-linecap="round"/><rect x="3" y="4" width="18" height="16" rx="2.4" stroke="#fff" stroke-width="1.4"/></svg>
                    Billing & Receipts
                </span>
                <span class="chip">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="M4 14l4-4 4 5 4-7 4 6" stroke="#fff" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    Real‑time Reports
                </span>
</div>

            <p class="subtitle" style="margin-top:1.1rem">
                <strong>Support:</strong> <a href="mailto:admin@gmail.com" style="text-decoration:underline">admin@gmail.com</a>
            </p>
        </section>

        <!-- RIGHT: Small product-style preview to add personality, optional -->
        <section class="preview" aria-hidden="true">
            <div class="preview-frame">
                <div class="preview-header">
                    <span>Register • Counter #1</span>
                    <span class="preview-pill">LIVE</span>
                </div>
                <div class="preview-body">
                    <div class="preview-grid">
                        <div class="preview-card">Latte <br><small>Qty: 2</small></div>
                        <div class="preview-card">Blueberry Muffin <br><small>Qty: 1</small></div>
                        <div class="preview-card">Croissant <br><small>Qty: 3</small></div>
                        <div class="preview-card">Espresso <br><small>Qty: 1</small></div>
                        <div class="preview-card">Bagel <br><small>Qty: 2</small></div>
                        <div class="preview-card">Iced Tea <br><small>Qty: 1</small></div>
                    </div>
                    <div class="preview-total">
                        <span>Total</span>
                        <span>$28.70</span>
                    </div>
                </div>
            </div>
        </section>
    </div>
</main>

<footer>
    <div class="container">
        <div class="meta">
            © {{ date('Y') }} {{ config('app.name', 'Ultimate POS') }} · Crafted with Laravel
        </div>
        <div class="links">
            <a href="#">Privacy</a>
            <a href="#">Terms</a>
            <a href="#">Status</a>
        </div>
    </div>
</footer>
</body>
</html>
            