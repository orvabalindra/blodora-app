<?php

namespace Tests\Feature;

use App\Models\Pendonor;
use App\Models\StokDarah;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicStockLocationTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_record_a_stock_batch_with_its_storage_location(): void
    {
        $admin = User::query()->create([
            'username' => 'admin-stock',
            'email' => 'admin-stock@example.test',
            'password' => 'password',
            'role' => 'admin',
        ]);

        $donorUser = User::query()->create([
            'username' => 'donor-stock',
            'email' => 'donor-stock@example.test',
            'password' => 'password',
            'role' => 'pendonor',
        ]);

        $pendonor = Pendonor::query()->create([
            'user_id' => $donorUser->id,
            'nik' => '1234567890123456',
            'nama_lengkap' => 'Pendonor Contoh',
            'no_hp' => '081234567890',
            'alamat' => 'Jalan Contoh',
            'golongan_darah' => 'O',
            'rhesus' => '+',
            'berat_badan' => 60,
        ]);

        $this->actingAs($admin)
            ->post(route('stok-darah.store'), [
                'pendonor_id' => $pendonor->id,
                'lokasi' => 'UDD PMI Kota Malang',
                'golongan_darah' => 'O',
                'rhesus' => '+',
                'jumlah_kantong' => 2,
                'tanggal_masuk' => today()->toDateString(),
                'tanggal_kadaluarsa' => today()->addDays(35)->toDateString(),
            ])
            ->assertRedirect(route('stok-darah.index'));

        $this->assertDatabaseHas('stok__darahs', [
            'pendonor_id' => $pendonor->id,
            'lokasi' => 'UDD PMI Kota Malang',
            'jumlah_kantong' => 2,
        ]);
    }

    public function test_landing_page_shows_active_stock_by_location_and_last_update(): void
    {
        $user = User::query()->create([
            'username' => 'donor-stock',
            'email' => 'donor-stock@example.test',
            'password' => 'password',
            'role' => 'pendonor',
        ]);

        $pendonor = Pendonor::query()->create([
            'user_id' => $user->id,
            'nik' => '1234567890123456',
            'nama_lengkap' => 'Pendonor Contoh',
            'no_hp' => '081234567890',
            'alamat' => 'Jalan Contoh',
            'golongan_darah' => 'O',
            'rhesus' => '+',
            'berat_badan' => 60,
        ]);

        foreach ([
            ['UDD PMI Kota Malang', 2, today()->addDays(30)],
            ['RSUD Kota Batu', 1, today()->addDays(20)],
            ['Lokasi stok kedaluwarsa', 9, today()->subDay()],
        ] as [$lokasi, $jumlah, $tanggalKadaluarsa]) {
            StokDarah::query()->create([
                'pendonor_id' => $pendonor->id,
                'lokasi' => $lokasi,
                'golongan_darah' => 'O',
                'rhesus' => '+',
                'jumlah_kantong' => $jumlah,
                'tanggal_masuk' => today()->subDay(),
                'tanggal_kadaluarsa' => $tanggalKadaluarsa,
            ]);
        }

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('UDD PMI Kota Malang')
            ->assertSee('RSUD Kota Batu')
            ->assertSee('3 kantong')
            ->assertSee('Pembaruan terakhir:')
            ->assertDontSee('Lokasi stok kedaluwarsa');
    }
}
