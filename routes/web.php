<?php

use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\Auth\VoterAuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - VOTINGYUK
|--------------------------------------------------------------------------
*/

// =========================================================================
// 1. AUTENTIKASI VOTER (PEMILIH: SISWA & GURU)
// =========================================================================
Route::get('/', [VoterAuthController::class, 'showLoginForm'])->name('home');
Route::get('/voter/login', [VoterAuthController::class, 'showLoginForm'])->name('voter.login');
Route::post('/voter/login', [VoterAuthController::class, 'login'])->name('voter.login.post');
Route::post('/voter/logout', [VoterAuthController::class, 'logout'])->name('voter.logout');

// =========================================================================
// 2. AUTENTIKASI PENGELOLA (SUPER ADMIN & PETUGAS)
// =========================================================================
Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('/login', [AdminAuthController::class, 'login'])->name('admin.login.post');
Route::post('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

// =========================================================================
// 3. PANEL SUPER ADMIN (HAK AKSES ADMINISTRATOR SISTEM)
// =========================================================================
Route::middleware(['auth:web', 'role:SUPER_ADMIN'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

    // Manajemen Akun (Super Admin, Petugas, dan Pemilih)
    Route::get('/users', [\App\Http\Controllers\Admin\UserController::class, 'index'])->name('users.index');
    Route::post('/users', [\App\Http\Controllers\Admin\UserController::class, 'storeUser'])->name('users.store');
    Route::put('/users/{user}', [\App\Http\Controllers\Admin\UserController::class, 'updateUser'])->name('users.update');
    Route::delete('/users/{user}', [\App\Http\Controllers\Admin\UserController::class, 'destroyUser'])->name('users.destroy');

    // Manajemen Pemilih (DPT: Siswa & Guru)
    Route::post('/voters', [\App\Http\Controllers\Admin\UserController::class, 'storeVoter'])->name('voters.store');
    Route::put('/voters/{voter}', [\App\Http\Controllers\Admin\UserController::class, 'updateVoter'])->name('voters.update');
    Route::delete('/voters/{voter}', [\App\Http\Controllers\Admin\UserController::class, 'destroyVoter'])->name('voters.destroy');
});




