<?php

namespace Database\Seeders;

use App\Models\Kampung;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            KampungSeeder::class,
        ]);

        // Akun admin kecamatan (super admin)
        User::updateOrCreate(
            ['email' => 'admin@buaybahuga.go.id'],
            [
                'name' => 'Admin Kecamatan Buay Bahuga',
                'password' => Hash::make('BuayBahuga#2026'),
                'role' => 'admin_kecamatan',
                'kampung_id' => null,
                'email_verified_at' => now(),
            ]
        );

        // Contoh akun operator tiap kampung (password default, wajib diganti)
        Kampung::all()->each(function (Kampung $kampung) {
            $slug = str()->slug($kampung->nama_kampung, '');
            User::updateOrCreate(
                ['email' => "operator.$slug@buaybahuga.go.id"],
                [
                    'name' => 'Operator Kampung ' . $kampung->nama_kampung,
                    'password' => Hash::make('Operator#2026'),
                    'role' => 'operator_kampung',
                    'kampung_id' => $kampung->id,
                    'email_verified_at' => now(),
                ]
            );
        });
    }
}
