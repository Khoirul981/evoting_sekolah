<?php

use App\Http\Controllers\Admin\AuditLogController;
use App\Http\Controllers\Admin\CandidateController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\EventResultController;
use App\Http\Controllers\Admin\PetugasController;
use App\Http\Controllers\Admin\SuperAdminController;
use App\Http\Controllers\Admin\VoterManagementController;
use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\Auth\VoterAuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - E-Voting Sekolah
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
// 3. PANEL PENGELOLA (ADMIN & PETUGAS) - GUARD: WEB
// =========================================================================
Route::middleware(['auth:web'])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard Ringkasan
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Master Pemilih (DPT)
    Route::get('/voters', [VoterManagementController::class, 'index'])->name('voters.index');
    Route::post('/voters', [VoterManagementController::class, 'store'])->name('voters.store');
    Route::post('/voters/import', [VoterManagementController::class, 'importCsv'])->name('voters.import');
    Route::get('/voters/export', [VoterManagementController::class, 'exportCsv'])->name('voters.export');
    Route::put('/voters/{voter}', [VoterManagementController::class, 'update'])->name('voters.update');
    Route::patch('/voters/{voter}/toggle', [VoterManagementController::class, 'toggleStatus'])->name('voters.toggle');
    Route::delete('/voters/{voter}', [VoterManagementController::class, 'destroy'])->name('voters.destroy');

    // Kelola Event Pemilihan
    Route::get('/events', [EventController::class, 'index'])->name('events.index');
    Route::get('/events/create', [EventController::class, 'create'])->name('events.create');
    Route::post('/events', [EventController::class, 'store'])->name('events.store');
    Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');
    Route::get('/events/{event}/edit', [EventController::class, 'edit'])->name('events.edit');
    Route::put('/events/{event}', [EventController::class, 'update'])->name('events.update');
    Route::patch('/events/{event}/status', [EventController::class, 'updateStatus'])->name('events.status');
    Route::delete('/events/{event}', [EventController::class, 'destroy'])->name('events.destroy');

    // Kelola Kandidat per Event
    Route::post('/events/{event}/candidates', [CandidateController::class, 'store'])->name('events.candidates.store');
    Route::put('/events/{event}/candidates/{candidate}', [CandidateController::class, 'update'])->name('events.candidates.update');
    Route::delete('/events/{event}/candidates/{candidate}', [CandidateController::class, 'destroy'])->name('events.candidates.destroy');

    // Quick Count & Hasil Real-time
    Route::get('/events/{event}/results', [EventResultController::class, 'show'])->name('events.results');
    Route::get('/events/{event}/results/data', [EventResultController::class, 'apiData'])->name('events.results.data');

    // =====================================================================
    // 4. KHUSUS SUPER ADMIN ONLY
    // =====================================================================
    Route::middleware(['role:SUPER_ADMIN'])->group(function () {
        // Manajemen Super Admin
        Route::get('/super-admins', [SuperAdminController::class, 'index'])->name('super-admins.index');
        Route::post('/super-admins', [SuperAdminController::class, 'store'])->name('super-admins.store');
        Route::put('/super-admins/{super_admin}', [SuperAdminController::class, 'update'])->name('super-admins.update');
        Route::delete('/super-admins/{super_admin}', [SuperAdminController::class, 'destroy'])->name('super-admins.destroy');

        // Manajemen Petugas
        Route::get('/petugas', [PetugasController::class, 'index'])->name('petugas.index');
        Route::post('/petugas', [PetugasController::class, 'store'])->name('petugas.store');
        Route::put('/petugas/{petuga}', [PetugasController::class, 'update'])->name('petugas.update');
        Route::delete('/petugas/{petuga}', [PetugasController::class, 'destroy'])->name('petugas.destroy');

        // Audit Log Viewer & Export
        Route::get('/audit-logs', [AuditLogController::class, 'index'])->name('audit-logs.index');
        Route::get('/audit-logs/export', [AuditLogController::class, 'exportCsv'])->name('audit-logs.export');
    });
});


