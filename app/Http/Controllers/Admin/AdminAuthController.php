<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AdminAuthController extends Controller
{
    /**
     * Role yang diizinkan mengakses panel ini.
     */
    protected array $allowedRoles = ['admin', 'general_officer', 'hr'];

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

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
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
            default => redirect()->route('admin.login'),
        };
    }
}