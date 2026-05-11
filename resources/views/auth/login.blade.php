<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Rekonsiliasi PDRB BPS Kalimantan Tengah</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Segoe UI', system-ui, sans-serif;
            background: #f1f5f9;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08);
            padding: 40px;
            width: 100%;
            max-width: 400px;
        }

        .logo-box {
            width: 44px;
            height: 44px;
            background: #1e293b;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
        }

        .logo-box svg {
            width: 22px;
            height: 22px;
            color: white;
        }

        h1 {
            font-size: 20px;
            font-weight: 700;
            color: #1e293b;
            margin: 0 0 4px;
        }

        .subtitle {
            font-size: 13px;
            color: #94a3b8;
            margin: 0 0 28px;
        }

        label {
            display: block;
            font-size: 12.5px;
            font-weight: 600;
            color: #475569;
            margin-bottom: 6px;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px 14px;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            font-size: 14px;
            color: #1e293b;
            background: #f8fafc;
            outline: none;
            transition: border-color 150ms, box-shadow 150ms;
            box-sizing: border-box;
        }

        input[type="text"]:focus,
        input[type="password"]:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59,130,246,0.1);
            background: white;
        }

        .field {
            margin-bottom: 16px;
        }

        .btn-login {
            width: 100%;
            padding: 11px;
            background: #1e293b;
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            margin-top: 8px;
            transition: background 150ms;
        }

        .btn-login:hover {
            background: #0f172a;
        }

        .btn-login:active {
            transform: scale(0.99);
        }

        .error-box {
            background: #fef2f2;
            border: 1px solid #fecaca;
            border-radius: 10px;
            padding: 10px 14px;
            font-size: 13px;
            color: #b91c1c;
            margin-bottom: 20px;
        }

        .footer-text {
            text-align: center;
            font-size: 12px;
            color: #cbd5e1;
            margin-top: 28px;
        }
    </style>
</head>
<body>

<div class="login-card">

    <div class="logo-box">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0
                002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0
                002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2
                2 0 01-2 2h-2a2 2 0 01-2-2z"/>
        </svg>
    </div>

    <h1>Rekonsiliasi PDRB</h1>
    <p class="subtitle">BPS Provinsi Kalimantan Tengah</p>

    @if ($errors->any())
        <div class="error-box">
            Username atau password salah.
        </div>
    @endif

    <form method="POST" action="{{ route('login.post') }}">
        @csrf

        <div class="field">
            <label for="username">Username</label>
            <input
                type="text"
                id="username"
                name="username"
                value="{{ old('username') }}"
                placeholder="Masukkan username"
                autocomplete="username"
                autofocus>
        </div>

        <div class="field">
            <label for="password">Password</label>
            <input
                type="password"
                id="password"
                name="password"
                placeholder="Masukkan password"
                autocomplete="current-password">
        </div>

        <button type="submit" class="btn-login">
            Masuk
        </button>

    </form>

    <p class="footer-text">
        Sistem internal BPS Kalimantan Tengah
    </p>

</div>

</body>
</html>