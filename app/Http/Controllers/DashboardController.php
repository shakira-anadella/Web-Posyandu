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

        // Ambil penimbangan terbaru per balita
        $latest = Penimbangan::select('balita_id', DB::raw('MAX(id) as max_id'))
            ->groupBy('balita_id');

        // PERBAIKAN 1: Hitung status gizi berdasarkan penimbangan terakhir menggunakan kolom 'status_bbu'
        $statusCounts = Penimbangan::joinSub($latest, 'latest', function ($join) {
                $join->on('penimbangans.id', '=', 'latest.max_id');
            })
            ->select('status_bbu', DB::raw('COUNT(*) as total'))
            ->groupBy('status_bbu')
            ->pluck('total', 'status_bbu'); 

        // Urutan 6 kelas (selalu tampil walau 0)
        $kelas = [
            'Gizi Buruk',
            'Gizi Kurang',
            'Normal',
            'Risiko Gizi Lebih',
            'Gizi Lebih',
            'Obesitas',
        ];

        $map = array_fill_keys($kelas, 0);

        foreach ($statusCounts as $status => $total) {
            if (isset($map[$status])) {
                $map[$status] = (int) $total;
            }
        }

        $donutLabels = array_keys($map);
        $donutValues = array_values($map);

        // Daftar balita
        $balitas = Balita::orderBy('nama_balita')->get();

        // Ambil record penimbangan terbaru lengkap (bb/tb/umur/status)
        $latestRows = Penimbangan::joinSub($latest, 'latest', function($join){
                $join->on('penimbangans.id', '=', 'latest.max_id');
            })
            ->get()
            ->keyBy('balita_id');

        // PERBAIKAN 2: Tempel data terakhir ke tiap balita menggunakan kolom baru untuk dibaca blade
        foreach ($balitas as $b) {
            $p = $latestRows->get($b->id);

            $b->tgl_terakhir = $p?->tanggal_penimbangan;
            $b->umur_terakhir = $p?->umur_bulan;
            $b->status_bbu = $p?->status_bbu; // Menyesuaikan dengan variabel baru di blade dashboard
        }

        return view('dashboard', compact(
            'totalBalita',
            'totalPenimbangan',
            'balitas',
            'statusCounts',
            'donutLabels',
            'donutValues',
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

        // PERBAIKAN 3: Membunkus redirect dengan helper url() agar jalur local tidak pecah
        return redirect(url('/dashboard'))->with('success', 'Data balita berhasil ditambahkan.');
    }
}