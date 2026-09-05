<?php

namespace App\Http\Controllers;

use App\Models\Kampung;
use App\Models\Penduduk;
use Illuminate\Http\Request;

class PendudukController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $penduduks = Penduduk::with('kampung')
            ->when($user && $user->role === 'operator_kampung', fn ($q) => $q->where('kampung_id', $user->kampung_id))
            ->when($request->kampung_id, fn ($q) => $q->where('kampung_id', $request->kampung_id))
            ->when($request->cari, function ($q) use ($request) {
                $q->where(function ($sub) use ($request) {
                    $sub->where('nama_lengkap', 'like', '%' . $request->cari . '%')
                        ->orWhere('nik', 'like', '%' . $request->cari . '%');
                });
            })
            ->orderBy('nama_lengkap')
            ->paginate(15)
            ->withQueryString();

        $kampungs = Kampung::orderBy('nama_kampung')->get();

        return view('penduduk.index', compact('penduduks', 'kampungs'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'kampung_id' => 'required|exists:kampungs,id',
            'nik' => 'required|digits:16|unique:penduduks,nik',
            'nomor_kk' => 'required|digits:16',
            'nama_lengkap' => 'required|string|max:100',
            'jenis_kelamin' => 'required|in:L,P',
            'tempat_lahir' => 'nullable|string|max:100',
            'tanggal_lahir' => 'nullable|date',
            'agama' => 'nullable|string|max:20',
            'pekerjaan' => 'nullable|string|max:100',
            'pendidikan_terakhir' => 'nullable|string|max:50',
            'status_perkawinan' => 'required|in:belum_kawin,kawin,cerai_hidup,cerai_mati',
            'alamat' => 'nullable|string',
            'rt' => 'nullable|string|max:5',
            'rw' => 'nullable|string|max:5',
        ]);

        Penduduk::create($data);

        return back()->with('success', 'Data penduduk berhasil ditambahkan.');
    }

    public function update(Request $request, Penduduk $penduduk)
    {
        $data = $request->validate([
            'nama_lengkap' => 'required|string|max:100',
            'jenis_kelamin' => 'required|in:L,P',
            'pekerjaan' => 'nullable|string|max:100',
            'status_perkawinan' => 'required|in:belum_kawin,kawin,cerai_hidup,cerai_mati',
            'alamat' => 'nullable|string',
        ]);

        $penduduk->update($data);

        return back()->with('success', 'Data penduduk berhasil diperbarui.');
    }

    public function destroy(Penduduk $penduduk)
    {
        $penduduk->delete();

        return back()->with('success', 'Data penduduk berhasil dihapus.');
    }
}
