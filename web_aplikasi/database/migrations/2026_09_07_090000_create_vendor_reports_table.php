<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vendor_reports', function (Blueprint $table) {
            $table->id();
            $table->string('no_agenda')->nullable()->index();
            $table->string('vendor_name')->nullable();
            $table->json('checklist')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();
        });

        Schema::create('vendor_report_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_report_id')->constrained('vendor_reports')->cascadeOnDelete();
            $table->string('nama_file');
            $table->string('path_file');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vendor_report_files');
        Schema::dropIfExists('vendor_reports');
    }
};
