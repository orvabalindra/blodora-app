<?php

namespace App\Http\Controllers;

use App\Models\StokDarah;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $stokDarahs = StokDarah::query()
            ->whereDate('tanggal_kadaluarsa', '>=', today())
            ->where('jumlah_kantong', '>', 0)
            ->selectRaw('golongan_darah, SUM(jumlah_kantong) as jumlah_kantong')
            ->groupBy('golongan_darah')
            ->pluck('jumlah_kantong', 'golongan_darah');

        $stokDarahs = collect([
            'A' => 0,
            'B' => 0,
            'AB' => 0,
            'O' => 0,
        ])->merge($stokDarahs);

        return view('landingpage.index', compact('stokDarahs'));
    }
}
