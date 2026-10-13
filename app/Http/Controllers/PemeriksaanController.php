<?php

namespace App\Http\Controllers;

use App\Models\Pemeriksaan;
use App\Models\Pendonor;
use Illuminate\Http\Request;

class PemeriksaanController extends Controller
{
    /**
     * Menampilkan semua data pemeriksaan.
     */
    public function index()
    {
        $pemeriksaans = Pemeriksaan::with(['pendonor', 'pendaftaran'])
            ->withCount('riwayatDonors')
            ->latest('id_pemeriksaan')
            ->get();

        return view('pemeriksaan.index', compact('pemeriksaans'));
    }

    /**
     * Menampilkan form tambah pemeriksaan.
     */
    public function create()
    {
        $pendonors = Pendonor::orderBy('nama_lengkap')->get();

        return view('pemeriksaan.create', compact('pendonors'));
    }

    /**
     * Menyimpan pemeriksaan baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'pendonor_id' => [
                'required',
                'exists:pendonors,id',
            ],

            'tanggal_pemeriksaan' => [
                'required',
                'date',
            ],

            'berat_badan' => [
                'required',
                'integer',
                'min:1',
            ],

            'tekanan_darah' => [
                'required',
                'string',
                'max:20',
            ],

            'hemoglobin' => [
                'required',
                'numeric',
                'min:0',
                'max:30',
            ],

            'hasil' => [
                'required',
                'in:layak,tidak layak',
            ],
        ]);

        Pemeriksaan::create($validated);

        return redirect()
            ->route('pemeriksaan.index')
            ->with('success', 'Data pemeriksaan berhasil ditambahkan.');
    }

    /**
     * Menampilkan detail pemeriksaan.
     */
    public function show(Pemeriksaan $pemeriksaan)
    {
        $pemeriksaan->load(['pendonor', 'pendaftaran.jadwalDonor', 'riwayatDonors']);

        return view('pemeriksaan.show', compact('pemeriksaan'));
    }

    /**
     * Menampilkan form edit pemeriksaan.
     */
    public function edit(Pemeriksaan $pemeriksaan)
    {
        if ($pemeriksaan->pendaftaran_id !== null || $pemeriksaan->riwayatDonors()->exists()) {
            return redirect()
                ->route('pemeriksaan.show', $pemeriksaan)
                ->with('info', 'Pemeriksaan yang terhubung dengan pendaftaran atau riwayat donor tidak dapat diubah.');
        }

        $pendonors = Pendonor::orderBy('nama_lengkap')->get();

        return view('pemeriksaan.edit', compact(
            'pemeriksaan',
            'pendonors'
        ));
    }

    /**
     * Memperbarui pemeriksaan.
     */
    public function update(
        Request $request,
        Pemeriksaan $pemeriksaan
    ) {
        if ($pemeriksaan->pendaftaran_id !== null || $pemeriksaan->riwayatDonors()->exists()) {
            return back()->withErrors([
                'pemeriksaan' => 'Pemeriksaan yang terhubung dengan pendaftaran atau riwayat donor tidak dapat diubah.',
            ]);
        }

        $validated = $request->validate([
            'pendonor_id' => [
                'required',
                'exists:pendonors,id',
            ],

            'tanggal_pemeriksaan' => [
                'required',
                'date',
            ],

            'berat_badan' => [
                'required',
                'integer',
                'min:1',
            ],

            'tekanan_darah' => [
                'required',
                'string',
                'max:20',
            ],

            'hemoglobin' => [
                'required',
                'numeric',
                'min:0',
                'max:30',
            ],

            'hasil' => [
                'required',
                'in:layak,tidak layak',
            ],
        ]);

        $pemeriksaan->update($validated);

        return redirect()
            ->route('pemeriksaan.index')
            ->with('success', 'Data pemeriksaan berhasil diperbarui.');
    }

    /**
     * Menghapus pemeriksaan.
     */
    public function destroy(Pemeriksaan $pemeriksaan)
    {
        if ($pemeriksaan->pendaftaran_id !== null || $pemeriksaan->riwayatDonors()->exists()) {
            return back()->withErrors([
                'pemeriksaan' => 'Pemeriksaan yang terhubung dengan pendaftaran atau riwayat donor tidak dapat dihapus.',
            ]);
        }

        $pemeriksaan->delete();

        return redirect()
            ->route('pemeriksaan.index')
            ->with('success', 'Data pemeriksaan berhasil dihapus.');
    }
}
