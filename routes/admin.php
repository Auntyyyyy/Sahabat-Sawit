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
use App\Http\Controllers\Admin\PengetahuanController;
// FIX: class ini dipakai di grup route 'go' di bawah tapi sebelumnya belum
// di-import sama sekali — bakal error "Class not found" begitu dipanggil.
use App\Http\Controllers\Admin\GeneralOfficerDashboardController;
use App\Http\Controllers\Admin\AssetController;
use App\Http\Controllers\Admin\PengetahuanKategoriController;

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
        // BARU: unggah foto dari editor isi berita (harus di atas resource 'berita')
        Route::post('berita/upload-image', [BeritaController::class, 'uploadImage'])->name('berita.upload-image');
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
        // BARU: unggah foto dari editor penjelasan pengetahuan (harus di atas resource 'pengetahuan')
        Route::post('pengetahuan/upload-image', [PengetahuanController::class, 'uploadImage'])->name('pengetahuan.upload-image');
        Route::resource('pengetahuan', PengetahuanController::class)->except(['show']);
        Route::resource('pengetahuan-kategori', PengetahuanKategoriController::class)
            ->except(['show', 'create'])
            ->parameters(['pengetahuan-kategori' => 'kategori']);
    });
});

// Dashboard General Officer (General Affair)
Route::middleware(['auth', 'admin:general_officer'])
    ->prefix('go')->name('go.')
    ->group(function () {
        Route::get('dashboard', [GeneralOfficerDashboardController::class, 'index'])->name('dashboard');

        // Modul Aset / Inventaris
        Route::resource('assets', AssetController::class)->except(['show']);
    });

// Dashboard HR
Route::middleware(['auth', 'admin:hr'])
    ->prefix('hr')->name('hr.')
    ->group(function () {
        Route::get('dashboard', [HrDashboardController::class, 'index'])->name('dashboard');
        Route::resource('karyawan', KaryawanController::class);
    });