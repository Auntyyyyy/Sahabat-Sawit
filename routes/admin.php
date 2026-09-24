<?php

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PageContentController;
use App\Http\Controllers\Admin\ProductController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\HrDashboardController;
use App\Http\Controllers\Admin\KaryawanController; 
use App\Http\Controllers\Admin\BeritaController;
use App\Http\Controllers\Admin\CsrCategoryController;
use App\Http\Controllers\Admin\CsrActivityController;

// Tempelkan file ini di routes/web.php dengan: require __DIR__.'/admin.php';
// atau daftarkan sebagai file route terpisah di bootstrap/app.php

Route::prefix('admin')->name('admin.')->group(function () {

    Route::get('/', function () {
        return redirect()->route(auth()->check() ? 'admin.dashboard' : 'admin.login');
    });

    Route::middleware('guest')->group(function () {
        Route::get('login', [AdminAuthController::class, 'showLoginForm'])->name('login');
        Route::post('login', [AdminAuthController::class, 'login'])->middleware('throttle:5,1');
        Route::get('register', [AdminAuthController::class, 'showRegisterForm'])->name('register');
        Route::post('register', [AdminAuthController::class, 'register'])->name('register.store');
    });

    Route::post('logout', [AdminAuthController::class, 'logout'])
        ->middleware(['auth', 'admin']) // semua role login boleh logout
        ->name('logout');

    // Dashboard admin: HANYA role admin
    Route::middleware(['auth', 'admin:admin'])->group(function () {
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::resource('products', ProductController::class)->except(['show']);
        Route::resource('berita', BeritaController::class)
            ->except(['show'])
            ->parameters(['berita' => 'berita']);
        Route::get('pages', [PageContentController::class, 'index'])->name('pages.index');
        Route::get('pages/{pageKey}/edit', [PageContentController::class, 'edit'])->name('pages.edit');
        Route::put('pages/{pageKey}', [PageContentController::class, 'update'])->name('pages.update');
        Route::resource('csr-categories', CsrCategoryController::class)->except(['show']);
        Route::resource('csr-categories.activities', CsrActivityController::class)
            ->except(['show', 'index'])
            ->shallow();
    });
});

// Dashboard General Officer
Route::middleware(['auth', 'admin:general_officer'])
    ->prefix('go')->name('go.')
    ->group(function () {
        Route::get('dashboard', [GeneralOfficerDashboardController::class, 'index'])->name('dashboard');
    });

// Dashboard HR
Route::middleware(['auth', 'admin:hr'])
    ->prefix('hr')->name('hr.')
    ->group(function () {
        Route::get('dashboard', [HrDashboardController::class, 'index'])->name('dashboard');
        Route::resource('karyawan', KaryawanController::class);
    });