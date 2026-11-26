<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#064e3b">
    <title>Login | RiceERP</title>

    {{-- Bootstrap 5 melalui CDN. Tidak membutuhkan npm untuk preview ini. --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            --rice-green: #075640;
            --rice-green-dark: #043d30;
            --rice-green-soft: #eaf4ef;
            --rice-text: #18352c;
            --rice-muted: #75847d;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            min-height: 100vh;
            color: var(--rice-text);
            background: #f5f7f5;
            font-family: Inter, "Segoe UI", Arial, sans-serif;
        }

        .login-layout {
            min-height: 100vh;
            display: grid;
            grid-template-columns: minmax(390px, 0.82fr) minmax(0, 1.18fr);
            background: #fff;
        }

        .login-panel {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 54px clamp(28px, 6vw, 92px);
            background: #fff;
        }

        .login-inner {
            width: 100%;
            max-width: 410px;
        }

        .brand {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 52px;
            color: var(--rice-green);
            text-decoration: none;
        }

        .brand-mark {
            width: 44px;
            height: 44px;
            display: grid;
            place-items: center;
            color: white;
            background: var(--rice-green);
            border-radius: 12px;
            box-shadow: 0 8px 18px rgba(7, 86, 64, .15);
        }

        .brand-name {
            display: block;
            font-size: 25px;
            line-height: 1.05;
            font-weight: 800;
            letter-spacing: -.8px;
        }

        .brand-caption {
            display: block;
            margin-top: 5px;
            color: #85928c;
            font-size: 10px;
            letter-spacing: .35px;
        }

        .form-heading {
            margin-bottom: 8px;
            font-size: 27px;
            font-weight: 750;
            letter-spacing: -.7px;
        }

        .form-description {
            margin-bottom: 30px;
            color: var(--rice-muted);
            font-size: 14px;
            line-height: 1.65;
        }

        .form-label {
            margin-bottom: 8px;
            color: #344c42;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: .35px;
        }

        .input-wrap { position: relative; }

        .form-control {
            min-height: 49px;
            padding: 12px 15px;
            border: 1px solid #dce5df;
            border-radius: 9px;
            background: #f8faf9;
            color: var(--rice-text);
            font-size: 14px;
            box-shadow: none !important;
        }

        .form-control:focus {
            border-color: #77a995;
            background: #fff;
        }

        .password-input { padding-right: 52px; }

        .password-toggle {
            position: absolute;
            top: 50%;
            right: 13px;
            transform: translateY(-50%);
            border: 0;
            padding: 5px;
            color: #7b8982;
            background: transparent;
            font-size: 12px;
        }

        .form-check-label, .forgot-link {
            color: #65766d;
            font-size: 12px;
        }

        .form-check-input:checked {
            border-color: var(--rice-green);
            background-color: var(--rice-green);
        }

        .forgot-link {
            color: var(--rice-green);
            font-weight: 650;
            text-decoration: none;
        }

        .forgot-link:hover { text-decoration: underline; }

        .btn-login {
            min-height: 50px;
            border: 0;
            border-radius: 9px;
            color: #fff;
            background: var(--rice-green);
            font-size: 14px;
            font-weight: 700;
            letter-spacing: .2px;
            box-shadow: 0 9px 18px rgba(7, 86, 64, .15);
            transition: background .2s ease, transform .2s ease;
        }

        .btn-login:hover, .btn-login:focus {
            color: #fff;
            background: var(--rice-green-dark);
            transform: translateY(-1px);
        }

        .login-footer {
            margin-top: 48px;
            color: #9aa59f;
            font-size: 10px;
            line-height: 1.6;
        }

        .hero-panel {
            position: relative;
            display: flex;
            align-items: flex-end;
            min-height: 100vh;
            padding: clamp(36px, 6vw, 88px);
            overflow: hidden;
            isolation: isolate;
            color: #fff;
            background:
                radial-gradient(ellipse at 72% 14%, rgba(161, 194, 151, .30), transparent 36%),
                radial-gradient(ellipse at 14% 76%, rgba(0, 20, 14, .35), transparent 48%),
                linear-gradient(135deg, #0a654b 0%, #064b39 54%, #032f27 100%);
        }

        /* Pola dekoratif bernuansa gudang dan karung beras, dibuat dengan CSS. */
        .hero-panel::before {
            content: "";
            position: absolute;
            z-index: -1;
            inset: 0;
            opacity: .24;
            background:
                repeating-linear-gradient(90deg, transparent 0 78px, rgba(255,255,255,.12) 79px 81px, transparent 82px 158px),
                linear-gradient(180deg, transparent 0 48%, rgba(0,0,0,.2) 49% 50%, transparent 51%);
            mask-image: linear-gradient(to bottom, #000, transparent 88%);
        }

        .warehouse-shapes {
            position: absolute;
            z-index: -1;
            inset: 0;
            overflow: hidden;
            opacity: .22;
            pointer-events: none;
        }

        .warehouse-shapes span {
            position: absolute;
            bottom: 13%;
            width: 110px;
            height: 145px;
            border: 1px solid rgba(255,255,255,.45);
            border-radius: 42% 42% 18% 18%;
            background: linear-gradient(135deg, rgba(239,236,204,.42), rgba(209,194,143,.08));
            box-shadow: inset 0 0 22px rgba(255,255,255,.12), 0 12px 22px rgba(0,0,0,.12);
            transform: rotate(-5deg);
        }
        .warehouse-shapes span:nth-child(1) { left: 8%; bottom: 19%; }
        .warehouse-shapes span:nth-child(2) { left: 24%; bottom: 14%; transform: rotate(4deg) scale(.86); }
        .warehouse-shapes span:nth-child(3) { left: 42%; bottom: 20%; transform: rotate(-2deg) scale(1.08); }
        .warehouse-shapes span:nth-child(4) { right: 7%; bottom: 16%; transform: rotate(5deg) scale(.92); }

        .hero-content {
            width: 100%;
            max-width: 650px;
            margin: 0 auto;
            padding-bottom: 5px;
        }

        .hero-tag {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 23px;
            padding: 8px 13px;
            border: 1px solid rgba(255,255,255,.22);
            border-radius: 99px;
            color: rgba(255,255,255,.9);
            background: rgba(255,255,255,.10);
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 1.1px;
            text-transform: uppercase;
            backdrop-filter: blur(8px);
        }

        .hero-tag-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: #b7dfb1;
            box-shadow: 0 0 0 4px rgba(183,223,177,.15);
        }

        .hero-title {
            max-width: 620px;
            margin: 0 0 18px;
            font-size: clamp(34px, 4.1vw, 58px);
            line-height: 1.08;
            font-weight: 780;
            letter-spacing: -1.9px;
            text-wrap: balance;
        }

        .hero-description {
            max-width: 510px;
            margin: 0;
            color: rgba(255,255,255,.78);
            font-size: 14px;
            line-height: 1.85;
        }

        .hero-bottom-line {
            width: 52px;
            height: 3px;
            margin-top: 30px;
            border-radius: 5px;
            background: #b9d7a9;
        }

        @media (max-width: 900px) {
            .login-layout { grid-template-columns: minmax(350px, .95fr) minmax(0, 1.05fr); }
            .login-panel { padding: 42px 30px; }
            .brand { margin-bottom: 40px; }
            .hero-panel { padding: 38px; }
        }

        @media (max-width: 700px) {
            .login-layout { display: flex; flex-direction: column; }
            .login-panel { min-height: auto; padding: 44px 25px 38px; }
            .login-inner { max-width: 440px; }
            .brand { margin-bottom: 38px; }
            .login-footer { margin-top: 35px; }
            .hero-panel { min-height: 330px; padding: 42px 26px; }
            .hero-title { font-size: 36px; }
            .hero-description { font-size: 13px; }
            .warehouse-shapes span { bottom: 8%; transform: scale(.72); }
        }
    </style>
</head>
<body>
    <main class="login-layout">
        <section class="login-panel" aria-label="Form login RiceERP">
            <div class="login-inner">
                <a href="{{ url('/login') }}" class="brand" aria-label="RiceERP beranda">
                    <span class="brand-mark" aria-hidden="true">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M18.5 4.5C12.1 4.7 7.1 7.1 6.2 12.1c-.5 2.8 1.2 5.1 3.7 5.1 5.6 0 8.5-6.2 8.6-12.7Z" fill="currentColor" opacity=".95"/>
                            <path d="M5 19.5c2.4-4.2 5.5-6.7 9.8-9.3" stroke="#DCEFE4" stroke-width="1.5" stroke-linecap="round"/>
                        </svg>
                    </span>
                    <span>
                        <span class="brand-name">RiceERP</span>
                        <span class="brand-caption">Premium Quality Management System</span>
                    </span>
                </a>

                <h1 class="form-heading">Masuk ke akun Anda</h1>
                <p class="form-description">Silakan masuk menggunakan username dan password yang telah terdaftar untuk mengakses sistem kasir beras.</p>

                @if ($errors->any())
                    <div class="alert alert-danger py-2 small" role="alert">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ url('/login') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="username" class="form-label">USERNAME</label>
                        <input
                            type="text"
                            class="form-control @error('username') is-invalid @enderror"
                            id="username"
                            name="username"
                            placeholder="Masukkan username"
                            value="{{ old('username') }}"
                            autocomplete="username"
                            required
                            autofocus
                        >
                        @error('username')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">PASSWORD</label>
                        <div class="input-wrap">
                            <input
                                type="password"
                                class="form-control password-input @error('password') is-invalid @enderror"
                                id="password"
                                name="password"
                                placeholder="Masukkan password"
                                autocomplete="current-password"
                                required
                            >
                            <button class="password-toggle" type="button" id="togglePassword" aria-label="Tampilkan password">Lihat</button>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex align-items-center justify-content-between mb-4 mt-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="1" id="remember" name="remember">
                            <label class="form-check-label" for="remember">Ingat Saya</label>
                        </div>
                        <a class="forgot-link" href="{{ url('/forgot-password') }}">Lupa Password?</a>
                    </div>

                    <button type="submit" class="btn btn-login w-100">
                        Masuk <span class="ms-2" aria-hidden="true">&#8594;</span>
                    </button>
                </form>

                <div class="login-footer">
                    &copy; {{ date('Y') }} RiceERP Solutions. All rights reserved. Professional Agricultural ERP<br>
                </div>
            </div>
        </section>

        <aside class="hero-panel" aria-label="Informasi aplikasi RiceERP">
            <div class="warehouse-shapes" aria-hidden="true"><span></span><span></span><span></span><span></span></div>
            <div class="hero-content">
                <div class="hero-tag"><span class="hero-tag-dot"></span> Enterprise Grade</div>
                <h2 class="hero-title">Optimasi Inventori Beras dengan Presisi Digital</h2>
                <p class="hero-description">Kelola stok gudang, transaksi kasir, dan hubungan supplier dalam satu ekosistem yang modern dan terintegrasi untuk hasil operasional yang lebih maksimal.</p>
                <div class="hero-bottom-line"></div>
            </div>
        </aside>
    </main>

    <script>
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');

        togglePassword.addEventListener('click', function () {
            const isHidden = passwordInput.type === 'password';
            passwordInput.type = isHidden ? 'text' : 'password';
            togglePassword.textContent = isHidden ? 'Sembunyikan' : 'Lihat';
            togglePassword.setAttribute('aria-label', isHidden ? 'Sembunyikan password' : 'Tampilkan password');
        });
    </script>
</body>
</html>
