<?php

namespace App\Http\Controllers;

use App\Models\StokDarah;
use App\Models\Pendonor;
use Illuminate\Http\Request;

class StokDarahController extends Controller
{
    /**
     * Menampilkan daftar stok darah.
     */
    public function index()
    {
        $stokDarah = StokDarah::with('pendonor')
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
        $stokDarah->load('pendonor');

        return view('stok-darah.show', compact('stokDarah'));
    }

    /**
     * Menampilkan form edit stok.
     */
    public function edit(StokDarah $stokDarah)
    {
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

        $stokDarah->update($validated);

        return redirect()
            ->route('stok-darah.index')
            ->with('success', 'Stok darah berhasil diperbarui.');
    }

    /**
     * Menghapus stok darah.
     */
    public function destroy(StokDarah $stokDarah)
    {
        $stokDarah->delete();

        return redirect()
            ->route('stok-darah.index')
            ->with('success', 'Stok darah berhasil dihapus.');
    }
}