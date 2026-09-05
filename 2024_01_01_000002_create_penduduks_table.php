<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penduduks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kampung_id')->constrained('kampungs')->cascadeOnDelete();
            $table->string('nik', 16)->unique();
            $table->string('nomor_kk', 16);
            $table->string('nama_lengkap', 100);
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->string('tempat_lahir', 100)->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->string('agama', 20)->nullable();
            $table->string('pekerjaan', 100)->nullable();
            $table->string('pendidikan_terakhir', 50)->nullable();
            $table->enum('status_perkawinan', ['belum_kawin', 'kawin', 'cerai_hidup', 'cerai_mati'])->default('belum_kawin');
            $table->text('alamat')->nullable();
            $table->string('rt', 5)->nullable();
            $table->string('rw', 5)->nullable();
            $table->timestamps();

            $table->index(['kampung_id', 'jenis_kelamin']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penduduks');
    }
};
