<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('stok__darahs')) {
            Schema::create('stok__darahs', function (Blueprint $table) {
                $table->id('id_stok_darah');
                $table->unsignedBigInteger('pendonor_id');
                $table->enum('golongan_darah', ['A', 'B', 'AB', 'O']);
                $table->enum('rhesus', ['+', '-']);
                $table->integer('jumlah_kantong');
                $table->date('tanggal_masuk');
                $table->date('tanggal_kadaluarsa');
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('stok__darahs');
    }
};