<?php

namespace App\Http\Controllers;

use App\Models\PermintaanDarah;
use App\Models\PermintaanDarahPenyaluran;
use App\Models\RumahSakit;
use App\Models\StokDarah;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PermintaanDarahController extends Controller
{
    public function index(): View
    {
        $permintaanDarahs = PermintaanDarah::with('rumahSakit')
            ->withCount('penyalurans')
            ->latest('id_permintaan_darah')
            ->get();

        return view('permintaan-darah.index', compact('permintaanDarahs'));
    }

    public function create(): View
    {
        $rumahSakits = RumahSakit::orderBy('nama_rs')->get();

        return view('permintaan-darah.create', compact('rumahSakits'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'rumah_sakit_id' => ['required', 'exists:rumah_sakits,id_rs'],
            'tanggal_permintaan' => ['required', 'date'],
            'golongan_darah' => ['required', 'in:A,B,AB,O'],
            'rhesus' => ['required', 'in:+,-'],
            'jumlah_minta' => ['required', 'integer', 'min:1'],
        ]);

        PermintaanDarah::create($validated + [
            'jumlah_terpenuhi' => 0,
            'status' => 'diproses',
        ]);

        return redirect()
            ->route('permintaan-darah.index')
            ->with('success', 'Permintaan darah berhasil ditambahkan.');
    }

    public function show(PermintaanDarah $permintaanDarah): View
    {
        $permintaanDarah->load([
            'rumahSakit',
            'penyalurans.stokDarah',
            'penyalurans.diserahkanOleh',
        ]);

        return view('permintaan-darah.show', compact('permintaanDarah'));
    }

    public function edit(PermintaanDarah $permintaanDarah): View|RedirectResponse
    {
        if ($permintaanDarah->status !== 'diproses' || $permintaanDarah->jumlah_terpenuhi > 0) {
            return redirect()
                ->route('permintaan-darah.show', $permintaanDarah)
                ->with('info', 'Permintaan yang sudah diputuskan tidak dapat diubah.');
        }

        $rumahSakits = RumahSakit::orderBy('nama_rs')->get();

        return view(
            'permintaan-darah.edit',
            compact('permintaanDarah', 'rumahSakits')
        );
    }

    public function update(
        Request $request,
        PermintaanDarah $permintaanDarah
    ): RedirectResponse {
        $validated = $request->validate([
            'rumah_sakit_id' => ['required', 'exists:rumah_sakits,id_rs'],
            'tanggal_permintaan' => ['required', 'date'],
            'golongan_darah' => ['required', 'in:A,B,AB,O'],
            'rhesus' => ['required', 'in:+,-'],
            'jumlah_minta' => ['required', 'integer', 'min:1'],
        ]);

        $updated = DB::transaction(function () use ($permintaanDarah, $validated): bool {
            $lockedPermintaan = PermintaanDarah::query()
                ->lockForUpdate()
                ->findOrFail($permintaanDarah->getKey());

            if ($lockedPermintaan->status !== 'diproses' || $lockedPermintaan->jumlah_terpenuhi > 0) {
                return false;
            }

            $lockedPermintaan->update($validated);

            return true;
        });

        if (! $updated) {
            return back()->withErrors([
                'permintaan' => 'Permintaan yang sudah diputuskan tidak dapat diubah.',
            ]);
        }

        return redirect()
            ->route('permintaan-darah.show', $permintaanDarah)
            ->with('success', 'Permintaan darah berhasil diperbarui.');
    }

    public function updateStatus(
        Request $request,
        PermintaanDarah $permintaanDarah
    ): RedirectResponse {
        $validated = $request->validate([
            'status' => ['required', 'in:diterima,ditolak'],
        ]);

        $updated = DB::transaction(function () use ($permintaanDarah, $validated): bool {
            $lockedPermintaan = PermintaanDarah::query()
                ->lockForUpdate()
                ->findOrFail($permintaanDarah->getKey());

            if ($lockedPermintaan->status !== 'diproses') {
                return false;
            }

            $lockedPermintaan->update(['status' => $validated['status']]);

            return true;
        });

        if (! $updated) {
            return back()->withErrors([
                'status' => 'Permintaan ini sudah diputuskan. Muat ulang halaman.',
            ]);
        }

        $message = $validated['status'] === 'diterima'
            ? 'Permintaan disetujui. Stok akan berkurang setelah penyerahan dicatat.'
            : 'Permintaan berhasil ditolak.';

        return redirect()
            ->route('permintaan-darah.show', $permintaanDarah)
            ->with('success', $message);
    }

    public function storePenyaluran(
        Request $request,
        PermintaanDarah $permintaanDarah
    ): RedirectResponse {
        $validated = $request->validate([
            'jumlah_kantong' => ['required', 'integer', 'min:1'],
        ]);

        $hasil = DB::transaction(function () use (
            $request,
            $permintaanDarah,
            $validated
        ): array {
            $lockedPermintaan = PermintaanDarah::query()
                ->lockForUpdate()
                ->findOrFail($permintaanDarah->getKey());

            if ($lockedPermintaan->status !== 'diterima') {
                return ['status' => 'belum_disetujui'];
            }

            $sisaPermintaan = $lockedPermintaan->jumlah_minta
                - $lockedPermintaan->jumlah_terpenuhi;

            if ($sisaPermintaan < 1) {
                return ['status' => 'sudah_terpenuhi'];
            }

            if ($validated['jumlah_kantong'] > $sisaPermintaan) {
                return ['status' => 'melebihi_sisa'];
            }

            $stokTersedia = StokDarah::query()
                ->where('golongan_darah', $lockedPermintaan->golongan_darah)
                ->where('rhesus', $lockedPermintaan->rhesus)
                ->whereDate('tanggal_kadaluarsa', '>=', today())
                ->where('jumlah_kantong', '>', 0)
                ->orderBy('tanggal_kadaluarsa')
                ->orderBy('id_stok_darah')
                ->lockForUpdate()
                ->get();

            $jumlahTersedia = (int) $stokTersedia->sum('jumlah_kantong');

            if ($jumlahTersedia < $validated['jumlah_kantong']) {
                return [
                    'status' => 'stok_tidak_cukup',
                    'tersedia' => $jumlahTersedia,
                ];
            }

            $sisaPenyaluran = $validated['jumlah_kantong'];

            foreach ($stokTersedia as $stokDarah) {
                if ($sisaPenyaluran === 0) {
                    break;
                }

                $jumlahDiambil = min($stokDarah->jumlah_kantong, $sisaPenyaluran);

                $stokDarah->decrement('jumlah_kantong', $jumlahDiambil);

                PermintaanDarahPenyaluran::create([
                    'permintaan_darah_id' => $lockedPermintaan->getKey(),
                    'stok_darah_id' => $stokDarah->getKey(),
                    'jumlah_kantong' => $jumlahDiambil,
                    'diserahkan_oleh' => $request->user()->id,
                    'diserahkan_pada' => now(),
                ]);

                $sisaPenyaluran -= $jumlahDiambil;
            }

            $lockedPermintaan->increment(
                'jumlah_terpenuhi',
                $validated['jumlah_kantong']
            );

            return ['status' => 'berhasil'];
        }, 3);

        if ($hasil['status'] !== 'berhasil') {
            $pesan = match ($hasil['status']) {
                'belum_disetujui' => 'Permintaan harus disetujui sebelum darah diserahkan.',
                'sudah_terpenuhi' => 'Permintaan ini sudah terpenuhi seluruhnya.',
                'melebihi_sisa' => 'Jumlah penyerahan melebihi sisa permintaan.',
                'stok_tidak_cukup' => 'Stok cocok yang masih layak hanya tersedia '
                    .$hasil['tersedia'].' kantong. Tidak ada stok yang diubah.',
            };

            return back()->withErrors(['jumlah_kantong' => $pesan]);
        }

        return redirect()
            ->route('permintaan-darah.show', $permintaanDarah)
            ->with('success', 'Penyerahan darah berhasil dicatat dan stok telah diperbarui.');
    }

    public function destroy(PermintaanDarah $permintaanDarah): RedirectResponse
    {
        $dihapus = DB::transaction(function () use ($permintaanDarah): bool {
            $lockedPermintaan = PermintaanDarah::query()
                ->lockForUpdate()
                ->findOrFail($permintaanDarah->getKey());

            if ($lockedPermintaan->status !== 'diproses' || $lockedPermintaan->penyalurans()->exists()) {
                return false;
            }

            $lockedPermintaan->delete();

            return true;
        });

        if (! $dihapus) {
            return back()->withErrors([
                'permintaan' => 'Permintaan yang sudah diputuskan atau memiliki riwayat penyerahan tidak dapat dihapus.',
            ]);
        }

        return redirect()
            ->route('permintaan-darah.index')
            ->with('success', 'Permintaan darah berhasil dihapus.');
    }
}
