@extends('layouts.admin')

@section('title', 'Edit Event - ' . $event->title)
@section('page_title', 'Edit Informasi Event')
@section('page_description', 'Perbarui judul, deskripsi, atau jadwal waktu pemilihan.')

@section('content')
<div class="max-w-2xl mx-auto bg-slate-900 border border-slate-800 rounded-xl p-6 sm:p-8 shadow-sm">
    <div class="flex items-center justify-between mb-6 pb-4 border-b border-slate-800">
        <div>
            <h3 class="text-base font-bold text-white">Edit Event: {{ $event->title }}</h3>
            <p class="text-xs text-slate-400 mt-0.5">Kode Room: <span class="font-mono text-indigo-400 font-bold">#{{ $event->room_code }}</span></p>
        </div>
        <a href="{{ route('admin.events.show', $event->id) }}" class="text-xs text-slate-400 hover:text-slate-200">
            &larr; Kembali
        </a>
    </div>

    <form action="{{ route('admin.events.update', $event->id) }}" method="POST" class="space-y-5">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5">
                Judul Event Pemilihan <span class="text-rose-400">*</span>
            </label>
            <input
                type="text"
                name="title"
                value="{{ old('title', $event->title) }}"
                required
                class="w-full px-3.5 py-2.5 bg-slate-950 border border-slate-700 rounded-lg text-xs text-white placeholder-slate-500 focus:outline-none focus:ring-1 focus:ring-indigo-500"
            >
        </div>

        <div>
            <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5">
                Kategori Pemilihan <span class="text-rose-400">*</span>
            </label>
            <div class="grid grid-cols-2 gap-3">
                <label class="p-3 bg-slate-950 border border-slate-700 rounded-lg flex items-start gap-3 cursor-pointer hover:border-slate-600 transition-colors">
                    <input type="radio" name="category" value="KANDIDAT" {{ old('category', $event->category) === 'KANDIDAT' ? 'checked' : '' }} class="mt-0.5 text-indigo-600 focus:ring-indigo-500">
                    <div>
                        <div class="text-xs font-bold text-white">Kandidat Paslon</div>
                        <div class="text-[11px] text-slate-400 mt-0.5">Pemilihan orang/figur dengan foto & visi-misi.</div>
                    </div>
                </label>

                <label class="p-3 bg-slate-950 border border-slate-700 rounded-lg flex items-start gap-3 cursor-pointer hover:border-slate-600 transition-colors">
                    <input type="radio" name="category" value="OPSI" {{ old('category', $event->category) === 'OPSI' ? 'checked' : '' }} class="mt-0.5 text-indigo-600 focus:ring-indigo-500">
                    <div>
                        <div class="text-xs font-bold text-white">Polling Aspirasi / Opsi</div>
                        <div class="text-[11px] text-slate-400 mt-0.5">Pemilihan opsi keputusan/kegiatan.</div>
                    </div>
                </label>
            </div>
        </div>

        <div>
            <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5">
                Deskripsi / Petunjuk Pemilihan
            </label>
            <textarea
                name="description"
                rows="3"
                class="w-full px-3.5 py-2.5 bg-slate-950 border border-slate-700 rounded-lg text-xs text-white placeholder-slate-500 focus:outline-none focus:ring-1 focus:ring-indigo-500"
            >{{ old('description', $event->description) }}</textarea>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5">
                    Waktu Mulai Pemilihan
                </label>
                <input
                    type="datetime-local"
                    name="start_time"
                    value="{{ old('start_time', $event->start_time ? $event->start_time->format('Y-m-d\TH:i') : '') }}"
                    class="w-full px-3.5 py-2.5 bg-slate-950 border border-slate-700 rounded-lg text-xs text-white placeholder-slate-500 focus:outline-none focus:ring-1 focus:ring-indigo-500"
                >
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5">
                    Waktu Berakhir Pemilihan
                </label>
                <input
                    type="datetime-local"
                    name="end_time"
                    value="{{ old('end_time', $event->end_time ? $event->end_time->format('Y-m-d\TH:i') : '') }}"
                    class="w-full px-3.5 py-2.5 bg-slate-950 border border-slate-700 rounded-lg text-xs text-white placeholder-slate-500 focus:outline-none focus:ring-1 focus:ring-indigo-500"
                >
            </div>
        </div>

        <div class="pt-4 border-t border-slate-800 flex items-center justify-end gap-3">
            <a href="{{ route('admin.events.show', $event->id) }}" class="px-4 py-2.5 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-lg text-xs font-medium transition-colors">
                Batal
            </a>
            <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-xs font-semibold transition-colors cursor-pointer">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection
