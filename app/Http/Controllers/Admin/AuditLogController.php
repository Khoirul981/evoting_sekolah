<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AuditLogController extends Controller
{
    /**
     * Tampilkan halaman daftar audit log dengan fitur pencarian dan filter aksi.
     */
    public function index(Request $request): View
    {
        $query = AuditLog::query();

        // Pencarian teks umum (nama pengguna, deskripsi, aksi, atau IP)
        if ($request->filled('search')) {
            $searchTerm = trim($request->search);
            $query->where(function ($q) use ($searchTerm) {
                $q->where('user_name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('description', 'like', '%' . $searchTerm . '%')
                  ->orWhere('ip_address', 'like', '%' . $searchTerm . '%');
            });
        }

        // Filter spesifik berdasarkan Role
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Pagination 15 per halaman dengan order terbaru
        $logs = $query->latest()->paginate(15)->withQueryString();

        return view('admin.audit_logs.index', compact('logs'));
    }
}
