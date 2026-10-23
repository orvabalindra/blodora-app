<?php

namespace Tests\Feature;

use App\Models\Pendonor;
use App\Models\PermintaanDarah;
use App\Models\PermintaanDarahPenyaluran;
use App\Models\RumahSakit;
use App\Models\StokDarah;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LaporanBulananTest extends TestCase
{
    use RefreshDatabase;

    public function test_selected_month_is_correct_for_months_with_numeric_array_keys(): void
    {
        $admin = $this->createAdmin();

        foreach (['10' => 'Oktober', '11' => 'November', '12' => 'Desember'] as $month => $label) {
            $this->actingAs($admin)
                ->get(route('laporan.bulanan', ['bulan' => '2026-'.$month]))
                ->assertOk()
                ->assertSee('<option value="'.$month.'" selected>'.$label.'</option>', false);
        }
    }

    public function test_hospital_report_excludes_deliveries_made_outside_selected_month(): void
    {
        $admin = $this->createAdmin();
        $donorUser = User::query()->create([
            'username' => 'donor-month-report',
            'email' => 'donor-month-report@example.test',
            'password' => Hash::make('password'),
            'role' => 'pendonor',
        ]);
        $pendonor = Pendonor::query()->create([
            'user_id' => $donorUser->id,
            'nik' => '1234567890123456',
            'nama_lengkap' => 'Pendonor Laporan',
            'no_hp' => '081234567890',
            'alamat' => 'Jalan Contoh',
            'golongan_darah' => 'O',
            'rhesus' => '+',
            'berat_badan' => 60,
        ]);
        $rumahSakit = RumahSakit::query()->create([
            'nama_rs' => 'Rumah Sakit Bulanan',
            'alamat' => 'Alamat rumah sakit',
            'no_telp' => '081234567891',
        ]);
        $permintaan = PermintaanDarah::query()->create([
            'rumah_sakit_id' => $rumahSakit->id_rs,
            'tanggal_permintaan' => '2026-10-05',
            'golongan_darah' => 'O',
            'rhesus' => '+',
            'jumlah_minta' => 4,
            'jumlah_terpenuhi' => 2,
            'status' => 'diterima',
        ]);
        $stok = StokDarah::query()->create([
            'pendonor_id' => $pendonor->id,
            'golongan_darah' => 'O',
            'rhesus' => '+',
            'jumlah_kantong' => 0,
            'tanggal_masuk' => '2026-09-01',
            'tanggal_kadaluarsa' => '2026-12-01',
        ]);
        PermintaanDarahPenyaluran::query()->create([
            'permintaan_darah_id' => $permintaan->id_permintaan_darah,
            'stok_darah_id' => $stok->id_stok_darah,
            'jumlah_kantong' => 2,
            'diserahkan_oleh' => $admin->id,
            'diserahkan_pada' => '2026-11-02 10:00:00',
        ]);

        $this->actingAs($admin)
            ->get(route('laporan.bulanan', ['bulan' => '2026-10']))
            ->assertOk()
            ->assertSee('Rumah Sakit Bulanan')
            ->assertDontSee('2 kantong');
    }

    private function createAdmin(): User
    {
        return User::query()->create([
            'username' => 'admin-month-report',
            'email' => 'admin-month-report@example.test',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);
    }
}
