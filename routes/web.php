<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\KaryawanController;

/*
|--------------------------------------------------------------------------
| Redirect root
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return redirect()->route('login');
});

/* safety net (sementara) */
Route::post('/', function () {
    return redirect()->route('login');
});

/*
|--------------------------------------------------------------------------
| Auth
|--------------------------------------------------------------------------
*/
Route::get('/login', [LoginController::class, 'showLoginForm'])
    ->middleware('guest')
    ->name('login');

Route::post('/login', [LoginController::class, 'login'])
    ->middleware('guest');

Route::post('/logout', [LoginController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

/*
|--------------------------------------------------------------------------
| Protected Pages
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Karyawan
    Route::get('/karyawan', [KaryawanController::class, 'index'])
        ->name('karyawan.index');

    Route::get('/karyawan/tambah', [KaryawanController::class, 'create'])
        ->name('karyawan.create');

    Route::post('/karyawan', [KaryawanController::class, 'store'])
        ->name('karyawan.store');
});