<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kampungs', function (Blueprint $table) {
            $table->id();
            $table->string('kode_kampung', 20)->unique();
            $table->string('nama_kampung', 100);
            $table->string('nama_kepala_kampung', 100)->nullable();
            $table->string('nip_kepala_kampung', 30)->nullable();
            $table->text('alamat_kantor')->nullable();
            $table->string('no_telepon', 20)->nullable();
            $table->decimal('luas_wilayah_km2', 8, 2)->default(0);
            $table->unsignedInteger('jumlah_rt')->default(0);
            $table->unsignedInteger('jumlah_rw')->default(0);
            $table->unsignedInteger('jumlah_dusun')->default(0);
            $table->enum('status_definitif', ['definitif', 'pjs', 'kosong'])->default('definitif');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kampungs');
    }
};
