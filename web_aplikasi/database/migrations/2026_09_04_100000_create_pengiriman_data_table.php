<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengiriman_data', function (Blueprint $table) {
            $table->id();
            $table->string('agendaKey');
            $table->string('dest');
            $table->string('no_agenda');
            $table->string('nama')->nullable();
            $table->string('alamat')->nullable();
            $table->string('transaksi')->nullable();
            $table->string('status')->nullable();
            $table->string('tarif_lama')->nullable();
            $table->integer('daya_lama')->default(0);
            $table->string('tarif_baru')->nullable();
            $table->integer('daya_baru')->default(0);
            $table->bigInteger('total_biaya')->default(0);
            $table->string('ulp')->nullable();
            $table->integer('ktpCount')->default(0);
            $table->integer('ittCount')->default(0);
            $table->timestamp('sentAt')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengiriman_data');
    }
};
