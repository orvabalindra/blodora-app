<?php

namespace App\Http\Controllers;

use App\Models\Pendonor;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class PendonorController extends Controller
{
    public function create(): View
    {
        $users = User::query()
            ->where('role', 'pendonor')
            ->whereDoesntHave('pendonor')
            ->orderBy('username')
            ->get(['id', 'username', 'email']);

        return view('pendonor.create', compact('users'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'user_id' => [
                'required',
                'integer',
                Rule::exists('users', 'id')->where('role', 'pendonor'),
            ],
            'nik' => [
                'required',
                'string',
                Rule::unique('pendonors', 'nik'),
            ],
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'no_hp' => ['required', 'string', 'max:20'],
            'alamat' => ['required', 'string'],
            'golongan_darah' => ['required', 'in:A,B,AB,O'],
            'rhesus' => ['required', 'in:+,-'],
            'berat_badan' => ['required', 'integer', 'min:1'],
        ]);

        $pendonor = DB::transaction(function () use ($validated): Pendonor {
            $user = User::query()
                ->whereKey($validated['user_id'])
                ->where('role', 'pendonor')
                ->lockForUpdate()
                ->firstOrFail();

            if ($user->pendonor()->exists()) {
                throw ValidationException::withMessages([
                    'user_id' => 'Akun ini sudah memiliki profil pendonor.',
                ]);
            }

            return $user->pendonor()->create([
                'nik' => $validated['nik'],
                'nama_lengkap' => $validated['nama_lengkap'],
                'no_hp' => $validated['no_hp'],
                'alamat' => $validated['alamat'],
                'golongan_darah' => $validated['golongan_darah'],
                'rhesus' => $validated['rhesus'],
                'berat_badan' => $validated['berat_badan'],
            ]);
        });

        return redirect()
            ->route('pendonor.show', $pendonor)
            ->with('success', 'Profil pendonor berhasil ditambahkan.');
    }

    /**
     * Menampilkan semua data pendonor.
     *
     * Khusus admin.
     */
    public function index()
    {
        $pendonors = Pendonor::withCount([
            'pendaftarans',
            'pemeriksaans',
            'riwayatDonor',
            'stokDarahs',
        ])->latest()->get();

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
        $pendaftarans = $pendonor->pendaftarans()
            ->with('jadwalDonor')
            ->latest()
            ->limit(5)
            ->get();

        $pemeriksaans = $pendonor->pemeriksaans()
            ->with('pendaftaran')
            ->latest('id_pemeriksaan')
            ->limit(5)
            ->get();

        $riwayatDonors = $pendonor->riwayatDonor()
            ->with(['pemeriksaan', 'pendaftaran'])
            ->latest('tanggal_donor')
            ->limit(5)
            ->get();

        $stokDarahs = $pendonor->stokDarahs()
            ->with('penyalurans.permintaanDarah.rumahSakit')
            ->latest('id_stok_darah')
            ->limit(5)
            ->get();

        return view(
            'pendonor.show',
            compact(
                'pendonor',
                'pendaftarans',
                'pemeriksaans',
                'riwayatDonors',
                'stokDarahs'
            )
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
        if (
            $pendonor->pendaftarans()->exists()
            || $pendonor->pemeriksaans()->exists()
            || $pendonor->riwayatDonor()->exists()
            || $pendonor->stokDarahs()->exists()
        ) {
            return back()->withErrors([
                'pendonor' => 'Data pendonor yang sudah memiliki pendaftaran, pemeriksaan, riwayat, atau stok tidak dapat dihapus.',
            ]);
        }

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

        if (! $pendonor) {
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
