<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class LaporanBulananController extends Controller
{
    public function __invoke(Request $request): View
    {
        $validated = $request->validate([
            'bulan' => ['nullable', 'date_format:Y-m'],
        ]);

        $bulan = $validated['bulan'] ?? now()->format('Y-m');
        $awalBulan = Carbon::createFromFormat('!Y-m', $bulan);
        $akhirBulan = $awalBulan->copy()->endOfMonth();

        $penyaluranPerBatch = DB::table('permintaan_darah_penyalurans')
            ->groupBy('stok_darah_id')
            ->select('stok_darah_id')
            ->selectRaw('SUM(jumlah_kantong) as jumlah_keluar');

        $stokMasuk = DB::table('stok__darahs as stok')
            ->leftJoinSub($penyaluranPerBatch, 'riwayat_keluar', function ($join) {
                $join->on('riwayat_keluar.stok_darah_id', '=', 'stok.id_stok_darah');
            })
            ->whereBetween('stok.tanggal_masuk', [$awalBulan->toDateString(), $akhirBulan->toDateString()])
            ->sum(DB::raw('stok.jumlah_kantong + COALESCE(riwayat_keluar.jumlah_keluar, 0)'));

        $penyaluranBulan = DB::table('permintaan_darah_penyalurans')
            ->whereBetween('diserahkan_pada', [$awalBulan->copy()->startOfDay(), $akhirBulan->copy()->endOfDay()]);

        $stokKeluar = (clone $penyaluranBulan)->sum('jumlah_kantong');
        $jumlahPenyaluran = (clone $penyaluranBulan)->count();

        $penyaluranPerPermintaan = DB::table('permintaan_darah_penyalurans')
            ->whereBetween('diserahkan_pada', [$awalBulan->copy()->startOfDay(), $akhirBulan->copy()->endOfDay()])
            ->groupBy('permintaan_darah_id')
            ->select('permintaan_darah_id')
            ->selectRaw('SUM(jumlah_kantong) as jumlah_disalurkan');

        $rumahSakitTeratas = DB::table('permintaan__darahs as permintaan')
            ->leftJoin('rumah_sakits as rumah_sakit', 'rumah_sakit.id_rs', '=', 'permintaan.rumah_sakit_id')
            ->leftJoinSub($penyaluranPerPermintaan, 'penyaluran', function ($join) {
                $join->on('penyaluran.permintaan_darah_id', '=', 'permintaan.id_permintaan_darah');
            })
            ->whereBetween('permintaan.tanggal_permintaan', [$awalBulan->toDateString(), $akhirBulan->toDateString()])
            ->groupBy('permintaan.rumah_sakit_id', 'rumah_sakit.nama_rs')
            ->selectRaw("COALESCE(rumah_sakit.nama_rs, 'Faskes tidak tersedia') as nama_rumah_sakit")
            ->selectRaw('COUNT(permintaan.id_permintaan_darah) as jumlah_permintaan')
            ->selectRaw('SUM(permintaan.jumlah_minta) as jumlah_diminta')
            ->selectRaw('SUM(COALESCE(penyaluran.jumlah_disalurkan, 0)) as jumlah_disalurkan')
            ->orderByDesc('jumlah_permintaan')
            ->orderByDesc('jumlah_diminta')
            ->limit(10)
            ->get();

        $pergerakanGolongan = DB::table('stok__darahs as stok')
            ->leftJoinSub($penyaluranPerBatch, 'riwayat_keluar', function ($join) {
                $join->on('riwayat_keluar.stok_darah_id', '=', 'stok.id_stok_darah');
            })
            ->whereBetween('stok.tanggal_masuk', [$awalBulan->toDateString(), $akhirBulan->toDateString()])
            ->groupBy('stok.golongan_darah', 'stok.rhesus')
            ->select('stok.golongan_darah', 'stok.rhesus')
            ->selectRaw('SUM(stok.jumlah_kantong + COALESCE(riwayat_keluar.jumlah_keluar, 0)) as masuk')
            ->get()
            ->keyBy(fn ($row) => $row->golongan_darah.$row->rhesus);

        $keluarGolongan = DB::table('permintaan_darah_penyalurans as penyaluran')
            ->join('permintaan__darahs as permintaan', 'permintaan.id_permintaan_darah', '=', 'penyaluran.permintaan_darah_id')
            ->whereBetween('penyaluran.diserahkan_pada', [$awalBulan->copy()->startOfDay(), $akhirBulan->copy()->endOfDay()])
            ->groupBy('permintaan.golongan_darah', 'permintaan.rhesus')
            ->select('permintaan.golongan_darah', 'permintaan.rhesus')
            ->selectRaw('SUM(penyaluran.jumlah_kantong) as keluar')
            ->get()
            ->keyBy(fn ($row) => $row->golongan_darah.$row->rhesus);

        $ringkasanGolongan = collect(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'])
            ->map(fn (string $golongan) => [
                'golongan' => $golongan,
                'masuk' => (int) ($pergerakanGolongan->get($golongan)->masuk ?? 0),
                'keluar' => (int) ($keluarGolongan->get($golongan)->keluar ?? 0),
            ]);

        return view('laporan.bulanan', [
            'bulan' => $bulan,
            'labelBulan' => $awalBulan->translatedFormat('F Y'),
            'stokMasuk' => (int) $stokMasuk,
            'stokKeluar' => (int) $stokKeluar,
            'jumlahPenyaluran' => (int) $jumlahPenyaluran,
            'rumahSakitTeratas' => $rumahSakitTeratas,
            'ringkasanGolongan' => $ringkasanGolongan,
        ]);
    }
}
