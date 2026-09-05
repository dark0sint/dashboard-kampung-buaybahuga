<?php

namespace App\Http\Controllers;

use App\Models\Kampung;
use App\Models\Laporan;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $laporans = Laporan::with('kampung')
            ->when($user && $user->role === 'operator_kampung', fn ($q) => $q->where('kampung_id', $user->kampung_id))
            ->when($request->tahun, fn ($q) => $q->where('tahun', $request->tahun))
            ->when($request->status, fn ($q) => $q->where('status_verifikasi', $request->status))
            ->orderByDesc('tahun')->orderByDesc('bulan')
            ->paginate(15)
            ->withQueryString();

        return view('laporan.index', compact('laporans'));
    }

    public function create()
    {
        $kampungs = Kampung::orderBy('nama_kampung')->get();

        return view('laporan.create', compact('kampungs'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'kampung_id' => 'required|exists:kampungs,id',
            'bulan' => 'required|integer|min:1|max:12',
            'tahun' => 'required|integer|min:2020|max:2100',
            'jumlah_kelahiran' => 'nullable|integer|min:0',
            'jumlah_kematian' => 'nullable|integer|min:0',
            'jumlah_pindah_masuk' => 'nullable|integer|min:0',
            'jumlah_pindah_keluar' => 'nullable|integer|min:0',
            'jumlah_kk_miskin' => 'nullable|integer|min:0',
            'catatan' => 'nullable|string',
        ]);

        $data['status_verifikasi'] = 'diajukan';

        Laporan::updateOrCreate(
            ['kampung_id' => $data['kampung_id'], 'bulan' => $data['bulan'], 'tahun' => $data['tahun']],
            $data
        );

        return redirect()->route('laporan.index')->with('success', 'Laporan bulanan berhasil diajukan ke kecamatan.');
    }

    public function verifikasi(Request $request, Laporan $laporan)
    {
        $request->validate(['status_verifikasi' => 'required|in:diverifikasi,ditolak']);

        $laporan->update([
            'status_verifikasi' => $request->status_verifikasi,
            'diverifikasi_oleh' => $request->user()->name,
            'diverifikasi_pada' => now(),
        ]);

        return back()->with('success', 'Status verifikasi laporan diperbarui.');
    }
}
