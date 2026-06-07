<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekapitulasi Bulanan Posyandu - {{ $nama_bulan }} {{ $tahun }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 11px; color: #333; line-height: 1.4; padding: 10px; }
        .Kop { text-align: center; border-bottom: 3px double #000; padding-bottom: 8px; margin-bottom: 15px; }
        .Kop h2, .Kop h3 { margin: 0; text-transform: uppercase; }
        .Kop p { margin: 3px 0 0 0; font-style: italic; }
        .filter-info { margin-bottom: 15px; font-weight: bold; font-size: 12px; }
        .data-table { width: 100%; border-collapse: collapse; margin-top: 5px; }
        .data-table th, .data-table td { border: 1px solid #000; padding: 6px; text-align: center; }
        .data-table th { background-color: #f2f2f2; }
        @media print {
            .no-print { display: none; }
            @page { size: landscape; margin: 10mm; }
        }
    </style>
</head>
<body>

    <div class="no-print" style="margin-bottom: 20px; background: #e1f5fe; padding: 15px; border: 1px solid #b3e5fc; border-radius: 6px;">
        <form action="{{ url('/laporan/bulanan') }}" method="GET" style="display: inline-block; margin-right: 20px;">
            <label style="font-weight: bold; margin-right: 5px;">Pilih Bulan & Tahun:</label>
            <select name="bulan" style="padding: 5px; border-radius: 4px;">
                @for($m=1; $m<=12; $m++)
                    <option value="{{ sprintf('%02d', $m) }}" {{ $bulan == sprintf('%02d', $m) ? 'selected' : '' }}>
                        {{ \Carbon\Carbon::createFromDate($tahun, $m, 1)->translatedFormat('F') }}
                    </option>
                @endfor
            </select>
            <select name="tahun" style="padding: 5px; border-radius: 4px; margin-left: 5px;">
                @for($y=date('Y')-2; $y<=date('Y')+2; $y++)
                    <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                @endfor
            </select>
            <button type="submit" style="padding: 5px 12px; background: #00897b; color: white; border: none; border-radius: 4px; cursor: pointer;">Filter Data</button>
        </form>
        <button onclick="window.print()" style="padding: 6px 15px; background: #0288d1; color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: bold;">🖨️ Cetak / Simpan PDF</button>
    </div>

    <div class="Kop">
        <h2>Posyandu Durian 14A</h2>
        <p>Alamat: Jl. Taman Asri, Subangjaya, Kec. Cikole, Kota Sukabumi, Jawa Barat 43116</p>
        <h3>REKAPITULASI BULANAN DATA PENIMBANGAN DAN STATUS GIZI BALITA</h3>
    </div>

    <div class="filter-info">
        Periode Laporan: {{ $nama_bulan }} {{ $tahun }}
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Balita</th>
                <th>JK</th>
                <th>Umur</th>
                <th>BB (kg)</th>
                <th>TB (cm)</th>
                <th>Status BB/U</th>
                <th>Status TB/U (Stunting)</th>
                <th>Status BB/TB (Nutrisi)</th>
                <th>Tanggal Ukur</th>
            </tr>
        </thead>
        <tbody>
            @if($riwayat->isEmpty())
                <tr>
                    <td colspan="10" style="padding: 20px; font-style: italic; color: #777;">Tidak ada data penimbangan pada bulan ini.</td>
                </tr>
            @else
                @foreach($riwayat as $index => $r)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td style="text-align: left; font-weight: bold;">{{ $r->balita->nama ?? 'Data Dihapus' }}</td>
                    <td>{{ $r->balita->jenis_kelamin ?? '-' }}</td>
                    <td>{{ $r->umur_bulan }} Bln</td>
                    <td>{{ $r->berat_badan }}</td>
                    <td>{{ $r->tinggi_badan }}</td>
                    <td>{{ $r->status_bbu }}</td>
                    <td>{{ $r->status_tbu }}</td>
                    <td>{{ $r->status_bbtb }}</td>
                    <td>{{ \Carbon\Carbon::parse($r->tanggal_penimbangan)->translatedFormat('d/m/Y') }}</td>
                </tr>
                @endforeach
            @endif
        </tbody>
    </table>

    <div style="margin-top: 40px; float: right; text-align: center; width: 200px;">
        <p>Kader Posyandu Durian 14A</p>
        <br><br><br>
        <p style="text-decoration: underline; font-weight: bold;">(...........................)</p>
    </div>

</body>
</html>