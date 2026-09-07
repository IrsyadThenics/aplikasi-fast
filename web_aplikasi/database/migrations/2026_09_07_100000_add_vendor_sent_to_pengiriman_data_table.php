<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pengiriman_data', function (Blueprint $table) {
            $table->boolean('vendor_sent')->default(false)->after('sentAt');
            $table->string('vendor_pt')->nullable()->after('vendor_sent');
            $table->string('vendor_status_layak')->nullable()->after('vendor_pt');
            $table->timestamp('vendor_sent_at')->nullable()->after('vendor_status_layak');
        });
    }

    public function down(): void
    {
        Schema::table('pengiriman_data', function (Blueprint $table) {
            $table->dropColumn(['vendor_sent', 'vendor_pt', 'vendor_status_layak', 'vendor_sent_at']);
        });
    }
};
