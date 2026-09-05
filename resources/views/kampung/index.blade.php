@extends('layouts.app')
@section('title', 'Data Kampung')

@section('content')
<div class="bb-panel">
    <div class="bb-panel-title">
        Daftar 9 Kampung
        @if (auth()->user()->role === 'admin_kecamatan')
            <a href="{{ route('kampung.create') }}" class="bb-btn bb-btn-primary bb-btn-sm"><i class="bi bi-plus-lg"></i> Tambah Kampung</a>
        @endif
    </div>

    <form method="GET" class="mb-3" style="max-width:320px;">
        <input type="text" name="cari" value="{{ request('cari') }}" class="bb-form-control" placeholder="Cari nama kampung...">
    </form>

    <div class="table-responsive">
        <table class="bb-table">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Nama Kampung</th>
                    <th>Kepala Kampung</th>
                    <th>RT / RW</th>
                    <th>Penduduk</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($kampungs as $k)
                    <tr>
                        <td style="font-family:var(--font-mono); font-size:12.5px;">{{ $k->kode_kampung }}</td>
                        <td><strong>{{ $k->nama_kampung }}</strong></td>
                        <td>{{ $k->nama_kepala_kampung ?? '—' }}</td>
                        <td>{{ $k->jumlah_rt }} / {{ $k->jumlah_rw }}</td>
                        <td>{{ number_format($k->penduduks_count, 0, ',', '.') }} jiwa</td>
                        <td>
                            <span class="bb-badge {{ $k->status_definitif === 'definitif' ? 'bb-badge-green' : ($k->status_definitif === 'pjs' ? 'bb-badge-gold' : 'bb-badge-red') }}">
                                {{ ucfirst($k->status_definitif) }}
                            </span>
                        </td>
                        <td class="text-nowrap">
                            <a href="{{ route('kampung.show', $k) }}" class="bb-btn bb-btn-outline bb-btn-sm"><i class="bi bi-eye"></i></a>
                            @if (auth()->user()->role === 'admin_kecamatan')
                                <a href="{{ route('kampung.edit', $k) }}" class="bb-btn bb-btn-outline bb-btn-sm"><i class="bi bi-pencil"></i></a>
                                <form action="{{ route('kampung.destroy', $k) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus data kampung {{ $k->nama_kampung }}?')">
                                    @csrf @method('DELETE')
                                    <button class="bb-btn bb-btn-danger-ghost bb-btn-sm"><i class="bi bi-trash"></i></button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center text-muted py-4">Belum ada data kampung.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">{{ $kampungs->links() }}</div>
</div>
@endsection
