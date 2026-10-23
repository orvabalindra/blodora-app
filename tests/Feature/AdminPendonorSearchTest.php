<?php

namespace Tests\Feature;

use App\Models\Pendonor;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminPendonorSearchTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_search_and_paginate_donors(): void
    {
        $admin = User::query()->create([
            'username' => 'admin',
            'email' => 'admin@example.test',
            'password' => 'password',
            'role' => 'admin',
        ]);

        foreach (range(1, 16) as $number) {
            $user = User::query()->create([
                'username' => "donor{$number}",
                'email' => "donor{$number}@example.test",
                'password' => 'password',
                'role' => 'pendonor',
            ]);

            Pendonor::query()->create([
                'user_id' => $user->id,
                'nik' => str_pad((string) $number, 16, '0', STR_PAD_LEFT),
                'nama_lengkap' => "Pendonor Contoh {$number}",
                'no_hp' => "0812345678{$number}",
                'alamat' => 'Jalan Contoh',
                'golongan_darah' => 'O',
                'rhesus' => '+',
                'berat_badan' => 60,
            ]);
        }

        $response = $this->actingAs($admin)
            ->get(route('pendonor.index', ['q' => 'Pendonor Contoh']));

        $response->assertOk()
            ->assertSee('Menampilkan 1–15 dari 16 data.')
            ->assertSee('q=Pendonor%20Contoh&amp;page=2', false)
            ->assertDontSee('Pendonor Contoh 16');
    }

    public function test_public_information_routes_open_the_matching_landing_sections(): void
    {
        $this->get(route('donor'))
            ->assertRedirect(route('home').'#donor');

        $this->get(route('stok.darah'))
            ->assertRedirect(route('home').'#stok-darah');
    }

    public function test_all_searchable_admin_lists_render_with_a_search_query(): void
    {
        $admin = User::query()->create([
            'username' => 'admin',
            'email' => 'admin@example.test',
            'password' => 'password',
            'role' => 'admin',
        ]);

        $this->actingAs($admin);

        foreach ([
            'pendonor.index',
            'pemeriksaan.index',
            'riwayat-donor.index',
            'stok-darah.index',
            'rumah-sakit.index',
            'permintaan-darah.index',
        ] as $routeName) {
            $this->get(route($routeName, ['q' => 'contoh']))
                ->assertOk();
        }
    }
}
