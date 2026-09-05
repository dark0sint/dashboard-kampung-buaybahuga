@csrf
<div class="row g-3">
    <div class="col-md-4">
        <label class="bb-form-label">Kode Kampung</label>
        <input type="text" name="kode_kampung" class="bb-form-control" value="{{ old('kode_kampung', $kampung->kode_kampung ?? '') }}" required>
    </div>
    <div class="col-md-8">
        <label class="bb-form-label">Nama Kampung</label>
        <input type="text" name="nama_kampung" class="bb-form-control" value="{{ old('nama_kampung', $kampung->nama_kampung ?? '') }}" required>
    </div>
    <div class="col-md-6">
        <label class="bb-form-label">Nama Kepala Kampung</label>
        <input type="text" name="nama_kepala_kampung" class="bb-form-control" value="{{ old('nama_kepala_kampung', $kampung->nama_kepala_kampung ?? '') }}">
    </div>
    <div class="col-md-6">
        <label class="bb-form-label">NIP Kepala Kampung</label>
        <input type="text" name="nip_kepala_kampung" class="bb-form-control" value="{{ old('nip_kepala_kampung', $kampung->nip_kepala_kampung ?? '') }}">
    </div>
    <div class="col-md-8">
        <label class="bb-form-label">Alamat Kantor Kampung</label>
        <input type="text" name="alamat_kantor" class="bb-form-control" value="{{ old('alamat_kantor', $kampung->alamat_kantor ?? '') }}">
    </div>
    <div class="col-md-4">
        <label class="bb-form-label">No. Telepon</label>
        <input type="text" name="no_telepon" class="bb-form-control" value="{{ old('no_telepon', $kampung->no_telepon ?? '') }}">
    </div>
    <div class="col-md-3">
        <label class="bb-form-label">Luas Wilayah (km²)</label>
        <input type="number" step="0.01" name="luas_wilayah_km2" class="bb-form-control" value="{{ old('luas_wilayah_km2', $kampung->luas_wilayah_km2 ?? 0) }}">
    </div>
    <div class="col-md-3">
        <label class="bb-form-label">Jumlah RT</label>
        <input type="number" name="jumlah_rt" class="bb-form-control" value="{{ old('jumlah_rt', $kampung->jumlah_rt ?? 0) }}">
    </div>
    <div class="col-md-3">
        <label class="bb-form-label">Jumlah RW</label>
        <input type="number" name="jumlah_rw" class="bb-form-control" value="{{ old('jumlah_rw', $kampung->jumlah_rw ?? 0) }}">
    </div>
    <div class="col-md-3">
        <label class="bb-form-label">Jumlah Dusun</label>
        <input type="number" name="jumlah_dusun" class="bb-form-control" value="{{ old('jumlah_dusun', $kampung->jumlah_dusun ?? 0) }}">
    </div>
    <div class="col-md-4">
        <label class="bb-form-label">Status</label>
        <select name="status_definitif" class="bb-form-control">
            @foreach (['definitif' => 'Definitif', 'pjs' => 'PJS (Penjabat Sementara)', 'kosong' => 'Kosong'] as $val => $label)
                <option value="{{ $val }}" {{ old('status_definitif', $kampung->status_definitif ?? '') === $val ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-12">
        <label class="bb-form-label">Keterangan</label>
        <textarea name="keterangan" class="bb-form-control" rows="3">{{ old('keterangan', $kampung->keterangan ?? '') }}</textarea>
    </div>
</div>
<div class="mt-4 d-flex gap-2">
    <button type="submit" class="bb-btn bb-btn-primary"><i class="bi bi-save"></i> Simpan Data</button>
    <a href="{{ route('kampung.index') }}" class="bb-btn bb-btn-outline">Batal</a>
</div>
