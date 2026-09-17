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
        Schema::create('pemeriksaans', function (Blueprint $table) {
            $table->id('id_pemeriksaan');

            $table->unsignedBigInteger('pendonor_id');

            $table->date('tanggal_pemeriksaan');

            $table->integer('berat_badan');

            $table->string('tekanan_darah');

            $table->decimal('hemoglobin', 4, 1);

            $table->enum('hasil', ['layak', 'tidak layak']);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pemeriksaans');
    }
};