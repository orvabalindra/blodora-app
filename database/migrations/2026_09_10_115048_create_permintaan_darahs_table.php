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
        Schema::create('permintaan__darahs', function (Blueprint $table) {
            $table->id('id_permintaan_darah');

            $table->unsignedBigInteger('rumah_sakit_id');

            $table->date('tanggal_permintaan');

            $table->enum('golongan_darah', ['A', 'B', 'AB', 'O']);

            $table->enum('rhesus', ['+', '-']);

            $table->integer('jumlah_minta');

            $table->enum('status', [
                'diproses',
                'diterima',
                'ditolak'
            ]);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permintaan__darahs');
    }
};