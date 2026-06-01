@extends('layouts.app')
@section('title', 'Dashboard Posyandu Durian')

@section('content')
    <!-- Header -->
    <div class="mb-6">
        <div class="text-xs font-semibold tracking-widest text-slate-400">DASHBOARD</div>
        <div class="text-2xl font-semibold">Posyandu Durian</div>
    </div>

    <!-- Ringkasan: KPI kiri (2 card) + Status kanan (1 card) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-6 items-stretch">

        <!-- KIRI: 2 card dibuat "seimbang tinggi" dengan kanan -->
        <div class="lg:col-span-1 grid grid-rows-2 gap-4 h-full">

            <!-- Total Balita -->
            <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-5 h-full flex flex-col justify-center">
                <div class="text-sm font-semibold text-black">Total Balita</div>
                <div class="mt-2 text-3xl font-bold text-slate-900">{{ $totalBalita }}</div>
                <div class="mt-1 text-xs text-slate-400">Terdaftar</div>
            </div>

            <!-- Total Penimbangan -->
            <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-5 h-full flex flex-col justify-center">
                <div class="text-sm font-semibold text-black">Total Penimbangan</div>
                <div class="mt-2 text-3xl font-bold text-slate-900">{{ $totalPenimbangan }}</div>
                <div class="mt-1 text-xs text-slate-400">Tercatat</div>
            </div>

        </div>

        <!-- KANAN: Status Gizi -->
        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-5 lg:col-span-2 h-full">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <div class="text-sm font-semibold text-black">Status Gizi</div>
                    <div class="text-xs text-slate-500">Berdasarkan penimbangan terakhir tiap balita</div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 items-center">

                <!-- Donut + center text -->
                <div class="flex items-center justify-center">
                    <div class="flex items-center justify-center">
                        <div class="relative h-36 w-36 sm:h-40 sm:w-40 lg:h-48 lg:w-48">
                            <canvas
                                id="donutGizi"
                                data-labels='@json($donutLabels)'
                                data-values='@json($donutValues)'></canvas>

                            @php $totalStatus = array_sum($donutValues); @endphp
                            <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                                <div class="text-3xl sm:text-4xl font-bold text-slate-900 leading-none">{{ $totalStatus }}</div>
                                <div class="mt-1 text-[11px] sm:text-xs font-medium text-slate-500">Balita (terakhir)</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- List 6 kelas (rapet & kecil) -->
                <div class="space-y-1">
                    @php
                        $colorDot = [
                            'Gizi Buruk' => 'bg-red-500',
                            'Gizi Kurang' => 'bg-orange-500',
                            'Normal' => 'bg-green-500',
                            'Risiko Gizi Lebih' => 'bg-yellow-400',
                            'Gizi Lebih' => 'bg-blue-500',
                            'Obesitas' => 'bg-purple-500',
                        ];
                    @endphp

                    @foreach($donutLabels as $i => $label)
                        <div class="flex items-center justify-between px-2 py-1 rounded-lg hover:bg-slate-50">
                            <div class="flex items-center gap-2 min-w-0">
                                <span class="h-2 w-2 rounded-full {{ $colorDot[$label] ?? 'bg-slate-400' }}"></span>
                                <span class="text-xs font-medium text-slate-700 truncate">{{ $label }}</span>
                            </div>

                            <span class="ml-3 text-[11px] font-semibold px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-700">
                                {{ $donutValues[$i] }}
                            </span>
                        </div>
                    @endforeach
                </div>

            </div>
        </div>

    </div>

    <!-- Daftar Balita (CARD) -->
    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">

        <!-- Header -->
        <div class="p-5 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div class="font-semibold text-black">Daftar Balita</div>

            <div class="flex items-center gap-3">
                <input
                    id="searchBalita"
                    type="text"
                    placeholder="Cari nama balita..."
                    class="w-64 max-w-full rounded-2xl bg-slate-100 border border-slate-200 px-4 py-2.5 text-sm text-slate-700 placeholder:text-slate-400 outline-none focus:bg-white focus:ring-2 focus:ring-slate-200 transition"
                />

                <a href="{{ url('/balita/create') }}"
                   class="inline-flex items-center gap-2 px-6 py-3 rounded-2xl bg-[#E85C4A] text-white text-sm font-semibold shadow-md hover:bg-[#d94f3e] hover:shadow-lg transition duration-200">
                    + Tambah Balita
                </a>
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-slate-200">
                        <th class="text-left px-5 py-4 font-semibold text-black">No</th>
                        <th class="text-left px-5 py-4 font-semibold text-black">Nama Balita</th>
                        <th class="text-left px-5 py-4 font-semibold text-black">Jenis Kelamin</th>
                        <th class="text-left px-5 py-4 font-semibold text-black">Penimbangan Terakhir</th>
                        <th class="text-left px-5 py-4 font-semibold text-black">Umur (bulan)</th>
                        <th class="text-left px-5 py-4 font-semibold text-black">Status Gizi Terakhir</th>
                        <th class="text-center px-5 py-4 font-semibold text-black">Aksi</th>
                    </tr>
                </thead>

                <tbody id="balitaRows" class="divide-y divide-slate-200">
                    @php
                        $statusColor = [
                            'Gizi Buruk' => 'bg-red-100 text-red-700',
                            'Gizi Kurang' => 'bg-orange-100 text-orange-700',
                            'Normal' => 'bg-green-100 text-green-700',
                            'Risiko Gizi Lebih' => 'bg-yellow-100 text-yellow-700',
                            'Gizi Lebih' => 'bg-blue-100 text-blue-700',
                            'Obesitas' => 'bg-purple-100 text-purple-700',
                        ];
                    @endphp

                    @forelse($balitas as $i => $b)
                        <tr class="hover:bg-slate-50">
                            <td class="px-5 py-4">{{ $i+1 }}</td>
                            <td class="px-5 py-4 font-semibold text-slate-900">{{ $b->nama_balita }}</td>
                            <td class="px-5 py-4">{{ $b->jenis_kelamin ?? '-' }}</td>
                            <td class="px-5 py-4">{{ $b->tgl_terakhir ?? '-' }}</td>
                            <td class="px-5 py-4">{{ $b->umur_terakhir ?? '-' }}</td>
                            <td class="px-5 py-4">
                                @php
                                    // PERBAIKAN: Membaca kolom baru 'status_bbu' hasil database Random Forest
                                    $status = $b->status_bbu ?? '-'; 
                                    $colorClass = $statusColor[$status] ?? 'bg-slate-100 text-slate-700';
                                @endphp

                                <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $colorClass }}">
                                    {{ $status }}
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
                            <td colspan="7" class="px-5 py-8 text-center text-slate-500">
                                Belum ada data balita.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', () => {
      const input = document.getElementById('searchBalita');
      const tbody = document.getElementById('balitaRows');
      if (!input || !tbody) return;

      input.addEventListener('input', () => {
        const q = input.value.toLowerCase().trim();
        const rows = tbody.querySelectorAll('tr');

        rows.forEach(row => {
          const text = row.innerText.toLowerCase();
          row.style.display = text.includes(q) ? '' : 'none';
        });
      });
    });
    </script>
@endsection