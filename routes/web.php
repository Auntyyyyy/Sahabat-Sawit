<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\PlantationController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SustainabilityController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\CareerController;
use App\Http\Controllers\ContactController;

/*
|--------------------------------------------------------------------------
| Web Routes - PT Sahabat Sawit
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/tentang', [AboutController::class, 'index'])->name('about');
Route::get('/perkebunan', [PlantationController::class, 'index'])->name('plantation');
Route::get('/produk', [ProductController::class, 'index'])->name('products');
Route::get('/keberlanjutan', [SustainabilityController::class, 'index'])->name('sustainability');
Route::get('/berita', [NewsController::class, 'index'])->name('news');
Route::get('/berita/{slug}', [NewsController::class, 'show'])->name('news.show');
Route::get('/karier', [CareerController::class, 'index'])->name('career');
Route::get('/kontak', [ContactController::class, 'index'])->name('contact');
Route::post('/kontak', [ContactController::class, 'store'])->name('contact.store');
