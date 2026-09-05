@extends('layouts.app')
@section('title', 'Integrasi Kecamatan')

@section('content')
<div class="bb-panel">
    <div class="bb-panel-title">
        Rekap Resmi Tingkat Kecamatan Buay Bahuga
        @if (auth()->user()->role === 'admin_kecamatan')
            <form method="POST" action="{{ route('integrasi.sinkron') }}">
                @csrf
                <button class="bb-btn bb-btn-gold bb-btn-sm"><i class="bi bi-arrow-repeat"></i> Sinkronkan Sekarang</button>
            </form>
        @endif
    </div>

    <p style="font-size:13.5px; color:var(--bb-ink-soft);">
        Rekap ini dihasilkan oleh layanan Python (<code>python-service/app.py</code>) yang mengagregasi data dari
        9 kampung — Bumiharjo, Lebung Lawe, Nuar Maju, Punjul Agung, Sri Tunggal, Suka Agung, Sukabumi, Sukadana, dan Way Agung —
        menjadi satu rekap resmi Kecamatan Buay Bahuga.
    </p>

    @if ($rekap)
        <div class="bb-stat-grid" style="grid-template-columns: repeat(3, 1fr);">
            <div class="bb-stat-card">
                <div class="bb-stat-label">Total Penduduk Kecamatan</div>
                <div class="bb-stat-value">{{ number_format($rekap['total_penduduk'] ?? 0, 0, ',', '.') }}</div>
            </div>
            <div class="bb-stat-card">
                <div class="bb-stat-label">Total Kelahiran (Tahun Berjalan)</div>
                <div class="bb-stat-value">{{ number_format($rekap['total_kelahiran'] ?? 0, 0, ',', '.') }}</div>
            </div>
            <div class="bb-stat-card">
                <div class="bb-stat-label">Terakhir Diproses</div>
                <div class="bb-stat-value" style="font-size:16px;">{{ $rekap['diproses_pada'] ?? '—' }}</div>
            </div>
        </div>
    @else
        <div class="alert-bb-error">
            Belum ada data rekap dari layanan Python. Pastikan <code>python-service</code> berjalan di
            <code>{{ config('services.python.url') }}</code>, lalu klik "Sinkronkan Sekarang".
        </div>
    @endif
</div>
@endsection
