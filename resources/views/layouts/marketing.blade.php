<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Vertical E-Invoicing') — Tax Compliance Platform</title>
    <meta name="description" content="@yield('meta_description', 'Vertical e-invoicing and tax compliance platform for multi-country invoices, VAT/GST rules, digital signatures, and audit-ready fiscal reporting.')">
    <meta name="keywords" content="e-invoicing, tax compliance, VAT GST, digital signature, fiscal reporting, multi-country invoices, PEPPOL, Laravel Filament, modular tax platform">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,700&family=Source+Sans+3:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --accent: #B45309;
            --accent-dark: #92400E;
            --ink: #1c1917;
            --muted: #57534e;
            --sand: #fff7ed;
            --line: #fed7aa;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: "Source Sans 3", system-ui, sans-serif;
            color: var(--ink);
            background:
                radial-gradient(1100px 560px at 8% -12%, #fdba74 0%, transparent 55%),
                radial-gradient(900px 480px at 100% 0%, #f59e0b 0%, transparent 42%),
                linear-gradient(180deg, #fffbeb 0%, #ffffff 42%, #fff7ed 100%);
            min-height: 100vh;
        }
        a { color: var(--accent); text-decoration: none; }
        .wrap { width: min(1120px, 92%); margin: 0 auto; }
        header {
            display: flex; align-items: center; justify-content: space-between;
            padding: 1.25rem 0; gap: 1rem; flex-wrap: wrap;
        }
        .brand {
            font-family: "Fraunces", Georgia, serif;
            font-size: 1.55rem; color: var(--accent-dark); letter-spacing: -0.02em;
        }
        nav { display: flex; gap: 1rem; flex-wrap: wrap; align-items: center; }
        nav a { color: var(--muted); font-weight: 600; font-size: .95rem; }
        nav a:hover { color: var(--accent); }
        .btn {
            display: inline-flex; align-items: center; justify-content: center;
            background: var(--accent); color: #fff !important; border-radius: 999px;
            padding: .7rem 1.2rem; font-weight: 700; border: 0; cursor: pointer;
            box-shadow: 0 10px 30px rgba(180, 83, 9, .28);
        }
        .btn.secondary { background: transparent; color: var(--accent) !important; border: 1.5px solid var(--accent); box-shadow: none; }
        .hero { padding: 3.5rem 0 2.5rem; display: grid; gap: 1.25rem; max-width: 780px; }
        .hero h1 {
            font-family: "Fraunces", Georgia, serif;
            font-size: clamp(2.4rem, 5vw, 3.7rem); line-height: 1.08; margin: 0;
            color: var(--ink);
        }
        .hero p { font-size: 1.15rem; color: var(--muted); margin: 0; line-height: 1.6; }
        .cta-row { display: flex; gap: .8rem; flex-wrap: wrap; margin-top: .5rem; }
        .grid { display: grid; gap: 1rem; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); }
        .card {
            background: rgba(255,255,255,.86); border: 1px solid var(--line);
            border-radius: 18px; padding: 1.2rem 1.25rem;
            backdrop-filter: blur(6px);
        }
        .card h3 { margin: 0 0 .4rem; font-size: 1.1rem; }
        .card p { margin: 0; color: var(--muted); line-height: 1.5; font-size: .95rem; }
        .section { padding: 2.5rem 0; }
        .section h2 {
            font-family: "Fraunces", Georgia, serif;
            font-size: clamp(1.8rem, 3vw, 2.4rem); margin: 0 0 .5rem;
        }
        .eyebrow { color: var(--accent); font-weight: 700; text-transform: uppercase; letter-spacing: .08em; font-size: .75rem; }
        .pill {
            display: inline-block; padding: .2rem .55rem; border-radius: 999px;
            background: #ffedd5; color: var(--accent-dark); font-size: .75rem; font-weight: 700;
        }
        .pill.off { background: #fee2e2; color: #991b1b; }
        footer { border-top: 1px solid var(--line); margin-top: 3rem; padding: 2rem 0 3rem; color: var(--muted); font-size: .92rem; }
        .kpi { font-size: 1.8rem; font-weight: 700; color: var(--accent-dark); }
        table { width: 100%; border-collapse: collapse; background: #fff; border-radius: 16px; overflow: hidden; }
        th, td { text-align: left; padding: .85rem 1rem; border-bottom: 1px solid var(--line); font-size: .95rem; }
        th { background: #ffedd5; color: var(--accent-dark); }
    </style>
</head>
<body>
<div class="wrap">
    <header>
        <a class="brand" href="{{ route('home') }}">Vertical E-Invoicing</a>
        <nav>
            <a href="{{ route('features') }}">Features</a>
            <a href="{{ route('modules') }}">Modules</a>
            <a href="{{ route('invoices') }}">Invoices</a>
            <a href="{{ route('tax-rules') }}">Tax Rules</a>
            <a href="{{ route('pricing') }}">Pricing / ROI</a>
            <a href="{{ route('portal') }}">Portal</a>
            <a href="{{ route('demo') }}">Demo</a>
            <a class="btn" href="/admin">Admin Login</a>
        </nav>
    </header>

    @yield('content')

    <footer>
        <strong style="color:var(--ink)">Vertical E-Invoicing</strong> — tax-compliant multi-country invoicing by
        <a href="https://imrandev.bd">Imran Ahmed</a> · <a href="https://imrandev.bd/contact">Contact</a>
    </footer>
</div>
</body>
</html>
