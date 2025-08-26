<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name') . ' • Entrar')</title>

    <style>
        :root {
            --bg: #0c111b;
            --bg-accent: #0f1726;
            --surface: #121a2a;
            --text: #e6edf3;
            --muted: #9fb0c3;
            --line: #1f2a44;
            --brand: #009de0;
            --radius: 16px;
            --shadow: 0 20px 45px rgba(0, 0, 0, .45);
        }

        html,
        body {
            height: 100%;
        }

        body {
            margin: 0;
            background: radial-gradient(1200px 700px at 20% -10%, #0e1625 0%, var(--bg) 60%),
                radial-gradient(900px 600px at 90% 10%, #0b1524 0%, var(--bg) 55%);
            color: var(--text);
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        .centerwrap {
            min-height: 100dvh;
            display: grid;
            place-items: center;
            padding: 32px 16px;
        }

        .stack {
            width: 100%;
            max-width: 520px;
            background: linear-gradient(180deg, var(--surface), #0f1624);
            border: 1px solid var(--line);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            padding: 36px 32px;
            text-align: center;
        }

        .logo {
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .logo img {
            height: 64px;
            width: auto;
            display: block;
            filter: drop-shadow(0 2px 14px rgba(0, 0, 0, .4));
        }

        h1 {
            margin: 18px 0 8px;
            font-size: 28px;
            line-height: 1.2;
            font-weight: 800;
            letter-spacing: .2px;
        }

        .tagline {
            margin: 0 0 24px;
            font-size: 15px;
            color: var(--muted);
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            text-decoration: none;
            cursor: pointer;
            border: 1px solid transparent;
            border-radius: 12px;
            padding: 13px 18px;
            font-weight: 700;
            font-size: 15px;
            transition: transform .04s ease, filter .15s ease, box-shadow .2s ease;
        }

        .btn:focus-visible {
            outline: 3px solid rgba(0, 157, 224, .35);
            outline-offset: 2px;
        }

        .btn-primary {
            background: var(--brand);
            color: #001824;
            box-shadow: 0 8px 24px rgba(0, 157, 224, .28);
        }

        .btn-primary:hover {
            filter: brightness(.96);
        }

        .btn-primary:active {
            transform: translateY(1px);
        }

        .meta {
            margin-top: 18px;
            font-size: 12px;
            color: var(--muted);
        }

        .flash {
            margin-bottom: 16px;
            border-radius: 10px;
            padding: 10px 12px;
            text-align: left;
        }

        .flash.success {
            background: #0c3c22;
            border: 1px solid #117a44;
            color: #b8ffdb;
        }

        .flash.error {
            background: #3c1212;
            border: 1px solid #7a1111;
            color: #ffd1d1;
        }

        .divider {
            height: 1px;
            background: var(--line);
            margin: 24px 0;
        }
    </style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <div class="centerwrap" role="main">
        <div style="width:100%; max-width:620px;">
            @if(session('success'))
            <div class="flash success">{{ session('success') }}</div> @endif
            @if(session('error'))
            <div class="flash error">{{ session('error') }}</div> @endif
            @yield('content')
        </div>
    </div>
</body>

</html>
