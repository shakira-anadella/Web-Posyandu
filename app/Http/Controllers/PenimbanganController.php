<?php

namespace App\Http\Controllers;

use App\Models\Balita;
use App\Models\Penimbangan;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http; // <-- Kabel data untuk menembak Python

class PenimbanganController extends Controller
{
    public function form($id)
    {
        $balita = Balita::findOrFail($id);

        $riwayat = Penimbangan::where('balita_id', $id)
            ->orderByDesc('tanggal_penimbangan')
            ->get();

        return view('penimbangan', compact('balita', 'riwayat'));
    }

    public function prediksi(Request $request, $id)
    {
        $request->validate([
            'tanggal_penimbangan' => 'required|date',
            'berat_badan' => 'required|numeric',
            'tinggi_badan' => 'required|numeric',
        ]);

        $balita = Balita::findOrFail($id);

        // Hitung umur bulan berdasarkan tanggal penimbangan
        $umur_bulan = Carbon::parse($balita->tanggal_lahir)->diffInMonths(Carbon::parse($request->tanggal_penimbangan));
        
        // Batasan umur balita (0-60 bulan)
        if ($umur_bulan > 60) {
            $riwayat = Penimbangan::where('balita_id', $id)->orderByDesc('tanggal_penimbangan')->get();

            return view('penimbangan', [
                'balita' => $balita,
                'riwayat' => $riwayat,
                'error' => 'Umur balita melebihi 60 bulan. Prediksi hanya untuk balita (0–60 bulan).',
                'input' => $request->only(['tanggal_penimbangan','berat_badan','tinggi_badan']),
            ]);
        }

        try {
            // 🔄 DISESUAIKAN: Menembak route URL default FastAPI baru
            $response = Http::timeout(5)->post('http://127.0.0.1:8000/predict', [
                'jk' => $balita->jenis_kelamin,               // Mengirim 'L' atau 'P'
                'usia_ukur' => (float) $umur_bulan,           // Menyesuaikan nama variabel baru FastAPI
                'berat' => (float) $request->berat_badan,     // Menyesuaikan nama variabel baru FastAPI
                'tinggi' => (float) $request->tinggi_badan,   // Menyesuaikan nama variabel baru FastAPI
            ]);

            if ($response->successful()) {
                $result = $response->json();
                
                // 🔄 DISESUAIKAN: Menangkap struktur key JSON hasil optimasi Bayesian baru
                $hasil_bbu  = $result['hasil']['bbu'];
                $hasil_tbu  = $result['hasil']['tbu'];
                $hasil_bbtb = $result['hasil']['bbtb'];
            } else {
                $riwayat = Penimbangan::where('balita_id', $id)->orderByDesc('tanggal_penimbangan')->get();
                return view('penimbangan', [
                    'balita' => $balita,
                    'riwayat' => $riwayat,
                    'error' => 'Server FastAPI AI merespon, tapi gagal mengeluarkan hasil prediksi XGBoost.',
                    'input' => $request->only(['tanggal_penimbangan','berat_badan','tinggi_badan']),
                ]);
            }

        } catch (\Exception $e) {
            $riwayat = Penimbangan::where('balita_id', $id)->orderByDesc('tanggal_penimbangan')->get();
            return view('penimbangan', [
                'balita' => $balita,
                'riwayat' => $riwayat,
                'error' => 'Gagal terhubung ke Mesin AI XGBoost. Pastikan server FastAPI sudah menyala di terminal! (Pesan: ' . $e->getMessage() . ')',
                'input' => $request->only(['tanggal_penimbangan','berat_badan','tinggi_badan']),
            ]);
        }

        $riwayat = Penimbangan::where('balita_id', $id)->orderByDesc('tanggal_penimbangan')->get();

        // Kirim hasil asli dari Python ke halaman web (Blade)
        return view('penimbangan', [
            'balita' => $balita,
            'riwayat' => $riwayat,
            'umur_bulan' => $umur_bulan,
            'hasil_bbu' => $hasil_bbu,
            'hasil_tbu' => $hasil_tbu,
            'hasil_bbtb' => $hasil_bbtb,
            'input' => $request->only(['tanggal_penimbangan','berat_badan','tinggi_badan']),
        ]);
    }

    public function simpan(Request $request, $id)
    {
        $request->validate([
            'tanggal_penimbangan' => 'required|date',
            'berat_badan' => 'required|numeric',
            'tinggi_badan' => 'required|numeric',
            'status_bbu' => 'required',
            'status_tbu' => 'required',
            'status_bbtb' => 'required',
        ]);

        $balita = Balita::findOrFail($id);
        $umur_bulan = Carbon::parse($balita->tanggal_lahir)->diffInMonths(Carbon::parse($request->tanggal_penimbangan));
        
        if ($umur_bulan > 60) {
            return redirect(url('/balita/'.$id.'/timbang'))
                ->with('error', 'Umur balita melebihi 60 bulan. Penimbangan hanya untuk balita (0–60 bulan).');
        }

        $tanggal = Carbon::parse($request->tanggal_penimbangan);

        // Proteksi agar kader posyandu tidak input data ganda di bulan yang sama
        $sudahAda = Penimbangan::where('balita_id', $id)
            ->whereYear('tanggal_penimbangan', $tanggal->year)
            ->whereMonth('tanggal_penimbangan', $tanggal->month)
            ->exists();

        if ($sudahAda) {
            return redirect(url('/balita/'.$id.'/timbang'))
                ->with('error', 'Penimbangan untuk bulan ini sudah ada. Hanya boleh 1 kali penimbangan per bulan.');
        }

        // Simpan data diagnosis final hasil prediksi XGBoost ke database MySQL
        Penimbangan::create([
            'balita_id'           => $id,
            'tanggal_penimbangan' => $request->tanggal_penimbangan,
            'umur_bulan'          => $umur_bulan,
            'berat_badan'         => $request->berat_badan,
            'tinggi_badan'        => $request->tinggi_badan,
            'status_bbu'          => $request->status_bbu,   // Kolom BB/U
            'status_tbu'          => $request->status_tbu,   // Kolom TB/U (Stunting)
            'status_bbtb'         => $request->status_bbtb,  // Kolom BB/TB (Wasting)
        ]);

        return redirect(url('/balita/'.$id.'/timbang'))->with('success', 'Data penimbangan dan status gizi berhasil disimpan.');
    }

    public function cetakBulanan(Request $request)
    {
        // Mengambil filter bulan dan tahun dari input, default-nya bulan & tahun sekarang
        $bulan = $request->input('bulan', date('m'));
        $tahun = $request->input('tahun', date('Y'));

        // Ambil data penimbangan semua balita pada bulan dan tahun tersebut
        $riwayat = Penimbangan::with('balita')
            ->whereYear('tanggal_penimbangan', $tahun)
            ->whereMonth('tanggal_penimbangan', $bulan)
            ->get();

        // Nama bulan untuk judul laporan
        $nama_bulan = \Carbon\Carbon::createFromDate($tahun, $bulan, 1)->translatedFormat('F');

        return view('cetak_bulanan', compact('riwayat', 'nama_bulan', 'bulan', 'tahun'));
    }
}