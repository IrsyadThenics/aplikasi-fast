<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('data', function (Blueprint $table) {
            $table->string('nama')->nullable()->after('ulp');
            $table->string('tanggal_ulp')->nullable()->after('nama');
            $table->string('total_biaya')->nullable()->after('daya_baru');
            $table->string('tanggal_bayar')->nullable()->after('total_biaya');
            $table->string('durasi_hari_kerja')->nullable()->after('tanggal_bayar');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('data', function (Blueprint $table) {
            $table->dropColumn(['nama', 'tanggal_ulp', 'total_biaya', 'tanggal_bayar', 'durasi_hari_kerja']);
        });
    }
};
