<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class PetugasController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'PETUGAS');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $petugasList = $query->latest()->paginate(10)->withQueryString();

        return view('admin.petugas.index', compact('petugasList'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        $petugas = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'PETUGAS',
        ]);

        AuditLog::record(
            'CREATE_PETUGAS',
            "Membuat akun Petugas baru: '{$petugas->name}' ({$petugas->email})"
        );

        return redirect()->route('admin.petugas.index')
            ->with('success', "Akun Petugas '{$petugas->name}' berhasil ditambahkan.");
    }

    public function update(Request $request, User $petuga)
    {
        if ($petuga->role !== 'PETUGAS') {
            abort(404);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($petuga->id)],
            'password' => ['nullable', 'string', 'min:8'],
        ]);

        $petuga->name = $validated['name'];
        $petuga->email = $validated['email'];

        if (!empty($validated['password'])) {
            $petuga->password = Hash::make($validated['password']);
        }

        $petuga->save();

        AuditLog::record(
            'UPDATE_PETUGAS',
            "Memperbarui data Petugas: '{$petuga->name}' ({$petuga->email})"
        );

        return redirect()->route('admin.petugas.index')
            ->with('success', "Data Petugas '{$petuga->name}' berhasil diperbarui.");
    }

    public function destroy(User $petuga)
    {
        if ($petuga->role !== 'PETUGAS') {
            abort(404);
        }

        $name = $petuga->name;
        $email = $petuga->email;

        $petuga->delete();

        AuditLog::record(
            'DELETE_PETUGAS',
            "Menghapus akun Petugas: '{$name}' ({$email})"
        );

        return redirect()->route('admin.petugas.index')
            ->with('success', "Akun Petugas '{$name}' berhasil dihapus.");
    }
}
