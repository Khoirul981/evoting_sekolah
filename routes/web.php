<?php

use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\Auth\VoterAuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - E-Voting Sekolah
|--------------------------------------------------------------------------
*/

// =======================
// 1. AUTENTIKASI VOTER (PEMILIH: SISWA & GURU)
// =======================
Route::get('/', [VoterAuthController::class, 'showLoginForm'])->name('home');
Route::get('/voter/login', [VoterAuthController::class, 'showLoginForm'])->name('voter.login');
Route::post('/voter/login', [VoterAuthController::class, 'login'])->name('voter.login.post');
Route::post('/voter/logout', [VoterAuthController::class, 'logout'])->name('voter.logout');

// =======================
// 2. AUTENTIKASI PENGELOLA (SUPER ADMIN & PETUGAS)
// =======================
Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('/login', [AdminAuthController::class, 'login'])->name('admin.login.post');
Route::post('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

