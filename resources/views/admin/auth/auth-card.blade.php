{{-- Kartu login/register bergaya neumorphism dengan panel geser.
     Dipakai oleh login.blade.php dan register.blade.php. Variabel: $mode = 'login' | 'register' --}}
@php $isReg = $mode === 'register'; @endphp

<style>
    :root {
        --base: #EFEBE0;
        --sd: #D2CBB9;
        --sl: #FFFFFF;
        --dark-green: #123524;
        --primary-green: #1F5F3B;
        --gold: #C9A227;
        --text: #1F2933;
        --muted: #5B6560;
        --raise: 12px 12px 24px var(--sd), -12px -12px 24px var(--sl);
        --inset: inset 5px 5px 10px var(--sd), inset -5px -5px 10px var(--sl);
    }
    * { box-sizing: border-box; }
    html, body { margin: 0; min-height: 100%; background: var(--base); font-family: 'Inter', sans-serif; color: var(--text); }

    .auth-page { min-height: 100vh; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 1.5rem; padding: 2rem 1rem; }

    .brand-row { display: flex; align-items: center; justify-content: center; gap: .6rem; }
    .brand-row img { height: 46px; width: auto; display: block; }
    .brand-row strong { display: block; font-family: 'Poppins', sans-serif; font-size: .95rem; color: var(--dark-green); line-height: 1.2; }
    .brand-row span { display: block; font-family: 'Poppins', sans-serif; font-size: .72rem; color: var(--muted); }

    /* ===== Kartu ===== */
    .auth-card { position: relative; width: min(1000px, 100%); height: 720px; background: var(--base); border-radius: 28px; box-shadow: var(--raise); overflow: hidden; }

    /* ===== Panel geser ===== */
    .switch { position: absolute; top: 0; left: 0; width: 40%; height: 100%; z-index: 2; background: var(--base); border-radius: 28px; box-shadow: var(--raise); overflow: hidden; transition: transform .7s cubic-bezier(.65, 0, .35, 1); }
    .is-login .switch { transform: translateX(150%); }
    .switch::before, .switch::after { content: ""; position: absolute; width: 320px; height: 320px; border-radius: 50%; box-shadow: var(--inset); }
    .switch::before { top: -140px; left: -120px; }
    .switch::after { bottom: -160px; right: -140px; }
    .switch-brand { position: absolute; top: 2rem; left: 0; right: 0; z-index: 1; }
    .switch-copy { position: absolute; inset: 0; z-index: 1; display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center; padding: 2.5rem; opacity: 0; visibility: hidden; transition: opacity .25s ease, visibility 0s linear .25s; }
    .is-register .copy-to-login, .is-login .copy-to-register { opacity: 1; visibility: visible; transition: opacity .35s ease .4s, visibility 0s linear .4s; }
    .switch-copy h2 { font-family: 'Poppins', sans-serif; font-size: 1.6rem; color: var(--dark-green); margin: 0; }
    .switch-copy p { font-size: .92rem; line-height: 1.6; color: var(--muted); margin: 0 0 1.75rem; max-width: 260px; }
    .bar { display: block; width: 40px; height: 4px; border-radius: 2px; background: var(--gold); margin: .8rem auto 1rem; }

    /* ===== Panel form ===== */
    .pane { position: absolute; top: 0; width: 60%; height: 100%; padding: 2rem 3.5rem; display: flex; flex-direction: column; justify-content: center; justify-content: safe center; overflow-y: auto; opacity: 0; visibility: hidden; transition: opacity .25s ease, visibility 0s linear .25s; }
    .pane-login { left: 0; }
    .pane-register { left: 40%; }
    .is-login .pane-login, .is-register .pane-register { opacity: 1; visibility: visible; transition: opacity .35s ease .3s, visibility 0s linear .3s; }
    .pane h2 { font-family: 'Poppins', sans-serif; font-size: 1.5rem; color: var(--dark-green); text-align: center; margin: 0 0 .25rem; }
    .subtitle { text-align: center; font-size: .85rem; color: var(--muted); margin: 0 0 1.4rem; }

    .field { margin-bottom: .9rem; }
    .field label { display: block; font-size: .8rem; font-weight: 600; color: var(--dark-green); margin: 0 0 .35rem .4rem; }
    .field input, .field select { width: 100%; border: 0; border-radius: 14px; padding: .75rem 1rem; font: inherit; font-size: .92rem; color: var(--text); background: var(--base); box-shadow: var(--inset); }
    .field select { appearance: none; background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%235B6560' stroke-width='2'%3e%3cpath d='M6 9l6 6 6-6'/%3e%3c/svg%3e"); background-repeat: no-repeat; background-position: right 1rem center; background-size: 18px; }
    .field input:focus-visible, .field select:focus-visible, .btn:focus-visible, .pane a:focus-visible { outline: 2px solid var(--primary-green); outline-offset: 3px; }
    .hint { font-size: .75rem; color: var(--muted); margin: .35rem 0 0 .4rem; }
    .remember-row { display: flex; align-items: center; gap: .5rem; margin: 0 0 1.2rem .4rem; font-size: .85rem; color: var(--muted); }
    .remember-row input { accent-color: var(--primary-green); }

    .btn { display: inline-block; border: 0; cursor: pointer; text-decoration: none; font-family: 'Poppins', sans-serif; font-weight: 600; font-size: .9rem; letter-spacing: .04em; border-radius: 999px; padding: .8rem 2.4rem; background: var(--dark-green); color: #FAF6EE; box-shadow: 6px 6px 14px var(--sd), -6px -6px 14px var(--sl); transition: background .2s, box-shadow .2s, transform .15s; }
    .btn:hover { background: var(--primary-green); }
    .btn:active { box-shadow: inset 3px 3px 6px rgba(0, 0, 0, .35); transform: scale(.98); }
    .btn-ghost { background: var(--base); color: var(--dark-green); }
    .btn-ghost:hover { background: var(--base); color: var(--primary-green); }
    .btn-ghost:active { box-shadow: var(--inset); }
    .btn-block { display: block; width: 100%; margin-top: .4rem; }

    .alert-box { background: #FCEBEC; border: 1px solid #F3C1C6; color: #92273A; border-radius: 14px; padding: .75rem 1rem; font-size: .83rem; margin-bottom: 1rem; }
    .alert-box ul { margin: 0; padding-left: 1.1rem; }

    .mobile-only { display: none; }
    .switch-link { text-align: center; font-size: .85rem; color: var(--muted); margin: 1.25rem 0 0; }
    .switch-link a { color: var(--primary-green); font-weight: 600; text-decoration: none; }

    @media (max-width: 820px) {
        .auth-card { height: auto; border-radius: 24px; }
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
                    <input type="email" id="login-email" name="email" value="{{ $isReg ? '' : old('email') }}" required {{ $isReg ? '' : 'autofocus' }}>
                </div>
                <div class="field">
                    <label for="login-password">Password</label>
                    <input type="password" id="login-password" name="password" required>
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
                    <input type="text" id="name" name="name" value="{{ $isReg ? old('name') : '' }}" required {{ $isReg ? 'autofocus' : '' }}>
                </div>
                <div class="field">
                    <label for="reg-email">Email</label>
                    <input type="email" id="reg-email" name="email" value="{{ $isReg ? old('email') : '' }}" required>
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
                    <input type="password" id="reg-password" name="password" required minlength="8">
                </div>
                <div class="field">
                    <label for="password_confirmation">Konfirmasi Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required minlength="8">
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