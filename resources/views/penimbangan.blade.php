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

    @php
    // Persiapan Data Balita untuk di-plot ke grafik
    $riwayatLine = $riwayat->sortBy('tanggal_penimbangan')->map(function($r){
        return [
            'umur' => (int) $r->umur_bulan,
            'bb'   => (float) $r->berat_badan,
            'tb'   => (float) $r->tinggi_badan,
        ];
    })->values();
    @endphp

    <!-- Container Utama Grafik -->
    <div class="mb-6 space-y-6">
        <div class="text-sm font-semibold text-black uppercase tracking-widest bg-white p-4 border border-slate-200 rounded-2xl shadow-sm">
            📈 Grafik Pemantauan Pertumbuhan (Standar Permenkes & KMS) - {{ $balita->nama_balita }}
        </div>

        <!-- GRAFIK 1: BB/U -->
        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-5">
            <div class="mb-4">
                <h3 class="text-md font-bold text-slate-800">1. Grafik Berat Badan menurut Umur (BB/U)</h3>
                <p class="text-xs text-slate-500">Mendeteksi Risiko Gizi Kurang / Gizi Buruk</p>
            </div>
            <div class="relative h-80 w-full">
                <canvas id="chartBBU"></canvas>
            </div>
        </div>

        <!-- GRAFIK 2: TB/U -->
        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-5">
            <div class="mb-4">
                <h3 class="text-md font-bold text-slate-800">2. Grafik Tinggi Badan menurut Umur (TB/U)</h3>
                <p class="text-xs text-slate-500">Mendeteksi Indikasi Stunting (Pendek / Sangat Pendek)</p>
            </div>
            <div class="relative h-80 w-full">
                <canvas id="chartTBU"></canvas>
            </div>
        </div>

        <!-- GRAFIK 3: BB/TB -->
        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-5">
            <div class="mb-4">
                <h3 class="text-md font-bold text-slate-800">3. Grafik Berat Badan menurut Tinggi Badan (BB/TB)</h3>
                <p class="text-xs text-slate-500">Mendeteksi Proporsi Tubuh (Gizi Buruk / Obesitas)</p>
            </div>
            <div class="relative h-80 w-full">
                <canvas id="chartBBTB"></canvas>
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
    <!-- Pastikan library Chart.js sudah dipanggil -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // 1. Tangkap data dari Controller (Sintaks Blade Laravel)
    const jenisKelamin = "{{ strtolower($balita->jenis_kelamin) }}";
    const dataRiwayat = @json($riwayat);

    // 2. Format titik-titik data balita dari database (Sesuaikan dengan nama kolom DB)
    const titikBBU = dataRiwayat.map(d => ({ x: parseFloat(d.umur_bulan), y: parseFloat(d.berat_badan) }));
    const titikTBU = dataRiwayat.map(d => ({ x: parseFloat(d.umur_bulan), y: parseFloat(d.tinggi_badan) }));
    const titikBBTB = dataRiwayat.map(d => ({ x: parseFloat(d.tinggi_badan), y: parseFloat(d.berat_badan) }));

    // 3. Label Sumbu X (Umur & Tinggi Badan)
    const labelUmurBulan = [0, 6, 12, 18, 24, 36, 48, 60]; 
    const labelTinggiCm = [45, 55, 65, 75, 85, 95, 105, 110];

    // 4. Siapkan Variabel Standar Permenkes
    let bbu_min3sd, bbu_min2sd, bbu_median, bbu_plus1sd;
    let tbu_min3sd, tbu_min2sd, tbu_median, tbu_plus3sd;
    let bbtb_min3sd, bbtb_min2sd, bbtb_median, bbtb_plus1sd, bbtb_plus2sd, bbtb_plus3sd;

    // 5. Cek Jenis Kelamin & Isi Data Standar (GANTI ANGKA INI DENGAN TABEL PDF PERMENKES)
    if (jenisKelamin === 'laki-laki' || jenisKelamin === 'l') {
        // --- DATA LAKI-LAKI ---
        // BB/U
        bbu_min3sd = [2.1, 5.7, 6.8, 7.7, 8.4, 9.8, 11.0, 12.1];
        bbu_min2sd = [2.5, 6.4, 7.7, 8.8, 9.7, 11.3, 12.7, 14.1];
        bbu_median = [3.3, 7.9, 9.6, 10.9, 12.2, 14.3, 16.3, 18.3];
        bbu_plus1sd = [3.9, 8.8, 10.8, 12.2, 13.6, 16.0, 18.3, 20.5];

        // TB/U
        tbu_min3sd = [44.2, 61.2, 68.6, 74.2, 78.0, 85.0, 90.7, 96.1];
        tbu_min2sd = [46.1, 63.3, 71.0, 76.9, 81.0, 88.7, 94.9, 100.7];
        tbu_median = [49.9, 67.6, 75.7, 82.3, 87.1, 96.1, 103.3, 110.0];
        tbu_plus3sd = [53.7, 71.9, 80.4, 87.7, 93.2, 103.2, 111.7, 119.3];

        // BB/TB
        bbtb_min3sd = [1.9, 3.5, 5.7, 7.6, 9.4, 11.2, 13.1, 14.4];
        bbtb_min2sd = [2.0, 3.8, 6.2, 8.2, 10.2, 12.2, 14.3, 15.6];
        bbtb_median = [2.4, 4.6, 7.3, 9.6, 12.0, 14.3, 16.8, 18.3];
        bbtb_plus1sd = [2.7, 5.1, 8.0, 10.5, 13.1, 15.6, 18.4, 20.0];
        bbtb_plus2sd = [3.0, 5.6, 8.8, 11.5, 14.3, 17.0, 20.1, 21.9];
        bbtb_plus3sd = [3.3, 6.2, 9.7, 12.6, 15.6, 18.5, 21.9, 23.9];

    } else {
        // --- DATA PEREMPUAN ---
        // BB/U
        bbu_min3sd = [2.0, 5.3, 6.3, 7.2, 8.1, 9.6, 10.9, 11.9];
        bbu_min2sd = [2.4, 5.8, 7.0, 8.1, 9.0, 10.8, 12.1, 13.7];
        bbu_median = [3.2, 7.3, 8.9, 10.2, 11.5, 13.9, 16.1, 18.2];
        bbu_plus1sd = [3.7, 8.2, 10.1, 11.6, 13.0, 15.8, 18.2, 20.5];

        // TB/U
        tbu_min3sd = [43.6, 59.1, 66.3, 72.0, 76.0, 83.6, 89.9, 95.2];
        tbu_min2sd = [45.4, 61.2, 68.9, 74.9, 79.3, 87.4, 94.1, 99.9];
        tbu_median = [49.1, 65.7, 74.0, 80.7, 85.7, 95.1, 102.7, 109.4];
        tbu_plus3sd = [52.8, 70.2, 79.1, 86.5, 92.1, 102.8, 111.3, 118.9];

        // BB/TB
        bbtb_min3sd = [1.9, 3.4, 5.5, 7.4, 9.2, 11.0, 12.9, 14.2];
        bbtb_min2sd = [2.0, 3.8, 6.0, 8.0, 10.1, 12.0, 14.1, 15.4];
        bbtb_median = [2.4, 4.5, 7.0, 9.4, 11.8, 14.0, 16.5, 18.2];
        bbtb_plus1sd = [2.7, 5.1, 7.8, 10.4, 13.0, 15.4, 18.2, 20.1];
        bbtb_plus2sd = [3.0, 5.7, 8.7, 11.6, 14.4, 17.0, 20.1, 22.2];
        bbtb_plus3sd = [3.4, 6.4, 9.7, 12.9, 16.0, 18.8, 22.3, 24.6];
    }

    // 6. Siapkan Dataset untuk Chart.js (Sesuai dengan tabel gambar)
    const datasetPermenkesBBU = [
        { label: '> +1 SD (Risiko Berat badan lebih)', data: bbu_plus1sd, borderColor: '#facc15', type: 'line', fill: false, tension: 0.4 },
        { label: 'Median (Berat badan normal)', data: bbu_median, borderColor: '#22c55e', borderDash: [5, 5], type: 'line', fill: false, tension: 0.4 },
        { label: '< -2 SD (Berat badan kurang / underweight)', data: bbu_min2sd, borderColor: '#f97316', type: 'line', fill: false, tension: 0.4 },
        { label: '< -3 SD (Berat badan sangat kurang / severely underweight)', data: bbu_min3sd, borderColor: '#ef4444', type: 'line', fill: false, tension: 0.4 }
    ];

    const datasetPermenkesTBU = [
        { label: '> +3 SD (Tinggi)', data: tbu_plus3sd, borderColor: '#a855f7', type: 'line', fill: false, tension: 0.4 },
        { label: 'Median (Normal)', data: tbu_median, borderColor: '#22c55e', borderDash: [5, 5], type: 'line', fill: false, tension: 0.4 },
        { label: '< -2 SD (Pendek / stunted)', data: tbu_min2sd, borderColor: '#f97316', type: 'line', fill: false, tension: 0.4 },
        { label: '< -3 SD (Sangat pendek / severely stunted)', data: tbu_min3sd, borderColor: '#ef4444', type: 'line', fill: false, tension: 0.4 }
    ];

    const datasetPermenkesBBTB = [
        { label: '> +3 SD (Obesitas / obese)', data: bbtb_plus3sd, borderColor: '#ef4444', type: 'line', fill: false, tension: 0.4 },
        { label: '> +2 SD (Gizi lebih / overweight)', data: bbtb_plus2sd, borderColor: '#f97316', type: 'line', fill: false, tension: 0.4 },
        { label: '> +1 SD (Berisiko gizi lebih / possible risk of overweight)', data: bbtb_plus1sd, borderColor: '#facc15', type: 'line', fill: false, tension: 0.4 },
        { label: 'Median (Gizi baik / normal)', data: bbtb_median, borderColor: '#22c55e', borderDash: [5, 5], type: 'line', fill: false, tension: 0.4 },
        { label: '< -2 SD (Gizi kurang / wasted)', data: bbtb_min2sd, borderColor: '#f97316', type: 'line', fill: false, tension: 0.4 },
        { label: '< -3 SD (Gizi buruk / severely wasted)', data: bbtb_min3sd, borderColor: '#ef4444', type: 'line', fill: false, tension: 0.4 }
    ];

    // 7. Fungsi untuk Render Grafik
    function renderChart(canvasId, titleX, titleY, labelsData, datasetsStandar, dataTitikBalita) {
        const canvas = document.getElementById(canvasId);
        if (!canvas) return; 

        new Chart(canvas, {
            data: {
                labels: labelsData,
                datasets: [
                    {
                        type: 'scatter',
                        label: 'Pertumbuhan {{ $balita->nama_balita }}',
                        data: dataTitikBalita,
                        backgroundColor: '#3b82f6', 
                        borderColor: '#2563eb',
                        pointRadius: 6,
                        pointHoverRadius: 8,
                        order: 0 // Scatter selalu di atas
                    },
                    ...datasetsStandar.map(ds => ({ ...ds, order: 1 })) // Garis Z-Score selalu di bawah
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: { title: { display: true, text: titleX } },
                    y: { title: { display: true, text: titleY } }
                },
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { boxWidth: 12 }
                    }
                }
            }
        });
    }

    // 8. Panggil Fungsi Render setelah halaman dimuat
    window.onload = function() {
        renderChart('chartBBU', 'Umur (Bulan)', 'Berat Badan (kg)', labelUmurBulan, datasetPermenkesBBU, titikBBU);
        renderChart('chartTBU', 'Umur (Bulan)', 'Tinggi Badan (cm)', labelUmurBulan, datasetPermenkesTBU, titikTBU);
        renderChart('chartBBTB', 'Tinggi Badan (cm)', 'Berat Badan (kg)', labelTinggiCm, datasetPermenkesBBTB, titikBBTB);
    };
</script>
@endsection