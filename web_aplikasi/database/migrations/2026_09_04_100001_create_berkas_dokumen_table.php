<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('berkas_dokumen', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pengiriman_id');
            $table->string('jenis_berkas'); // ktp/itt/wo/excel
            $table->string('nama_file');
            $table->string('path_file');
            $table->timestamps();

            $table->foreign('pengiriman_id')->references('id')->on('pengiriman_data')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('berkas_dokumen');
    }
};
