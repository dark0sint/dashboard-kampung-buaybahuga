<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin_kecamatan', 'operator_kampung'])->default('operator_kampung')->after('email');
            $table->foreignId('kampung_id')->nullable()->after('role')->constrained('kampungs')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('kampung_id');
            $table->dropColumn('role');
        });
    }
};
