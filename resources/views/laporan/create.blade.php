@extends('layouts.app')
@section('title', 'Ajukan Laporan Bulanan')

@section('content')
<div class="bb-panel" style="max-width:720px;">
    <div class="bb-panel-title">Ajukan Laporan Bulanan Kampung</div>
    <form method="POST" action="{{ route('laporan.store') }}">
        @csrf
        <div class="row g-3">
            <div class="col-md-6">
                <label class="bb-form-label">Kampung</label>
                <select name="kampung_id" class="bb-form-control" required>
                    @foreach ($kampungs as $k)
                        <option value="{{ $k->id }}" {{ auth()->user()->kampung_id == $k->id ? 'selected' : '' }}>{{ $k->nama_kampung }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="bb-form-label">Bulan</label>
                <select name="bulan" class="bb-form-control" required>
                    @foreach (range(1,12) as $m)
                        <option value="{{ $m }}" {{ now()->month == $m ? 'selected' : '' }}>{{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="bb-form-label">Tahun</label>
                <input type="number" name="tahun" class="bb-form-control" value="{{ now()->year }}" required>
            </div>
            <div class="col-md-3"><label class="bb-form-label">Jumlah Kelahiran</label><input type="number" name="jumlah_kelahiran" class="bb-form-control" value="0" min="0"></div>
            <div class="col-md-3"><label class="bb-form-label">Jumlah Kematian</label><input type="number" name="jumlah_kematian" class="bb-form-control" value="0" min="0"></div>
            <div class="col-md-3"><label class="bb-form-label">Pindah Masuk</label><input type="number" name="jumlah_pindah_masuk" class="bb-form-control" value="0" min="0"></div>
            <div class="col-md-3"><label class="bb-form-label">Pindah Keluar</label><input type="number" name="jumlah_pindah_keluar" class="bb-form-control" value="0" min="0"></div>
            <div class="col-md-4"><label class="bb-form-label">Jumlah KK Miskin</label><input type="number" name="jumlah_kk_miskin" class="bb-form-control" value="0" min="0"></div>
            <div class="col-12"><label class="bb-form-label">Catatan Tambahan</label><textarea name="catatan" class="bb-form-control" rows="3"></textarea></div>
        </div>
        <div class="mt-4 d-flex gap-2">
            <button type="submit" class="bb-btn bb-btn-primary"><i class="bi bi-send"></i> Ajukan ke Kecamatan</button>
            <a href="{{ route('laporan.index') }}" class="bb-btn bb-btn-outline">Batal</a>
        </div>
    </form>
</div>
@endsection
