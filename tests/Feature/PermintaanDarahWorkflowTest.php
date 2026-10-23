<?php

namespace Tests\Feature;

use App\Models\Pendonor;
use App\Models\PermintaanDarah;
use App\Models\RumahSakit;
use App\Models\StokDarah;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class PermintaanDarahWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_approving_a_request_does_not_reduce_stock_before_delivery(): void
    {
        $this->travelTo('2030-01-01 09:00:00');

        $admin = $this->createUser('admin', 'admin-approval');
        $pendonor = $this->createPendonor($this->createUser('pendonor', 'donor-approval'));
        $stok = $this->createStock($pendonor, 3);
        $permintaan = $this->createRequest(2);

        $this->actingAs($admin)
            ->patch(route('permintaan-darah.status', $permintaan), [
                'status' => 'diterima',
            ])
            ->assertRedirect(route('permintaan-darah.show', $permintaan))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('permintaan__darahs', [
            'id_permintaan_darah' => $permintaan->id_permintaan_darah,
            'status' => 'diterima',
            'jumlah_terpenuhi' => 0,
        ]);
        $this->assertDatabaseHas('stok__darahs', [
            'id_stok_darah' => $stok->id_stok_darah,
            'jumlah_kantong' => 3,
        ]);
        $this->assertDatabaseCount('permintaan_darah_penyalurans', 0);
    }

    public function test_request_creation_starts_in_processing_even_when_status_is_submitted(): void
    {
        $admin = $this->createUser('admin', 'admin-create-request');
        $rumahSakit = RumahSakit::create([
            'nama_rs' => 'Rumah Sakit Blodora',
            'alamat' => 'Alamat rumah sakit',
            'no_telp' => '081234567890',
        ]);

        $this->actingAs($admin)
            ->post(route('permintaan-darah.store'), [
                'rumah_sakit_id' => $rumahSakit->id_rs,
                'tanggal_permintaan' => today()->toDateString(),
                'golongan_darah' => 'O',
                'rhesus' => '+',
                'jumlah_minta' => 2,
                'status' => 'diterima',
            ])
            ->assertRedirect(route('permintaan-darah.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('permintaan__darahs', [
            'rumah_sakit_id' => $rumahSakit->id_rs,
            'status' => 'diproses',
            'jumlah_terpenuhi' => 0,
        ]);
    }

    public function test_partial_delivery_uses_exact_matching_stock_in_expiry_order(): void
    {
        $this->travelTo('2030-01-01 09:00:00');

        $admin = $this->createUser('admin', 'admin-partial-delivery');
        $pendonor = $this->createPendonor($this->createUser('pendonor', 'donor-partial-delivery'));
        $firstToExpire = $this->createStock($pendonor, 1, today()->addDays(10));
        $secondToExpire = $this->createStock($pendonor, 2, today()->addDays(20));
        $wrongRhesus = $this->createStock($pendonor, 5, today()->addDays(5), 'O', '-');
        $permintaan = $this->createRequest(4);

        $this->actingAs($admin)
            ->patch(route('permintaan-darah.status', $permintaan), [
                'status' => 'diterima',
            ])
            ->assertSessionHas('success');

        $this->post(route('permintaan-darah.penyaluran', $permintaan), [
            'jumlah_kantong' => 2,
        ])
            ->assertRedirect(route('permintaan-darah.show', $permintaan))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('permintaan__darahs', [
            'id_permintaan_darah' => $permintaan->id_permintaan_darah,
            'status' => 'diterima',
            'jumlah_terpenuhi' => 2,
        ]);
        $this->assertDatabaseHas('stok__darahs', [
            'id_stok_darah' => $firstToExpire->id_stok_darah,
            'jumlah_kantong' => 0,
        ]);
        $this->assertDatabaseHas('stok__darahs', [
            'id_stok_darah' => $secondToExpire->id_stok_darah,
            'jumlah_kantong' => 1,
        ]);
        $this->assertDatabaseHas('stok__darahs', [
            'id_stok_darah' => $wrongRhesus->id_stok_darah,
            'jumlah_kantong' => 5,
        ]);
        $this->assertDatabaseHas('permintaan_darah_penyalurans', [
            'permintaan_darah_id' => $permintaan->id_permintaan_darah,
            'stok_darah_id' => $firstToExpire->id_stok_darah,
            'jumlah_kantong' => 1,
            'diserahkan_oleh' => $admin->id,
        ]);
        $this->assertDatabaseHas('permintaan_darah_penyalurans', [
            'permintaan_darah_id' => $permintaan->id_permintaan_darah,
            'stok_darah_id' => $secondToExpire->id_stok_darah,
            'jumlah_kantong' => 1,
            'diserahkan_oleh' => $admin->id,
        ]);

        $this->actingAs($admin)
            ->get(route('permintaan-darah.show', $permintaan))
            ->assertOk()
            ->assertSee('Penyerahan Darah')
            ->assertSee('Stok O+');
        $this->get(route('stok-darah.show', $firstToExpire))
            ->assertOk()
            ->assertSee('Riwayat Penyaluran')
            ->assertSee('Detail Stok Darah');
        $this->get(route('rumah-sakit.show', $permintaan->rumahSakit))
            ->assertOk()
            ->assertSee('Permintaan dari Faskes Ini')
            ->assertSee('Detail permintaan');

        $this->from(route('permintaan-darah.show', $permintaan))
            ->post(route('permintaan-darah.penyaluran', $permintaan), ['jumlah_kantong' => 3])
            ->assertSessionHasErrors([
                'jumlah_kantong' => 'Jumlah penyerahan melebihi sisa permintaan.',
            ]);

        $this->from(route('permintaan-darah.show', $permintaan))
            ->delete(route('permintaan-darah.destroy', $permintaan))
            ->assertSessionHasErrors([
                'permintaan' => 'Permintaan yang sudah diputuskan atau memiliki riwayat penyerahan tidak dapat dihapus.',
            ]);
        $this->from(route('stok-darah.show', $firstToExpire))
            ->delete(route('stok-darah.destroy', $firstToExpire))
            ->assertSessionHasErrors([
                'stok' => 'Batch stok yang terhubung dengan donor atau penyaluran tidak dapat dihapus.',
            ]);

        $this->assertDatabaseHas('permintaan__darahs', [
            'id_permintaan_darah' => $permintaan->id_permintaan_darah,
        ]);
        $this->assertDatabaseHas('stok__darahs', [
            'id_stok_darah' => $firstToExpire->id_stok_darah,
            'jumlah_kantong' => 0,
        ]);
    }

    public function test_fully_delivered_request_rejects_another_delivery(): void
    {
        $this->travelTo('2030-01-01 09:00:00');

        $admin = $this->createUser('admin', 'admin-full-delivery');
        $pendonor = $this->createPendonor($this->createUser('pendonor', 'donor-full-delivery'));
        $stok = $this->createStock($pendonor, 3);
        $permintaan = $this->createRequest(2);

        $this->actingAs($admin)
            ->patch(route('permintaan-darah.status', $permintaan), ['status' => 'diterima']);
        $this->post(route('permintaan-darah.penyaluran', $permintaan), ['jumlah_kantong' => 2])
            ->assertSessionHas('success');
        $this->from(route('permintaan-darah.show', $permintaan))
            ->post(route('permintaan-darah.penyaluran', $permintaan), ['jumlah_kantong' => 1])
            ->assertSessionHasErrors([
                'jumlah_kantong' => 'Permintaan ini sudah terpenuhi seluruhnya.',
            ]);

        $this->assertDatabaseHas('permintaan__darahs', [
            'id_permintaan_darah' => $permintaan->id_permintaan_darah,
            'jumlah_terpenuhi' => 2,
        ]);
        $this->assertDatabaseHas('stok__darahs', [
            'id_stok_darah' => $stok->id_stok_darah,
            'jumlah_kantong' => 1,
        ]);
        $this->assertDatabaseCount('permintaan_darah_penyalurans', 1);
    }

    public function test_insufficient_stock_does_not_partially_mutate_inventory_or_request(): void
    {
        $this->travelTo('2030-01-01 09:00:00');

        $admin = $this->createUser('admin', 'admin-short-stock');
        $pendonor = $this->createPendonor($this->createUser('pendonor', 'donor-short-stock'));
        $stok = $this->createStock($pendonor, 1);
        $expiredStock = $this->createStock($pendonor, 8, today()->subDay());
        $wrongBloodType = $this->createStock($pendonor, 6, today()->addDays(5), 'A', '+');
        $wrongRhesus = $this->createStock($pendonor, 4, today()->addDays(6), 'O', '-');
        $permintaan = $this->createRequest(2);

        $this->actingAs($admin)
            ->patch(route('permintaan-darah.status', $permintaan), ['status' => 'diterima']);
        $this->from(route('permintaan-darah.show', $permintaan))
            ->post(route('permintaan-darah.penyaluran', $permintaan), ['jumlah_kantong' => 2])
            ->assertSessionHasErrors([
                'jumlah_kantong' => 'Stok cocok yang masih layak hanya tersedia 1 kantong. Tidak ada stok yang diubah.',
            ]);

        $this->assertDatabaseHas('permintaan__darahs', [
            'id_permintaan_darah' => $permintaan->id_permintaan_darah,
            'jumlah_terpenuhi' => 0,
        ]);
        $this->assertDatabaseHas('stok__darahs', [
            'id_stok_darah' => $stok->id_stok_darah,
            'jumlah_kantong' => 1,
        ]);
        $this->assertDatabaseHas('stok__darahs', [
            'id_stok_darah' => $expiredStock->id_stok_darah,
            'jumlah_kantong' => 8,
        ]);
        $this->assertDatabaseHas('stok__darahs', [
            'id_stok_darah' => $wrongBloodType->id_stok_darah,
            'jumlah_kantong' => 6,
        ]);
        $this->assertDatabaseHas('stok__darahs', [
            'id_stok_darah' => $wrongRhesus->id_stok_darah,
            'jumlah_kantong' => 4,
        ]);
        $this->assertDatabaseCount('permintaan_darah_penyalurans', 0);
    }

    public function test_unapproved_or_rejected_requests_cannot_be_fulfilled(): void
    {
        $this->travelTo('2030-01-01 09:00:00');

        $admin = $this->createUser('admin', 'admin-rejected-request');
        $pendonor = $this->createPendonor($this->createUser('pendonor', 'donor-rejected-request'));
        $stok = $this->createStock($pendonor, 2);
        $permintaan = $this->createRequest(1, 'ditolak');

        $this->actingAs($admin)
            ->post(route('permintaan-darah.penyaluran', $permintaan), ['jumlah_kantong' => 1])
            ->assertSessionHasErrors([
                'jumlah_kantong' => 'Permintaan harus disetujui sebelum darah diserahkan.',
            ]);

        $this->assertDatabaseHas('stok__darahs', [
            'id_stok_darah' => $stok->id_stok_darah,
            'jumlah_kantong' => 2,
        ]);
        $this->assertDatabaseCount('permintaan_darah_penyalurans', 0);
    }

    private function createUser(string $role, string $username): User
    {
        return User::create([
            'username' => $username,
            'email' => $username.'@example.test',
            'password' => 'password',
            'role' => $role,
        ]);
    }

    private function createPendonor(User $user): Pendonor
    {
        return Pendonor::create([
            'user_id' => $user->id,
            'nik' => '1234567890123456',
            'nama_lengkap' => $user->username,
            'no_hp' => '081234567890',
            'alamat' => 'Alamat pendonor',
            'golongan_darah' => 'O',
            'rhesus' => '+',
            'berat_badan' => 60,
        ]);
    }

    private function createStock(
        Pendonor $pendonor,
        int $jumlah,
        ?Carbon $tanggalKadaluarsa = null,
        string $golonganDarah = 'O',
        string $rhesus = '+',
    ): StokDarah {
        return StokDarah::create([
            'pendonor_id' => $pendonor->id,
            'golongan_darah' => $golonganDarah,
            'rhesus' => $rhesus,
            'jumlah_kantong' => $jumlah,
            'tanggal_masuk' => today(),
            'tanggal_kadaluarsa' => $tanggalKadaluarsa ?? today()->addDays(30),
        ]);
    }

    private function createRequest(int $jumlah, string $status = 'diproses'): PermintaanDarah
    {
        $rumahSakit = RumahSakit::create([
            'nama_rs' => 'Rumah Sakit Blodora',
            'alamat' => 'Alamat rumah sakit',
            'no_telp' => '081234567890',
        ]);

        return PermintaanDarah::create([
            'rumah_sakit_id' => $rumahSakit->id_rs,
            'tanggal_permintaan' => today(),
            'golongan_darah' => 'O',
            'rhesus' => '+',
            'jumlah_minta' => $jumlah,
            'jumlah_terpenuhi' => 0,
            'status' => $status,
        ]);
    }
}
