<?php

namespace App\Http\Controllers;

use App\Models\Pemeriksaan;
use App\Models\Pendonor;
use App\Models\RiwayatDonor;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RiwayatDonorController extends Controller
{
    /**
     * Menampilkan semua riwayat donor.
     */
    public function index(Request $request)
    {
        $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
        ]);

        $riwayatDonors = RiwayatDonor::with([
            'pendonor',
            'pemeriksaan',
            'pendaftaran',
        ])
            ->when($request->filled('q'), function ($query) use ($request): void {
                $search = trim((string) $request->query('q'));

                $query->where(function ($query) use ($search): void {
                    $query->where('status', mb_strtolower($search))
                        ->orWhereHas('pendonor', function ($pendonorQuery) use ($search): void {
                            $pendonorQuery->where('nama_lengkap', 'like', "{$search}%");
                        });
                });
            })
            ->latest('id_riwayat_donor')
            ->paginate(15)
            ->withQueryString();

        return view('riwayat-donor.index', compact('riwayatDonors'));
    }

    /**
     * Menampilkan form tambah riwayat donor.
     */
    public function create()
    {
        $selectedPendonor = Pendonor::query()->find(old('pendonor_id'));
        $selectedPemeriksaan = old('pemeriksaan_id')
            ? Pemeriksaan::query()->with('pendonor')->find(old('pemeriksaan_id'))
            : null;

        return view('riwayat-donor.create', compact(
            'selectedPendonor',
            'selectedPemeriksaan'
        ));
    }

    /**
     * Menyimpan riwayat donor baru.
     */
    public function store(Request $request)
    {
        $this->mergeDateParts($request);
        $validated = $request->validate([
            'pendonor_id' => [
                'required',
                'exists:pendonors,id',
            ],

            'pemeriksaan_id' => [
                'nullable',
                Rule::exists('pemeriksaans', 'id_pemeriksaan')
                    ->where('pendonor_id', $request->input('pendonor_id'))
                    ->when($request->input('status') === 'sukses', fn ($rule) => $rule->where('hasil', 'layak')),
            ],

            'tanggal_donor' => [
                'required',
                'date',
                'before_or_equal:today',
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
            'pemeriksaan',
            'pendaftaran.jadwalDonor',
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
        if ($riwayatDonor->pendaftaran_id !== null) {
            return redirect()
                ->route('riwayat-donor.show', $riwayatDonor)
                ->with('info', 'Riwayat yang dibuat dari pendaftaran donor tidak dapat diubah.');
        }

        $selectedPendonor = old('pendonor_id')
            ? Pendonor::query()->find(old('pendonor_id'))
            : $riwayatDonor->pendonor;
        $selectedPemeriksaan = old('pemeriksaan_id')
            ? Pemeriksaan::query()->with('pendonor')->find(old('pemeriksaan_id'))
            : $riwayatDonor->pemeriksaan?->loadMissing('pendonor');

        return view(
            'riwayat-donor.edit',
            compact(
                'riwayatDonor',
                'selectedPendonor',
                'selectedPemeriksaan'
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
        $this->mergeDateParts($request);
        if ($riwayatDonor->pendaftaran_id !== null) {
            return back()->withErrors([
                'riwayat' => 'Riwayat yang dibuat dari pendaftaran donor tidak dapat diubah.',
            ]);
        }

        $validated = $request->validate([
            'pendonor_id' => [
                'required',
                'exists:pendonors,id',
            ],

            'pemeriksaan_id' => [
                'nullable',
                Rule::exists('pemeriksaans', 'id_pemeriksaan')
                    ->where('pendonor_id', $request->input('pendonor_id'))
                    ->when($request->input('status') === 'sukses', fn ($rule) => $rule->where('hasil', 'layak')),
            ],

            'tanggal_donor' => [
                'required',
                'date',
                'before_or_equal:today',
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
        if ($riwayatDonor->pendaftaran_id !== null) {
            return back()->withErrors([
                'riwayat' => 'Riwayat yang dibuat dari pendaftaran donor tidak dapat dihapus.',
            ]);
        }

        $riwayatDonor->delete();

        return redirect()
            ->route('riwayat-donor.index')
            ->with('success', 'Riwayat donor berhasil dihapus.');
    }
}
