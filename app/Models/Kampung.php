<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kampung extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_kampung',
        'nama_kampung',
        'nama_kepala_kampung',
        'nip_kepala_kampung',
        'alamat_kantor',
        'no_telepon',
        'luas_wilayah_km2',
        'jumlah_rt',
        'jumlah_rw',
        'jumlah_dusun',
        'status_definitif',
        'keterangan',
    ];

    protected $casts = [
        'luas_wilayah_km2' => 'float',
        'jumlah_rt' => 'integer',
        'jumlah_rw' => 'integer',
        'jumlah_dusun' => 'integer',
    ];

    public function penduduks(): HasMany
    {
        return $this->hasMany(Penduduk::class);
    }

    public function laporans(): HasMany
    {
        return $this->hasMany(Laporan::class);
    }

    public function jumlahPenduduk(): int
    {
        return $this->penduduks()->count();
    }

    public function jumlahKK(): int
    {
        return $this->penduduks()->distinct('nomor_kk')->count('nomor_kk');
    }
}
