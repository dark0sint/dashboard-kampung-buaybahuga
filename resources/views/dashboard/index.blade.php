@extends('layouts.app')
@section('title', 'Ringkasan Kecamatan')

@section('content')
<div class="bb-stat-grid">
    <div class="bb-stat-card">
        <div class="bb-stat-icon bb-ic-green"><i class="bi bi-people-fill"></i></div>
        <div class="bb-stat-label">Total Penduduk</div>
        <div class="bb-stat-value">{{ number_format($totalPenduduk, 0, ',', '.') }}</div>
        <div class="bb-stat-sub">Tersebar di 9 kampung</div>
    </div>
    <div class="bb-stat-card">
        <div class="bb-stat-icon bb-ic-gold"><i class="bi bi-house-door-fill"></i></div>
        <div class="bb-stat-label">Total Kepala Keluarga</div>
        <div class="bb-stat-value">{{ number_format($totalKK, 0, ',', '.') }}</div>
        <div class="bb-stat-sub">Berdasarkan Nomor KK unik</div>
    </div>
    <div class="bb-stat-card">
        <div class="bb-stat-icon bb-ic-blue"><i class="bi bi-signpost-split-fill"></i></div>
        <div class="bb-stat-label">Jumlah Kampung</div>
        <div class="bb-stat-value">9</div>
        <div class="bb-stat-sub">Wilayah Kec. Buay Bahuga</div>
    </div>
    <div class="bb-stat-card">
        <div class="bb-stat-icon bb-ic-rose"><i class="bi bi-clipboard2-x-fill"></i></div>
        <div class="bb-stat-label">Laporan Menunggu</div>
        <div class="bb-stat-value">{{ $laporanBelumDiverifikasi }}</div>
        <div class="bb-stat-sub">Perlu verifikasi kecamatan</div>
    </div>
</div>

<div class="bb-row-2">
    <div class="bb-panel">
        <div class="bb-panel-title">
            Jumlah Penduduk per Kampung
            <span class="bb-badge bb-badge-gray"><i class="bi bi-bar-chart-line"></i> Real-time</span>
        </div>
        <canvas id="chartPenduduk" height="230"></canvas>
    </div>
    <div class="bb-panel">
        <div class="bb-panel-title">Komposisi Jenis Kelamin</div>
        <canvas id="chartGender" height="230"></canvas>
        <div class="d-flex justify-content-around mt-3" style="font-size:13px;">
            <div><strong>{{ number_format($totalLaki,0,',','.') }}</strong> Laki-laki</div>
            <div><strong>{{ number_format($totalPerempuan,0,',','.') }}</strong> Perempuan</div>
        </div>
    </div>
</div>

<div class="bb-panel">
    <div class="bb-panel-title">
        Rekap Data 9 Kampung
        <a href="{{ route('kampung.index') }}" class="bb-btn bb-btn-outline bb-btn-sm">Kelola data kampung <i class="bi bi-arrow-right"></i></a>
    </div>
    <div class="table-responsive">
        <table class="bb-table">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Nama Kampung</th>
                    <th>Kepala Kampung</th>
                    <th>Jumlah Penduduk</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($kampungs as $k)
                    <tr>
                        <td class="text-muted" style="font-family:var(--font-mono); font-size:12.5px;">{{ $k->kode_kampung }}</td>
                        <td><strong>{{ $k->nama_kampung }}</strong></td>
                        <td>{{ $k->nama_kepala_kampung ?? '—' }}</td>
                        <td>{{ number_format($k->penduduks_count, 0, ',', '.') }} jiwa</td>
                        <td>
                            @if ($k->status_definitif === 'definitif')
                                <span class="bb-badge bb-badge-green">Definitif</span>
                            @elseif ($k->status_definitif === 'pjs')
                                <span class="bb-badge bb-badge-gold">PJS</span>
                            @else
                                <span class="bb-badge bb-badge-red">Kosong</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="bb-panel">
    <div class="bb-panel-title">
        Status Integrasi Layanan Python — Rekap Kecamatan
        @if ($rekapKecamatan)
            <span class="bb-badge bb-badge-green"><i class="bi bi-plug-fill"></i> Terhubung</span>
        @else
            <span class="bb-badge bb-badge-red"><i class="bi bi-plug-fill"></i> Belum Terhubung</span>
        @endif
    </div>
    <p style="font-size:13.5px; color:var(--bb-ink-soft); margin-bottom:0;">
        @if ($rekapKecamatan)
            Data 9 kampung terakhir disinkronkan dan diproses oleh layanan analitik Python untuk keperluan pelaporan resmi ke Kecamatan Buay Bahuga.
        @else
            Layanan Python (<code>python-service/app.py</code>) belum aktif atau tidak dapat dihubungi. Dashboard tetap berjalan normal menggunakan data lokal Laravel. Jalankan layanan Python lalu kunjungi menu <strong>Integrasi Kecamatan</strong> untuk menyinkronkan data.
        @endif
    </p>
</div>
@endsection

@section('scripts')
<script>
    new Chart(document.getElementById('chartPenduduk'), {
        type: 'bar',
        data: {
            labels: @json($kampungs->pluck('nama_kampung')),
            datasets: [{
                label: 'Jumlah Penduduk',
                data: @json($kampungs->pluck('penduduks_count')),
                backgroundColor: '#1F4738',
                borderRadius: 6,
                maxBarThickness: 38
            }]
        },
        options: {
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true, grid: { color: '#EDEFE9' } }, x: { grid: { display: false } } }
        }
    });

    new Chart(document.getElementById('chartGender'), {
        type: 'doughnut',
        data: {
            labels: ['Laki-laki', 'Perempuan'],
            datasets: [{ data: [{{ $totalLaki }}, {{ $totalPerempuan }}], backgroundColor: ['#1F4738', '#C6862E'] }]
        },
        options: { plugins: { legend: { position: 'bottom' } }, cutout: '68%' }
    });
</script>
@endsection
