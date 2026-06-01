@extends('layouts.app')
@section('title', 'Tambah Balita')

@section('content')
    <div class="mb-6">
        <div class="text-xs font-semibold tracking-widest text-slate-400">DATA BALITA</div>
        <div class="text-2xl font-semibold text-slate-900">Tambah Data Balita</div>
        <div class="mt-1 text-sm text-slate-500">Lengkapi data balita untuk mulai melakukan penimbangan dan prediksi status gizi.</div>
    </div>

    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-200">
            <div class="text-sm font-semibold text-black">Form Balita</div>
            <div class="text-xs text-slate-500">Pastikan tanggal lahir dan jenis kelamin benar.</div>
        </div>

        <form method="POST" action="{{ url('/balita/store') }}" class="p-5">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <div>
                    <label class="block text-sm font-semibold text-slate-800 mb-1">Nama Balita <span class="text-red-500">*</span></label>
                    <input
                        type="text"
                        name="nama_balita"
                        required
                        class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-800 outline-none focus:bg-white focus:ring-2 focus:ring-slate-200 transition"
                        placeholder="Contoh: Malik Ahmad"
                    >
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-800 mb-1">Nama Ibu</label>
                    <input
                        type="text"
                        name="nama_ibu"
                        class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-800 outline-none focus:bg-white focus:ring-2 focus:ring-slate-200 transition"
                        placeholder="Contoh: Siti Rohmah"
                    >
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-800 mb-1">Tanggal Lahir <span class="text-red-500">*</span></label>
                    <input
                        type="date"
                        name="tanggal_lahir"
                        required
                        class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-800 outline-none focus:bg-white focus:ring-2 focus:ring-slate-200 transition"
                    >
                    <div class="mt-1 text-xs text-slate-500">Digunakan untuk menghitung umur (bulan).</div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-800 mb-1">Jenis Kelamin <span class="text-red-500">*</span></label>
                    <select
                        name="jenis_kelamin"
                        required
                        class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-800 outline-none focus:bg-white focus:ring-2 focus:ring-slate-200 transition"
                    >
                        <option value="">-- Pilih --</option>
                        <option value="L">Laki-laki</option>
                        <option value="P">Perempuan</option>
                    </select>
                </div>

            </div>

            <div class="mt-6 flex flex-col sm:flex-row gap-3 sm:items-center sm:justify-between">
                <a href="{{ url('/dashboard') }}"
                   class="inline-flex items-center justify-center rounded-2xl bg-[#E85C4A] px-6 py-3 text-sm font-semibold text-white shadow-md hover:bg-[#d94f3e] hover:shadow-lg transition">
                    ← Kembali
                </a>

                <button type="submit"
                        class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-2.5 rounded-2xl bg-blue-600 text-white text-sm font-semibold hover:bg-blue-700 transition">
                    Simpan Data
                </button>
            </div>
        </form>
    </div>
@endsection