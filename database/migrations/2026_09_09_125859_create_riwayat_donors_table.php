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
        Schema::create('riwayat__donors', function (Blueprint $table) {
            $table->id('id_riwayat_donor');

            $table->unsignedBigInteger('pendonor_id');

            $table->unsignedBigInteger('pemeriksaan_id')->nullable();

            $table->date('tanggal_donor');

            $table->integer('jumlah_donor');

            $table->enum('status', [
                'sukses',
                'gagal'
            ]);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('riwayat__donors');
    }
};