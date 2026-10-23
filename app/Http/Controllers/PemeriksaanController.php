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
    public function index(Request $request)
    {
        $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
        ]);

        $pemeriksaans = Pemeriksaan::with(['pendonor', 'pendaftaran'])
            ->withCount('riwayatDonors')
            ->when($request->filled('q'), function ($query) use ($request): void {
                $search = trim((string) $request->query('q'));

                $query->where(function ($query) use ($search): void {
                    $query->where('hasil', mb_strtolower($search))
                        ->orWhereHas('pendonor', function ($pendonorQuery) use ($search): void {
                            $pendonorQuery->where('nama_lengkap', 'like', "{$search}%");
                        });
                });
            })
            ->latest('id_pemeriksaan')
            ->paginate(15)
            ->withQueryString();

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
        $this->mergeDateParts($request);
        $validated = $request->validate([
            'pendonor_id' => [
                'required',
                'exists:pendonors,id',
            ],

            'tanggal_pemeriksaan' => [
                'required',
                'date',
                'before_or_equal:today',
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
        $this->mergeDateParts($request);
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
                'before_or_equal:today',
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
