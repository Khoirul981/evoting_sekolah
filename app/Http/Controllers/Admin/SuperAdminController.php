<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class SuperAdminController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'SUPER_ADMIN');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $superAdmins = $query->latest()->paginate(10)->withQueryString();

        return view('admin.super-admins.index', compact('superAdmins'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'SUPER_ADMIN',
        ]);

        AuditLog::record(
            'CREATE_SUPER_ADMIN',
            "Membuat akun Super Admin baru: '{$user->name}' ({$user->email})"
        );

        return redirect()->route('admin.super-admins.index')
            ->with('success', "Akun Super Admin '{$user->name}' berhasil ditambahkan.");
    }

    public function update(Request $request, User $super_admin)
    {
        // Pastikan role target adalah SUPER_ADMIN
        if ($super_admin->role !== 'SUPER_ADMIN') {
            abort(404);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($super_admin->id)],
            'password' => ['nullable', 'string', 'min:8'],
        ]);

        $super_admin->name = $validated['name'];
        $super_admin->email = $validated['email'];

        if (!empty($validated['password'])) {
            $super_admin->password = Hash::make($validated['password']);
        }

        $super_admin->save();

        AuditLog::record(
            'UPDATE_SUPER_ADMIN',
            "Memperbarui data Super Admin: '{$super_admin->name}' ({$super_admin->email})"
        );

        return redirect()->route('admin.super-admins.index')
            ->with('success', "Data Super Admin '{$super_admin->name}' berhasil diperbarui.");
    }

    public function destroy(User $super_admin)
    {
        // Proteksi: Tidak dapat menghapus akun diri sendiri
        if (auth()->id() === $super_admin->id) {
            return redirect()->route('admin.super-admins.index')
                ->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        if ($super_admin->role !== 'SUPER_ADMIN') {
            abort(404);
        }

        $name = $super_admin->name;
        $email = $super_admin->email;

        $super_admin->delete();

        AuditLog::record(
            'DELETE_SUPER_ADMIN',
            "Menghapus akun Super Admin: '{$name}' ({$email})"
        );

        return redirect()->route('admin.super-admins.index')
            ->with('success', "Akun Super Admin '{$name}' berhasil dihapus.");
    }
}
