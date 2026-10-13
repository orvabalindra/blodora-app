<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::hasColumn('permintaan__darahs', 'jumlah_terpenuhi')) {
            Schema::table('permintaan__darahs', function (Blueprint $table) {
                $table->unsignedInteger('jumlah_terpenuhi')
                    ->default(0)
                    ->after('jumlah_minta');
            });
        }

        if (! Schema::hasTable('permintaan_darah_penyalurans')) {
            Schema::create('permintaan_darah_penyalurans', function (Blueprint $table) {
                $table->id();
                $table->foreignId('permintaan_darah_id')
                    ->constrained('permintaan__darahs', 'id_permintaan_darah')
                    ->restrictOnDelete();
                $table->foreignId('stok_darah_id')
                    ->constrained('stok__darahs', 'id_stok_darah')
                    ->restrictOnDelete();
                $table->unsignedInteger('jumlah_kantong');
                $table->foreignId('diserahkan_oleh')
                    ->nullable()
                    ->constrained('users')
                    ->nullOnDelete();
                $table->timestamp('diserahkan_pada');
                $table->timestamps();
            });
        }

        if (! Schema::hasIndex('permintaan_darah_penyalurans', 'pd_penyaluran_req_deliv_idx')) {
            Schema::table('permintaan_darah_penyalurans', function (Blueprint $table) {
                $table->index(
                    ['permintaan_darah_id', 'diserahkan_pada'],
                    'pd_penyaluran_req_deliv_idx'
                );
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('permintaan_darah_penyalurans')) {
            $allocationsByStock = DB::table('permintaan_darah_penyalurans')
                ->select('stok_darah_id', DB::raw('SUM(jumlah_kantong) as jumlah_kantong'))
                ->groupBy('stok_darah_id')
                ->get();

            foreach ($allocationsByStock as $allocation) {
                DB::table('stok__darahs')
                    ->where('id_stok_darah', $allocation->stok_darah_id)
                    ->increment('jumlah_kantong', $allocation->jumlah_kantong);
            }
        }

        Schema::dropIfExists('permintaan_darah_penyalurans');

        if (Schema::hasColumn('permintaan__darahs', 'jumlah_terpenuhi')) {
            Schema::table('permintaan__darahs', function (Blueprint $table) {
                $table->dropColumn('jumlah_terpenuhi');
            });
        }
    }
};
