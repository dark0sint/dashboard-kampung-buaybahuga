@extends('layouts.app')
@section('title', 'Data Penduduk')

@section('content')
<div class="bb-panel">
    <div class="bb-panel-title">
        Data Penduduk
        <button class="bb-btn bb-btn-primary bb-btn-sm" data-bs-toggle="modal" data-bs-target="#modalTambahPenduduk"><i class="bi bi-person-plus"></i> Tambah Penduduk</button>
    </div>

    <form method="GET" class="row g-2 mb-3">
        <div class="col-md-4"><input type="text" name="cari" value="{{ request('cari') }}" class="bb-form-control" placeholder="Cari nama / NIK..."></div>
        <div class="col-md-4">
            <select name="kampung_id" class="bb-form-control">
                <option value="">Semua Kampung</option>
                @foreach ($kampungs as $k)
                    <option value="{{ $k->id }}" {{ request('kampung_id') == $k->id ? 'selected' : '' }}>{{ $k->nama_kampung }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2"><button class="bb-btn bb-btn-outline w-100 justify-content-center">Filter</button></div>
    </form>

    <div class="table-responsive">
        <table class="bb-table">
            <thead>
                <tr><th>NIK</th><th>Nama</th><th>Kampung</th><th>L/P</th><th>Pekerjaan</th><th>Status Kawin</th><th></th></tr>
            </thead>
            <tbody>
                @forelse ($penduduks as $p)
                    <tr>
                        <td style="font-family:var(--font-mono); font-size:12px;">{{ $p->nik }}</td>
                        <td><strong>{{ $p->nama_lengkap }}</strong></td>
                        <td>{{ $p->kampung->nama_kampung }}</td>
                        <td><span class="bb-badge {{ $p->jenis_kelamin === 'L' ? 'bb-badge-green' : 'bb-badge-gold' }}">{{ $p->jenis_kelamin }}</span></td>
                        <td>{{ $p->pekerjaan ?? '—' }}</td>
                        <td>{{ ucfirst(str_replace('_',' ', $p->status_perkawinan)) }}</td>
                        <td>
                            <form action="{{ route('penduduk.destroy', $p) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus data {{ $p->nama_lengkap }}?')">
                                @csrf @method('DELETE')
                                <button class="bb-btn bb-btn-danger-ghost bb-btn-sm"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center text-muted py-4">Belum ada data penduduk.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-3">{{ $penduduks->links() }}</div>
</div>

<div class="modal fade" id="modalTambahPenduduk" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="POST" action="{{ route('penduduk.store') }}">
                @csrf
                <div class="modal-header"><h5 class="modal-title">Tambah Data Penduduk</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="bb-form-label">Kampung</label>
                            <select name="kampung_id" class="bb-form-control" required>
                                @foreach ($kampungs as $k)
                                    <option value="{{ $k->id }}">{{ $k->nama_kampung }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6"><label class="bb-form-label">Nama Lengkap</label><input name="nama_lengkap" class="bb-form-control" required></div>
                        <div class="col-md-6"><label class="bb-form-label">NIK (16 digit)</label><input name="nik" class="bb-form-control" maxlength="16" required></div>
                        <div class="col-md-6"><label class="bb-form-label">Nomor KK (16 digit)</label><input name="nomor_kk" class="bb-form-control" maxlength="16" required></div>
                        <div class="col-md-4">
                            <label class="bb-form-label">Jenis Kelamin</label>
                            <select name="jenis_kelamin" class="bb-form-control" required><option value="L">Laki-laki</option><option value="P">Perempuan</option></select>
                        </div>
                        <div class="col-md-4"><label class="bb-form-label">Tempat Lahir</label><input name="tempat_lahir" class="bb-form-control"></div>
                        <div class="col-md-4"><label class="bb-form-label">Tanggal Lahir</label><input type="date" name="tanggal_lahir" class="bb-form-control"></div>
                        <div class="col-md-6"><label class="bb-form-label">Pekerjaan</label><input name="pekerjaan" class="bb-form-control"></div>
                        <div class="col-md-6">
                            <label class="bb-form-label">Status Perkawinan</label>
                            <select name="status_perkawinan" class="bb-form-control">
                                <option value="belum_kawin">Belum Kawin</option>
                                <option value="kawin">Kawin</option>
                                <option value="cerai_hidup">Cerai Hidup</option>
                                <option value="cerai_mati">Cerai Mati</option>
                            </select>
                        </div>
                        <div class="col-12"><label class="bb-form-label">Alamat</label><textarea name="alamat" class="bb-form-control" rows="2"></textarea></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="bb-btn bb-btn-outline" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="bb-btn bb-btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
