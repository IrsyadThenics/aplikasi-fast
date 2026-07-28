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
        Schema::create('data_pb_pd__u_p3_s', function (Blueprint $table) {
            $table->id();
            $table->string('dtl');
            $table->string('ulp');
            $table->string('transaksi');
            $table->string('status');
            $table->integer('no_agenda');
            $table->string('alamat');
            $table->string('tarif_lama')->nullable();
            $table->integer('daya_lama')->default(0);
            $table->string('tarif_baru')->nullable();
            $table->integer('daya_baru')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_pb_pd__u_p3_s');
    }
};
