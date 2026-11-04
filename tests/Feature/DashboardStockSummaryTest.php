<?php

namespace Tests\Feature;

use App\Models\Pendonor;
use App\Models\StokDarah;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class DashboardStockSummaryTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_dashboard_aggregates_active_stock_and_counts_expired_stock_separately(): void
    {
        $admin = $this->createUser('dashboard-admin', 'admin');
        $donorUser = $this->createUser('dashboard-donor', 'pendonor');
        $donor = Pendonor::query()->create([
            'user_id' => $donorUser->id,
            'nik' => '1234567890123456',
            'nama_lengkap' => 'Pendonor Dashboard',
            'no_hp' => '081234567890',
            'alamat' => 'Jalan Contoh',
            'golongan_darah' => 'A',
            'rhesus' => '+',
            'berat_badan' => 60,
        ]);

        $this->createStock($donor->id, 'A', '+', 2, today());
        $this->createStock($donor->id, 'A', '+', 3, today()->addDays(10));
        $this->createStock($donor->id, 'A', '-', 1, today());
        $this->createStock($donor->id, 'O', '+', 4, today()->subDay());

        $this->actingAs($admin)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertViewHas('stok', [
                'A+' => 5,
                'A-' => 1,
                'B+' => 0,
                'B-' => 0,
                'AB+' => 0,
                'AB-' => 0,
                'O+' => 0,
                'O-' => 0,
            ])
            ->assertViewHas('totalStok', 6)
            ->assertViewHas('jumlahStokKedaluwarsa', 4);
    }

    private function createUser(string $username, string $role): User
    {
        return User::query()->create([
            'username' => $username,
            'email' => $username.'@example.test',
            'password' => Hash::make('testing-password'),
            'role' => $role,
        ]);
    }

    private function createStock(int $pendonorId, string $golongan, string $rhesus, int $jumlah, $tanggalKadaluarsa): void
    {
        StokDarah::query()->create([
            'pendonor_id' => $pendonorId,
            'lokasi' => 'Gudang utama',
            'golongan_darah' => $golongan,
            'rhesus' => $rhesus,
            'jumlah_kantong' => $jumlah,
            'tanggal_masuk' => today()->subDays(10),
            'tanggal_kadaluarsa' => $tanggalKadaluarsa,
        ]);
    }
}
