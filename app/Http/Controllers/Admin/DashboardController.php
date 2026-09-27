<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\User;
use App\Models\Voter;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Menampilkan Dashboard Utama Super Admin
     */
    public function index(): View
    {
        // 1. Metrik Akun & Pengguna
        $totalSuperAdmin = User::where('role', 'SUPER_ADMIN')->count();
        $totalPetugas = User::where('role', 'PETUGAS')->count();
        $totalStudentVoters = Voter::where('type', 'STUDENT')->count();
        $totalTeacherVoters = Voter::where('type', 'TEACHER')->count();

        // 2. Kesiapan DPT
        $totalActiveVoters = Voter::where('is_active', true)->count();
        $totalInactiveVoters = Voter::where('is_active', false)->count();

        // 3. Log Keamanan & Aktivitas Terkini (6 aktivitas terbaru)
        $recentAuditLogs = AuditLog::latest()->take(6)->get();

        // 4. Spesifikasi Lingkungan
        $dbVersion = 'MySQL 8.0.30';
        try {
            $pdo = DB::connection()->getPdo();
            $rawVersion = $pdo->getAttribute(\PDO::ATTR_SERVER_VERSION);
            $dbVersion = 'MySQL ' . explode('-', $rawVersion)[0];
        } catch (\Throwable $e) {
            // fallback
        }

        $systemInfo = [
            'db_engine' => $dbVersion,
            'php_version' => 'PHP ' . PHP_VERSION,
            'laravel_version' => app()->version(),
            'encryption' => 'AES-256-CBC',
            'timezone' => 'WIB (UTC+7)',
        ];

        return view('admin.dashboard', compact(
            'totalSuperAdmin',
            'totalPetugas',
            'totalStudentVoters',
            'totalTeacherVoters',
            'totalActiveVoters',
            'totalInactiveVoters',
            'recentAuditLogs',
            'systemInfo'
        ));
    }
}
