<?php

namespace App\Http\Controllers;

use App\Models\JadwalDonor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class JadwalDonorController extends Controller
{
    public function index(): View
    {
        $jadwalDonors = JadwalDonor::withCount([
            'pendaftarans as pendaftar_aktif_count' => fn ($query) => $query
                ->whereNotIn('status', ['gagal_screening', 'dibatalkan', 'tidak_hadir']),
        ])->orderBy('tanggal')->orderBy('waktu_mulai')->paginate(15);

        return view('jadwal-donor.index', compact('jadwalDonors'));
    }

    public function create(): View
    {
        return view('jadwal-donor.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama_kegiatan' => ['required', 'string', 'max:255'],
            'lokasi' => ['required', 'string', 'max:255'],
            'tanggal' => ['required', 'date', 'after_or_equal:today'],
            'waktu_mulai' => ['required', 'date_format:H:i'],
            'waktu_selesai' => ['required', 'date_format:H:i', 'after:waktu_mulai'],
            'kuota' => ['required', 'integer', 'min:1'],
        ]);

        JadwalDonor::create($validated);

        return redirect()->route('jadwal-donor.index')
            ->with('success', 'Jadwal donor berhasil dibuat.');
    }

    public function edit(JadwalDonor $jadwalDonor): View
    {
        return view('jadwal-donor.edit', compact('jadwalDonor'));
    }

    public function update(Request $request, JadwalDonor $jadwalDonor): RedirectResponse
    {
        $validated = $request->validate([
            'nama_kegiatan' => ['required', 'string', 'max:255'],
            'lokasi' => ['required', 'string', 'max:255'],
            'tanggal' => ['required', 'date', 'after_or_equal:today'],
            'waktu_mulai' => ['required', 'date_format:H:i'],
            'waktu_selesai' => ['required', 'date_format:H:i', 'after:waktu_mulai'],
            'kuota' => ['required', 'integer', 'min:1'],
        ]);

        $pendaftarAktif = $jadwalDonor->pendaftarans()
            ->whereNotIn('status', ['gagal_screening', 'dibatalkan', 'tidak_hadir'])
            ->count();

        if ($validated['kuota'] < $pendaftarAktif) {
            return back()->withErrors([
                'kuota' => 'Kuota tidak boleh lebih kecil dari jumlah pendaftar aktif.',
            ])->withInput();
        }

        $jadwalDonor->update($validated);

        return redirect()->route('jadwal-donor.index')
            ->with('success', 'Jadwal donor berhasil diperbarui.');
    }
}
