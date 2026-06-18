@extends('layouts.app')
@section('title', 'Dashboard Posyandu Durian')

@section('content')
    <div class="mb-6">
        <div class="text-xs font-semibold tracking-widest text-slate-400">DASHBOARD</div>
        <div class="text-2xl font-semibold">Posyandu Durian 14A</div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-6 items-stretch">

        <!-- FIX: Sisi kiri menggunakan Flexbox Row menyamping agar tidak menyisakan ruang kosong vertikal yang aneh -->
        <div class="lg:col-span-1 flex flex-col gap-4 justify-between h-full">
            <!-- Kartu Total Balita -->
            <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-5 flex-1 flex items-center justify-between">
                <div class="space-y-1">
                    <div class="text-xs font-semibold uppercase tracking-wider text-slate-400">Total Balita</div>
                    <div class="text-3xl font-black text-slate-900 leading-none">{{ $totalBalita }}</div>
                    <div class="text-[11px] font-medium text-green-600 bg-green-50 px-2 py-0.5 rounded-md inline-block">
                        ✓ {{ $totalBalita }} Terdaftar
                    </div>
                </div>
                <div class="p-3 bg-red-50 text-[#E85C4A] rounded-xl">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                    </svg>
                </div>
            </div>

            <!-- Kartu Total Penimbangan -->
            <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-5 flex-1 flex items-center justify-between">
                <div class="space-y-1">
                    <div class="text-xs font-semibold uppercase tracking-wider text-slate-400">Total Penimbangan</div>
                    <div class="text-3xl font-black text-slate-900 leading-none">{{ $totalPenimbangan }}</div>
                    <div class="text-[11px] font-medium text-blue-600 bg-blue-50 px-2 py-0.5 rounded-md inline-block">
                        ✓ {{ $totalPenimbangan }} Tercatat
                    </div>
                </div>
                <div class="p-3 bg-blue-50 text-blue-600 rounded-xl">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 7.5h1.5m-1.5 3h1.5m-7.5 3h7.5m-7.5 3h7.5m3-9h3.375c.621 0 1.125.504 1.125 1.125V18a2.25 2.25 0 0 1-2.25 2.25M16.5 7.5V18a2.25 2.25 0 0 0 2.25 2.25M16.5 7.5V4.875c0-.621-.504-1.125-1.125-1.125H4.125C3.504 3.75 3 4.254 3 4.875V18a2.25 2.25 0 0 0 2.25 2.25h13.5M6 7.5h3v3H6v-3Z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Sisi Kanan: Grafik Distribusi Gizi Balita -->
        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-5 lg:col-span-2 h-full">
            <div class="mb-6">
                <div class="text-sm font-semibold text-black">Sebaran Status Gizi Balita</div>
                <div class="text-xs text-slate-500">Berdasarkan data penimbangan terakhir tiap balita di Posyandu Durian 14A</div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Indikator BB/U -->
                <div class="flex flex-col items-center border-r border-slate-100 last:border-0 pr-2">
                    <div class="text-xs font-bold text-red-600 mb-3 bg-red-50 px-2 py-0.5 rounded-full uppercase tracking-wider">Indikator BB/U</div>
                    <div class="relative h-32 w-32 mb-4">
                        <canvas id="donutBBU" 
                                data-labels='@json($bbuLabels ?? [])' 
                                data-values='@json($bbuValues ?? [])'></canvas>
                        <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                            <div class="text-xl font-bold text-slate-800 leading-none">{{ array_sum($bbuValues ?? [0]) }}</div>
                            <div class="text-[9px] text-slate-400 mt-0.5">Anak</div>
                        </div>
                    </div>
                    <!-- FIX: Keterangan Legenda BB/U Sejajar Vertikal Rapi -->
                    <div class="w-full max-w-[140px] mx-auto space-y-1 text-[11px]">
                        @foreach($bbuLabels ?? [] as $idx => $lbl)
                            <div class="grid grid-cols-12 items-center text-slate-600 border-b border-dashed border-slate-100 pb-0.5 last:border-0">
                                <span class="col-span-10 truncate flex items-center gap-1">
                                    <span class="h-1.5 w-1.5 rounded-full bg-slate-400 inline-block"></span>
                                    {{ $lbl }}
                                </span>
                                <span class="col-span-2 font-bold text-slate-800 text-right">{{ $bbuValues[$idx] }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Indikator TB/U -->
                <div class="flex flex-col items-center border-r border-slate-100 last:border-0 px-2">
                    <div class="text-xs font-bold text-orange-600 mb-3 bg-orange-50 px-2 py-0.5 rounded-full uppercase tracking-wider">Indikator TB/U</div>
                    <div class="relative h-32 w-32 mb-4">
                        <canvas id="donutTBU" 
                                data-labels='@json($tbuLabels ?? [])' 
                                data-values='@json($tbuValues ?? [])'></canvas>
                        <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                            <div class="text-xl font-bold text-slate-800 leading-none">{{ array_sum($tbuValues ?? [0]) }}</div>
                            <div class="text-[9px] text-slate-400 mt-0.5">Anak</div>
                        </div>
                    </div>
                    <!-- FIX: Keterangan Legenda TB/U Sejajar Vertikal Rapi -->
                    <div class="w-full max-w-[140px] mx-auto space-y-1 text-[11px]">
                        @foreach($tbuLabels ?? [] as $idx => $lbl)
                            <div class="grid grid-cols-12 items-center text-slate-600 border-b border-dashed border-slate-100 pb-0.5 last:border-0">
                                <span class="col-span-10 truncate flex items-center gap-1">
                                    <span class="h-1.5 w-1.5 rounded-full bg-slate-400 inline-block"></span>
                                    {{ $lbl }}
                                </span>
                                <span class="col-span-2 font-bold text-slate-800 text-right">{{ $tbuValues[$idx] }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Indikator BB/TB -->
                <div class="flex flex-col items-center pl-2">
                    <div class="text-xs font-bold text-blue-600 mb-3 bg-blue-50 px-2 py-0.5 rounded-full uppercase tracking-wider">Indikator BB/TB</div>
                    <div class="relative h-32 w-32 mb-4">
                        <canvas id="donutBBTB" 
                                data-labels='@json($bbtbLabels ?? [])' 
                                data-values='@json($bbtbValues ?? [])'></canvas>
                        <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                            <div class="text-xl font-bold text-slate-800 leading-none">{{ array_sum($bbtbValues ?? [0]) }}</div>
                            <div class="text-[9px] text-slate-400 mt-0.5">Anak</div>
                        </div>
                    </div>
                    <!-- FIX: Keterangan Legenda BB/TB Sejajar Vertikal Rapi -->
                    <div class="w-full max-w-[140px] mx-auto space-y-1 text-[11px]">
                        @foreach($bbtbLabels ?? [] as $idx => $lbl)
                            <div class="grid grid-cols-12 items-center text-slate-600 border-b border-dashed border-slate-100 pb-0.5 last:border-0">
                                <span class="col-span-10 truncate flex items-center gap-1">
                                    <span class="h-1.5 w-1.5 rounded-full bg-slate-400 inline-block"></span>
                                    {{ $lbl }}
                                </span>
                                <span class="col-span-2 font-bold text-slate-800 text-right">{{ $bbtbValues[$idx] }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bagian bawah: Daftar Balita -->
    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
        <div class="p-5 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div class="font-semibold text-black">Daftar Status Gizi Terakhir Balita</div>
            <div class="flex items-center gap-3">
                <input id="searchBalita" type="text" placeholder="Cari nama balita..."
                    class="w-64 max-w-full rounded-2xl bg-slate-100 border border-slate-200 px-4 py-2.5 text-sm text-slate-700 placeholder:text-slate-400 outline-none focus:bg-white focus:ring-2 focus:ring-slate-200 transition" />
                <a href="{{ url('/balita/create') }}"
                   class="inline-flex items-center gap-2 px-6 py-3 rounded-2xl bg-[#E85C4A] text-white text-sm font-semibold shadow-md hover:bg-[#d94f3e] transition duration-200">
                    + Tambah Balita
                </a>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-slate-200 bg-slate-50">
                        <th class="text-left px-5 py-4 font-semibold text-black">No</th>
                        <th class="text-left px-5 py-4 font-semibold text-black">Nama Balita</th>
                        <th class="text-center px-4 py-4 font-semibold text-black">JK</th>
                        <th class="text-center px-4 py-4 font-semibold text-black">Umur</th>
                        <th class="text-center px-4 py-4 font-semibold bg-red-50 text-red-900">BB/U</th>
                        <th class="text-center px-4 py-4 font-semibold bg-orange-50 text-orange-900">TB/U</th>
                        <th class="text-center px-4 py-4 font-semibold bg-blue-50 text-blue-900">BB/TB</th>
                        <th class="text-center px-5 py-4 font-semibold text-black">Aksi</th>
                    </tr>
                </thead>
                <tbody id="balitaRows" class="divide-y divide-slate-200">
                    @php
                        $statusColor = [
                            'Gizi Buruk' => 'bg-red-100 text-red-700',
                            'Sangat Kurang' => 'bg-red-100 text-red-700',
                            'Gizi Kurang' => 'bg-orange-100 text-orange-700',
                            'Normal' => 'bg-green-100 text-green-700',
                            'Risiko Gizi Lebih' => 'bg-yellow-100 text-yellow-700',
                            'Gizi Lebih' => 'bg-blue-100 text-blue-700',
                            'Obesitas' => 'bg-purple-100 text-purple-700',
                            'Sangat Pendek' => 'bg-red-100 text-red-700',
                            'Pendek' => 'bg-orange-100 text-orange-700',
                            'Tinggi' => 'bg-purple-100 text-purple-700',
                        ];
                    @endphp

                    @forelse($balitas as $i => $b)
                        <tr class="hover:bg-slate-50">
                            <td class="px-5 py-4">{{ $i+1 }}</td>
                            <td class="px-5 py-4 font-semibold text-slate-900">{{ $b->nama_balita }}</td>
                            <td class="px-4 py-4 text-center">{{ $b->jenis_kelamin ?? '-' }}</td>
                            <td class="px-4 py-4 text-center">{{ $b->umur_terakhir ?? '-' }} Bln</td>
                            
                            <td class="px-4 py-4 text-center bg-red-50/20">
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $statusColor[$b->status_bbu] ?? 'bg-slate-100 text-slate-700' }}">
                                    {{ $b->status_bbu ?? '-' }}
                                </span>
                            </td>
                            <td class="px-4 py-4 text-center bg-orange-50/20">
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $statusColor[$b->status_tbu] ?? 'bg-slate-100 text-slate-700' }}">
                                    {{ $b->status_tbu ?? '-' }}
                                </span>
                            </td>
                            <td class="px-4 py-4 text-center bg-blue-50/20">
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $statusColor[$b->status_bbtb] ?? 'bg-slate-100 text-slate-700' }}">
                                    {{ $b->status_bbtb ?? '-' }}
                                </span>
                            </td>

                            <td class="px-5 py-4 text-center">
                                <a href="{{ url('/balita/'.$b->id.'/timbang') }}"
                                   class="inline-flex items-center px-4 py-2 rounded-2xl bg-blue-600 text-white text-xs font-semibold hover:bg-blue-700">
                                    Timbang
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-5 py-8 text-center text-slate-500">Belum ada data balita.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        // 1. Logika Live Search Tabel
        const input = document.getElementById('searchBalita');
        const tbody = document.getElementById('balitaRows');
        if (input && tbody) {
            input.addEventListener('input', () => {
                const q = input.value.toLowerCase().trim();
                const rows = tbody.querySelectorAll('tr');
                rows.forEach(row => {
                    const text = row.innerText.toLowerCase();
                    row.style.display = text.includes(q) ? '' : 'none';
                });
            });
        }

        // 2. Logika Render Donut Chart
        const renderDonut = (canvasId, colors) => {
            const ctx = document.getElementById(canvasId);
            if (!ctx) return;

            const labels = JSON.parse(ctx.getAttribute('data-labels') || '[]');
            const values = JSON.parse(ctx.getAttribute('data-values') || '[]');

            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: labels,
                    datasets: [{
                        data: values,
                        backgroundColor: colors,
                        borderWidth: 1,
                        hoverOffset: 3
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    cutout: '72%'
                }
            });
        };

        renderDonut('donutBBU', ['#ef4444', '#f97316', '#22c55e', '#a855f7']);
        renderDonut('donutTBU', ['#dc2626', '#fb923c', '#4ade80', '#c084fc']);
        renderDonut('donutBBTB', ['#ef4444', '#f97316', '#22c55e', '#facc15', '#3b82f6', '#a855f7']);
    });
    </script>
@endsection