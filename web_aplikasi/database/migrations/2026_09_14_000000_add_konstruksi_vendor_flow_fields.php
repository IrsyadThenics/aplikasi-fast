<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pengiriman_data', function (Blueprint $table) {
            $table->boolean('konstruksi_vendor_sent')->default(false)->after('vendor_sent');
            $table->timestamp('konstruksi_vendor_sent_at')->nullable()->after('konstruksi_vendor_sent');
        });
        Schema::table('vendor_reports', function (Blueprint $table) {
            $table->string('recipient_role')->nullable()->after('vendor_user_id')->index();
        });
    }

    public function down(): void
    {
        Schema::table('vendor_reports', fn (Blueprint $table) => $table->dropColumn('recipient_role'));
        Schema::table('pengiriman_data', fn (Blueprint $table) => $table->dropColumn(['konstruksi_vendor_sent', 'konstruksi_vendor_sent_at']));
    }
};
