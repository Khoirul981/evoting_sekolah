<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\User;
use App\Models\Voter;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Tampilkan halaman kelola akun dengan 3 tab: Super Admin, Petugas, dan Pemilih.
     */
    public function index(Request $request): View
    {
        $activeTab = $request->query('tab', 'super_admin');
        if (!in_array($activeTab, ['super_admin', 'petugas', 'pemilih'])) {
            $activeTab = 'super_admin';
        }

        // Query Tab 1: Super Admin
        $superAdminQuery = User::where('role', 'SUPER_ADMIN');
        if ($request->filled('search_admin')) {
            $superAdminQuery->where('name', 'like', '%' . trim($request->search_admin) . '%');
        }
        $superAdmins = $superAdminQuery->orderByDesc('created_at')
            ->paginate(10, ['*'], 'page_admin')
            ->withQueryString();

        // Query Tab 2: Petugas
        $petugasQuery = User::where('role', 'PETUGAS');
        if ($request->filled('search_petugas')) {
            $petugasQuery->where('name', 'like', '%' . trim($request->search_petugas) . '%');
        }
        $petugasList = $petugasQuery->orderByDesc('created_at')
            ->paginate(10, ['*'], 'page_petugas')
            ->withQueryString();

        // Query Tab 3: Pemilih (Siswa & Guru)
        $voterQuery = Voter::query();
        if ($request->filled('search_voter')) {
            $searchTerm = trim($request->search_voter);
            $voterQuery->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('identifier', 'like', '%' . $searchTerm . '%');
            });
        }
        if ($request->filled('type_voter') && in_array($request->type_voter, ['SISWA', 'GURU'])) {
            $voterQuery->where('type', $request->type_voter);
        }
        $voters = $voterQuery->orderByDesc('created_at')
            ->paginate(10, ['*'], 'page_voter')
            ->withQueryString();

        // Counter untuk badge indikator pada tab
        $countSuperAdmin = User::where('role', 'SUPER_ADMIN')->count();
        $countPetugas = User::where('role', 'PETUGAS')->count();
        $countVoter = Voter::count();

        return view('admin.users.index', compact(
            'activeTab',
            'superAdmins',
            'petugasList',
            'voters',
            'countSuperAdmin',
            'countPetugas',
            'countVoter'
        ));
    }

    /**
     * Tambah Akun Pengguna (Super Admin atau Petugas).
     */
    public function storeUser(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'role' => ['required', Rule::in(['SUPER_ADMIN', 'PETUGAS'])],
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar pada sistem.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'role.required' => 'Role pengguna wajib dipilih.',
            'role.in' => 'Role pengguna tidak valid.',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
        ]);

        // Audit Log
        AuditLog::create([
            'user_id' => Auth::id(),
            'user_name' => Auth::user()->name,
            'role' => Auth::user()->role,
            'action' => 'CREATE_USER',
            'description' => "Menambahkan akun {$validated['role']} baru dengan nama '{$validated['name']}' dan email '{$validated['email']}'.",
            'ip_address' => $request->ip(),
        ]);

        $roleLabel = $user->role === 'SUPER_ADMIN' ? 'Super Admin' : 'Petugas';
        $tab = $user->role === 'SUPER_ADMIN' ? 'super_admin' : 'petugas';

        return redirect()->route('admin.users.index', ['tab' => $tab])
            ->with('success', "Akun {$roleLabel} '{$user->name}' berhasil ditambahkan.");
    }

    /**
     * Perbarui Akun Pengguna (Super Admin atau Petugas).
     */
    public function updateUser(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:6', 'confirmed'],
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan oleh akun lain.',
            'password.min' => 'Password baru minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi password baru tidak cocok.',
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        // Audit Log
        AuditLog::create([
            'user_id' => Auth::id(),
            'user_name' => Auth::user()->name,
            'role' => Auth::user()->role,
            'action' => 'UPDATE_USER',
            'description' => "Memperbarui data akun {$user->role} '{$user->name}' (Email: {$user->email})" . (!empty($validated['password']) ? ' dengan perubahan password.' : '.'),
            'ip_address' => $request->ip(),
        ]);

        $roleLabel = $user->role === 'SUPER_ADMIN' ? 'Super Admin' : 'Petugas';
        $tab = $user->role === 'SUPER_ADMIN' ? 'super_admin' : 'petugas';

        return redirect()->route('admin.users.index', ['tab' => $tab])
            ->with('success', "Data {$roleLabel} '{$user->name}' berhasil diperbarui.");
    }

    /**
     * Hapus Akun Pengguna (Super Admin atau Petugas).
     */
    public function destroyUser(User $user): RedirectResponse
    {
        // Proteksi: Super Admin yang sedang login tidak boleh menghapus dirinya sendiri
        if (Auth::id() === $user->id) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri yang sedang aktif digunakan.');
        }

        $roleLabel = $user->role === 'SUPER_ADMIN' ? 'Super Admin' : 'Petugas';
        $tab = $user->role === 'SUPER_ADMIN' ? 'super_admin' : 'petugas';
        $userName = $user->name;

        $user->delete();

        // Audit Log
        AuditLog::create([
            'user_id' => Auth::id(),
            'user_name' => Auth::user()->name,
            'role' => Auth::user()->role,
            'action' => 'DELETE_USER',
            'description' => "Menghapus akun {$roleLabel} '{$userName}' dari sistem.",
            'ip_address' => request()->ip(),
        ]);

        return redirect()->route('admin.users.index', ['tab' => $tab])
            ->with('success', "Akun {$roleLabel} '{$userName}' berhasil dihapus dari sistem.");
    }

    /**
     * Tambah Pemilih Baru (Siswa atau Guru).
     */
    public function storeVoter(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'identifier' => ['required', 'string', 'max:50', 'unique:voters,identifier'],
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', Rule::in(['SISWA', 'GURU'])],
        ], [
            'identifier.required' => 'NIS / NIP wajib diisi.',
            'identifier.unique' => 'NIS / NIP sudah terdaftar pada sistem.',
            'name.required' => 'Nama pemilih wajib diisi.',
            'type.required' => 'Tipe pemilih wajib dipilih.',
            'type.in' => 'Tipe pemilih harus SISWA atau GURU.',
        ]);

        $voter = Voter::create([
            'identifier' => trim($validated['identifier']),
            'name' => trim($validated['name']),
            'type' => $validated['type'],
            'is_active' => true,
        ]);

        // Audit Log
        AuditLog::create([
            'user_id' => Auth::id(),
            'user_name' => Auth::user()->name,
            'role' => Auth::user()->role,
            'action' => 'CREATE_VOTER',
            'description' => "Menambahkan pemilih {$validated['type']} baru '{$validated['name']}' dengan NIS/NIP: {$validated['identifier']}.",
            'ip_address' => $request->ip(),
        ]);

        $typeLabel = $voter->type === 'SISWA' ? 'Siswa' : 'Guru';

        return redirect()->route('admin.users.index', ['tab' => 'pemilih'])
            ->with('success', "Pemilih {$typeLabel} '{$voter->name}' ({$voter->identifier}) berhasil ditambahkan.");
    }

    /**
     * Perbarui Data Pemilih (Siswa atau Guru).
     */
    public function updateVoter(Request $request, Voter $voter): RedirectResponse
    {
        $validated = $request->validate([
            'identifier' => ['required', 'string', 'max:50', Rule::unique('voters', 'identifier')->ignore($voter->id)],
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', Rule::in(['SISWA', 'GURU'])],
        ], [
            'identifier.required' => 'NIS / NIP wajib diisi.',
            'identifier.unique' => 'NIS / NIP sudah digunakan oleh pemilih lain.',
            'name.required' => 'Nama pemilih wajib diisi.',
            'type.required' => 'Tipe pemilih wajib dipilih.',
            'type.in' => 'Tipe pemilih harus SISWA atau GURU.',
        ]);

        $voter->identifier = trim($validated['identifier']);
        $voter->name = trim($validated['name']);
        $voter->type = $validated['type'];
        $voter->save();

        // Audit Log
        AuditLog::create([
            'user_id' => Auth::id(),
            'user_name' => Auth::user()->name,
            'role' => Auth::user()->role,
            'action' => 'UPDATE_VOTER',
            'description' => "Memperbarui data pemilih {$voter->type} '{$voter->name}' (NIS/NIP: {$voter->identifier}).",
            'ip_address' => $request->ip(),
        ]);

        $typeLabel = $voter->type === 'SISWA' ? 'Siswa' : 'Guru';

        return redirect()->route('admin.users.index', ['tab' => 'pemilih'])
            ->with('success', "Data pemilih {$typeLabel} '{$voter->name}' berhasil diperbarui.");
    }

    /**
     * Hapus Data Pemilih.
     */
    public function destroyVoter(Voter $voter): RedirectResponse
    {
        $typeLabel = $voter->type === 'SISWA' ? 'Siswa' : 'Guru';
        $voterName = $voter->name;
        $voterId = $voter->identifier;

        $voter->delete();

        // Audit Log
        AuditLog::create([
            'user_id' => Auth::id(),
            'user_name' => Auth::user()->name,
            'role' => Auth::user()->role,
            'action' => 'DELETE_VOTER',
            'description' => "Menghapus pemilih {$typeLabel} '{$voterName}' (NIS/NIP: {$voterId}) dari sistem.",
            'ip_address' => request()->ip(),
        ]);

        return redirect()->route('admin.users.index', ['tab' => 'pemilih'])
            ->with('success', "Pemilih {$typeLabel} '{$voterName}' ({$voterId}) berhasil dihapus dari sistem.");
    }
}
