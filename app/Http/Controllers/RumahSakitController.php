<?php

namespace App\Http\Controllers;

use App\Models\RumahSakit;
use Illuminate\Http\Request;

class RumahSakitController extends Controller
{
    /**
     * Menampilkan semua data Faskes.
     */
    public function index()
    {
        $rumahSakits = RumahSakit::latest('id_rs')->get();

        return view(
            'rumah-sakit.index',
            compact('rumahSakits')
        );
    }

    /**
     * Menampilkan form tambah Faskes.
     */
    public function create()
    {
        return view('rumah-sakit.create');
    }

    /**
     * Menyimpan data Faskes baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_rs' => [
                'required',
                'string',
                'max:255',
            ],

            'alamat' => [
                'required',
                'string',
            ],

            'no_telp' => [
                'required',
                'string',
                'max:20',
            ],
        ]);

        RumahSakit::create($validated);

        return redirect()
            ->route('rumah-sakit.index')
            ->with(
                'success',
                'Data Faskes berhasil ditambahkan.'
            );
    }

    /**
     * Menampilkan detail Faskes.
     */
    public function show(RumahSakit $rumahSakit)
    {
        return view(
            'rumah-sakit.show',
            compact('rumahSakit')
        );
    }

    /**
     * Menampilkan form edit Faskes.
     */
    public function edit(RumahSakit $rumahSakit)
    {
        return view(
            'rumah-sakit.edit',
            compact('rumahSakit')
        );
    }

    /**
     * Memperbarui data Faskes.
     */
    public function update(
        Request $request,
        RumahSakit $rumahSakit
    ) {
        $validated = $request->validate([
            'nama_rs' => [
                'required',
                'string',
                'max:255',
            ],

            'alamat' => [
                'required',
                'string',
            ],

            'no_telp' => [
                'required',
                'string',
                'max:20',
            ],
        ]);

        $rumahSakit->update($validated);

        return redirect()
            ->route('rumah-sakit.index')
            ->with(
                'success',
                'Data Faskes berhasil diperbarui.'
            );
    }

    /**
     * Menghapus data Faskes.
     */
    public function destroy(RumahSakit $rumahSakit)
    {
        $rumahSakit->delete();

        return redirect()
            ->route('rumah-sakit.index')
            ->with(
                'success',
                'Data Faskes berhasil dihapus.'
            );
    }
}