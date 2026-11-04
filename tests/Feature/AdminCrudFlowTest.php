<?php

namespace Tests\Feature;

use App\Models\JadwalDonor;
use App\Models\RumahSakit;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminCrudFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_update_and_delete_a_health_facility(): void
    {
        $this->actingAs($this->createAdmin());

        $this->post(route('rumah-sakit.store'), [
            'nama_rs' => 'RS Sehat',
            'alamat' => 'Jalan Merdeka 10',
            'no_telp' => '0211234567',
        ])->assertRedirect(route('rumah-sakit.index'));

        $facility = RumahSakit::query()->firstOrFail();
        $this->assertSame('RS Sehat', $facility->nama_rs);

        $this->put(route('rumah-sakit.update', $facility), [
            'nama_rs' => 'RS Sehat Utama',
            'alamat' => 'Jalan Merdeka 12',
            'no_telp' => '0217654321',
        ])->assertRedirect(route('rumah-sakit.index'));

        $this->assertDatabaseHas('rumah_sakits', [
            'id_rs' => $facility->id_rs,
            'nama_rs' => 'RS Sehat Utama',
            'alamat' => 'Jalan Merdeka 12',
        ]);

        $this->delete(route('rumah-sakit.destroy', $facility))
            ->assertRedirect(route('rumah-sakit.index'));

        $this->assertDatabaseMissing('rumah_sakits', ['id_rs' => $facility->id_rs]);
    }

    public function test_health_facility_creation_rejects_missing_contact_fields(): void
    {
        $this->actingAs($this->createAdmin())
            ->post(route('rumah-sakit.store'), [
                'nama_rs' => 'RS Tanpa Kontak',
                'alamat' => 'Jalan Merdeka',
            ])
            ->assertSessionHasErrors(['no_telp']);

        $this->assertDatabaseCount('rumah_sakits', 0);
    }

    public function test_schedule_requires_end_time_after_start_time(): void
    {
        $this->actingAs($this->createAdmin())
            ->post(route('jadwal-donor.store'), [
                'nama_kegiatan' => 'Donor Darah Kampus',
                'lokasi' => 'Aula Kampus',
                'tanggal' => today()->addDays(7)->toDateString(),
                'waktu_mulai' => '12:00',
                'waktu_selesai' => '11:00',
                'kuota' => 40,
            ])
            ->assertSessionHasErrors(['waktu_selesai']);

        $this->assertDatabaseCount('jadwal_donors', 0);
    }

    public function test_admin_can_create_a_valid_donor_schedule(): void
    {
        $this->actingAs($this->createAdmin())
            ->post(route('jadwal-donor.store'), [
                'nama_kegiatan' => 'Donor Darah Kampus',
                'lokasi' => 'Aula Kampus',
                'tanggal' => today()->addDays(7)->toDateString(),
                'waktu_mulai' => '09:00',
                'waktu_selesai' => '12:00',
                'kuota' => 40,
            ])
            ->assertRedirect(route('jadwal-donor.index'));

        $this->assertDatabaseHas('jadwal_donors', [
            'nama_kegiatan' => 'Donor Darah Kampus',
            'lokasi' => 'Aula Kampus',
            'kuota' => 40,
        ]);
    }

    private function createAdmin(): User
    {
        return User::create([
            'username' => 'admin-'.fake()->unique()->lexify('??????'),
            'email' => fake()->unique()->safeEmail(),
            'password' => Hash::make('testing-password'),
            'role' => 'admin',
        ]);
    }
}
