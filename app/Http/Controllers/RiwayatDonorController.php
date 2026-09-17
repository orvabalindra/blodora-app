<?php

namespace App\Http\Controllers;

use App\Models\RiwayatDonor;
use App\Models\Pendonor;
use App\Models\Pemeriksaan;
use Illuminate\Http\Request;

class RiwayatDonorController extends Controller
{
    /**
     * Menampilkan semua riwayat donor.
     */
    public function index()
    {
        $riwayatDonors = RiwayatDonor::with([
            'pendonor',
            'pemeriksaan'
        ])
            ->latest('id_riwayat_donor')
            ->get();

        return view('riwayat-donor.index', compact('riwayatDonors'));
    }

    /**
     * Menampilkan form tambah riwayat donor.
     */
    public function create()
    {
        $pendonors = Pendonor::orderBy('nama_lengkap')->get();

        $pemeriksaans = Pemeriksaan::with('pendonor')
            ->latest('id_pemeriksaan')
            ->get();

        return view('riwayat-donor.create', compact(
            'pendonors',
            'pemeriksaans'
        ));
    }

    /**
     * Menyimpan riwayat donor baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'pendonor_id' => [
                'required',
                'exists:pendonors,id',
            ],

            'pemeriksaan_id' => [
                'nullable',
                'exists:pemeriksaans,id_pemeriksaan',
            ],

            'tanggal_donor' => [
                'required',
                'date',
            ],

            'jumlah_donor' => [
                'required',
                'integer',
                'min:1',
            ],

            'status' => [
                'required',
                'in:sukses,gagal',
            ],
        ]);

        RiwayatDonor::create($validated);

        return redirect()
            ->route('riwayat-donor.index')
            ->with('success', 'Riwayat donor berhasil ditambahkan.');
    }

    /**
     * Menampilkan detail riwayat donor.
     */
    public function show($id)
    {
        $riwayatDonor = RiwayatDonor::with([
            'pendonor',
            'pemeriksaan'
        ])->findOrFail($id);

        return view(
            'riwayat-donor.show',
            compact('riwayatDonor')
        );
    }

    /**
     * Menampilkan form edit riwayat donor.
     */
    public function edit(RiwayatDonor $riwayatDonor)
    {
        $pendonors = Pendonor::orderBy('nama_lengkap')->get();

        $pemeriksaans = Pemeriksaan::with('pendonor')
            ->latest('id_pemeriksaan')
            ->get();

        return view(
            'riwayat-donor.edit',
            compact(
                'riwayatDonor',
                'pendonors',
                'pemeriksaans'
            )
        );
    }

    /**
     * Memperbarui riwayat donor.
     */
    public function update(
        Request $request,
        RiwayatDonor $riwayatDonor
    ) {
        $validated = $request->validate([
            'pendonor_id' => [
                'required',
                'exists:pendonors,id',
            ],

            'pemeriksaan_id' => [
                'nullable',
                'exists:pemeriksaans,id_pemeriksaan',
            ],

            'tanggal_donor' => [
                'required',
                'date',
            ],

            'jumlah_donor' => [
                'required',
                'integer',
                'min:1',
            ],

            'status' => [
                'required',
                'in:sukses,gagal',
            ],
        ]);

        $riwayatDonor->update($validated);

        return redirect()
            ->route('riwayat-donor.index')
            ->with('success', 'Riwayat donor berhasil diperbarui.');
    }

    /**
     * Menghapus riwayat donor.
     */
    public function destroy(RiwayatDonor $riwayatDonor)
    {
        $riwayatDonor->delete();

        return redirect()
            ->route('riwayat-donor.index')
            ->with('success', 'Riwayat donor berhasil dihapus.');
    }
}