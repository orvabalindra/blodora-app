<?php

namespace Tests\Feature;

use App\Models\Pemeriksaan;
use App\Models\Pendonor;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminRemoteLookupTest extends TestCase
{
    use RefreshDatabase;

    public function test_donor_lookup_is_admin_only_and_returns_at_most_twenty_matches(): void
    {
        $this->get(route('admin.lookup.pendonors', ['q' => 'Pendonor']))
            ->assertRedirect(route('login'));

        $admin = $this->createUser('lookup-admin', 'admin');
        for ($number = 1; $number <= 25; $number++) {
            $this->createPendonor($number, 'Pendonor '.$number);
        }

        $this->actingAs($admin)
            ->getJson(route('admin.lookup.pendonors', ['q' => 'Pendonor']))
            ->assertOk()
            ->assertJsonCount(20)
            ->assertJsonPath('0.label', 'Pendonor 1 — O+ · NIK 0000000000000001');
    }

    public function test_exam_lookup_can_find_a_donor_and_returns_a_compact_label(): void
    {
        $admin = $this->createUser('lookup-exam-admin', 'admin');
        $pendonor = $this->createPendonor(30, 'Siti Pendonor');
        Pemeriksaan::query()->create([
            'pendonor_id' => $pendonor->id,
            'tanggal_pemeriksaan' => today()->toDateString(),
            'berat_badan' => 60,
            'tekanan_darah' => '120/80',
            'hemoglobin' => 13,
            'hasil' => 'layak',
        ]);

        $this->actingAs($admin)
            ->getJson(route('admin.lookup.pemeriksaans', ['q' => 'Siti']))
            ->assertOk()
            ->assertJsonCount(1)
            ->assertJsonPath('0.label', 'Siti Pendonor — Layak · '.today()->format('d-m-Y'));
    }

    public function test_available_donor_user_lookup_excludes_accounts_that_already_have_profiles(): void
    {
        $admin = $this->createUser('lookup-user-admin', 'admin');
        $available = $this->createUser('available-donor', 'pendonor');
        $existing = $this->createUser('existing-donor', 'pendonor');
        Pendonor::query()->create([
            'user_id' => $existing->id,
            'nik' => '9999999999999999',
            'nama_lengkap' => 'Pendonor Existing',
            'no_hp' => '081111111111',
            'alamat' => 'Jalan Contoh',
            'golongan_darah' => 'O',
            'rhesus' => '+',
            'berat_badan' => 60,
        ]);

        $this->actingAs($admin)
            ->getJson(route('admin.lookup.available-donor-users', ['q' => 'available']))
            ->assertOk()
            ->assertJsonCount(1)
            ->assertJsonPath('0.id', $available->id);
    }

    public function test_stock_and_donation_history_forms_render_without_loading_full_option_lists(): void
    {
        $admin = $this->createUser('lookup-form-admin', 'admin');

        $this->actingAs($admin)
            ->get(route('stok-darah.create'))
            ->assertOk()
            ->assertViewHas('selectedPendonor', null)
            ->assertDontSee('Pendonor 1');

        $this->get(route('riwayat-donor.create'))
            ->assertOk()
            ->assertViewHas('selectedPendonor', null)
            ->assertViewHas('selectedPemeriksaan', null);

        $this->get(route('pemeriksaan.create'))
            ->assertOk()
            ->assertViewHas('selectedPendonor', null);

        $this->get(route('pendonor.create'))
            ->assertOk()
            ->assertViewHas('selectedUser', null);
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

    private function createPendonor(int $number, string $name): Pendonor
    {
        $username = 'lookup-donor-'.$number;
        $user = $this->createUser($username, 'pendonor');

        return Pendonor::query()->create([
            'user_id' => $user->id,
            'nik' => str_pad((string) $number, 16, '0', STR_PAD_LEFT),
            'nama_lengkap' => $name,
            'no_hp' => '08'.str_pad((string) $number, 10, '0', STR_PAD_LEFT),
            'alamat' => 'Jalan Contoh',
            'golongan_darah' => 'O',
            'rhesus' => '+',
            'berat_badan' => 60,
        ]);
    }
}
