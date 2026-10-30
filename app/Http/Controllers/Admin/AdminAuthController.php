<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Laravel\Socialite\Facades\Socialite;

class AdminAuthController extends Controller
{
    /**
     * Role yang diizinkan mengakses panel ini.
     * BARU: 'k3' ditambahkan untuk dashboard K3 (Keselamatan & Kesehatan Kerja).
     * BARU: 'personalia' ditambahkan untuk dashboard Personalia.
     */
    protected array $allowedRoles = ['admin', 'general_officer', 'hr', 'k3', 'personalia'];

    /**
     * FIX: kalau sudah login, jangan tampilkan form login lagi — langsung lempar ke
     * dashboard sesuai role. Sebelumnya hal ini ditangani middleware 'guest' yang
     * redirect ke RouteServiceProvider::HOME (/home), padahal route itu tidak ada
     * di proyek ini sehingga muncul 404. Sekarang redirect-nya lewat redirectByRole(),
     * yang sudah tahu role dan tujuan dashboard masing-masing.
     */
    public function showLoginForm(): View|RedirectResponse
    {
        if (Auth::check()) {
            return $this->redirectByRole(Auth::user());
        }

        return view('admin.auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();

            if (! in_array($user->role, $this->allowedRoles, true)) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()->withErrors(['email' => 'Akun ini tidak memiliki akses ke panel.']);
            }

            return $this->redirectByRole($user);
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    /**
 * Arahkan ke halaman login Google.
 */
public function redirectToGoogle(): RedirectResponse
{
    return Socialite::driver('google')->redirect();
}

/**
 * Terima hasil login Google. Hanya email yang SUDAH terdaftar di tabel users
 * (dan punya role panel yang valid) yang boleh masuk. Akun tidak pernah dibuat di sini.
 */
public function handleGoogleCallback(Request $request): RedirectResponse
{
    $gagal = fn (string $pesan) => redirect()->route('admin.login')->withErrors(['email' => $pesan]);

    try {
        $google = Socialite::driver('google')->user();
    } catch (\Throwable $e) {
        return $gagal('Login dengan Google dibatalkan atau gagal. Silakan coba lagi.');
    }

    $email = strtolower((string) $google->getEmail());
    $terverifikasi = filter_var($google->user['email_verified'] ?? false, FILTER_VALIDATE_BOOLEAN);

    $user = $email !== ''
        ? User::whereRaw('LOWER(email) = ?', [$email])->first()
        : null;

    // Satu pesan untuk semua penolakan, supaya tidak membocorkan email mana yang terdaftar.
    if (! $terverifikasi || ! $user || ! in_array($user->role, $this->allowedRoles, true)) {
        return $gagal('Akun Google ini tidak memiliki akses ke panel. Hubungi admin untuk mendaftarkan email Anda.');
    }

    Auth::login($user);
    $request->session()->regenerate();

    return $this->redirectByRole($user);
}

    /**
     * FIX: sama seperti showLoginForm() — cek dulu apakah sudah login.
     */
    public function showRegisterForm(): View|RedirectResponse
    {
        if (Auth::check()) {
            return $this->redirectByRole(Auth::user());
        }

        return view('admin.auth.register');
    }

    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            // Sengaja TIDAK mengizinkan 'admin' dipilih sendiri saat register.
            // 'k3' juga sengaja tidak bisa daftar sendiri: dashboard K3 memuat data
            // kesehatan karyawan (MCU), jadi akun K3 dibuat oleh admin, bukan lewat form publik.
            // 'personalia' juga sengaja tidak ada di sini: akunnya dibuat lewat seeder / oleh admin.
            'role' => ['required', 'in:general_officer,hr'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return $this->redirectByRole($user);
    }

    /**
     * Logout untuk semua role (admin, hr, general_officer, k3, personalia).
     * Setelah keluar, pengunjung diarahkan ke halaman Beranda website.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }

    /**
     * Arahkan user ke dashboard sesuai role-nya.
     */
    protected function redirectByRole(User $user): RedirectResponse
    {
        return match ($user->role) {
            'admin' => redirect()->intended(route('admin.dashboard')),
            'general_officer' => redirect()->intended(route('go.dashboard')),
            'hr' => redirect()->intended(route('hr.dashboard')),
            'k3' => redirect()->intended(route('k3.dashboard')),
            'personalia' => redirect()->intended(route('personalia.dashboard')),
            default => redirect()->route('admin.login'),
        };
    }
}