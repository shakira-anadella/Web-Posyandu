<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PenimbanganController;


Route::get('/dashboard', [DashboardController::class, 'index']);
Route::get('/balita/create', [DashboardController::class, 'createBalita']);
Route::post('/balita/store', [DashboardController::class, 'storeBalita']);


Route::get('/balita/{id}/timbang', [PenimbanganController::class, 'form']);
Route::post('/balita/{id}/prediksi', [PenimbanganController::class, 'prediksi']);
Route::post('/balita/{id}/simpan', [PenimbanganController::class, 'simpan']);

// Redirect halaman awal langsung ke dashboard
Route::get('/', function () {
    return redirect('/dashboard');
});