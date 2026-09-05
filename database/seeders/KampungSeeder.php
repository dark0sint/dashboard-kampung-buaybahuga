<?php

namespace Database\Seeders;

use App\Models\Kampung;
use Illuminate\Database\Seeder;

class KampungSeeder extends Seeder
{
    /**
     * 9 kampung di wilayah Kecamatan Buay Bahuga, Kabupaten Way Kanan, Lampung.
     */
    public function run(): void
    {
        $kampungs = [
            ['kode' => '18.08.13.2001', 'nama' => 'Bumiharjo'],
            ['kode' => '18.08.13.2002', 'nama' => 'Lebung Lawe'],
            ['kode' => '18.08.13.2003', 'nama' => 'Nuar Maju'],
            ['kode' => '18.08.13.2004', 'nama' => 'Punjul Agung'],
            ['kode' => '18.08.13.2005', 'nama' => 'Sri Tunggal'],
            ['kode' => '18.08.13.2006', 'nama' => 'Suka Agung'],
            ['kode' => '18.08.13.2007', 'nama' => 'Sukabumi'],
            ['kode' => '18.08.13.2008', 'nama' => 'Sukadana'],
            ['kode' => '18.08.13.2009', 'nama' => 'Way Agung'],
        ];

        foreach ($kampungs as $item) {
            Kampung::updateOrCreate(
                ['kode_kampung' => $item['kode']],
                [
                    'nama_kampung' => $item['nama'],
                    'nama_kepala_kampung' => null,
                    'alamat_kantor' => 'Kantor Kepala Kampung ' . $item['nama'] . ', Kec. Buay Bahuga, Kab. Way Kanan',
                    'luas_wilayah_km2' => 0,
                    'jumlah_rt' => 0,
                    'jumlah_rw' => 0,
                    'jumlah_dusun' => 0,
                    'status_definitif' => 'definitif',
                ]
            );
        }
    }
}
