<?php

namespace App\Http\Controllers;

use App\Models\Pendonor;
use App\Models\StokDarah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StokDarahController extends Controller
{
    /**
     * Menampilkan daftar stok darah.
     */
    public function index()
    {
        $stokDarah = StokDarah::with(['pendonor', 'penyalurans'])
            ->latest('id_stok_darah')
            ->get();

        return view('stok-darah.index', compact('stokDarah'));
    }

    /**
     * Menampilkan form tambah stok.
     */
    public function create()
    {
        $pendonors = Pendonor::orderBy('nama_lengkap')->get();

        return view('stok-darah.create', compact('pendonors'));
    }

    /**
     * Menyimpan stok baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'pendonor_id' => [
                'required',
                'exists:pendonors,id',
            ],

            'golongan_darah' => [
                'required',
                'in:A,B,AB,O',
            ],

            'rhesus' => [
                'required',
                'in:+,-',
            ],

            'jumlah_kantong' => [
                'required',
                'integer',
                'min:1',
            ],

            'tanggal_masuk' => [
                'required',
                'date',
            ],

            'tanggal_kadaluarsa' => [
                'required',
                'date',
                'after_or_equal:tanggal_masuk',
            ],
        ]);

        StokDarah::create($validated);

        return redirect()
            ->route('stok-darah.index')
            ->with('success', 'Stok darah berhasil ditambahkan.');
    }

    /**
     * Menampilkan detail stok darah.
     */
    public function show(StokDarah $stokDarah)
    {
        $stokDarah->load([
            'pendonor',
            'pendaftaran',
            'penyalurans.permintaanDarah.rumahSakit',
        ]);

        return view('stok-darah.show', compact('stokDarah'));
    }

    /**
     * Menampilkan form edit stok.
     */
    public function edit(StokDarah $stokDarah)
    {
        if ($this->isManagedByWorkflow($stokDarah)) {
            return redirect()
                ->route('stok-darah.show', $stokDarah)
                ->with('info', 'Batch stok yang terhubung dengan donor atau penyaluran tidak dapat diubah.');
        }

        $pendonors = Pendonor::orderBy('nama_lengkap')->get();

        return view(
            'stok-darah.edit',
            compact('stokDarah', 'pendonors')
        );
    }

    /**
     * Memperbarui stok darah.
     */
    public function update(Request $request, StokDarah $stokDarah)
    {
        $validated = $request->validate([
            'pendonor_id' => [
                'required',
                'exists:pendonors,id',
            ],

            'golongan_darah' => [
                'required',
                'in:A,B,AB,O',
            ],

            'rhesus' => [
                'required',
                'in:+,-',
            ],

            'jumlah_kantong' => [
                'required',
                'integer',
                'min:1',
            ],

            'tanggal_masuk' => [
                'required',
                'date',
            ],

            'tanggal_kadaluarsa' => [
                'required',
                'date',
                'after_or_equal:tanggal_masuk',
            ],
        ]);

        $updated = DB::transaction(function () use ($stokDarah, $validated): bool {
            $lockedStokDarah = StokDarah::query()
                ->lockForUpdate()
                ->findOrFail($stokDarah->getKey());

            if ($this->isManagedByWorkflow($lockedStokDarah)) {
                return false;
            }

            $lockedStokDarah->update($validated);

            return true;
        });

        if (! $updated) {
            return back()->withErrors([
                'stok' => 'Batch stok yang terhubung dengan donor atau penyaluran tidak dapat diubah.',
            ]);
        }

        return redirect()
            ->route('stok-darah.index')
            ->with('success', 'Stok darah berhasil diperbarui.');
    }

    /**
     * Menghapus stok darah.
     */
    public function destroy(StokDarah $stokDarah)
    {
        $deleted = DB::transaction(function () use ($stokDarah): bool {
            $lockedStokDarah = StokDarah::query()
                ->lockForUpdate()
                ->findOrFail($stokDarah->getKey());

            if ($this->isManagedByWorkflow($lockedStokDarah)) {
                return false;
            }

            $lockedStokDarah->delete();

            return true;
        });

        if (! $deleted) {
            return back()->withErrors([
                'stok' => 'Batch stok yang terhubung dengan donor atau penyaluran tidak dapat dihapus.',
            ]);
        }

        return redirect()
            ->route('stok-darah.index')
            ->with('success', 'Stok darah berhasil dihapus.');
    }

    private function isManagedByWorkflow(StokDarah $stokDarah): bool
    {
        return $stokDarah->pendaftaran_id !== null
            || $stokDarah->penyalurans()->exists();
    }
}
