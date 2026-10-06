@extends('layouts.voter')

@section('title', 'Bilik Suara Pemilih')

@section('content')
<div class="w-full max-w-md mx-auto space-y-4">

    <!-- Card Ringkasan Identitas Pemilih Terverifikasi (Tema Putih Bersih) -->
    <div class="bg-white border border-slate-200/90 rounded-2xl p-4 sm:p-5 shadow-xs flex items-center justify-between gap-4">
        <div class="flex items-center gap-3.5 min-w-0">
            <div class="w-11 h-11 rounded-xl bg-slate-900 text-white font-extrabold text-base flex items-center justify-center shadow-xs shrink-0">
                {{ strtoupper(substr($voter->name, 0, 1)) }}
            </div>
            <div class="min-w-0 flex-1">
                <div class="flex items-center gap-1.5 flex-wrap">
                    <span class="text-[10px] font-bold px-2 py-0.5 rounded uppercase border {{ $voter->type === 'GURU' ? 'bg-orange-950/90 text-orange-400 border-orange-700/70' : 'bg-cyan-950/80 text-cyan-300 border-cyan-700/60' }}">
                        {{ $voter->type }}
                    </span>
                </div>
                <h1 class="text-sm font-bold text-slate-900 truncate mt-1">
                    {{ $voter->name }}
                </h1>
                <p class="text-xs text-slate-500 font-mono font-medium">
                    NIS/NIP: <span class="text-slate-800 font-bold">{{ $voter->identifier }}</span>
                </p>
            </div>
        </div>
    </div>

    <!-- Card Utama: Masukkan Kode Room (Tema Putih Bersih, Centered) -->
    <div class="bg-white border border-slate-200/90 rounded-2xl p-6 sm:p-7 shadow-sm text-center">
        <h2 class="text-xl font-bold text-slate-900 tracking-tight">
            Ketik Kode Room
        </h2>
        <p class="text-xs text-slate-600 mt-1 max-w-xs mx-auto">
            Masukkan kode ruangan yang diberikan oleh petugas pemilihan event untuk membuka bilik suara.
        </p>

        <!-- Form Input Kode Room -->
        <form action="{{ route('voter.join.post') }}" method="POST" class="mt-6 space-y-4">
            @csrf

            <div>
                <input
                    type="text"
                    id="room_code_input"
                    name="room_code"
                    value="{{ old('room_code') }}"
                    placeholder="Contoh: OSIS26"
                    maxlength="20"
                    required
                    autofocus
                    autocomplete="off"
                    class="w-full py-3.5 px-4 bg-slate-50 border-2 @error('room_code') border-rose-500 @else border-slate-300 hover:border-slate-400 @enderror focus:outline-none focus:bg-white focus:border-slate-900 focus:ring-4 focus:ring-slate-900/10 rounded-xl text-center text-slate-900 placeholder:text-slate-400 text-2xl font-mono font-bold tracking-widest uppercase transition-all shadow-inner"
                >

                @error('room_code')
                    <p class="mt-2 text-xs text-rose-600 flex items-center justify-center gap-1 font-medium">
                        <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                        <span>{{ $message }}</span>
                    </p>
                @enderror
            </div>

            <!-- Tombol Konfirmasi Masuk Bilik Suara di Bawah Input -->
            <button
                type="submit"
                class="w-full py-3.5 px-4 bg-slate-900 hover:bg-slate-800 active:bg-black text-white font-semibold rounded-xl text-base shadow-sm transition-all flex items-center justify-center gap-2 cursor-pointer"
            >
                <span>Masuk Bilik Suara</span>
            </button>
        </form>
    </div>

</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const roomInput = document.getElementById('room_code_input');
        if (roomInput) {
            roomInput.addEventListener('input', (e) => {
                e.target.value = e.target.value.toUpperCase();
            });
        }
    });
</script>
@endpush
