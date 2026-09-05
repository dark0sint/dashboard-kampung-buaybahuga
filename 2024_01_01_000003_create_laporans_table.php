<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('laporans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kampung_id')->constrained('kampungs')->cascadeOnDelete();
            $table->unsignedTinyInteger('bulan');
            $table->unsignedSmallInteger('tahun');
            $table->unsignedInteger('jumlah_kelahiran')->default(0);
            $table->unsignedInteger('jumlah_kematian')->default(0);
            $table->unsignedInteger('jumlah_pindah_masuk')->default(0);
            $table->unsignedInteger('jumlah_pindah_keluar')->default(0);
            $table->unsignedInteger('jumlah_kk_miskin')->default(0);
            $table->text('catatan')->nullable();
            $table->enum('status_verifikasi', ['draft', 'diajukan', 'diverifikasi', 'ditolak'])->default('draft');
            $table->string('diverifikasi_oleh', 100)->nullable();
            $table->timestamp('diverifikasi_pada')->nullable();
            $table->timestamps();

            $table->unique(['kampung_id', 'bulan', 'tahun']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laporans');
    }
};
