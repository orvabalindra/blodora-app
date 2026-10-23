<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    public function index()
    {
        /*
        |--------------------------------------------------------------------------
        | CEK ROLE
        |--------------------------------------------------------------------------
        |
        | Admin    -> dashboard.admin
        | Pendonor -> dashboard.pendonor
        |
        */

        if (Auth::check() && Auth::user()->role === 'pendonor') {

            return $this->pendonorDashboard();
        }

        /*
        |--------------------------------------------------------------------------
        | DASHBOARD ADMIN
        |--------------------------------------------------------------------------
        */

        /*
        |--------------------------------------------------------------------------
        | 1. TOTAL PENDONOR
        |--------------------------------------------------------------------------
        */

        $totalPendonor = Schema::hasTable('pendonors')
            ? DB::table('pendonors')->count()
            : 0;

        /*
        |--------------------------------------------------------------------------
        | 2. TOTAL RUMAH SAKIT / FASKES
        |--------------------------------------------------------------------------
        */

        $totalRumahSakit = Schema::hasTable('rumah_sakits')
            ? DB::table('rumah_sakits')->count()
            : 0;

        /*
        |--------------------------------------------------------------------------
        | 3. DATA PEMERIKSAAN
        |--------------------------------------------------------------------------
        */

        $totalPemeriksaan = 0;
        $screeningLayak = 0;
        $screeningTidakLayak = 0;

        if (Schema::hasTable('pemeriksaans')) {
            $pemeriksaanStats = DB::table('pemeriksaans')
                ->selectRaw('COUNT(*) as total')
                ->selectRaw("SUM(CASE WHEN hasil = 'layak' THEN 1 ELSE 0 END) as layak")
                ->selectRaw("SUM(CASE WHEN hasil = 'tidak layak' THEN 1 ELSE 0 END) as tidak_layak")
                ->first();

            $totalPemeriksaan = (int) $pemeriksaanStats->total;
            $screeningLayak = (int) $pemeriksaanStats->layak;
            $screeningTidakLayak = (int) $pemeriksaanStats->tidak_layak;
        }

        /*
        |--------------------------------------------------------------------------
        | 4. RIWAYAT DONOR
        |--------------------------------------------------------------------------
        */

        $totalRiwayatDonor = 0;
        $donorSukses = 0;
        $donorGagal = 0;

        if (Schema::hasTable('riwayat__donors')) {
            $donorStats = DB::table('riwayat__donors')
                ->selectRaw('COUNT(*) as total')
                ->selectRaw("SUM(CASE WHEN status = 'sukses' THEN 1 ELSE 0 END) as sukses")
                ->selectRaw("SUM(CASE WHEN status = 'gagal' THEN 1 ELSE 0 END) as gagal")
                ->first();

            $totalRiwayatDonor = (int) $donorStats->total;
            $donorSukses = (int) $donorStats->sukses;
            $donorGagal = (int) $donorStats->gagal;
        }

        /*
        |--------------------------------------------------------------------------
        | 5. PERMINTAAN DARAH
        |--------------------------------------------------------------------------
        */

        $totalPermintaan = 0;
        $permintaanDiproses = 0;
        $permintaanMenungguPenyaluran = 0;
        $permintaanDitolak = 0;
        $permintaanSebagian = 0;
        $permintaanTerpenuhi = 0;

        if (Schema::hasTable('permintaan__darahs')) {

            $permintaanStats = DB::table('permintaan__darahs')
                ->selectRaw('COUNT(*) as total')
                ->selectRaw("SUM(CASE WHEN status = 'diproses' THEN 1 ELSE 0 END) as diproses")
                ->selectRaw("SUM(CASE WHEN status = 'ditolak' THEN 1 ELSE 0 END) as ditolak")
                ->first();

            $totalPermintaan = (int) $permintaanStats->total;
            $permintaanDiproses = (int) $permintaanStats->diproses;
            $permintaanDitolak = (int) $permintaanStats->ditolak;

            if (Schema::hasColumn('permintaan__darahs', 'jumlah_terpenuhi')) {
                $penyaluranStats = DB::table('permintaan__darahs')
                    ->where('status', 'diterima')
                    ->selectRaw('SUM(CASE WHEN jumlah_terpenuhi = 0 THEN 1 ELSE 0 END) as menunggu')
                    ->selectRaw('SUM(CASE WHEN jumlah_terpenuhi > 0 AND jumlah_terpenuhi < jumlah_minta THEN 1 ELSE 0 END) as sebagian')
                    ->selectRaw('SUM(CASE WHEN jumlah_terpenuhi >= jumlah_minta THEN 1 ELSE 0 END) as terpenuhi')
                    ->first();

                $permintaanMenungguPenyaluran = (int) $penyaluranStats->menunggu;
                $permintaanSebagian = (int) $penyaluranStats->sebagian;
                $permintaanTerpenuhi = (int) $penyaluranStats->terpenuhi;
            } else {
                $permintaanMenungguPenyaluran = DB::table('permintaan__darahs')
                    ->where('status', 'diterima')
                    ->count();
            }
        }

        /*
        |--------------------------------------------------------------------------
        | 6. STOK DARAH
        |--------------------------------------------------------------------------
        */

        $stok = [
            'A+' => 0,
            'A-' => 0,
            'B+' => 0,
            'B-' => 0,
            'AB+' => 0,
            'AB-' => 0,
            'O+' => 0,
            'O-' => 0,
        ];

        $totalStok = 0;
        $stokAkanKedaluwarsa = collect();
        $jumlahStokKedaluwarsa = 0;

        if (Schema::hasTable('stok__darahs')) {

            $jumlahStokKedaluwarsa = DB::table('stok__darahs')
                ->whereDate('tanggal_kadaluarsa', '<', Carbon::today())
                ->where('jumlah_kantong', '>', 0)
                ->sum('jumlah_kantong');

            $stokAkanKedaluwarsa = DB::table('stok__darahs')
                ->whereBetween('tanggal_kadaluarsa', [Carbon::today(), Carbon::today()->copy()->addDays(30)])
                ->where('jumlah_kantong', '>', 0)
                ->orderBy('tanggal_kadaluarsa')
                ->limit(5)
                ->get(['id_stok_darah', 'golongan_darah', 'rhesus', 'jumlah_kantong', 'tanggal_kadaluarsa']);

            $stokRows = DB::table('stok__darahs')
                ->whereDate(
                    'tanggal_kadaluarsa',
                    '>=',
                    Carbon::today()
                )
                ->get([
                    'golongan_darah',
                    'rhesus',
                    'jumlah_kantong',
                ]);

            foreach ($stokRows as $row) {

                $golongan = strtoupper(
                    trim($row->golongan_darah)
                );

                $rhesus = trim($row->rhesus);

                if (
                    strtolower($rhesus) === 'positif' ||
                    $rhesus === '+'
                ) {
                    $rhesus = '+';
                } elseif (
                    strtolower($rhesus) === 'negatif' ||
                    $rhesus === '-'
                ) {
                    $rhesus = '-';
                }

                $key = $golongan.$rhesus;

                if (array_key_exists($key, $stok)) {

                    $jumlah = (int) $row->jumlah_kantong;

                    $stok[$key] += $jumlah;

                    $totalStok += $jumlah;
                }
            }
        }

        /*
        |--------------------------------------------------------------------------
        | 7. STOK PER GOLONGAN UTAMA
        |--------------------------------------------------------------------------
        */

        $stokGolongan = [

            'A' => $stok['A+'] + $stok['A-'],

            'B' => $stok['B+'] + $stok['B-'],

            'AB' => $stok['AB+'] + $stok['AB-'],

            'O' => $stok['O+'] + $stok['O-'],
        ];

        $stokMenipis = collect($stokGolongan)
            ->filter(fn (int $jumlah) => $jumlah <= 5)
            ->map(fn (int $jumlah, string $golongan) => ['golongan' => $golongan, 'jumlah' => $jumlah])
            ->values();

        /*
        |--------------------------------------------------------------------------
        | 8. DATA AKTIVITAS TERBARU
        |--------------------------------------------------------------------------
        */

        $aktivitas = collect();

        if (Schema::hasTable('riwayat__donors') && Schema::hasTable('pendonors')) {
            $aktivitas = DB::table('riwayat__donors')
                ->join(
                    'pendonors',
                    'riwayat__donors.pendonor_id',
                    '=',
                    'pendonors.id'
                )
                ->select(
                    'riwayat__donors.id_riwayat_donor',
                    'riwayat__donors.tanggal_donor',
                    'riwayat__donors.jumlah_donor',
                    'riwayat__donors.status',
                    'pendonors.nama_lengkap',
                    'pendonors.golongan_darah',
                    'pendonors.rhesus'
                )
                ->orderByDesc(
                    'riwayat__donors.id_riwayat_donor'
                )
                ->whereBetween('riwayat__donors.tanggal_donor', [Carbon::today()->subDays(6), Carbon::today()->endOfDay()])
                ->limit(5)
                ->get();
        }

        /*
        |--------------------------------------------------------------------------
        | 9. DONOR 7 HARI TERAKHIR
        |--------------------------------------------------------------------------
        */

        $chartLabels = [];
        $chartData = [];

        if (Schema::hasTable('riwayat__donors')) {

            $today = Carbon::today();
            $startDate = $today->copy()->subDays(6);

            for ($i = 6; $i >= 0; $i--) {

                $date = $today->copy()->subDays($i);

                $chartLabels[] =
                    $date->translatedFormat('d M');

                $chartData[] = 0;
            }

            $jumlahPerHari = DB::table('riwayat__donors')
                ->where('status', 'sukses')
                ->whereBetween('tanggal_donor', [
                    $startDate->copy()->startOfDay(),
                    $today->copy()->endOfDay(),
                ])
                ->selectRaw('DATE(tanggal_donor) as tanggal, COUNT(*) as total')
                ->groupByRaw('DATE(tanggal_donor)')
                ->pluck('total', 'tanggal');

            for ($i = 0; $i < 7; $i++) {
                $dateKey = $startDate->copy()->addDays($i)->toDateString();
                $chartData[$i] = (int) ($jumlahPerHari[$dateKey] ?? 0);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | 10. PERMINTAAN BERDASARKAN GOLONGAN
        |--------------------------------------------------------------------------
        */

        $permintaanGolongan = [
            'A' => 0,
            'B' => 0,
            'AB' => 0,
            'O' => 0,
        ];

        if (Schema::hasTable('permintaan__darahs')) {

            $rows =
                DB::table('permintaan__darahs')
                    ->select(
                        'golongan_darah',
                        DB::raw('COUNT(*) as total')
                    )
                    ->groupBy('golongan_darah')
                    ->get();

            foreach ($rows as $row) {

                $golongan =
                    strtoupper(
                        trim($row->golongan_darah)
                    );

                if (isset($permintaanGolongan[$golongan])) {

                    $permintaanGolongan[$golongan]
                        = (int) $row->total;
                }
            }
        }

        return view(
            'dashboard.admin',
            compact(
                'totalPendonor',
                'totalRumahSakit',
                'totalPemeriksaan',
                'screeningLayak',
                'screeningTidakLayak',
                'totalRiwayatDonor',
                'donorSukses',
                'donorGagal',
                'totalPermintaan',
                'permintaanDiproses',
                'permintaanMenungguPenyaluran',
                'permintaanDitolak',
                'permintaanSebagian',
                'permintaanTerpenuhi',
                'stok',
                'stokGolongan',
                'totalStok',
                'stokMenipis',
                'stokAkanKedaluwarsa',
                'jumlahStokKedaluwarsa',
                'aktivitas',
                'chartLabels',
                'chartData',
                'permintaanGolongan'
            )
        );
    }

    /*
|--------------------------------------------------------------------------
| DASHBOARD PENDONOR
|--------------------------------------------------------------------------
*/

    private function pendonorDashboard()
    {
        $user = Auth::user();

        /*
        |--------------------------------------------------------------------------
        | DATA PROFIL PENDONOR
        |--------------------------------------------------------------------------
        */

        $pendonor = $user->pendonor;

        /*
        |--------------------------------------------------------------------------
        | Jika profil belum dibuat
        |--------------------------------------------------------------------------
        */

        if (! $pendonor) {

            return view(
                'dashboard.pendonor',
                [
                    'pendonor' => null,
                    'totalDonor' => 0,
                    'pemeriksaanTerakhir' => null,
                    'riwayatDonors' => collect(),
                    'pendaftaranTerakhir' => null,
                ]
            );
        }

        $pendaftaranTerakhir = $pendonor->pendaftarans()
            ->with([
                'jadwalDonor',
                'pemeriksaans' => fn (HasMany $query): HasMany => $query->latest('id_pemeriksaan'),
            ])
            ->latest()
            ->first();

        /*
        |--------------------------------------------------------------------------
        | TOTAL DONOR SUKSES
        |--------------------------------------------------------------------------
        */

        $totalDonor = 0;

        if (Schema::hasTable('riwayat__donors')) {

            $totalDonor = DB::table('riwayat__donors')
                ->where(
                    'pendonor_id',
                    $pendonor->id
                )
                ->where(
                    'status',
                    'sukses'
                )
                ->count();
        }

        /*
        |--------------------------------------------------------------------------
        | PEMERIKSAAN TERAKHIR
        |--------------------------------------------------------------------------
        */

        $pemeriksaanTerakhir = null;

        if (Schema::hasTable('pemeriksaans')) {

            $pemeriksaanTerakhir = DB::table('pemeriksaans')
                ->where(
                    'pendonor_id',
                    $pendonor->id
                )
                ->orderByDesc(
                    'tanggal_pemeriksaan'
                )
                ->orderByDesc(
                    'id_pemeriksaan'
                )
                ->first();

            /*
            | Ubah tanggal string menjadi Carbon
            */

            if (
                $pemeriksaanTerakhir &&
                ! empty($pemeriksaanTerakhir->tanggal_pemeriksaan)
            ) {

                $pemeriksaanTerakhir->tanggal_pemeriksaan =
                    Carbon::parse(
                        $pemeriksaanTerakhir->tanggal_pemeriksaan
                    );
            }
        }

        /*
        |--------------------------------------------------------------------------
        | RIWAYAT DONOR TERBARU
        |--------------------------------------------------------------------------
        */

        $riwayatDonors = collect();

        if (Schema::hasTable('riwayat__donors')) {

            $riwayatDonors = DB::table('riwayat__donors')
                ->where(
                    'pendonor_id',
                    $pendonor->id
                )
                ->orderByDesc(
                    'tanggal_donor'
                )
                ->orderByDesc(
                    'id_riwayat_donor'
                )
                ->limit(3)
                ->get();

            /*
            | Ubah tanggal riwayat menjadi Carbon
            */

            foreach ($riwayatDonors as $riwayat) {

                if (! empty($riwayat->tanggal_donor)) {

                    $riwayat->tanggal_donor =
                        Carbon::parse(
                            $riwayat->tanggal_donor
                        );
                }
            }
        }

        /*
        |--------------------------------------------------------------------------
        | DASHBOARD PENDONOR
        |--------------------------------------------------------------------------
        */

        return view(
            'dashboard.pendonor',
            compact(
                'pendonor',
                'totalDonor',
                'pemeriksaanTerakhir',
                'riwayatDonors',
                'pendaftaranTerakhir'
            )
        );
    }
}
