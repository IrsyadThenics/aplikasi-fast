<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vendor_reports', function (Blueprint $table) {
            $table->foreignId('vendor_user_id')->nullable()->after('vendor_name')->constrained('users')->nullOnDelete()->index();
        });
    }

    public function down(): void
    {
        Schema::table('vendor_reports', function (Blueprint $table) {
            $table->dropConstrainedForeignId('vendor_user_id');
        });
    }
};
