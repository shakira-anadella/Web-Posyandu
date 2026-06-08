<?php

namespace App\Http\Controllers;

use App\Models\Balita;
use App\Models\Penimbangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalBalita = Balita::count();
        $totalPenimbangan = Penimbangan::count();

        // 1. Ambil ID penimbangan terbaru per balita (Subquery)
        $latest = Penimbangan::select('balita_id', DB::raw('MAX(id) as max_id'))
            ->groupBy('balita_id');

        // 2. Ambil record penimbangan terbaru lengkap untuk semua balita
        $latestRows = Penimbangan::joinSub($latest, 'latest', function($join){
                $join->on('penimbangans.id', '=', 'latest.max_id');
            })
            ->get()
            ->keyBy('balita_id');

        // --- A. Rekap Indikator BB/U ---
        $kelasBBU = ['Sangat Kurang', 'Kurang', 'Normal', 'Risiko BB Lebih'];
        $mapBBU = array_fill_keys($kelasBBU, 0);
        foreach ($latestRows as $row) {
            if ($row->status_bbu && isset($mapBBU[$row->status_bbu])) {
                $mapBBU[$row->status_bbu]++;
            }
        }
        $bbuLabels = array_keys($mapBBU);
        $bbuValues = array_values($mapBBU);

        // --- B. Rekap Indikator TB/U (Stunting) ---
        $kelasTBU = ['Sangat Pendek', 'Pendek', 'Normal', 'Tinggi'];
        $mapTBU = array_fill_keys($kelasTBU, 0);
        foreach ($latestRows as $row) {
            if ($row->status_tbu && isset($mapTBU[$row->status_tbu])) {
                $mapTBU[$row->status_tbu]++;
            }
        }
        $tbuLabels = array_keys($mapTBU);
        $tbuValues = array_values($mapTBU);

        // --- C. Rekap Indikator BB/TB (Nutrisi) ---
        $kelasBBTB = ['Gizi Buruk', 'Gizi Kurang', 'Normal', 'Risiko Gizi Lebih', 'Gizi Lebih', 'Obesitas'];
        $mapBBTB = array_fill_keys($kelasBBTB, 0);
        foreach ($latestRows as $row) {
            if ($row->status_bbtb && isset($mapBBTB[$row->status_bbtb])) {
                $mapBBTB[$row->status_bbtb]++;
            }
        }
        $bbtbLabels = array_keys($mapBBTB);
        $bbtbValues = array_values($mapBBTB);

        $balitas = Balita::orderBy('nama_balita')->get();

        foreach ($balitas as $b) {
            $p = $latestRows->get($b->id);

            $b->tgl_terakhir = $p?->tanggal_penimbangan;
            $b->umur_terakhir = $p?->umur_bulan;
            $b->status_bbu = $p?->status_bbu;   // Status Berat Badan
            $b->status_tbu = $p?->status_tbu;   // Status Tinggi Badan (Stunting)
            $b->status_bbtb = $p?->status_bbtb; // Status Nutrisi klinis
        }

        return view('dashboard', compact(
            'totalBalita',
            'totalPenimbangan',
            'balitas',
            'bbuLabels', 'bbuValues',
            'tbuLabels', 'tbuValues',
            'bbtbLabels', 'bbtbValues'
        ));
    }

    public function createBalita()
    {
        return view('balita_create');
    }

    public function storeBalita(Request $request)
    {
        $request->validate([
            'nama_balita' => 'required',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required'
        ]);

        \App\Models\Balita::create([
            'nama_balita' => $request->nama_balita,
            'nama_ibu' => $request->nama_ibu,
            'tanggal_lahir' => $request->tanggal_lahir,
            'jenis_kelamin' => $request->jenis_kelamin,
        ]);

        return redirect(url('/dashboard'))->with('success', 'Data balita berhasil ditambahkan.');
    }
}