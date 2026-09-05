<?php

namespace App\Http\Controllers;

use App\Models\Kampung;
use App\Models\Laporan;
use App\Models\Penduduk;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

/**
 * Menjembatani dashboard Laravel dengan layanan Python (Flask) yang
 * bertugas melakukan agregasi & analitik data 9 kampung menjadi
 * rekap resmi tingkat Kecamatan Buay Bahuga.
 */
class IntegrasiController extends Controller
{
    public function rekapKecamatan()
    {
        $data = Cache::remember('rekap-kecamatan-python', 300, fn () => $this->fetchFromPython('/api/rekap-kecamatan'));

        return view('laporan.rekap-kecamatan', ['rekap' => $data]);
    }

    /**
     * Mengirim snapshot data terbaru (kampung, penduduk, laporan) dari
     * Laravel ke layanan Python agar diproses/diagregasi, lalu disimpan
     * sebagai rekap resmi kecamatan.
     */
    public function sinkronkanKeKecamatan()
    {
        $payload = [
            'kampung' => Kampung::withCount('penduduks')->get(),
            'laporan' => Laporan::where('status_verifikasi', 'diverifikasi')
                ->whereYear('created_at', now()->year)
                ->get(),
            'ringkasan_penduduk' => Penduduk::selectRaw('kampung_id, jenis_kelamin, count(*) as total')
                ->groupBy('kampung_id', 'jenis_kelamin')
                ->get(),
        ];

        $response = Http::withToken(config('services.python.token'))
            ->timeout(15)
            ->post(config('services.python.url') . '/api/sinkronisasi', $payload);

        Cache::forget('rekap-kecamatan-python');

        if ($response->successful()) {
            return back()->with('success', 'Data berhasil disinkronkan ke sistem rekap Kecamatan Buay Bahuga.');
        }

        return back()->with('error', 'Gagal menyinkronkan data ke layanan Python. Periksa koneksi service.');
    }

    private function fetchFromPython(string $path): ?array
    {
        try {
            $response = Http::withToken(config('services.python.token'))
                ->timeout(8)
                ->get(config('services.python.url') . $path);

            return $response->successful() ? $response->json() : null;
        } catch (\Throwable $e) {
            return null;
        }
    }
}
