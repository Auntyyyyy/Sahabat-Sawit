<?php

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PageContentController;
use App\Http\Controllers\Admin\ProductController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\HrDashboardController;
use App\Http\Controllers\Admin\KaryawanController;
use App\Http\Controllers\Admin\KehadiranController;
use App\Http\Controllers\Admin\BeritaController;
use App\Http\Controllers\Admin\CsrCategoryController;
use App\Http\Controllers\Admin\CsrActivityController;
use App\Http\Controllers\Admin\PengetahuanController;
use App\Http\Controllers\Admin\PerformaController; 
use App\Http\Controllers\Admin\PengetahuanKategoriController;
use App\Http\Controllers\Admin\DivisionController;
use App\Http\Controllers\Admin\SettingController;

// Dashboard GA
// di-import sama sekali — bakal error "Class not found" begitu dipanggil.
use App\Http\Controllers\Admin\GeneralOfficerDashboardController;
use App\Http\Controllers\Admin\AssetController;
use App\Http\Controllers\Admin\AtkItemController;
use App\Http\Controllers\Admin\AtkStockInController;
use App\Http\Controllers\Admin\AtkRequestController;
use App\Http\Controllers\Admin\FacilityController;
use App\Http\Controllers\Admin\LetterController;
use App\Http\Controllers\Admin\FacilityLoanController;

// route K3
use App\Http\Controllers\Admin\K3DashboardController;

// Personalia
use App\Http\Controllers\Admin\PersonaliaDashboardController;
use App\Http\Controllers\Admin\PersonaliaKaryawanController;
use App\Http\Controllers\Admin\PersonaliaAbsensiController;
use App\Http\Controllers\Admin\PersonaliaCutiController;
use App\Http\Controllers\Admin\PersonaliaRekrutmenController;
use App\Http\Controllers\Admin\PersonaliaPerformanceController;
use App\Http\Controllers\Admin\DokumenKepegawaianController;

Route::prefix('admin')->name('admin.')->group(function () {

    Route::get('/', function () {
        return redirect()->route(auth()->check() ? 'admin.dashboard' : 'admin.login');
    });

    Route::middleware('guest')->group(function () {
        Route::get('login', [AdminAuthController::class, 'showLoginForm'])->name('login');
        Route::post('login', [AdminAuthController::class, 'login'])->middleware('throttle:5,1');
        Route::get('register', [AdminAuthController::class, 'showRegisterForm'])->name('register');
        Route::post('register', [AdminAuthController::class, 'register'])->name('register.store');
        Route::get('auth/google', [AdminAuthController::class, 'redirectToGoogle'])->name('google.redirect');
Route::get('auth/google/callback', [AdminAuthController::class, 'handleGoogleCallback'])->middleware('throttle:10,1')->name('google.callback');
    });

    // Logout sengaja TANPA middleware 'auth' dan 'admin': semua role (admin, hr, general_officer)
    // harus selalu bisa keluar, termasuk kalau sesinya sudah habis. Sebelumnya middleware 'admin'
    // tanpa parameter hanya meloloskan role 'admin', sehingga role lain mendapat 403.
    Route::post('logout', [AdminAuthController::class, 'logout'])->name('logout');

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

        // Modul Fasilitas
        Route::resource('facilities', FacilityController::class);

        // Peminjaman Fasilitas
        Route::patch('facility-loans/{facilityLoan}/status', [FacilityLoanController::class, 'updateStatus'])->name('facility-loans.status');
        Route::patch('facility-loans/{facilityLoan}/return', [FacilityLoanController::class, 'returnLoan'])->name('facility-loans.return');
        Route::resource('facility-loans', FacilityLoanController::class)
            ->except(['edit', 'update'])
            ->parameters(['facility-loans' => 'facilityLoan']);

        // Modul Surat-Menyurat
        Route::get('letters/{letter}/lampiran', [LetterController::class, 'lampiran'])->name('letters.lampiran');
        Route::patch('letters/{letter}/status', [LetterController::class, 'updateStatus'])->name('letters.status');
        Route::resource('letters', LetterController::class);

        // Barang ATK (master barang + stok)
        Route::resource('atk-items', AtkItemController::class)
            ->except(['show'])
            ->parameters(['atk-items' => 'atkItem']);

        // Barang masuk (menambah stok)
        Route::resource('atk-stock-ins', AtkStockInController::class)
            ->only(['index', 'create', 'store']);

        // Permintaan ATK
        Route::resource('atk-requests', AtkRequestController::class)
            ->except(['edit', 'update'])
            ->parameters(['atk-requests' => 'atkRequest']);

        Route::patch('atk-requests/{atkRequest}/status', [AtkRequestController::class, 'updateStatus'])
            ->name('atk-requests.status');

                // Pengaturan GA
        Route::prefix('settings')->name('settings.')->group(function () {
            Route::redirect('/', '/go/settings/divisions')->name('index');
            Route::get('dashboard', [SettingController::class, 'dashboard'])->name('dashboard');
            Route::put('dashboard', [SettingController::class, 'updateDashboard'])->name('dashboard.update');
            Route::resource('divisions', DivisionController::class)->except(['show', 'create']);
        });
    });
    
    // Dashboard HR
