<?php

namespace Tests\Feature;

use App\Models\Pendonor;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PendonorAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_add_a_profile_to_a_registered_donor_without_one(): void
    {
        $admin = $this->createUser('admin', 'admin');
        $donorUser = $this->createUser('pendonor', 'donor-no-profile');

        $this->actingAs($admin)
            ->get(route('pendonor.create'))
            ->assertOk()
            ->assertSee($donorUser->username)
            ->assertSee(route('pendonor.store'));

        $response = $this->post(route('pendonor.store'), [
            'user_id' => $donorUser->id,
            'nik' => '1234567890123456',
            'nama_lengkap' => 'Pendonor Baru',
            'no_hp' => '081234567890',
            'alamat' => 'Jalan Merdeka',
            'golongan_darah' => 'O',
            'rhesus' => '+',
            'berat_badan' => 60,
        ]);

        $pendonor = Pendonor::query()->firstOrFail();

        $response->assertRedirect(route('pendonor.show', $pendonor))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('pendonors', [
            'user_id' => $donorUser->id,
            'nik' => '1234567890123456',
            'nama_lengkap' => 'Pendonor Baru',
            'golongan_darah' => 'O',
            'rhesus' => '+',
        ]);
        $this->assertTrue($donorUser->refresh()->pendonor->is($pendonor));
        $this->assertTrue($pendonor->user->is($donorUser));
    }

    public function test_admin_cannot_add_another_profile_to_a_donor_account(): void
    {
        $admin = $this->createUser('admin', 'admin-duplicate');
        $donorUser = $this->createUser('pendonor', 'donor-existing-profile');
        $this->createPendonor($donorUser);

        $this->actingAs($admin)
            ->post(route('pendonor.store'), [
                'user_id' => $donorUser->id,
                'nik' => '2234567890123456',
                'nama_lengkap' => 'Profil Duplikat',
                'no_hp' => '081234567890',
                'alamat' => 'Jalan Merdeka',
                'golongan_darah' => 'A',
                'rhesus' => '-',
                'berat_badan' => 55,
            ])
            ->assertSessionHasErrors('user_id');

        $this->assertSame(1, Pendonor::query()->where('user_id', $donorUser->id)->count());
    }

    public function test_database_rejects_more_than_one_donor_profile_for_the_same_account(): void
    {
        $donorUser = $this->createUser('pendonor', 'donor-unique-constraint');
        $this->createPendonor($donorUser);

        $this->expectException(QueryException::class);

        Pendonor::create([
            'user_id' => $donorUser->id,
            'nik' => '4234567890123456',
            'nama_lengkap' => 'Profil Kedua',
            'no_hp' => '081234567890',
            'alamat' => 'Alamat pendonor',
            'golongan_darah' => 'A',
            'rhesus' => '-',
            'berat_badan' => 55,
        ]);
    }

    public function test_database_rejects_donor_profile_without_an_existing_user(): void
    {
        $this->expectException(QueryException::class);

        Pendonor::create([
            'user_id' => 999999,
            'nik' => '5234567890123456',
            'nama_lengkap' => 'Tanpa Akun',
            'no_hp' => '081234567890',
            'alamat' => 'Alamat pendonor',
            'golongan_darah' => 'B',
            'rhesus' => '+',
            'berat_badan' => 58,
        ]);
    }

    public function test_admin_cannot_access_donor_pages_and_donor_cannot_access_admin_add_page(): void
    {
        $admin = $this->createUser('admin', 'admin-area');
        $donor = $this->createUser('pendonor', 'donor-area');

        $this->actingAs($admin)
            ->get(route('dashboard.pendonor'))
            ->assertForbidden();

        $this->get(route('pendonor.profile'))
            ->assertForbidden();

        $this->get(route('donor.jadwal'))
            ->assertForbidden();

        $this->actingAs($donor)
            ->get(route('pendonor.create'))
            ->assertRedirect(route('dashboard.pendonor'));
    }

    public function test_only_authenticated_donors_can_open_donor_pages(): void
    {
        $this->get(route('donor.jadwal'))
            ->assertRedirect(route('login'));

        $donor = $this->createUser('pendonor', 'donor-authorized');

        $this->actingAs($donor)
            ->get(route('donor.jadwal'))
            ->assertOk()
            ->assertSee('Lengkapi Profil');
    }

    private function createUser(string $role, string $username): User
    {
        return User::create([
            'username' => $username,
            'email' => $username.'@example.test',
            'password' => Hash::make('testing-password'),
            'role' => $role,
        ]);
    }

    private function createPendonor(User $user): Pendonor
    {
        return Pendonor::create([
            'user_id' => $user->id,
            'nik' => '3234567890123456',
            'nama_lengkap' => $user->username,
            'no_hp' => '081234567890',
            'alamat' => 'Alamat pendonor',
            'golongan_darah' => 'O',
            'rhesus' => '+',
            'berat_badan' => 60,
        ]);
    }
}
