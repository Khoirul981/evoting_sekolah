@extends('layouts.admin')

@section('title', 'Manajemen Super Admin')
@section('page_title', 'Manajemen Super Admin')
@section('page_description', 'Kelola akun administrator level tertinggi dengan hak akses penuh sistem.')

@section('content')
<div class="space-y-6">

    <!-- Header Actions & Search -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-slate-900 border border-slate-800 rounded-xl p-4">
        <!-- Search -->
        <form method="GET" action="{{ route('admin.super-admins.index') }}" class="flex items-center gap-2 flex-1 max-w-md">
            <div class="relative flex-1">
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Cari nama atau email Super Admin..."
                    class="w-full pl-9 pr-4 py-2 bg-slate-950 border border-slate-700 rounded-lg text-xs text-white placeholder-slate-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500"
                >
                <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            <button type="submit" class="px-3 py-2 bg-slate-800 hover:bg-slate-700 text-slate-200 rounded-lg text-xs font-medium transition-colors border border-slate-700">
                Cari
            </button>
            @if(request('search'))
                <a href="{{ route('admin.super-admins.index') }}" class="px-2.5 py-2 text-slate-400 hover:text-slate-200 text-xs">Reset</a>
            @endif
        </form>

        <!-- Tambah Button -->
        <button
            type="button"
            onclick="document.getElementById('modalTambah').classList.remove('hidden')"
            class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-xs font-semibold flex items-center justify-center gap-2 transition-colors cursor-pointer"
        >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            <span>Tambah Super Admin</span>
        </button>
    </div>

    <!-- Table Card -->
    <div class="bg-slate-900 border border-slate-800 rounded-xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-300">
                <thead class="bg-slate-950/60 uppercase text-[11px] font-semibold text-slate-400 border-b border-slate-800">
                    <tr>
                        <th class="py-3.5 px-4">Nama Lengkap</th>
                        <th class="py-3.5 px-4">Alamat Email</th>
                        <th class="py-3.5 px-4">Role Sistem</th>
                        <th class="py-3.5 px-4">Tanggal Dibuat</th>
                        <th class="py-3.5 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60">
                    @forelse($superAdmins as $admin)
                        <tr class="hover:bg-slate-800/40 transition-colors">
                            <td class="py-3.5 px-4 font-medium text-white">
                                {{ $admin->name }}
                                @if(auth()->id() === $admin->id)
                                    <span class="ml-2 px-2 py-0.5 rounded text-[10px] font-semibold bg-emerald-950 text-emerald-400 border border-emerald-800">
                                        Akun Anda
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-slate-300 font-mono">
                                {{ $admin->email }}
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-indigo-950 text-indigo-400 border border-indigo-800">
                                    SUPER_ADMIN
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-slate-400">
                                {{ $admin->created_at->format('d/m/Y') }}
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button
                                        type="button"
                                        onclick="openEditModal({{ $admin->id }}, '{{ addslashes($admin->name) }}', '{{ addslashes($admin->email) }}')"
                                        class="px-2.5 py-1 bg-slate-800 hover:bg-slate-700 text-slate-200 rounded text-[11px] font-medium transition-colors border border-slate-700"
                                    >
                                        Edit
                                    </button>

                                    @if(auth()->id() !== $admin->id)
                                        <form action="{{ route('admin.super-admins.destroy', $admin->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus Super Admin \'{{ $admin->name }}\'?')">
                                            @csrf
                                            @method('DELETE')
                                            <button
                                                type="submit"
                                                class="px-2.5 py-1 bg-rose-950/60 hover:bg-rose-900 text-rose-300 rounded text-[11px] font-medium transition-colors border border-rose-800"
                                            >
                                                Hapus
                                            </button>
                                        </form>
                                    @else
                                        <button
                                            type="button"
                                            disabled
                                            title="Tidak dapat menghapus akun Anda sendiri"
                                            class="px-2.5 py-1 bg-slate-800/50 text-slate-500 rounded text-[11px] font-medium cursor-not-allowed border border-slate-800"
                                        >
                                            Hapus
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-slate-500">
                                Tidak ada data Super Admin yang sesuai.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($superAdmins->hasPages())
            <div class="p-4 border-t border-slate-800">
                {{ $superAdmins->links() }}
            </div>
        @endif
    </div>

</div>

<!-- Modal Tambah Super Admin -->
<div id="modalTambah" class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 p-4 hidden">
    <div class="bg-slate-900 border border-slate-800 rounded-xl max-w-md w-full p-6 shadow-xl relative">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-base font-bold text-white">Tambah Super Admin Baru</h3>
            <button type="button" onclick="document.getElementById('modalTambah').classList.add('hidden')" class="text-slate-400 hover:text-white">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <form action="{{ route('admin.super-admins.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-slate-300 mb-1">Nama Lengkap</label>
                <input type="text" name="name" required class="w-full px-3 py-2 bg-slate-950 border border-slate-700 rounded-lg text-xs text-white placeholder-slate-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 mb-1">Alamat Email</label>
                <input type="email" name="email" required class="w-full px-3 py-2 bg-slate-950 border border-slate-700 rounded-lg text-xs text-white placeholder-slate-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 mb-1">Kata Sandi (Min. 8 Karakter)</label>
                <input type="password" name="password" required minlength="8" class="w-full px-3 py-2 bg-slate-950 border border-slate-700 rounded-lg text-xs text-white placeholder-slate-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
            </div>

            <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-800">
                <button type="button" onclick="document.getElementById('modalTambah').classList.add('hidden')" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-lg text-xs font-medium">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-xs font-semibold">
                    Simpan Super Admin
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Super Admin -->
<div id="modalEdit" class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 p-4 hidden">
    <div class="bg-slate-900 border border-slate-800 rounded-xl max-w-md w-full p-6 shadow-xl relative">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-base font-bold text-white">Edit Data Super Admin</h3>
            <button type="button" onclick="document.getElementById('modalEdit').classList.add('hidden')" class="text-slate-400 hover:text-white">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <form id="formEdit" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-xs font-semibold text-slate-300 mb-1">Nama Lengkap</label>
                <input type="text" id="editName" name="name" required class="w-full px-3 py-2 bg-slate-950 border border-slate-700 rounded-lg text-xs text-white focus:outline-none focus:ring-1 focus:ring-indigo-500">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 mb-1">Alamat Email</label>
                <input type="email" id="editEmail" name="email" required class="w-full px-3 py-2 bg-slate-950 border border-slate-700 rounded-lg text-xs text-white focus:outline-none focus:ring-1 focus:ring-indigo-500">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 mb-1">Kata Sandi Baru (Kosongkan jika tidak diubah)</label>
                <input type="password" name="password" minlength="8" placeholder="••••••••" class="w-full px-3 py-2 bg-slate-950 border border-slate-700 rounded-lg text-xs text-white placeholder-slate-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
            </div>

            <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-800">
                <button type="button" onclick="document.getElementById('modalEdit').classList.add('hidden')" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-lg text-xs font-medium">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-xs font-semibold">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function openEditModal(id, name, email) {
        document.getElementById('formEdit').action = '/admin/super-admins/' + id;
        document.getElementById('editName').value = name;
        document.getElementById('editEmail').value = email;
        document.getElementById('modalEdit').classList.remove('hidden');
    }
</script>
@endpush
@endsection