Route::middleware(['auth', 'admin:hr'])
    ->prefix('hr')->name('hr.')
    ->group(function () {
        Route::get('dashboard', [HrDashboardController::class, 'index'])->name('dashboard');
        Route::resource('karyawan', KaryawanController::class);

        // Dashboard Kehadiran → URL /hr/kehadiran, nama route hr.kehadiran.dashboard
        Route::get('kehadiran', [KehadiranController::class, 'dashboard'])->name('kehadiran.dashboard');

        // BARU: Dashboard Performa → URL /hr/performa, nama route hr.performa.dashboard
        Route::get('performa', [PerformaController::class, 'index'])->name('performa.dashboard');

        Route::get('dokumen', [DokumenKepegawaianController::class, 'index'])->name('dokumen.index');
        Route::get('dokumen/{dokumen}/berkas', [DokumenKepegawaianController::class, 'berkas'])->name('dokumen.berkas');
    });

    // Dashboard K3 (Keselamatan & Kesehatan Kerja)
Route::middleware(['auth', 'admin:k3'])
    ->prefix('k3')->name('k3.')
    ->group(function () {
        Route::get('dashboard', [K3DashboardController::class, 'index'])->name('dashboard');

        // Modul Dokumen Keselamatan
        Route::patch('dokumen/{dokumen}/status', [\App\Http\Controllers\Admin\K3DokumenController::class, 'toggleStatus'])->name('dokumen.status');
        Route::resource('dokumen', \App\Http\Controllers\Admin\K3DokumenController::class)
            ->except(['show'])
            ->parameters(['dokumen' => 'dokumen']);
    
        // Modul Safety Patrol → URL /k3/patrol, nama route k3.patrol.*
        Route::patch('patrol/{patrol}/selesai', [\App\Http\Controllers\Admin\K3PatrolController::class, 'selesaikan'])->name('patrol.selesai');
        Route::resource('patrol', \App\Http\Controllers\Admin\K3PatrolController::class)
            ->except(['show'])
            ->parameters(['patrol' => 'patrol']);       

         // Modul Monitoring Kesehatan → URL /k3/kesehatan, nama route k3.kesehatan.*
        Route::resource('kesehatan', \App\Http\Controllers\Admin\K3KesehatanController::class)
            ->except(['show'])
            ->parameters(['kesehatan' => 'kesehatan']);
                // Modul Peralatan → URL /k3/peralatan, nama route k3.peralatan.*
        Route::resource('peralatan', \App\Http\Controllers\Admin\K3PeralatanController::class)
            ->except(['show'])
            ->parameters(['peralatan' => 'peralatan']);

                // Modul Program K3L → URL /k3/program, nama route k3.program.*
        Route::patch('program/{program}/selesai', [\App\Http\Controllers\Admin\K3ProgramController::class, 'selesaikan'])->name('program.selesai');
        Route::resource('program', \App\Http\Controllers\Admin\K3ProgramController::class)
            ->except(['show'])
            ->parameters(['program' => 'program']);
                // Modul Pengukuran Lingkungan → URL /k3/pengukuran, nama route k3.pengukuran.*
        Route::resource('pengukuran', \App\Http\Controllers\Admin\K3PengukuranController::class)
            ->except(['show'])
            ->parameters(['pengukuran' => 'pengukuran']);    
            
            });


// Dashboard Personalia
Route::middleware(['auth', 'admin:personalia'])
    ->prefix('personalia')->name('personalia.')
    ->group(function () {
        Route::get('dashboard', [PersonaliaDashboardController::class, 'index'])->name('dashboard');

        // Karyawan
        Route::resource('karyawan', PersonaliaKaryawanController::class);        Route::get('karyawan/{karyawan}', [PersonaliaKaryawanController::class, 'show'])->name('karyawan.show');

        // Absensi
        Route::resource('absensi', PersonaliaAbsensiController::class)
            ->except(['show'])
            ->parameters(['absensi' => 'kehadiran']);

        // Cuti
        Route::get('cuti', [PersonaliaCutiController::class, 'index'])->name('cuti.index');

        // Rekrutmen
        Route::patch('rekrutmen/{lowongan}/status', [PersonaliaRekrutmenController::class, 'toggle'])->name('rekrutmen.status');
        Route::resource('rekrutmen', PersonaliaRekrutmenController::class)
            ->except(['show'])
            ->parameters(['rekrutmen' => 'lowongan']);

        // Menu yang belum dirancang (halaman sementara)
        Route::view('payroll', 'admin.personalia.payroll.index')->name('payroll.index');
        Route::resource('performance', PersonaliaPerformanceController::class)
            ->except(['show'])
            ->parameters(['performance' => 'penilaian']);        Route::view('training', 'admin.personalia.training.index')->name('training.index');
        Route::get('dokumen/{dokumen}/berkas', [DokumenKepegawaianController::class, 'berkas'])->name('dokumen.berkas');
        Route::resource('dokumen', DokumenKepegawaianController::class)->except(['show']);        Route::view('setup', 'admin.personalia.setup.index')->name('setup.index');
    });

