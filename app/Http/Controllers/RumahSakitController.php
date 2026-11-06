<?php

namespace App\Http\Controllers;

use App\Models\RumahSakit;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class RumahSakitController extends Controller
{
    /**
     * Menampilkan semua data Faskes.
     */
    public function index(Request $request)
    {
        $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
        ]);

        $rumahSakits = RumahSakit::with(['user:id,username,email'])
            ->withCount('permintaanDarah')
            ->when($request->filled('q'), function ($query) use ($request): void {
                $search = trim((string) $request->query('q'));

                $query->where(function ($query) use ($search): void {
                    $query->where('nama_rs', 'like', "{$search}%")
                        ->orWhere('no_telp', 'like', "{$search}%");
                });
            })
            ->latest('id_rs')
            ->paginate(15)
            ->withQueryString();

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
        $request->merge([
            'username' => Str::lower(trim((string) $request->input('username', ''))),
            'email' => Str::lower(trim((string) $request->input('email', ''))),
        ]);

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
            'username' => ['required', 'string', 'min:3', 'max:30', 'alpha_dash:ascii', 'unique:users,username'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'max:255', 'confirmed'],
        ]);

        DB::transaction(function () use ($validated): void {
            $user = User::create([
                'username' => $validated['username'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => 'rumah_sakit',
            ]);

            RumahSakit::create([
                'nama_rs' => $validated['nama_rs'],
                'alamat' => $validated['alamat'],
                'no_telp' => $validated['no_telp'],
                'user_id' => $user->id,
            ]);
        });

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
        $permintaanDarahs = $rumahSakit->permintaanDarah()
            ->latest('id_permintaan_darah')
            ->limit(5)
            ->get();

        return view(
            'rumah-sakit.show',
            compact('rumahSakit', 'permintaanDarahs')
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
        $request->merge([
            'username' => Str::lower(trim((string) $request->input('username', ''))),
            'email' => Str::lower(trim((string) $request->input('email', ''))),
        ]);

        $account = $rumahSakit->user;
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
            'username' => [
                'required', 'string', 'min:3', 'max:30', 'alpha_dash:ascii',
                Rule::unique('users', 'username')->ignore($account?->id),
            ],
            'email' => [
                'required', 'email', 'max:255',
                Rule::unique('users', 'email')->ignore($account?->id),
            ],
            'password' => [$account ? 'nullable' : 'required', 'string', 'min:8', 'max:255', 'confirmed'],
        ]);

        DB::transaction(function () use ($validated, $rumahSakit, $account): void {
            if ($account) {
                $account->username = $validated['username'];
                $account->email = $validated['email'];
                if (! empty($validated['password'])) {
                    $account->password = Hash::make($validated['password']);
                }
                $account->save();
            } else {
                $account = User::create([
                    'username' => $validated['username'],
                    'email' => $validated['email'],
                    'password' => Hash::make($validated['password']),
                    'role' => 'rumah_sakit',
                ]);
                $rumahSakit->user_id = $account->id;
            }

            $rumahSakit->fill([
                'nama_rs' => $validated['nama_rs'],
                'alamat' => $validated['alamat'],
                'no_telp' => $validated['no_telp'],
            ])->save();
        });

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
        $deleted = DB::transaction(function () use ($rumahSakit): bool {
            $lockedRumahSakit = RumahSakit::query()
                ->lockForUpdate()
                ->findOrFail($rumahSakit->getKey());

            if ($lockedRumahSakit->permintaanDarah()->exists()) {
                return false;
            }

            $user = $lockedRumahSakit->user;
            $lockedRumahSakit->delete();
            $user?->delete();

            return true;
        });

        if (! $deleted) {
            return back()->withErrors([
                'rumah_sakit' => 'Faskes yang sudah memiliki permintaan darah tidak dapat dihapus.',
            ]);
        }

        return redirect()
            ->route('rumah-sakit.index')
            ->with(
                'success',
                'Data Faskes berhasil dihapus.'
            );
    }
}
