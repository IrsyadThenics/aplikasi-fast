<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pengiriman_data', function (Blueprint $table) {
            $table->foreignId('konstruksi_vendor_user_id')->nullable()->after('konstruksi_vendor_sent_at')->constrained('users')->nullOnDelete()->index();
        });
    }

    public function down(): void
    {
        Schema::table('pengiriman_data', function (Blueprint $table) {
            $table->dropConstrainedForeignId('konstruksi_vendor_user_id');
        });
    }
};
