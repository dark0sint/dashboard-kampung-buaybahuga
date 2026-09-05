<?php

namespace App\Http\Controllers;

use App\Models\Kampung;
use Illuminate\Http\Request;

class KampungController extends Controller
{
    public function index(Request $request)
    {
        $kampungs = Kampung::withCount('penduduks')
            ->when($request->cari, fn ($q) => $q->where('nama_kampung', 'like', '%' . $request->cari . '%'))
            ->orderBy('nama_kampung')
            ->paginate(10)
            ->withQueryString();

        return view('kampung.index', compact('kampungs'));
    }

    public function create()
    {
        return view('kampung.create');
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        Kampung::create($data);

        return redirect()->route('kampung.index')->with('success', 'Data kampung berhasil ditambahkan.');
    }

    public function show(Kampung $kampung)
    {
        $kampung->load(['penduduks' => fn ($q) => $q->latest()->limit(10), 'laporans' => fn ($q) => $q->latest()->limit(6)]);

        return view('kampung.show', compact('kampung'));
    }

    public function edit(Kampung $kampung)
    {
        return view('kampung.edit', compact('kampung'));
    }

    public function update(Request $request, Kampung $kampung)
    {
        $data = $this->validated($request, $kampung->id);
        $kampung->update($data);

        return redirect()->route('kampung.index')->with('success', 'Data kampung berhasil diperbarui.');
    }

    public function destroy(Kampung $kampung)
    {
        $kampung->delete();

        return redirect()->route('kampung.index')->with('success', 'Data kampung berhasil dihapus.');
    }

    private function validated(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'kode_kampung' => 'required|string|max:20|unique:kampungs,kode_kampung,' . $ignoreId,
            'nama_kampung' => 'required|string|max:100',
            'nama_kepala_kampung' => 'nullable|string|max:100',
            'nip_kepala_kampung' => 'nullable|string|max:30',
            'alamat_kantor' => 'nullable|string',
            'no_telepon' => 'nullable|string|max:20',
            'luas_wilayah_km2' => 'nullable|numeric|min:0',
            'jumlah_rt' => 'nullable|integer|min:0',
            'jumlah_rw' => 'nullable|integer|min:0',
            'jumlah_dusun' => 'nullable|integer|min:0',
            'status_definitif' => 'required|in:definitif,pjs,kosong',
            'keterangan' => 'nullable|string',
        ]);
    }
}
