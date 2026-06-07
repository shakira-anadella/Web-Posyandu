<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PenimbanganController;
use App\Http\Controllers\LoginController;

// --- 1. RUTE PUBLIK (BISA DIAKSES SEBELUM LOGIN) ---
Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
Route::post('/login', [LoginController::class, 'prosesLogin']);
Route::post('/logout', [LoginController::class, 'logout']);

Route::get('/', function () {
    return redirect('/dashboard');
});

// --- 2. RUTE PROTEKSI KADER (MENGGUNAKAN MIDDLEWARE WEB SESSION) ---
Route::middleware(['web'])->group(function () {
    
    // Kita gunakan logic check session manual di dalam rute yang mau dikunci
    Route::group(['middleware' => function ($request, $next) {
        if (!session()->has('kader_logged_in')) {
            return redirect('/login');
        }
        return $next($request);
    }], function () {
        
        Route::get('/dashboard', [DashboardController::class, 'index']);
        Route::get('/balita/create', [DashboardController::class, 'createBalita']);
        Route::post('/balita/store', [DashboardController::class, 'storeBalita']);

        Route::get('/balita/{id}/timbang', [PenimbanganController::class, 'form']);
        Route::post('/balita/{id}/prediksi', [PenimbanganController::class, 'prediksi']);
        Route::post('/balita/{id}/simpan', [PenimbanganController::class, 'simpan']);
        Route::get('/laporan/bulanan', [PenimbanganController::class, 'cetakBulanan']);
        
    });
});