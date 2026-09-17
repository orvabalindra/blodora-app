<?php

namespace App\Http\Controllers;

use App\Models\PermintaanDarah;
use App\Models\RumahSakit;
use Illuminate\Http\Request;

class PermintaanDarahController extends Controller
{
    /**
     * Menampilkan semua permintaan darah.
     */
    public function index()
    {
        $permintaanDarahs = PermintaanDarah::with('rumahSakit')
            ->latest('id_permintaan_darah')
            ->get();

        return view(
            'permintaan-darah.index',
            compact('permintaanDarahs')
        );
    }

    /**
     * Menampilkan form tambah permintaan darah.
     */
    public function create()
    {
        $rumahSakits = RumahSakit::orderBy('nama_rs')->get();

        return view(
            'permintaan-darah.create',
            compact('rumahSakits')
        );
    }

    /**
     * Menyimpan permintaan darah baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'rumah_sakit_id' => [
                'required',
                'exists:rumah_sakits,id_rs',
            ],

            'tanggal_permintaan' => [
                'required',
                'date',
            ],

            'golongan_darah' => [
                'required',
                'in:A,B,AB,O',
            ],

            'rhesus' => [
                'required',
                'in:+,-',
            ],

            'jumlah_minta' => [
                'required',
                'integer',
                'min:1',
            ],

            'status' => [
                'required',
                'in:diproses,diterima,ditolak',
            ],
        ]);

        PermintaanDarah::create($validated);

        return redirect()
            ->route('permintaan-darah.index')
            ->with(
                'success',
                'Permintaan darah berhasil ditambahkan.'
            );
    }

    /**
     * Menampilkan detail permintaan darah.
     */
    public function show(PermintaanDarah $permintaanDarah)
    {
        $permintaanDarah->load('rumahSakit');

        return view(
            'permintaan-darah.show',
            compact('permintaanDarah')
        );
    }

    /**
     * Menampilkan form edit permintaan darah.
     */
    public function edit(PermintaanDarah $permintaanDarah)
    {
        $rumahSakits = RumahSakit::orderBy('nama_rs')->get();

        return view(
            'permintaan-darah.edit',
            compact(
                'permintaanDarah',
                'rumahSakits'
            )
        );
    }

    /**
     * Memperbarui permintaan darah.
     */
    public function update(
        Request $request,
        PermintaanDarah $permintaanDarah
    ) {
        $validated = $request->validate([
            'rumah_sakit_id' => [
                'required',
                'exists:rumah_sakits,id_rs',
            ],

            'tanggal_permintaan' => [
                'required',
                'date',
            ],

            'golongan_darah' => [
                'required',
                'in:A,B,AB,O',
            ],

            'rhesus' => [
                'required',
                'in:+,-',
            ],

            'jumlah_minta' => [
                'required',
                'integer',
                'min:1',
            ],

            'status' => [
                'required',
                'in:diproses,diterima,ditolak',
            ],
        ]);

        $permintaanDarah->update($validated);

        return redirect()
            ->route('permintaan-darah.index')
            ->with(
                'success',
                'Permintaan darah berhasil diperbarui.'
            );
    }

    /**
     * Menghapus permintaan darah.
     */
    public function destroy(PermintaanDarah $permintaanDarah)
    {
        $permintaanDarah->delete();

        return redirect()
            ->route('permintaan-darah.index')
            ->with(
                'success',
                'Permintaan darah berhasil dihapus.'
            );
    }
}