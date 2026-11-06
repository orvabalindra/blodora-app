<?php

namespace App\Http\Controllers;

use App\Models\PermintaanDarah;
use App\Models\PermintaanDarahPenyaluran;
use App\Models\StokDarah;
use App\Models\User;
use App\Notifications\PermintaanDarahStatusNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PermintaanDarahController extends Controller
{
    public function index(Request $request): View
    {
        $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
        ]);

        $permintaanDarahs = PermintaanDarah::with('rumahSakit')
            ->withCount('penyalurans')
            ->when($request->filled('q'), function ($query) use ($request): void {
                $search = trim((string) $request->query('q'));

                $query->where(function ($query) use ($search): void {
                    $query->where('golongan_darah', strtoupper($search))
                        ->orWhere('status', mb_strtolower($search))
                            ->orWhere('tingkat_kebutuhan', mb_strtolower($search))
                        ->orWhereHas('rumahSakit', function ($rumahSakitQuery) use ($search): void {
                            $rumahSakitQuery->where('nama_rs', 'like', "%{$search}%");
                        });
                });
            })
            ->orderByRaw("CASE tingkat_kebutuhan WHEN 'darurat' THEN 0 WHEN 'mendesak' THEN 1 ELSE 2 END")
            ->latest('id_permintaan_darah')
            ->paginate(15)
            ->withQueryString();

        return view('permintaan-darah.index', compact('permintaanDarahs'));
    }

    public function hospitalIndex(Request $request): View
    {
        $rumahSakit = $request->user()->rumahSakit;
        $request->validate(['q' => ['nullable', 'string', 'max:100']]);
        $search = trim((string) $request->query('q', ''));

        $permintaanDarahs = $rumahSakit->permintaanDarah()
            ->when($search !== '', function ($query) use ($search): void {
                $query->where(function ($query) use ($search): void {
                    $query->where('golongan_darah', strtoupper($search))
                        ->orWhere('status', mb_strtolower($search))
                        ->orWhere('tingkat_kebutuhan', mb_strtolower($search));
                });
            })
            ->orderByRaw("CASE tingkat_kebutuhan WHEN 'darurat' THEN 0 WHEN 'mendesak' THEN 1 ELSE 2 END")
            ->latest('id_permintaan_darah')
            ->paginate(15)
            ->withQueryString();

        return view('permintaan-darah.hospital-index', compact('rumahSakit', 'permintaanDarahs'));
    }

    public function hospitalCreate(Request $request): View
    {
        return view('permintaan-darah.hospital-create', [
            'rumahSakit' => $request->user()->rumahSakit,
        ]);
    }

    public function hospitalStore(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'golongan_darah' => ['required', 'in:A,B,AB,O'],
            'rhesus' => ['required', 'in:+,-'],
            'jumlah_minta' => ['required', 'integer', 'min:1'],
            'tingkat_kebutuhan' => ['required', 'in:darurat,mendesak,normal'],
        ]);

        $rumahSakit = $request->user()->rumahSakit;
        $permintaanDarah = $rumahSakit->permintaanDarah()->create($validated + [
            'tanggal_permintaan' => today(),
            'jumlah_terpenuhi' => 0,
            'status' => 'diproses',
        ]);

        $this->notifyAdmins(
            $permintaanDarah,
            $validated['tingkat_kebutuhan'] === 'darurat' ? 'Permintaan darah darurat' : 'Permintaan darah baru',
            'Permintaan '.$permintaanDarah->golongan_darah.$permintaanDarah->rhesus.' sebanyak '.$permintaanDarah->jumlah_minta.' kantong dari '.$rumahSakit->nama_rs.' dengan tingkat kebutuhan '.$this->priorityLabel($permintaanDarah->tingkat_kebutuhan).' menunggu keputusan.',
        );

        return redirect()
            ->route('faskes.permintaan.index')
            ->with('success', 'Permintaan darah berhasil dikirim kepada admin.');
    }

    public function hospitalShow(Request $request, PermintaanDarah $permintaanDarah): View
    {
        abort_unless((int) $permintaanDarah->rumah_sakit_id === (int) $request->user()->rumahSakit->id_rs, 404);

        $permintaanDarah->load('penyalurans.stokDarah');

        return view('permintaan-darah.hospital-show', compact('permintaanDarah'));
    }

    public function notifikasi(Request $request): View
    {
        $notifications = $request->user()->notifications()
            ->where('type', PermintaanDarahStatusNotification::class)
            ->latest()
            ->paginate(20);
        $unreadCount = $request->user()->unreadNotifications()
            ->where('type', PermintaanDarahStatusNotification::class)
            ->count();

        return view('permintaan-darah.notifikasi', compact('notifications', 'unreadCount'));
    }

    public function bacaNotifikasi(Request $request, string $id): RedirectResponse
    {
        $notification = $request->user()->notifications()
            ->where('type', PermintaanDarahStatusNotification::class)
            ->whereKey($id)
            ->firstOrFail();
        $notification->markAsRead();

        return back()->with('success', 'Notifikasi ditandai sudah dibaca.');
    }

    public function bacaSemuaNotifikasi(Request $request): RedirectResponse
    {
        $request->user()->unreadNotifications()
            ->where('type', PermintaanDarahStatusNotification::class)
            ->update(['read_at' => now(), 'updated_at' => now()]);

        return back()->with('success', 'Semua notifikasi permintaan darah ditandai sudah dibaca.');
    }

    public function hapusSemuaNotifikasi(Request $request): RedirectResponse
    {
        $request->user()->notifications()
            ->where('type', PermintaanDarahStatusNotification::class)
            ->delete();

        return back()->with('success', 'Semua notifikasi permintaan darah berhasil dihapus.');
    }

    public function show(PermintaanDarah $permintaanDarah): View
    {
        $permintaanDarah->load([
            'rumahSakit',
            'penyalurans.stokDarah',
            'penyalurans.diserahkanOleh',
        ]);

        $stokCocokTersedia = $this->stokCocokTersedia($permintaanDarah);

        $sisaPermintaan = max(0, $permintaanDarah->jumlah_minta - $permintaanDarah->jumlah_terpenuhi);

        return view('permintaan-darah.show', compact('permintaanDarah', 'stokCocokTersedia', 'sisaPermintaan'));
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

        $warning = null;
        if ($validated['status'] === 'diterima') {
            $permintaanDarah->refresh();
            $sisaPermintaan = max(0, $permintaanDarah->jumlah_minta - $permintaanDarah->jumlah_terpenuhi);
            $stokTersedia = $this->stokCocokTersedia($permintaanDarah);

            if ($sisaPermintaan > $stokTersedia) {
                $warning = 'Permintaan disetujui, tetapi stok '.$permintaanDarah->golongan_darah.$permintaanDarah->rhesus
                    .' belum mencukupi: tersedia '.$stokTersedia.' kantong, sementara masih dibutuhkan '.$sisaPermintaan
                    .' kantong. Hubungi rumah sakit melalui nomor telepon pada detail permintaan.';
            }
        }

        $permintaanDarah->load('rumahSakit');
        $this->notifyAdmins(
            $permintaanDarah,
            $validated['status'] === 'diterima' ? 'Permintaan darah diterima' : 'Permintaan darah ditolak',
            'Permintaan dari '.($permintaanDarah->rumahSakit?->nama_rs ?? 'rumah sakit').' untuk '.$permintaanDarah->golongan_darah.$permintaanDarah->rhesus.' berstatus '.$validated['status'].'.',
        );

        $redirect = redirect()->route('permintaan-darah.show', $permintaanDarah);

        return $warning
            ? $redirect->with('warning', $warning)
            : $redirect->with('success', $message);
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
                ->where('tanggal_kadaluarsa', '>=', today()->toDateString())
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

        $permintaanDarah->refresh()->load('rumahSakit');
        $this->notifyAdmins(
            $permintaanDarah,
            'Penyaluran permintaan diperbarui',
            'Penyaluran '.$validated['jumlah_kantong'].' kantong ke '.($permintaanDarah->rumahSakit?->nama_rs ?? 'rumah sakit').' dicatat. Total terpenuhi '.$permintaanDarah->jumlah_terpenuhi.' dari '.$permintaanDarah->jumlah_minta.' kantong.',
        );

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

    private function notifyAdmins(PermintaanDarah $permintaanDarah, string $title, string $message): void
    {
        User::query()->where('role', 'admin')->get()->each(
            fn (User $admin) => $admin->notify(new PermintaanDarahStatusNotification(
                $title,
                $message,
                (int) $permintaanDarah->getKey(),
            )),
        );
    }

    private function priorityLabel(string $priority): string
    {
        return match ($priority) {
            'darurat' => 'darurat / urgent',
            'mendesak' => 'mendesak',
            default => 'terencana',
        };
    }

    private function stokCocokTersedia(PermintaanDarah $permintaanDarah): int
    {
        return (int) StokDarah::query()
            ->where('golongan_darah', $permintaanDarah->golongan_darah)
            ->where('rhesus', $permintaanDarah->rhesus)
            ->where('tanggal_kadaluarsa', '>=', today()->toDateString())
            ->where('jumlah_kantong', '>', 0)
            ->sum('jumlah_kantong');
    }
}
