<?php

namespace Tests\Feature;

use App\Models\JadwalDonor;
use App\Models\Pemeriksaan;
use App\Models\Pendaftaran;
use App\Models\Pendonor;
use App\Models\RiwayatDonor;
use App\Models\StokDarah;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class PendaftaranDonorWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_successful_donation_registration_creates_screening_history_and_blood_stock(): void
    {
        $this->travelTo(Carbon::parse('2030-01-01 09:00:00'));

        $admin = $this->createUser('admin', 'admin-workflow');
        $donorUser = $this->createUser('pendonor', 'donor-workflow');
        $pendonor = $this->createPendonor($donorUser);
        $jadwal = $this->createJadwal(today()->addDay());

        $this->actingAs($donorUser)
            ->post(route('donor.pendaftaran.store', $jadwal))
            ->assertRedirect();

        $pendaftaran = Pendaftaran::query()
            ->where('pendonor_id', $pendonor->id)
            ->where('jadwal_donor_id', $jadwal->id)
            ->firstOrFail();

        $this->assertSame('menunggu_screening', $pendaftaran->status);

        $this->actingAs($admin)
            ->put(route('pendaftaran-donor.screening', $pendaftaran), [
                'keputusan' => 'layak',
                'tanggal_pemeriksaan' => today()->toDateString(),
                'berat_badan' => 60,
                'tekanan_darah' => '120/80',
                'hemoglobin' => 13.5,
            ])
            ->assertRedirect()
            ->assertSessionHas('success');

        $pemeriksaan = Pemeriksaan::query()
            ->where('pendaftaran_id', $pendaftaran->id)
            ->firstOrFail();

        $this->assertSame('layak', $pemeriksaan->hasil);
        $this->assertSame($admin->id, $pemeriksaan->screened_by);
        $this->assertDatabaseHas('pendaftarans', [
            'id' => $pendaftaran->id,
            'status' => 'proses_donor',
        ]);

        $this->travelTo($jadwal->tanggal->copy()->setTime(13, 0));

        $tanggalKadaluarsa = $jadwal->tanggal->copy()->addDays(35)->toDateString();

        $this->patch(route('pendaftaran-donor.status', $pendaftaran), [
            'status' => 'selesai',
            'jumlah_donor' => 2,
            'tanggal_kadaluarsa' => $tanggalKadaluarsa,
        ])
            ->assertRedirect()
            ->assertSessionHas('success');

        $pendaftaran->refresh();
        $this->assertSame('selesai', $pendaftaran->status);
        $this->assertSame($jadwal->tanggal->toDateString(), $pendaftaran->tanggal_selesai_donor->toDateString());
        $riwayatDonor = RiwayatDonor::query()
            ->where('pendaftaran_id', $pendaftaran->id)
            ->firstOrFail();
        $this->assertSame($pendonor->id, $riwayatDonor->pendonor_id);
        $this->assertSame($pemeriksaan->id_pemeriksaan, $riwayatDonor->pemeriksaan_id);
        $this->assertSame($jadwal->tanggal->toDateString(), $riwayatDonor->tanggal_donor->toDateString());
        $this->assertSame(2, $riwayatDonor->jumlah_donor);
        $this->assertSame('sukses', $riwayatDonor->status);

        $stokDarah = StokDarah::query()
            ->where('pendaftaran_id', $pendaftaran->id)
            ->firstOrFail();
        $this->assertSame($pendonor->id, $stokDarah->pendonor_id);
        $this->assertSame('O', $stokDarah->golongan_darah);
        $this->assertSame('+', $stokDarah->rhesus);
        $this->assertSame(2, $stokDarah->jumlah_kantong);
        $this->assertSame($jadwal->tanggal->toDateString(), $stokDarah->tanggal_masuk->toDateString());
        $this->assertSame($tanggalKadaluarsa, $stokDarah->tanggal_kadaluarsa->toDateString());

        $this->patch(route('pendaftaran-donor.status', $pendaftaran), [
            'status' => 'selesai',
            'jumlah_donor' => 2,
            'tanggal_kadaluarsa' => $tanggalKadaluarsa,
        ])->assertSessionHasErrors('status');

        $this->assertSame(1, RiwayatDonor::query()->where('pendaftaran_id', $pendaftaran->id)->count());
        $this->assertSame(1, StokDarah::query()->where('pendaftaran_id', $pendaftaran->id)->count());

        $this->actingAs($donorUser)
            ->get(route('dashboard.pendonor'))
            ->assertOk()
            ->assertSee('Pendaftaran Donor Terakhir')
            ->assertSee('Selesai donor');
    }

    public function test_failed_screening_does_not_create_donation_history_or_stock(): void
    {
        $this->travelTo(Carbon::parse('2030-01-01 09:00:00'));

        $admin = $this->createUser('admin', 'admin-failed-screen');
        $pendonor = $this->createPendonor($this->createUser('pendonor', 'donor-failed-screen'));
        $jadwal = $this->createJadwal(today()->addDay());
        $pendaftaran = $this->createPendaftaran($pendonor, $jadwal);

        $this->actingAs($admin)
            ->put(route('pendaftaran-donor.screening', $pendaftaran), [
                'keputusan' => 'tidak_layak',
                'tanggal_pemeriksaan' => today()->toDateString(),
                'berat_badan' => 60,
                'tekanan_darah' => '120/80',
                'hemoglobin' => 13.5,
                'catatan' => 'Tidak memenuhi syarat screening.',
            ])
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseHas('pendaftarans', [
            'id' => $pendaftaran->id,
            'status' => 'gagal_screening',
        ]);
        $this->assertDatabaseHas('pemeriksaans', [
            'pendaftaran_id' => $pendaftaran->id,
            'hasil' => 'tidak layak',
        ]);
        $this->assertDatabaseMissing('riwayat__donors', [
            'pendaftaran_id' => $pendaftaran->id,
        ]);
        $this->assertDatabaseMissing('stok__darahs', [
            'pendaftaran_id' => $pendaftaran->id,
        ]);
    }

    public function test_donor_cannot_submit_an_admin_screening_decision(): void
    {
        $donorUser = $this->createUser('pendonor', 'donor-unauthorized-screen');
        $pendonor = $this->createPendonor($donorUser);
        $jadwal = $this->createJadwal(today()->addDay());
        $pendaftaran = $this->createPendaftaran($pendonor, $jadwal);

        $this->actingAs($donorUser)
            ->put(route('pendaftaran-donor.screening', $pendaftaran), [
                'keputusan' => 'layak',
                'tanggal_pemeriksaan' => today()->toDateString(),
                'berat_badan' => 60,
                'tekanan_darah' => '120/80',
                'hemoglobin' => 13.5,
            ])
            ->assertRedirect(route('dashboard.pendonor'));

        $this->assertDatabaseHas('pendaftarans', [
            'id' => $pendaftaran->id,
            'status' => 'menunggu_screening',
        ]);
        $this->assertDatabaseMissing('pemeriksaans', [
            'pendaftaran_id' => $pendaftaran->id,
        ]);
    }

    public function test_donor_completion_rejects_expiry_before_the_scheduled_donation_date(): void
    {
        $this->travelTo(Carbon::parse('2030-01-01 09:00:00'));

        $admin = $this->createUser('admin', 'admin-expiry');
        $pendonor = $this->createPendonor($this->createUser('pendonor', 'donor-expiry'));
        $jadwal = $this->createJadwal(today()->addDay());
        $pendaftaran = $this->createPendaftaran($pendonor, $jadwal, 'proses_donor');
        $this->createPemeriksaanLayak($pendaftaran, $pendonor, $admin);

        $this->actingAs($admin)
            ->patch(route('pendaftaran-donor.status', $pendaftaran), [
                'status' => 'selesai',
                'jumlah_donor' => 1,
                'tanggal_kadaluarsa' => today()->toDateString(),
            ])
            ->assertSessionHasErrors('tanggal_kadaluarsa');

        $this->assertDatabaseHas('pendaftarans', [
            'id' => $pendaftaran->id,
            'status' => 'proses_donor',
        ]);
        $this->assertDatabaseMissing('riwayat__donors', [
            'pendaftaran_id' => $pendaftaran->id,
        ]);
        $this->assertDatabaseMissing('stok__darahs', [
            'pendaftaran_id' => $pendaftaran->id,
        ]);
    }

    public function test_marking_donor_absent_does_not_create_donation_history_or_stock(): void
    {
        $this->travelTo(Carbon::parse('2030-01-01 09:00:00'));

        $admin = $this->createUser('admin', 'admin-absent');
        $pendonor = $this->createPendonor($this->createUser('pendonor', 'donor-absent'));
        $jadwal = $this->createJadwal(today());
        $pendaftaran = $this->createPendaftaran($pendonor, $jadwal, 'proses_donor');
        $this->createPemeriksaanLayak($pendaftaran, $pendonor, $admin);
        $this->travelTo($jadwal->tanggal->copy()->setTime(13, 0));

        $this->actingAs($admin)
            ->patch(route('pendaftaran-donor.status', $pendaftaran), [
                'status' => 'tidak_hadir',
            ])
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseHas('pendaftarans', [
            'id' => $pendaftaran->id,
            'status' => 'tidak_hadir',
        ]);
        $this->assertDatabaseMissing('riwayat__donors', [
            'pendaftaran_id' => $pendaftaran->id,
        ]);
        $this->assertDatabaseMissing('stok__darahs', [
            'pendaftaran_id' => $pendaftaran->id,
        ]);
    }

    public function test_records_created_by_donor_workflow_cannot_be_manually_deleted(): void
    {
        $admin = $this->createUser('admin', 'admin-protected-records');
        $pendonor = $this->createPendonor($this->createUser('pendonor', 'donor-protected-records'));
        $jadwal = $this->createJadwal(today());
        $pendaftaran = $this->createPendaftaran($pendonor, $jadwal, 'selesai');
        $pemeriksaan = $this->createPemeriksaanLayak($pendaftaran, $pendonor, $admin);
        $riwayat = RiwayatDonor::create([
            'pendonor_id' => $pendonor->id,
            'pendaftaran_id' => $pendaftaran->id,
            'pemeriksaan_id' => $pemeriksaan->id_pemeriksaan,
            'tanggal_donor' => today(),
            'jumlah_donor' => 1,
            'status' => 'sukses',
        ]);
        $stok = StokDarah::create([
            'pendonor_id' => $pendonor->id,
            'pendaftaran_id' => $pendaftaran->id,
            'golongan_darah' => $pendonor->golongan_darah,
            'rhesus' => $pendonor->rhesus,
            'jumlah_kantong' => 1,
            'tanggal_masuk' => today(),
            'tanggal_kadaluarsa' => today()->addDays(35),
        ]);

        $this->actingAs($admin)
            ->delete(route('pemeriksaan.destroy', $pemeriksaan))
            ->assertSessionHasErrors([
                'pemeriksaan' => 'Pemeriksaan yang terhubung dengan pendaftaran atau riwayat donor tidak dapat dihapus.',
            ]);
        $this->delete(route('riwayat-donor.destroy', $riwayat))
            ->assertSessionHasErrors([
                'riwayat' => 'Riwayat yang dibuat dari pendaftaran donor tidak dapat dihapus.',
            ]);
        $this->delete(route('stok-darah.destroy', $stok))
            ->assertSessionHasErrors([
                'stok' => 'Batch stok yang terhubung dengan donor atau penyaluran tidak dapat dihapus.',
            ]);

        $this->assertDatabaseHas('pemeriksaans', [
            'id_pemeriksaan' => $pemeriksaan->id_pemeriksaan,
            'pendaftaran_id' => $pendaftaran->id,
        ]);
        $this->assertDatabaseHas('riwayat__donors', [
            'id_riwayat_donor' => $riwayat->id_riwayat_donor,
            'pendaftaran_id' => $pendaftaran->id,
        ]);
        $this->assertDatabaseHas('stok__darahs', [
            'id_stok_darah' => $stok->id_stok_darah,
            'pendaftaran_id' => $pendaftaran->id,
        ]);

        $this->actingAs($admin)
            ->get(route('pendonor.show', $pendonor))
            ->assertOk()
            ->assertSee('Aktivitas Terhubung')
            ->assertSee('Stok dari Donasi Pendonor');
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

    private function createJadwal(Carbon $tanggal): JadwalDonor
    {
        return JadwalDonor::create([
            'nama_kegiatan' => 'Donor Darah',
            'lokasi' => 'UTD Blodora',
            'tanggal' => $tanggal->toDateString(),
            'waktu_mulai' => '09:00',
            'waktu_selesai' => '12:00',
            'kuota' => 10,
        ]);
    }

    private function createPendaftaran(
        Pendonor $pendonor,
        JadwalDonor $jadwal,
        string $status = 'menunggu_screening',
    ): Pendaftaran {
        return Pendaftaran::create([
            'pendonor_id' => $pendonor->id,
            'jadwal_donor_id' => $jadwal->id,
            'status' => $status,
        ]);
    }

    private function createPemeriksaanLayak(Pendaftaran $pendaftaran, Pendonor $pendonor, User $admin): Pemeriksaan
    {
        return Pemeriksaan::create([
            'pendonor_id' => $pendonor->id,
            'pendaftaran_id' => $pendaftaran->id,
            'screened_by' => $admin->id,
            'tanggal_pemeriksaan' => today()->toDateString(),
            'berat_badan' => 60,
            'tekanan_darah' => '120/80',
            'hemoglobin' => 13.5,
            'hasil' => 'layak',
        ]);
    }
}
