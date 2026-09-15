<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SustainabilityController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\PlantationController;

/*
|--------------------------------------------------------------------------
| DEBUG SEMENTARA — hapus setelah selesai cek
|--------------------------------------------------------------------------
*/
Route::get('/cek-auth', function () {
    dd([
        'is_logged_in' => auth()->check(),
        'user' => auth()->user(),
        'session_id' => session()->getId(),
    ]);
});

/*
|--------------------------------------------------------------------------
| Web Routes - PT Sahabat Sawit
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/tentang', [AboutController::class, 'index'])->name('about');
Route::get('/produk', [ProductController::class, 'index'])->name('products');
Route::get('/produk/{slug}', [ProductController::class, 'show'])->name('products.show');
Route::get('/keberlanjutan', [SustainabilityController::class, 'index'])->name('sustainability');
Route::get('/kontak', [ContactController::class, 'index'])->name('contact');
Route::post('/kontak', [ContactController::class, 'store'])->name('contact.store');
Route::get('/perkebunan', [PlantationController::class, 'index'])->name('plantation');

/*
|--------------------------------------------------------------------------
| Web Routes - Untuk Bahasa
|--------------------------------------------------------------------------
*/
Route::get('/lang/{locale}', function ($locale) {
    if (in_array($locale, ['id', 'en'])) {
        session(['locale' => $locale]);
    }
    return redirect()->back();
})->name('lang.switch');

require __DIR__.'/admin.php';