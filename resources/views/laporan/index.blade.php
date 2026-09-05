@extends('layouts.app')
@section('title', 'Laporan Bulanan')

@section('content')
<div class="bb-panel">
    <div class="bb-panel-title">
        Laporan Bulanan Kampung
        <a href="{{ route('laporan.create') }}" class="bb-btn bb-btn-primary bb-btn-sm"><i class="bi bi-plus-lg"></i> Ajukan Laporan</a>
    </div>
    <div class="table-responsive">
        <table class="bb-table">
            <thead>
                <tr><th>Kampung</th><th>Periode</th><th>Lahir</th><th>Meninggal</th><th>Pindah Masuk</th><th>Pindah Keluar</th><th>Status</th><th></th></tr>
            </thead>
            <tbody>
                @forelse ($laporans as $l)
                    <tr>
                        <td><strong>{{ $l->kampung->nama_kampung }}</strong></td>
                        <td>{{ \Carbon\Carbon::create()->month($l->bulan)->translatedFormat('F') }} {{ $l->tahun }}</td>
                        <td>{{ $l->jumlah_kelahiran }}</td>
                        <td>{{ $l->jumlah_kematian }}</td>
                        <td>{{ $l->jumlah_pindah_masuk }}</td>
                        <td>{{ $l->jumlah_pindah_keluar }}</td>
                        <td>
                            @php
                                $badge = ['draft' => 'bb-badge-gray', 'diajukan' => 'bb-badge-gold', 'diverifikasi' => 'bb-badge-green', 'ditolak' => 'bb-badge-red'][$l->status_verifikasi];
                            @endphp
                            <span class="bb-badge {{ $badge }}">{{ ucfirst($l->status_verifikasi) }}</span>
                        </td>
                        <td class="text-nowrap">
                            @if (auth()->user()->role === 'admin_kecamatan' && $l->status_verifikasi === 'diajukan')
                                <form action="{{ route('laporan.verifikasi', $l) }}" method="POST" class="d-inline">
                                    @csrf @method('PUT')
                                    <input type="hidden" name="status_verifikasi" value="diverifikasi">
                                    <button class="bb-btn bb-btn-primary bb-btn-sm"><i class="bi bi-check2"></i> Verifikasi</button>
                                </form>
                                <form action="{{ route('laporan.verifikasi', $l) }}" method="POST" class="d-inline">
                                    @csrf @method('PUT')
                                    <input type="hidden" name="status_verifikasi" value="ditolak">
                                    <button class="bb-btn bb-btn-danger-ghost bb-btn-sm"><i class="bi bi-x"></i> Tolak</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="text-center text-muted py-4">Belum ada laporan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-3">{{ $laporans->links() }}</div>
</div>
@endsection
