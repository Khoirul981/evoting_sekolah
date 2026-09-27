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
                  ->orWhere('action', 'like', '%' . $searchTerm . '%')
                  ->orWhere('ip_address', 'like', '%' . $searchTerm . '%');
            });
        }

        // Filter spesifik berdasarkan Tipe Aksi
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        // Pagination 15 per halaman dengan order terbaru
        $logs = $query->latest()->paginate(15)->withQueryString();

        // Daftar aksi utama untuk dropdown filter
        $availableActions = [
            'CHANGE_EVENT_STATUS' => 'CHANGE_EVENT_STATUS (Perubahan Status Event)',
            'CREATE_EVENT'        => 'CREATE_EVENT (Pembuatan Event Baru)',
            'UPDATE_EVENT'        => 'UPDATE_EVENT (Pembaruan Data Event)',
            'DELETE_EVENT'        => 'DELETE_EVENT (Penghapusan Event)',
        ];

        return view('admin.audit_logs.index', compact('logs', 'availableActions'));
    }
}
