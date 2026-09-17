<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pendaftarans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pendonor_id')->constrained('pendonors')->cascadeOnDelete();
            $table->foreignId('jadwal_donor_id')->constrained('jadwal_donors')->cascadeOnDelete();
            $table->enum('status', [
                'menunggu_screening',
                'gagal_screening',
                'proses_donor',
                'selesai'
            ]);
            $table->text('keterangan_medis')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pendaftarans');
    }
};
