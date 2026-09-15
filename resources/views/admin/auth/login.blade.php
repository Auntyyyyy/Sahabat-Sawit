<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - PT. Sahabat Sawit Rokan Sejahtera</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

    <style>
        :root {
            --dark-green: #123524;
            --primary-green: #1F5F3B;
            --secondary-green: #2E7D32;
            --gold: #C9A227;
            --cream: #FAF6EE;
        }

        * { box-sizing: border-box; }

        html, body {
            margin: 0;
            height: 100%;
            font-family: 'Inter', sans-serif;
            color: #1F2933;
        }

        /* ================= LATAR FOTO DENGAN ZOOM PERLAHAN ================= */
        .login-bg {
            position: fixed;
            inset: 0;
            overflow: hidden;
            z-index: 0;
        }

        .login-bg img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
            animation: slow-zoom 24s ease-in-out infinite alternate;
        }

        @keyframes slow-zoom {
            from { transform: scale(1); }
            to   { transform: scale(1.12); }
        }

        .login-bg::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(
                120deg,
                rgba(18,53,36,.88) 0%,
                rgba(18,53,36,.55) 45%,
                rgba(18,53,36,.25) 100%
            );
        }

        @media (prefers-reduced-motion: reduce) {
            .login-bg img { animation: none; }
        }

        /* ================= TEKS SAMBUTAN DI ATAS FOTO ================= */
        .welcome-copy {
            position: relative;
            z-index: 1;
            color: #fff;
            max-width: 420px;
            padding: 3rem;
        }

        .welcome-copy .eyebrow-mark {
            width: 44px;
            height: 4px;
            background: var(--gold);
            border-radius: 2px;
            margin-bottom: 1.5rem;
        }

        .welcome-copy h1 {
            font-family: 'Poppins', sans-serif;
            font-weight: 700;
            font-size: 2.1rem;
            line-height: 1.25;
            margin: 0 0 1rem;
        }

        .welcome-copy p {
            font-size: .98rem;
            line-height: 1.6;
            color: rgba(255,255,255,.8);
            margin: 0;
        }

        /* ================= LAYOUT UTAMA ================= */
        .login-shell {
            position: relative;
            z-index: 1;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            padding: 2rem 6vw;
        }

        /* ================= KARTU LOGIN ================= */
        .login-card {
            background: var(--cream);
            border-radius: 20px;
            padding: 2.75rem 2.5rem;
            width: 100%;
            max-width: 380px;
            box-shadow: 0 24px 60px rgba(0,0,0,.35);
        }

        .login-card .brand-row {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: .6rem;
            margin-bottom: 1.75rem;
        }

        .login-card .brand-row img.brand-logo {
            display: block;
            height: 48px;
            width: auto;
            margin: 0;
        }

        .login-card .brand-row .brand-text {
            display: flex;
            flex-direction: column;
            line-height: 1.2;
            text-align: left;
        }

        .login-card .brand-row .brand-text strong {
            font-family: 'Poppins', sans-serif;
            font-weight: 700;
            font-size: .95rem;
            color: var(--dark-green);
        }

        .login-card .brand-row .brand-text span {
            font-family: 'Poppins', sans-serif;
            font-size: .72rem;
            color: #6B7280;
            letter-spacing: .02em;
        }

        .login-card h2 {
            font-family: 'Poppins', sans-serif;
            font-weight: 700;
            font-size: 1.15rem;
            color: var(--dark-green);
            text-align: center;
            margin: 0 0 .3rem;
        }

        .login-card .subtitle {
            text-align: center;
            font-size: .85rem;
            color: #6B7280;
            margin: 0 0 1.75rem;
        }

        .field {
            margin-bottom: 1.1rem;
        }

        .field label {
            display: block;
            font-size: .82rem;
            font-weight: 600;
            color: var(--dark-green);
            margin-bottom: .4rem;
        }

        .field input[type="email"],
        .field input[type="password"] {
            width: 100%;
            padding: .68rem .85rem;
            border: 1px solid #D9D2C3;
            border-radius: 10px;
            font-size: .92rem;
            font-family: 'Inter', sans-serif;
            background: #fff;
            transition: border-color .2s;
        }

        .field input:focus {
            outline: none;
            border-color: var(--secondary-green);
            box-shadow: 0 0 0 3px rgba(46,125,50,.15);
        }

        .remember-row {
            display: flex;
            align-items: center;
            gap: .5rem;
            margin-bottom: 1.4rem;
            font-size: .85rem;
            color: #6B7280;
        }

        .remember-row input { accent-color: var(--secondary-green); }

        .btn-submit {
            width: 100%;
            padding: .78rem;
            border: none;
            border-radius: 10px;
            background: var(--gold);
            color: #fff;
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
            font-size: .95rem;
            cursor: pointer;
            transition: background .2s, transform .15s;
        }

        .btn-submit:hover { background: #b4901f; }
        .btn-submit:active { transform: scale(.98); }

        .alert-box {
            background: #FCEBEC;
            border: 1px solid #F3C1C6;
            color: #92273A;
            border-radius: 10px;
            padding: .85rem 1rem;
            font-size: .85rem;
            margin-bottom: 1.25rem;
        }

        .alert-box ul { margin: 0; padding-left: 1.1rem; }

        /* ================= RESPONSIVE ================= */
        @media (max-width: 900px) {
            .welcome-copy { display: none; }
            .login-shell { justify-content: center; padding: 1.5rem; }
        }
    </style>
</head>
<body>

    <div class="login-bg">
        <img src="{{ asset('images/tangki.png') }}" alt="">
    </div>

    <div class="login-shell">

        <div class="welcome-copy">
            <div class="eyebrow-mark"></div>
            <h1>Panel pengelolaan PT. Sahabat Sawit Rokan Sejahtera</h1>
            <p>Kelola konten, produk, dan data operasional perkebunan dari satu tempat. Masuk dengan akun admin Anda untuk melanjutkan.</p>
        </div>

        <div class="login-card">
            <div class="brand-row">
                <img src="{{ asset('images/logoaja.png') }}" alt="Logo PT. Sahabat Sawit Rokan Sejahtera" class="brand-logo">
                <div class="brand-text">
                    <strong>Sahabat Sawit</strong>
                    <span>Rokan Sejahtera</span>
                </div>
            </div>
            <h2>Masuk ke Panel Admin</h2>
            <p class="subtitle">Khusus untuk tim internal yang berwenang</p>

            @if ($errors->any())
                <div class="alert-box">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.login') }}">
                @csrf

                <div class="field">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus>
                </div>

                <div class="field">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>

                <div class="remember-row">
                    <input type="checkbox" name="remember" id="remember">
                    <label for="remember">Ingat saya</label>
                </div>

                <button type="submit" class="btn-submit">Masuk</button>
            </form>
        </div>

    </div>

</body>
</html>