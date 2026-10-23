<?php

namespace App\Http\Controllers;

use App\Models\StokDarah;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $stokDarahs = StokDarah::query()
            ->where('tanggal_kadaluarsa', '>=', today()->toDateString())
            ->where('jumlah_kantong', '>', 0)
            ->selectRaw('lokasi, golongan_darah, rhesus, SUM(jumlah_kantong) as jumlah_kantong, MAX(updated_at) as terakhir_diperbarui')
            ->groupBy('lokasi', 'golongan_darah', 'rhesus')
            ->orderBy('golongan_darah')
            ->orderBy('lokasi')
            ->get();

        $stokDarahPerGolongan = $stokDarahs->groupBy(fn (StokDarah $stok) => $stok->golongan_darah.$stok->rhesus);
        $stokTerakhirDiperbarui = $stokDarahs->max('terakhir_diperbarui');

        return view('landingpage.index', compact(
            'stokDarahPerGolongan',
            'stokTerakhirDiperbarui'
        ));
    }
}
