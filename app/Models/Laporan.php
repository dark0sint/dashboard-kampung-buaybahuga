<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Laporan extends Model
{
    use HasFactory;

    protected $fillable = [
        'kampung_id',
        'bulan',
        'tahun',
        'jumlah_kelahiran',
        'jumlah_kematian',
        'jumlah_pindah_masuk',
        'jumlah_pindah_keluar',
        'jumlah_kk_miskin',
        'catatan',
        'status_verifikasi',
        'diverifikasi_oleh',
        'diverifikasi_pada',
    ];

    protected $casts = [
        'diverifikasi_pada' => 'datetime',
    ];

    public function kampung(): BelongsTo
    {
        return $this->belongsTo(Kampung::class);
    }

    public function scopeStatus($query, string $status)
    {
        return $query->where('status_verifikasi', $status);
    }
}
