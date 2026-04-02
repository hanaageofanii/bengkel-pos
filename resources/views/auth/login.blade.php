<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login | 5a AUTO SERVICE</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@500;600;700&family=Inter:wght@400;500&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --bg:       #0f1117;
            --surface:  #181c27;
            --border:   #262c3d;
            --accent:   #4f8ef7;
            --accent2:  #1e90ff;
            --text:     #e4e8f0;
            --muted:    #6b7694;
            --danger:   #f26c6c;
        }

        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: var(--bg);
            /* background-image:
                repeating-linear-gradient(0deg,   transparent, transparent 39px, var(--border) 39px, var(--border) 40px),
                repeating-linear-gradient(90deg,  transparent, transparent 39px, var(--border) 39px, var(--border) 40px); */
            font-family: 'Inter', sans-serif;
        }

        .card {
            width: 100%;
            max-width: 380px;
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 6px;
            overflow: hidden;
            box-shadow: 0 0 0 1px rgba(79,142,247,.12), 0 24px 48px rgba(0,0,0,.5);
            animation: slideUp .4s cubic-bezier(.22,1,.36,1) both;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(18px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .card-bar {
            height: 3px;
            background: linear-gradient(90deg, var(--accent2), var(--accent), #8ab6ff);
        }

        .card-header {
            padding: 28px 28px 0;
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .logo-box {
            width: 42px;
            height: 42px;
            border-radius: 8px;
            /* background: linear-gradient(135deg, var(--accent2), var(--accent)); */
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .logo-box svg { width: 22px; height: 22px; fill: #fff; }

        .brand-name {
            font-family: 'Rajdhani', sans-serif;
            font-size: 20px;
            font-weight: 700;
            color: var(--text);
            letter-spacing: .5px;
            line-height: 1;
        }

        .brand-sub {
            font-size: 11px;
            color: var(--muted);
            margin-top: 3px;
            letter-spacing: .8px;
            text-transform: uppercase;
        }

        .divider {
            margin: 20px 28px 0;
            border: none;
            border-top: 1px solid var(--border);
        }

        .card-body { padding: 20px 28px 28px; }

        .alert {
            display: flex;
            align-items: flex-start;
            gap: 8px;
            background: rgba(242,108,108,.08);
            border: 1px solid rgba(242,108,108,.25);
            border-radius: 5px;
            padding: 10px 12px;
            margin-bottom: 16px;
            font-size: 12.5px;
            color: var(--danger);
            line-height: 1.4;
        }

        .alert svg { width: 14px; height: 14px; flex-shrink: 0; margin-top: 1px; fill: var(--danger); }

        .form-group { margin-bottom: 14px; }

        label {
            display: block;
            font-size: 11px;
            font-weight: 500;
            letter-spacing: .6px;
            text-transform: uppercase;
            color: var(--muted);
            margin-bottom: 6px;
        }

        input[type="email"],
        input[type="password"] {
            width: 100%;
            background: var(--bg);
            border: 1px solid var(--border);
            border-radius: 5px;
            padding: 10px 13px;
            font-size: 13.5px;
            color: var(--text);
            outline: none;
            transition: border-color .18s, box-shadow .18s;
            font-family: 'Inter', sans-serif;
        }

        input:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(79,142,247,.15);
        }

        input::placeholder { color: #3a4059; }

        .btn-submit {
            width: 100%;
            margin-top: 6px;
            background: linear-gradient(135deg, #1a6fe8, var(--accent));
            color: #fff;
            font-family: 'Rajdhani', sans-serif;
            font-size: 15px;
            font-weight: 600;
            letter-spacing: 1px;
            text-transform: uppercase;
            border: none;
            border-radius: 5px;
            padding: 11px;
            cursor: pointer;
            transition: opacity .18s, transform .12s;
            position: relative;
            overflow: hidden;
        }

        .btn-submit::after {
            content: '';
            position: absolute;
            inset: 0;
            /* background: linear-gradient(90deg, transparent 0%, rgba(255,255,255,.08) 50%, transparent 100%); */
            transform: translateX(-100%);
            transition: transform .5s;
        }

        .btn-submit:hover::after { transform: translateX(100%); }
        .btn-submit:hover  { opacity: .92; }
        .btn-submit:active { transform: scale(.98); }

        .card-footer {
            padding: 10px 28px 14px;
            display: flex;
            align-items: center;
            gap: 6px;
            border-top: 1px solid var(--border);
        }

        .dot { width: 6px; height: 6px; border-radius: 50%; background: #3ef08a; flex-shrink: 0; box-shadow: 0 0 6px #3ef08a; }
        .status-text { font-size: 11px; color: var(--muted); }
    </style>
</head>
<body>

<div class="card">
    <div class="card-bar"></div>

    <div class="card-header">
        <div class="logo-box">
            <!-- wrench icon -->
            <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path d="M22.7 19l-9.1-9.1A6 6 0 0 0 4.8 3.3L8.4 6.9l-1.5 1.5L3.3 4.8a6 6 0 0 0 6.7 8.8l9.1 9.1a1 1 0 0 0 1.4 0l2.2-2.2a1 1 0 0 0 0-1.5z"/>
            </svg>
        </div>
        <div>
            <div class="brand-name">5A AUTO SERVICE</div>
            <div class="brand-sub">Admin Portal</div>
        </div>
    </div>

    <hr class="divider">

    <div class="card-body">

        @if ($errors->any())
            <div class="alert">
                <svg viewBox="0 0 24 24"><path d="M12 2a10 10 0 1 0 0 20A10 10 0 0 0 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/></svg>
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="form-group">
                <label for="email">Email</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email') }}"
                    placeholder="admin@example.com"
                    required
                    autocomplete="email"
                >
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="••••••••"
                    required
                    autocomplete="current-password"
                >
            </div>

            <button type="submit" class="btn-submit">Masuk</button>
        </form>

    </div>

    <div class="card-footer">
        <div class="dot"></div>
        <span class="status-text">System online — Secure connection</span>
    </div>
</div>

</body>
</html>
