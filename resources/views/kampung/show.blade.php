@extends('layouts.app')
@section('title', 'Profil Kampung')

@section('content')
<div class="bb-row-2">
    <div class="bb-panel">
        <div class="bb-panel-title">Profil {{ $kampung->nama_kampung }}</div>
        <table class="bb-table">
            <tr><td style="width:200px;color:var(--bb-ink-soft);">Kode Kampung</td><td><strong>{{ $kampung->kode_kampung }}</strong></td></tr>
            <tr><td style="color:var(--bb-ink-soft);">Kepala Kampung</td><td>{{ $kampung->nama_kepala_kampung ?? '—' }}</td></tr>
            <tr><td style="color:var(--bb-ink-soft);">Alamat Kantor</td><td>{{ $kampung->alamat_kantor ?? '—' }}</td></tr>
            <tr><td style="color:var(--bb-ink-soft);">No. Telepon</td><td>{{ $kampung->no_telepon ?? '—' }}</td></tr>
            <tr><td style="color:var(--bb-ink-soft);">Luas Wilayah</td><td>{{ $kampung->luas_wilayah_km2 }} km²</td></tr>
            <tr><td style="color:var(--bb-ink-soft);">RT / RW / Dusun</td><td>{{ $kampung->jumlah_rt }} / {{ $kampung->jumlah_rw }} / {{ $kampung->jumlah_dusun }}</td></tr>
            <tr><td style="color:var(--bb-ink-soft);">Status</td><td><span class="bb-badge bb-badge-green">{{ ucfirst($kampung->status_definitif) }}</span></td></tr>
        </table>
    </div>
    <div class="bb-panel">
        <div class="bb-panel-title">Ringkasan Penduduk</div>
        <div class="bb-stat-value" style="font-size:38px;">{{ number_format($kampung->jumlahPenduduk(), 0, ',', '.') }}</div>
        <div class="bb-stat-sub mb-3">Total jiwa terdaftar</div>
        <a href="{{ route('penduduk.index', ['kampung_id' => $kampung->id]) }}" class="bb-btn bb-btn-outline w-100 justify-content-center">Lihat Data Penduduk</a>
    </div>
</div>

<div class="bb-panel">
    <div class="bb-panel-title">Laporan Bulanan Terakhir</div>
    <table class="bb-table">
        <thead><tr><th>Periode</th><th>Lahir</th><th>Meninggal</th><th>Pindah Masuk</th><th>Pindah Keluar</th><th>Status</th></tr></thead>
        <tbody>
            @forelse ($kampung->laporans as $l)
                <tr>
                    <td>{{ \Carbon\Carbon::create()->month($l->bulan)->translatedFormat('F') }} {{ $l->tahun }}</td>
                    <td>{{ $l->jumlah_kelahiran }}</td>
                    <td>{{ $l->jumlah_kematian }}</td>
                    <td>{{ $l->jumlah_pindah_masuk }}</td>
                    <td>{{ $l->jumlah_pindah_keluar }}</td>
                    <td><span class="bb-badge bb-badge-gold">{{ ucfirst($l->status_verifikasi) }}</span></td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center text-muted py-3">Belum ada laporan.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
