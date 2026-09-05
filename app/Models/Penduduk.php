<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Penduduk extends Model
{
    use HasFactory;

    protected $table = 'penduduks';

    protected $fillable = [
        'kampung_id',
        'nik',
        'nomor_kk',
        'nama_lengkap',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'agama',
        'pekerjaan',
        'pendidikan_terakhir',
        'status_perkawinan',
        'alamat',
        'rt',
        'rw',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    public function kampung(): BelongsTo
    {
        return $this->belongsTo(Kampung::class);
    }
}
