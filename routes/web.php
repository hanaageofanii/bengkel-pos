<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\KaryawanController;
use App\Models\Karyawan;

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

Route::get('/dashboard', function () {
    return view('dashboard-home', [
        'totalKaryawan' => Karyawan::count(),
    ]);
})->name('dashboard');

// Karyawan
    Route::get('/karyawan', [KaryawanController::class, 'index'])
        ->name('karyawan.index');

    Route::get('/karyawan/tambah', [KaryawanController::class, 'create'])
        ->name('karyawan.create');

    Route::post('/karyawan', [KaryawanController::class, 'store'])
        ->name('karyawan.store');

    Route::get('/karyawan/{karyawan}/edit', [KaryawanController::class, 'edit'])
        ->name('karyawan.edit');

    Route::put('/karyawan/{karyawan}', [KaryawanController::class, 'update'])
        ->name('karyawan.update');

    Route::delete('/karyawan/{karyawan}', [KaryawanController::class, 'destroy'])
        ->name('karyawan.destroy');

    Route::patch('/karyawan/{karyawan}/status', [KaryawanController::class, 'toggleStatus'])
        ->name('karyawan.toggle-status');});
