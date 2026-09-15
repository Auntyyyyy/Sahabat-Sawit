<?php

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PageContentController;
use App\Http\Controllers\Admin\ProductController;
use Illuminate\Support\Facades\Route;

// Tempelkan file ini di routes/web.php dengan: require __DIR__.'/admin.php';
// atau daftarkan sebagai file route terpisah di bootstrap/app.php

Route::prefix('admin')->name('admin.')->group(function () {

    // Redirect /admin -> ke dashboard kalau sudah login, ke login kalau belum
    Route::get('/', function () {
        return redirect()->route(auth()->check() ? 'admin.dashboard' : 'admin.login');
    });

    // Login (tanpa middleware auth)
    Route::middleware('guest')->group(function () {
        Route::get('login', [AdminAuthController::class, 'showLoginForm'])->name('login');
        Route::post('login', [AdminAuthController::class, 'login'])
            ->middleware('throttle:5,1'); // maksimal 5 percobaan per menit
    });

    // Area terproteksi: harus login DAN role admin
    Route::middleware(['auth', 'admin'])->group(function () {
        Route::post('logout', [AdminAuthController::class, 'logout'])->name('logout');

        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Kelola Produk
        Route::resource('products', ProductController::class)->except(['show']);

        // Kelola Konten Halaman Statis
        Route::get('pages', [PageContentController::class, 'index'])->name('pages.index');
        Route::get('pages/{pageKey}/edit', [PageContentController::class, 'edit'])->name('pages.edit');
        Route::put('pages/{pageKey}', [PageContentController::class, 'update'])->name('pages.update');
    });
});