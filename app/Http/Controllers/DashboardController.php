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

            $totalPemeriksaan = DB::table('pemeriksaans')
                ->count();

            $screeningLayak = DB::table('pemeriksaans')
                ->where('hasil', 'layak')
                ->count();

            $screeningTidakLayak = DB::table('pemeriksaans')
                ->where('hasil', 'tidak layak')
                ->count();
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

            $totalRiwayatDonor =
                DB::table('riwayat__donors')->count();

            $donorSukses =
                DB::table('riwayat__donors')
                    ->where('status', 'sukses')
                    ->count();

            $donorGagal =
                DB::table('riwayat__donors')
                    ->where('status', 'gagal')
                    ->count();
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

            $totalPermintaan =
                DB::table('permintaan__darahs')->count();

            $permintaanDiproses =
                DB::table('permintaan__darahs')
                    ->where('status', 'diproses')
                    ->count();

            $permintaanDitolak =
                DB::table('permintaan__darahs')
                    ->where('status', 'ditolak')
                    ->count();

            if (Schema::hasColumn('permintaan__darahs', 'jumlah_terpenuhi')) {
                $permintaanMenungguPenyaluran = DB::table('permintaan__darahs')
                    ->where('status', 'diterima')
                    ->where('jumlah_terpenuhi', 0)
                    ->count();

                $permintaanSebagian = DB::table('permintaan__darahs')
                    ->where('status', 'diterima')
                    ->where('jumlah_terpenuhi', '>', 0)
                    ->whereColumn('jumlah_terpenuhi', '<', 'jumlah_minta')
                    ->count();

                $permintaanTerpenuhi = DB::table('permintaan__darahs')
                    ->where('status', 'diterima')
                    ->whereColumn('jumlah_terpenuhi', '>=', 'jumlah_minta')
                    ->count();
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

        if (Schema::hasTable('stok__darahs')) {

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

        /*
        |--------------------------------------------------------------------------
        | 8. DATA AKTIVITAS TERBARU
        |--------------------------------------------------------------------------
        */

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
            ->limit(5)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | 9. DONOR 7 HARI TERAKHIR
        |--------------------------------------------------------------------------
        */

        $chartLabels = [];
        $chartData = [];

        if (Schema::hasTable('riwayat__donors')) {

            for ($i = 6; $i >= 0; $i--) {

                $date = Carbon::today()
                    ->subDays($i);

                $chartLabels[] =
                    $date->translatedFormat('d M');

                $jumlah =
                    DB::table('riwayat__donors')
                        ->whereDate(
                            'tanggal_donor',
                            $date
                        )
                        ->where('status', 'sukses')
                        ->count();

                $chartData[] = $jumlah;
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
