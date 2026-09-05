<?php

namespace App\Http\Controllers;

use App\Models\Kampung;
use App\Models\Laporan;
use App\Models\Penduduk;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function index()
    {
        $kampungs = Kampung::withCount('penduduks')->orderBy('nama_kampung')->get();

        $totalPenduduk = Penduduk::count();
        $totalKK = Penduduk::distinct('nomor_kk')->count('nomor_kk');
        $totalLaki = Penduduk::where('jenis_kelamin', 'L')->count();
        $totalPerempuan = Penduduk::where('jenis_kelamin', 'P')->count();

        $laporanBelumDiverifikasi = Laporan::status('diajukan')->count();

        // Rekap agregat tingkat kecamatan diambil dari layanan Python
        // (services/rekap-kecamatan). Di-cache 10 menit agar dashboard tetap
        // responsif walau layanan Python sedang sibuk memproses data besar.
        $rekapKecamatan = Cache::remember('rekap-kecamatan-python', 600, function () {
            try {
                $response = Http::withToken(config('services.python.token'))
                    ->timeout(5)
                    ->get(config('services.python.url') . '/api/rekap-kecamatan');

                if ($response->successful()) {
                    return $response->json();
                }
            } catch (\Throwable $e) {
                Log::warning('Layanan Python rekap-kecamatan tidak dapat diakses: ' . $e->getMessage());
            }

            return null; // fallback: dashboard tetap tampil dengan data lokal Laravel
        });

        return view('dashboard.index', compact(
            'kampungs',
            'totalPenduduk',
            'totalKK',
            'totalLaki',
            'totalPerempuan',
            'laporanBelumDiverifikasi',
            'rekapKecamatan'
        ));
    }
}
