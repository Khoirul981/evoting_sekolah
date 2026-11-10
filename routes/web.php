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

    // Manajemen Pemilih (Siswa & Guru)
    Route::post('/voters', [\App\Http\Controllers\Admin\UserController::class, 'storeVoter'])->name('voters.store');
    Route::put('/voters/{voter}', [\App\Http\Controllers\Admin\UserController::class, 'updateVoter'])->name('voters.update');
    Route::delete('/voters/{voter}', [\App\Http\Controllers\Admin\UserController::class, 'destroyVoter'])->name('voters.destroy');

    // Audit Log & Security (Jejak Digital Sistem)
    Route::get('/audit-logs', [\App\Http\Controllers\Admin\AuditLogController::class, 'index'])->name('audit-logs.index');
});

// =========================================================================
// 4. PANEL PETUGAS (MANAJEMEN EVENT, KANDIDAT & HASIL VOTING)
// =========================================================================
Route::middleware(['auth:web', 'role:PETUGAS'])->prefix('petugas')->name('petugas.')->group(function () {
    // 4.1 Kelola Event Pemilihan
    Route::get('/events', [\App\Http\Controllers\Petugas\EventController::class, 'index'])->name('events.index');
    Route::post('/events', [\App\Http\Controllers\Petugas\EventController::class, 'store'])->name('events.store');
    Route::put('/events/{event}', [\App\Http\Controllers\Petugas\EventController::class, 'update'])->name('events.update');
    Route::patch('/events/{event}/status', [\App\Http\Controllers\Petugas\EventController::class, 'updateStatus'])->name('events.status');
    Route::delete('/events/{event}', [\App\Http\Controllers\Petugas\EventController::class, 'destroy'])->name('events.destroy');

    // 4.2 Kelola Kandidat & Opsi Pemilihan
    Route::get('/events/{event}/candidates', [\App\Http\Controllers\Petugas\CandidateController::class, 'index'])->name('candidates.index');
    Route::post('/events/{event}/candidates', [\App\Http\Controllers\Petugas\CandidateController::class, 'store'])->name('candidates.store');
    Route::put('/events/{event}/candidates/{candidate}', [\App\Http\Controllers\Petugas\CandidateController::class, 'update'])->name('candidates.update');
    Route::delete('/events/{event}/candidates/{candidate}', [\App\Http\Controllers\Petugas\CandidateController::class, 'destroy'])->name('candidates.destroy');

    // 4.3 Live Monitoring & Rekapitulasi Suara
    Route::get('/events/{event}/monitor', [\App\Http\Controllers\Petugas\MonitoringController::class, 'show'])->name('events.monitor');
    Route::get('/events/{event}/monitor/data', [\App\Http\Controllers\Petugas\MonitoringController::class, 'data'])->name('events.monitor.data');
});

// =========================================================================
// 5. PANEL PEMILIH (VOTER DASHBOARD, KODE RUANGAN & BILIK SUARA)
// =========================================================================
Route::middleware(['auth:voter'])->prefix('voter')->name('voter.')->group(function () {
    // 5.1 Lobi Utama & Validasi Kode Ruangan
    Route::get('/dashboard', [\App\Http\Controllers\Voter\VoterDashboardController::class, 'index'])->name('dashboard');
    Route::get('/join', [\App\Http\Controllers\Voter\VoterDashboardController::class, 'index'])->name('join');
    Route::post('/join', [\App\Http\Controllers\Voter\VoterDashboardController::class, 'joinRoom'])->name('join.post');

    // 5.2 Bilik Suara (Voting Booth) & Tanda Terima Sukses
    Route::get('/events/{room_code}/vote', [\App\Http\Controllers\Voter\VotingBoothController::class, 'show'])->name('events.vote');
    Route::post('/events/{room_code}/vote', [\App\Http\Controllers\Voter\VotingBoothController::class, 'castVote'])->name('events.vote.post');
    Route::get('/events/{room_code}/success', [\App\Http\Controllers\Voter\VotingBoothController::class, 'success'])->name('events.success');

    // 5.3 Live Monitoring Perolehan Suara untuk Event yang Sudah Diikuti
    Route::get('/events/{room_code}/monitoring', [\App\Http\Controllers\Voter\VoterMonitoringController::class, 'show'])->name('events.monitoring');
    Route::get('/events/{room_code}/monitoring/data', [\App\Http\Controllers\Voter\VoterMonitoringController::class, 'data'])->name('events.monitoring.data');
});




