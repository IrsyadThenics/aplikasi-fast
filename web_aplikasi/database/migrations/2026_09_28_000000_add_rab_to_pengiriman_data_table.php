<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pengiriman_data', function (Blueprint $table) {
            $table->bigInteger('rab')->default(0)->after('total_biaya');
        });
    }

    public function down(): void
    {
        Schema::table('pengiriman_data', function (Blueprint $table) {
            $table->dropColumn('rab');
        });
    }
};
