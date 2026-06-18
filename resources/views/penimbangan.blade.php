@extends('layouts.app')
@section('title', 'Penimbangan Balita')

@section('content')
    <div class="mb-6">
        <div class="text-xs font-semibold tracking-widest text-slate-400">PENIMBANGAN</div>
        <div class="text-2xl font-semibold text-slate-900">Penimbangan Balita</div>
    </div>

    @if(isset($error))
        <div class="mb-4 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            <span class="font-semibold">Error:</span> {{ $error }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-6">

        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-5">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <div class="text-sm font-semibold text-black">Data Balita</div>
                    <div class="text-xs text-slate-500">Informasi dasar balita</div>
                </div>

                <a href="{{ url('/dashboard') }}"
                   class="text-xs font-semibold text-slate-600 hover:text-slate-900">
                    ← Kembali
                </a>
            </div>

            <div class="grid grid-cols-2 gap-4 text-sm">
                <div class="col-span-2">
                    <div class="text-xs text-slate-500">Nama</div>
                    <div class="font-semibold text-slate-900">{{ $balita->nama_balita }}</div>
                </div>

                <div>
                    <div class="text-xs text-slate-500">Jenis Kelamin</div>
                    <div class="font-medium text-slate-900">{{ $balita->jenis_kelamin ?? '-' }}</div>
                </div>

                <div>
                    <div class="text-xs text-slate-500">Tanggal Lahir</div>
                    <div class="font-medium text-slate-900">{{ $balita->tanggal_lahir }}</div>
                </div>
            </div>

            <div class="mt-4 rounded-2xl border border-slate-200 bg-slate-50 p-4">
                <div class="text-xs text-slate-500">Umur saat tanggal penimbangan</div>
                <div class="mt-1 text-2xl font-bold text-slate-900">
                    <span id="umur_bulan_text">-</span>
                    <span class="text-sm font-semibold text-slate-500">bulan</span>
                </div>
            </div>
        </div>

        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-5">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <div class="text-sm font-semibold text-black">Form Penimbangan</div>
                    <div class="text-xs text-slate-500">Masukkan BB & TB untuk prediksi status gizi</div>
                </div>

                <button type="submit" form="formPrediksi"
                        class="inline-flex items-center gap-2 px-5 py-2.5 rounded-2xl bg-[#E85C4A] text-white text-sm font-semibold shadow-md hover:bg-[#d94f3e] hover:shadow-lg transition">
                    Prediksi
                </button>
            </div>

            <form id="formPrediksi" method="POST" action="{{ url('/balita/'.$balita->id.'/prediksi') }}" class="space-y-4">
                @csrf

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs font-medium text-slate-600">Tanggal Penimbangan</label>
                        <input type="date"
                               name="tanggal_penimbangan"
                               value="{{ $input['tanggal_penimbangan'] ?? date('Y-m-d') }}"
                               required
                               class="mt-1 w-full px-4 py-2.5 rounded-2xl border border-slate-200 bg-slate-50 focus:outline-none focus:ring-2 focus:ring-orange-200">
                    </div>

                    <div>
                        <label class="text-xs font-medium text-slate-600">Umur (bulan)</label>
                        <input type="number" id="umur_bulan" readonly
                               class="mt-1 w-full px-4 py-2.5 rounded-2xl border border-slate-200 bg-slate-100 text-slate-600">
                    </div>
                </div>

                <div>
                    <label class="text-xs font-medium text-slate-600">Berat Badan (kg)</label>
                    <input type="number" step="0.1" name="berat_badan"
                           value="{{ $input['berat_badan'] ?? '' }}"
                           required
                           class="mt-1 w-full px-4 py-2.5 rounded-2xl border border-slate-200 bg-slate-50 focus:outline-none focus:ring-2 focus:ring-orange-200"
                           placeholder="contoh: 12.3">
                </div>

                <div>
                    <label class="text-xs font-medium text-slate-600">Tinggi Badan (cm)</label>
                    <input type="number" step="0.1" name="tinggi_badan"
                           value="{{ $input['tinggi_badan'] ?? '' }}"
                           required
                           class="mt-1 w-full px-4 py-2.5 rounded-2xl border border-slate-200 bg-slate-50 focus:outline-none focus:ring-2 focus:ring-orange-200"
                           placeholder="contoh: 86">
                </div>
            </form>

            @if(isset($hasil_bbu) && isset($hasil_tbu) && isset($hasil_bbtb))
                <div class="mt-5 rounded-2xl border border-slate-200 bg-white p-5 space-y-4 shadow-sm">
                    <div class="text-sm font-semibold text-slate-900 border-b border-slate-100 pb-2 flex items-center gap-2">
                        <span class="flex h-2 w-2 rounded-full bg-green-500"></span>
                        Hasil Klasifikasi XGBoost 
                    </div>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-center">
                        <div class="p-3 rounded-xl border {{ Str::contains(strtolower($hasil_bbu), 'normal') ? 'bg-green-50 border-green-200 text-green-800' : 'bg-red-50 border-red-200 text-red-800' }}">
                            <div class="text-[10px] uppercase tracking-widest font-bold opacity-70">BB/U (Berat / Umur)</div>
                            <div class="mt-1 text-sm font-extrabold">{{ $hasil_bbu }}</div>
                        </div>

                        <div class="p-3 rounded-xl border {{ Str::contains(strtolower($hasil_tbu), 'normal') ? 'bg-green-50 border-green-200 text-green-800' : 'bg-red-50 border-red-200 text-red-800' }}">
                            <div class="text-[10px] uppercase tracking-widest font-bold opacity-70">TB/U (Tinggi / Umur)</div>
                            <div class="mt-1 text-sm font-extrabold">{{ $hasil_tbu }}</div>
                        </div>

                        <div class="p-3 rounded-xl border {{ Str::contains(strtolower($hasil_bbtb), 'gizi baik') || Str::contains(strtolower($hasil_bbtb), 'normal') ? 'bg-green-50 border-green-200 text-green-800' : 'bg-red-50 border-red-200 text-red-800' }}">
                            <div class="text-[10px] uppercase tracking-widest font-bold opacity-70">BB/TB (Status Nutrisi)</div>
                            <div class="mt-1 text-sm font-extrabold">{{ $hasil_bbtb }}</div>
                        </div>
                    </div>

                    <form method="POST" action="{{ url('/balita/'.$balita->id.'/simpan') }}" class="mt-2">
                        @csrf
                        <input type="hidden" name="tanggal_penimbangan" value="{{ $input['tanggal_penimbangan'] }}">
                        <input type="hidden" name="berat_badan" value="{{ $input['berat_badan'] }}">
                        <input type="hidden" name="tinggi_badan" value="{{ $input['tinggi_badan'] }}">
                        
                        <input type="hidden" name="status_bbu" value="{{ $hasil_bbu }}">
                        <input type="hidden" name="status_tbu" value="{{ $hasil_tbu }}">
                        <input type="hidden" name="status_bbtb" value="{{ $hasil_bbtb }}">

                        <button type="submit"
                                class="w-full inline-flex items-center justify-center px-6 py-3 rounded-xl bg-blue-600 text-white text-sm font-semibold hover:bg-blue-700 transition shadow-md">
                            Simpan Hasil Penimbangan
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </div>

    @php
    $riwayatLine = $riwayat->sortBy('tanggal_penimbangan')->map(function($r){
        return [
        'umur' => (int) $r->umur_bulan,
        'bb'   => (float) $r->berat_badan,
        'tb'   => (float) $r->tinggi_badan,
        'tgl'  => (string) $r->tanggal_penimbangan,
        ];
    })->values();
    @endphp

    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm mb-6">
        <div class="p-5 flex items-start justify-between gap-4">
            <div>
                <div class="text-sm font-semibold text-black">Grafik Perkembangan</div>
                <div class="text-xs text-slate-500">Riwayat BB & TB berdasarkan umur (bulan)</div>
            </div>

            <div class="text-xs font-semibold text-slate-600">
                {{ $balita->nama_balita }}
            </div>
        </div>

        <div class="px-5 pb-5">
            <div class="h-80">
                <canvas id="riwayatChart" data-points='@json($riwayatLine)'></canvas>
            </div>
        </div>
    </div>

    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm">
        <div class="p-5">
            <div class="text-sm font-semibold text-black">Riwayat Penimbangan</div>
            <div class="text-xs text-slate-500">Data penimbangan balita yang sudah tersimpan</div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-slate-200 bg-slate-50 text-left">
                        <th class="px-5 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">Tanggal</th>
                        <th class="px-5 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">Umur (Bln)</th>
                        <th class="px-5 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">Berat</th>
                        <th class="px-5 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">Tinggi</th>
                        <th class="px-5 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">Status BB/U</th>
                        <th class="px-5 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">Status TB/U</th>
                        <th class="px-5 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">Status BB/TB</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-200">
                    @forelse($riwayat as $r)
                        <tr class="hover:bg-slate-50">
                            <td class="px-5 py-3 whitespace-nowrap">{{ $r->tanggal_penimbangan }}</td>
                            <td class="px-5 py-3">{{ $r->umur_bulan }}</td>
                            <td class="px-5 py-3 font-medium text-slate-900">{{ $r->berat_badan }} kg</td>
                            <td class="px-5 py-3 font-medium text-slate-900">{{ $r->tinggi_badan }} cm</td>
                            <td class="px-5 py-3"><span class="px-2 py-1 rounded text-xs bg-slate-100 font-medium text-slate-700">{{ $r->status_bbu ?? '-' }}</span></td>
                            <td class="px-5 py-3"><span class="px-2 py-1 rounded text-xs bg-slate-100 font-medium text-slate-700">{{ $r->status_tbu ?? '-' }}</span></td>
                            <td class="px-5 py-3"><span class="px-2 py-1 rounded text-xs bg-slate-100 font-medium text-slate-700">{{ $r->status_bbtb ?? '-' }}</span></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-6 text-center text-slate-500">
                                Belum ada riwayat penimbangan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <script>
        const tanggalLahir = new Date("{{ $balita->tanggal_lahir }}");
        const tanggalInput = document.querySelector('input[name="tanggal_penimbangan"]');
        const umurField = document.getElementById('umur_bulan');
        const umurText = document.getElementById('umur_bulan_text');

        function hitungUmurBulan() {
            const tanggalPenimbangan = new Date(tanggalInput.value);
            if (!tanggalInput.value) return;

            let bulan = (tanggalPenimbangan.getFullYear() - tanggalLahir.getFullYear()) * 12;
            bulan += tanggalPenimbangan.getMonth() - tanggalLahir.getMonth();

            if (bulan < 0) bulan = 0;

            umurField.value = bulan;
            umurText.textContent = bulan;
        }

        tanggalInput.addEventListener('change', hitungUmurBulan);

        if (tanggalInput.value) {
            hitungUmurBulan();
        }
    </script>
@endsection