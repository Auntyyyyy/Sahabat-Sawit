{{-- Kartu login/register dengan panel geser (gaya disamakan dengan website: hijau tua, huruf tegas, tanpa efek timbul).
     Dipakai oleh login.blade.php dan register.blade.php. Variabel: $mode = 'login' | 'register' --}}
@php $isReg = $mode === 'register'; @endphp

<style>
    @import url('https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,400;12..96,600;12..96,800&display=swap');

    :root {
        --hijau: #16321f;
        --hijau-muda: #2f5a3a;
        --emas: #e3a72f;
        --krem: #f2f4ec;
    }
    * { box-sizing: border-box; }
    html, body { margin: 0; min-height: 100%; background: var(--hijau); font-family: "Bricolage Grotesque", system-ui, -apple-system, "Segoe UI", sans-serif; color: var(--krem); line-height: 1.6; }

    .auth-page { min-height: 100vh; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 1.5rem; padding: 2rem 1rem; }

    .brand-row { display: flex; align-items: center; justify-content: center; gap: .7rem; }
    .brand-row img { height: 46px; width: auto; display: block; }
    .brand-row strong { display: block; font-size: 1rem; font-weight: 800; letter-spacing: -.01em; line-height: 1.2; color: var(--krem); }
    .brand-row span { display: block; font-size: .75rem; color: rgba(242,244,236,.65); }

    /* ===== Kartu ===== */
    .auth-card { position: relative; width: min(1000px, 100%); height: 720px; background: var(--krem); border-radius: .5rem; overflow: hidden; box-shadow: 0 30px 70px rgba(0,0,0,.4); }

    /* ===== Panel geser (gelap, berfoto) ===== */
    .switch { position: absolute; top: 0; left: 0; width: 40%; height: 100%; z-index: 2; overflow: hidden; background: linear-gradient(145deg, var(--hijau-muda), var(--hijau)); transition: transform .7s cubic-bezier(.65, 0, .35, 1); }
    .is-login .switch { transform: translateX(150%); }
    .switch-bg { position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; opacity: .4; }
    .switch::after { content: ""; position: absolute; inset: 0; background: linear-gradient(0deg, rgba(15,36,23,.88) 0%, rgba(15,36,23,.4) 100%); }
    .switch-brand { position: absolute; top: 2rem; left: 0; right: 0; z-index: 1; }
    .switch-copy { position: absolute; inset: 0; z-index: 1; display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center; padding: 2.5rem; opacity: 0; visibility: hidden; transition: opacity .25s ease, visibility 0s linear .25s; }
    .is-register .copy-to-login, .is-login .copy-to-register { opacity: 1; visibility: visible; transition: opacity .35s ease .4s, visibility 0s linear .4s; }
    .switch-copy h2 { margin: 0; max-width: 12ch; font-size: clamp(1.8rem, 3vw, 2.5rem); font-weight: 800; line-height: 1.05; letter-spacing: -.03em; color: var(--krem); }
    .switch-copy p { margin: 0 0 1.75rem; max-width: 260px; font-size: .95rem; line-height: 1.6; color: rgba(242,244,236,.78); }
    .bar { display: block; width: 40px; height: 3px; margin: 1rem auto; background: var(--emas); }

    /* ===== Panel form ===== */
    .pane { position: absolute; top: 0; width: 60%; height: 100%; padding: 2rem 3.5rem; display: flex; flex-direction: column; justify-content: center; justify-content: safe center; overflow-y: auto; color: var(--hijau); opacity: 0; visibility: hidden; transition: opacity .25s ease, visibility 0s linear .25s; }
    .pane-login { left: 0; }
    .pane-register { left: 40%; }
    .is-login .pane-login, .is-register .pane-register { opacity: 1; visibility: visible; transition: opacity .35s ease .3s, visibility 0s linear .3s; }
    .pane h2 { margin: 0 0 .25rem; font-size: clamp(1.6rem, 3vw, 2.2rem); font-weight: 800; letter-spacing: -.03em; line-height: 1.1; color: var(--hijau); }
    .subtitle { margin: 0 0 1.6rem; font-size: .92rem; color: rgba(22,50,31,.65); }

    .field { margin-bottom: 1rem; }
    .field label { display: block; margin: 0 0 .4rem; font-size: .85rem; font-weight: 600; color: var(--hijau); }
    .field input, .field select { width: 100%; padding: .75rem .9rem; border: 1px solid rgba(22,50,31,.3); border-radius: .25rem; background: #fff; font: inherit; font-size: .95rem; color: var(--hijau); transition: border-color .2s; }
    .field input:hover, .field select:hover { border-color: rgba(22,50,31,.55); }
    .field select { appearance: none; background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%2316321f' stroke-width='2'%3e%3cpath d='M6 9l6 6 6-6'/%3e%3c/svg%3e"); background-repeat: no-repeat; background-position: right .9rem center; background-size: 18px; }
    .field input:focus-visible, .field select:focus-visible { outline: 2px solid var(--hijau-muda); outline-offset: 2px; border-color: var(--hijau-muda); }
    .hint { margin: .4rem 0 0; font-size: .8rem; color: rgba(22,50,31,.6); }
    .remember-row { display: flex; align-items: center; gap: .5rem; margin: 0 0 1.25rem; font-size: .9rem; color: rgba(22,50,31,.75); }
    .remember-row input { accent-color: var(--hijau-muda); }

    .btn { display: inline-block; padding: .8rem 2rem; border: 1px solid var(--hijau); border-radius: .25rem; background: var(--hijau); color: var(--krem); font: inherit; font-weight: 600; font-size: .95rem; text-decoration: none; cursor: pointer; transition: background .2s, border-color .2s, color .2s, transform .15s; }
    .btn:hover { background: var(--hijau-muda); border-color: var(--hijau-muda); }
    .btn:active { transform: scale(.98); }
    .btn:focus-visible, .pane a:focus-visible { outline: 3px solid var(--hijau-muda); outline-offset: 3px; }
    .btn-ghost { background: transparent; border-color: rgba(242,244,236,.6); color: var(--krem); }
    .btn-ghost:hover { background: var(--emas); border-color: var(--emas); color: var(--hijau); }
    .switch .btn:focus-visible { outline-color: var(--krem); }
    .btn-block { display: block; width: 100%; margin-top: .4rem; }

    .alert-box { margin-bottom: 1rem; padding: .75rem 1rem; border-left: 3px solid #b3261e; border-radius: .25rem; background: #fdecea; color: #7a1a14; font-size: .88rem; }
    .alert-box ul { margin: 0; padding-left: 1.1rem; }

    .mobile-only { display: none; }
    .switch-link { margin: 1.25rem 0 0; text-align: center; font-size: .9rem; color: rgba(22,50,31,.7); }
    .switch-link a { font-weight: 600; color: var(--hijau); text-decoration: underline; text-decoration-color: var(--emas); text-underline-offset: 3px; }

    @media (max-width: 820px) {
        .auth-card { height: auto; }
        .switch { display: none; }
        .pane { position: static; width: 100%; height: auto; padding: 2rem 1.5rem; display: none; opacity: 1; visibility: visible; }
        .is-login .pane-login, .is-register .pane-register { display: flex; }
        .brand-row.mobile-only { display: flex; }
        .switch-link.mobile-only { display: block; }
    }
    @media (prefers-reduced-motion: reduce) {
        .switch, .pane, .switch-copy, .btn { transition: none !important; }
    }
</style>

<div class="auth-page">

    <div class="brand-row mobile-only">
        <img src="{{ asset('images/logoaja.png') }}" alt="Logo PT. Sahabat Sawit Rokan Sejahtera">
        <div><strong>Sahabat Sawit</strong><span>Rokan Sejahtera</span></div>
    </div>

    <div id="auth-card" class="auth-card {{ $isReg ? 'is-register' : 'is-login' }}">

        {{-- Panel geser --}}
        <aside class="switch">
            <img class="switch-bg" src="{{ asset('images/herotentang.png') }}" alt="" onerror="this.style.display='none'">

            <div class="brand-row switch-brand">
                <img src="{{ asset('images/logoaja.png') }}" alt="Logo PT. Sahabat Sawit Rokan Sejahtera">
                <div><strong>Sahabat Sawit</strong><span>Rokan Sejahtera</span></div>
            </div>

            <div class="switch-copy copy-to-login">
                <h2>Sudah punya akun?</h2>
                <span class="bar"></span>
                <p>Masuk untuk melanjutkan mengelola konten dan data perusahaan.</p>
                <a class="btn btn-ghost" href="{{ route('admin.login') }}" data-switch="login">Masuk</a>
            </div>

            <div class="switch-copy copy-to-register">
                <h2>Belum punya akun?</h2>
                <span class="bar"></span>
                <p>Daftar sebagai staff untuk mengakses panel sesuai jabatan Anda.</p>
                <a class="btn btn-ghost" href="{{ route('admin.register') }}" data-switch="register">Daftar</a>
            </div>
        </aside>

        {{-- Form Login --}}
        <section class="pane pane-login">
            <h2>Masuk ke Panel Admin</h2>
            <p class="subtitle">Khusus untuk tim internal yang berwenang</p>

            @if ($errors->any() && ! $isReg)
                <div class="alert-box" role="alert">
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
                    <label for="login-email">Email</label>
                    <input type="email" id="login-email" name="email" value="{{ $isReg ? '' : old('email') }}" autocomplete="username" required {{ $isReg ? '' : 'autofocus' }}>
                </div>
                <div class="field">
                    <label for="login-password">Password</label>
                    <input type="password" id="login-password" name="password" autocomplete="current-password" required>
                </div>
                <div class="remember-row">
                    <input type="checkbox" name="remember" id="remember">
                    <label for="remember">Ingat saya</label>
                </div>
                <button type="submit" class="btn btn-block">Masuk</button>
            </form>

            <p class="switch-link mobile-only">Belum punya akun? <a href="{{ route('admin.register') }}" data-switch="register">Daftar di sini</a></p>
        </section>

        {{-- Form Register --}}
        <section class="pane pane-register">
            <h2>Daftar Akun Staff</h2>
            <p class="subtitle">Khusus untuk tim internal yang berwenang</p>

            @if ($errors->any() && $isReg)
                <div class="alert-box" role="alert">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.register.store') }}">
                @csrf
                <div class="field">
                    <label for="name">Nama Lengkap</label>
                    <input type="text" id="name" name="name" value="{{ $isReg ? old('name') : '' }}" autocomplete="name" required {{ $isReg ? 'autofocus' : '' }}>
                </div>
                <div class="field">
                    <label for="reg-email">Email</label>
                    <input type="email" id="reg-email" name="email" value="{{ $isReg ? old('email') : '' }}" autocomplete="email" required>
                </div>
                <div class="field">
                    <label for="role">Jabatan</label>
                    <select id="role" name="role" required>
                        <option value="" disabled {{ ($isReg && old('role')) ? '' : 'selected' }}>Pilih jabatan</option>
                        <option value="general_officer" {{ ($isReg && old('role') === 'general_officer') ? 'selected' : '' }}>General Officer</option>
                        <option value="hr" {{ ($isReg && old('role') === 'hr') ? 'selected' : '' }}>HR</option>
                    </select>
                    <p class="hint">Akun Admin tidak dapat dibuat melalui form ini.</p>
                </div>
                <div class="field">
                    <label for="reg-password">Password</label>
                    <input type="password" id="reg-password" name="password" autocomplete="new-password" required minlength="8">
                </div>
                <div class="field">
                    <label for="password_confirmation">Konfirmasi Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" autocomplete="new-password" required minlength="8">
                </div>
                <button type="submit" class="btn btn-block">Daftar</button>
            </form>

            <p class="switch-link mobile-only">Sudah punya akun? <a href="{{ route('admin.login') }}" data-switch="login">Masuk di sini</a></p>
        </section>
    </div>
</div>

<script>
    (function () {
        var card = document.getElementById('auth-card');
        var titles = {
            login: 'Login Admin - PT. Sahabat Sawit Rokan Sejahtera',
            register: 'Daftar Akun Staff - PT. Sahabat Sawit Rokan Sejahtera'
        };
        document.querySelectorAll('[data-switch]').forEach(function (a) {
            a.addEventListener('click', function (e) {
                e.preventDefault();
                var m = a.dataset.switch;
                card.classList.toggle('is-register', m === 'register');
                card.classList.toggle('is-login', m === 'login');
                document.title = titles[m];
                // Alamat ikut berubah, supaya validasi yang gagal kembali ke form yang benar
                try { history.replaceState(null, '', a.href); } catch (err) {}
                setTimeout(function () {
                    var f = card.querySelector('.pane-' + m + ' input');
                    if (f) f.focus({ preventScroll: true });
                }, 700);
            });
        });
    })();
</script>