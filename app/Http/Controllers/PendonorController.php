<?php

namespace App\Http\Controllers;

use App\Models\Pendonor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class PendonorController extends Controller
{
    /**
     * Menampilkan semua data pendonor.
     *
     * Khusus admin.
     */
    public function index()
    {
        $pendonors = Pendonor::latest()->get();

        return view(
            'pendonor.index',
            compact('pendonors')
        );
    }


    /**
     * Menampilkan detail satu pendonor.
     *
     * Khusus admin.
     */
    public function show(Pendonor $pendonor)
    {
        return view(
            'pendonor.show',
            compact('pendonor')
        );
    }


    /**
     * Menampilkan form edit pendonor.
     *
     * Khusus admin.
     */
    public function edit(Pendonor $pendonor)
    {
        return view(
            'pendonor.edit',
            compact('pendonor')
        );
    }


    /**
     * Memperbarui data pendonor.
     *
     * Khusus admin.
     */
    public function update(
        Request $request,
        Pendonor $pendonor
    ) {
        $validated = $request->validate([
            'nik' => [
                'required',
                'string',
                Rule::unique('pendonors', 'nik')
                    ->ignore($pendonor->id),
            ],

            'nama_lengkap' => [
                'required',
                'string',
                'max:255',
            ],

            'no_hp' => [
                'required',
                'string',
                'max:20',
            ],

            'alamat' => [
                'required',
                'string',
            ],

            'golongan_darah' => [
                'required',
                'in:A,B,AB,O',
            ],

            'rhesus' => [
                'required',
                'in:+,-',
            ],

            'berat_badan' => [
                'required',
                'integer',
            ],
        ]);

        $pendonor->update($validated);

        return redirect()
            ->route('pendonor.index')
            ->with(
                'success',
                'Data pendonor berhasil diperbarui.'
            );
    }


    /**
     * Menghapus data pendonor.
     *
     * Khusus admin.
     */
    public function destroy(Pendonor $pendonor)
    {
        $pendonor->delete();

        return redirect()
            ->route('pendonor.index')
            ->with(
                'success',
                'Data pendonor berhasil dihapus.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | PROFIL PENDONOR
    |--------------------------------------------------------------------------
    */


    /**
     * Menampilkan form untuk melengkapi profil.
     *
     * Digunakan ketika akun pendonor belum memiliki data pendonor.
     */
    public function createProfile()
    {
        $user = Auth::user();

        if ($user->pendonor) {
            return redirect()
                ->route('dashboard.pendonor');
        }

        return view('pendonor.profile');
    }


    /**
     * Menyimpan profil pendonor baru.
     */
    public function storeProfile(Request $request)
    {
        $validated = $request->validate([
            'nik' => [
                'required',
                'string',
                'unique:pendonors,nik',
            ],

            'nama_lengkap' => [
                'required',
                'string',
                'max:255',
            ],

            'no_hp' => [
                'required',
                'string',
                'max:20',
            ],

            'alamat' => [
                'required',
                'string',
            ],

            'golongan_darah' => [
                'required',
                'in:A,B,AB,O',
            ],

            'rhesus' => [
                'required',
                'in:+,-',
            ],

            'berat_badan' => [
                'required',
                'integer',
            ],
        ]);


        $validated['user_id'] = Auth::id();

        Pendonor::create($validated);

        return redirect()
            ->route('dashboard.pendonor')
            ->with(
                'success',
                'Profil pendonor berhasil dilengkapi.'
            );
    }


    /**
     * Menampilkan profil pendonor yang sedang login.
     *
     * Pendonor hanya dapat melihat profil miliknya sendiri.
     */
    public function profile()
    {
        $user = Auth::user();

        $pendonor = $user->pendonor;

        /*
        |--------------------------------------------------------------------------
        | Jika profil belum ada
        |--------------------------------------------------------------------------
        */

        if (!$pendonor) {
            return redirect()
                ->route('pendonor.profile.create')
                ->with(
                    'info',
                    'Silakan lengkapi profil pendonor terlebih dahulu.'
                );
        }


        return view(
            'pendonor.profile-show',
            compact('pendonor')
        );
    }
}