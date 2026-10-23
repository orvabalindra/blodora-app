<?php

namespace App\Http\Controllers;

use App\Models\JadwalDonor;
use App\Models\Pemeriksaan;
use App\Models\Pendaftaran;
use App\Models\RiwayatDonor;
use App\Models\StokDarah;
use App\Notifications\StatusPendaftaranDonorNotification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PendaftaranDonorController extends Controller
{
    public function jadwal(Request $request): View
    {
        abort_unless($request->user()->role === 'pendonor', 403);

        $pendonor = $request->user()->pendonor;

        if (! $pendonor) {
            return view('jadwal-donor.available', [
                'jadwalDonors' => collect(),
                'pendaftaranByJadwal' => collect(),
                'butuhProfil' => true,
            ]);
        }

        $jadwalDonors = JadwalDonor::withCount([
            'pendaftarans as pendaftar_aktif_count' => fn (Builder $query) => $query
                ->where('persetujuan_admin', '!=', 'ditolak')
                ->whereNotIn('status', ['gagal_screening', 'dibatalkan', 'tidak_hadir']),
        ])->where(function (Builder $query) {
            $query->whereDate('tanggal', '>', today())
                ->orWhere(function (Builder $todayQuery) {
                    $todayQuery->whereDate('tanggal', today())
                        ->where('waktu_mulai', '>', now()->format('H:i:s'));
                });
        })
            ->orderBy('tanggal')->orderBy('waktu_mulai')->get();

        $pendaftaranByJadwal = Pendaftaran::where('pendonor_id', $pendonor->id)
            ->whereIn('jadwal_donor_id', $jadwalDonors->modelKeys())
            ->get()->keyBy('jadwal_donor_id');

        return view('jadwal-donor.available', compact(
            'jadwalDonors',
            'pendaftaranByJadwal'
        ) + ['butuhProfil' => false]);
    }

    public function daftar(Request $request, JadwalDonor $jadwalDonor): RedirectResponse
    {
        abort_unless($request->user()->role === 'pendonor', 403);

        $pendonor = $request->user()->pendonor;

        if (! $pendonor) {
            return redirect()->route('pendonor.profile.create')
                ->with('info', 'Lengkapi profil sebelum mendaftar donor.');
        }

        $hasil = DB::transaction(function () use ($jadwalDonor, $pendonor) {
            $jadwal = JadwalDonor::query()->lockForUpdate()->findOrFail($jadwalDonor->getKey());

            $waktuMulai = $jadwal->tanggal->copy()
                ->setTimeFromTimeString($jadwal->waktu_mulai);

            if ($waktuMulai->isPast()) {
                return 'jadwal_tutup';
            }

            $pendaftaran = Pendaftaran::where('pendonor_id', $pendonor->id)
                ->where('jadwal_donor_id', $jadwal->id)
                ->first();

            if ($pendaftaran && $pendaftaran->status !== 'dibatalkan') {
                return 'sudah_terdaftar';
            }

            $jumlahAktif = $jadwal->pendaftarans()
                ->where('persetujuan_admin', '!=', 'ditolak')
                ->whereNotIn('status', ['gagal_screening', 'dibatalkan', 'tidak_hadir'])
                ->count();

            if ($jumlahAktif >= $jadwal->kuota) {
                return 'kuota_penuh';
            }

            if ($pendaftaran) {
                $pendaftaran->update([
                    'status' => 'menunggu_screening',
                    'persetujuan_admin' => 'menunggu',
                    'catatan_admin' => null,
                    'keterangan_medis' => null,
                ]);
            } else {
                Pendaftaran::create([
                    'pendonor_id' => $pendonor->id,
                    'jadwal_donor_id' => $jadwal->id,
                    'status' => 'menunggu_screening',
                    'persetujuan_admin' => 'menunggu',
                ]);
            }

            return 'berhasil';
        });

        if ($hasil === 'berhasil') {
            $this->notifyDonorStatus(
                Pendaftaran::where('pendonor_id', $pendonor->id)
                    ->where('jadwal_donor_id', $jadwalDonor->id)
                    ->firstOrFail(),
                'Pengajuan donor terkirim',
                'Pengajuanmu sudah diterima dan sedang menunggu persetujuan admin.',
            );
        }

        return match ($hasil) {
            'jadwal_tutup' => back()->withErrors(['jadwal' => 'Jadwal ini sudah lewat.']),
            'sudah_terdaftar' => back()->withErrors(['jadwal' => 'Anda sudah terdaftar pada jadwal ini.']),
            'kuota_penuh' => back()->withErrors(['jadwal' => 'Kuota jadwal sudah penuh.']),
            default => back()->with('success', 'Pengajuan donor berhasil dikirim dan menunggu persetujuan admin.'),
        };
    }

    public function batal(Request $request, Pendaftaran $pendaftaran): RedirectResponse
    {
        abort_unless($request->user()->role === 'pendonor', 403);
        abort_unless($pendaftaran->pendonor?->user_id === $request->user()->id, 403);

        if (! in_array($pendaftaran->persetujuan_admin, ['menunggu', 'disetujui'], true)
            || $pendaftaran->status !== 'menunggu_screening') {
            return back()->withErrors([
                'pendaftaran' => 'Pendaftaran hanya dapat dibatalkan sebelum screening dimulai.',
            ]);
        }

        $jadwal = $pendaftaran->jadwalDonor;
        $waktuMulai = $jadwal?->tanggal?->copy()->setTimeFromTimeString($jadwal->waktu_mulai);

        if ($waktuMulai && now()->greaterThanOrEqualTo($waktuMulai)) {
            return back()->withErrors([
                'pendaftaran' => 'Pendaftaran tidak dapat dibatalkan karena waktu screening sudah dimulai.',
            ]);
        }

        $pendaftaran->update(['status' => 'dibatalkan']);

        return back()->with('success', 'Pendaftaran berhasil dibatalkan.');
    }

    public function riwayat(Request $request): View
    {
        abort_unless($request->user()->role === 'pendonor', 403);

        $pendonor = $request->user()->pendonor;
        $pendaftarans = $pendonor
            ? $pendonor->pendaftarans()->with('jadwalDonor')->latest()->paginate(15)
            : collect();

        return view('pendaftaran-donor.riwayat', compact('pendaftarans', 'pendonor'));
    }

    public function notifikasi(Request $request): View
    {
        abort_unless($request->user()->role === 'pendonor', 403);

        $notifications = $request->user()->notifications()->latest()->paginate(20);
        $unreadCount = $request->user()->unreadNotifications()->count();

        return view('pendaftaran-donor.notifikasi', compact('notifications', 'unreadCount'));
    }

    public function bacaNotifikasi(Request $request, string $id): RedirectResponse
    {
        abort_unless($request->user()->role === 'pendonor', 403);

        $notification = $request->user()->notifications()->whereKey($id)->firstOrFail();
        $notification->markAsRead();

        return back()->with('success', 'Notifikasi ditandai sudah dibaca.');
    }

    public function bacaSemuaNotifikasi(Request $request): RedirectResponse
    {
        abort_unless($request->user()->role === 'pendonor', 403);

        $request->user()->unreadNotifications()->update([
            'read_at' => now(),
            'updated_at' => now(),
        ]);

        return back()->with('success', 'Semua notifikasi ditandai sudah dibaca.');
    }

    public function hapusSemuaNotifikasi(Request $request): RedirectResponse
    {
        abort_unless($request->user()->role === 'pendonor', 403);

        $request->user()->notifications()->delete();

        return back()->with('success', 'Semua notifikasi berhasil dihapus.');
    }

    public function adminIndex(Request $request): View
    {
        $validated = $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
            'status' => ['nullable', Rule::in([
                'menunggu_screening', 'ditunda', 'proses_donor', 'selesai',
                'gagal_screening', 'tidak_hadir', 'dibatalkan',
            ])],
        ]);

        $pendaftarans = Pendaftaran::with(['pendonor', 'jadwalDonor', 'pemeriksaans'])
            ->when($request->filled('q'), function (Builder $query) use ($validated): void {
                $search = trim($validated['q']);

                $query->where(function (Builder $query) use ($search): void {
                    $query->whereHas('pendonor', fn (Builder $pendonor) => $pendonor
                        ->where('nama_lengkap', 'like', "%{$search}%"))
                        ->orWhereHas('jadwalDonor', fn (Builder $jadwal) => $jadwal
                            ->where('nama_kegiatan', 'like', "%{$search}%")
                            ->orWhere('lokasi', 'like', "%{$search}%"))
                        ->orWhere('status', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('status'), fn (Builder $query) => $query->where('status', $validated['status']))
            ->latest()->paginate(12)->withQueryString();

        return view('pendaftaran-donor.admin-index', [
            'pendaftarans' => $pendaftarans,
            'q' => $validated['q'] ?? '',
            'status' => $validated['status'] ?? '',
        ]);
    }

    public function persetujuan(Request $request, Pendaftaran $pendaftaran): RedirectResponse
    {
        $validated = $request->validate([
            'keputusan' => ['required', Rule::in(['setujui', 'tolak'])],
            'catatan_admin' => ['required_if:keputusan,tolak', 'nullable', 'string', 'max:2000'],
        ]);

        $berhasil = DB::transaction(function () use ($validated, $pendaftaran): bool {
            $lockedPendaftaran = Pendaftaran::query()
                ->lockForUpdate()
                ->findOrFail($pendaftaran->getKey());

            if ($lockedPendaftaran->persetujuan_admin !== 'menunggu'
                || $lockedPendaftaran->status !== 'menunggu_screening') {
                return false;
            }

            $ditolak = $validated['keputusan'] === 'tolak';
            $lockedPendaftaran->update([
                'persetujuan_admin' => $ditolak ? 'ditolak' : 'disetujui',
                'catatan_admin' => $ditolak ? $validated['catatan_admin'] : null,
            ]);

            return true;
        });

        if (! $berhasil) {
            return back()->withErrors(['persetujuan' => 'Pendaftaran ini sudah diproses. Muat ulang halaman sebelum mencoba lagi.']);
        }

        $this->notifyDonorStatus(
            $pendaftaran,
            $validated['keputusan'] === 'setujui' ? 'Pengajuan donor disetujui' : 'Pengajuan donor ditolak',
            $validated['keputusan'] === 'setujui'
                ? 'Admin menyetujui pengajuanmu. Datang sesuai jadwal untuk mengikuti screening.'
                : 'Admin menolak pengajuanmu. Alasan: '.$validated['catatan_admin'],
        );

        return back()->with('success', $validated['keputusan'] === 'setujui'
            ? 'Pendaftaran donor disetujui. Pendonor dapat mengikuti screening pada jadwal kegiatan.'
            : 'Pendaftaran donor ditolak. Pendonor dapat melihat catatan keputusan ini.');
    }

    public function screening(Request $request, Pendaftaran $pendaftaran): RedirectResponse
    {
        $this->mergeDateParts($request);
        if ($pendaftaran->persetujuan_admin !== 'disetujui'
            || ! in_array($pendaftaran->status, ['menunggu_screening', 'ditunda'], true)) {
            return back()->withErrors(['screening' => 'Pendaftaran ini tidak lagi menunggu keputusan screening.']);
        }

        $jadwal = $pendaftaran->jadwalDonor;
        $waktuMulai = $jadwal?->tanggal?->copy()->setTimeFromTimeString($jadwal->waktu_mulai);

        if (! $waktuMulai || now()->lessThan($waktuMulai)) {
            return back()->withErrors(['screening' => 'Screening baru dapat dimulai saat jadwal donor berlangsung.']);
        }

        $validated = $request->validate([
            'keputusan' => ['required', Rule::in(['layak', 'tidak_layak', 'ditunda'])],
            'tanggal_pemeriksaan' => ['required_unless:keputusan,ditunda', 'nullable', 'date', 'before_or_equal:today'],
            'tekanan_darah' => ['required_unless:keputusan,ditunda', 'nullable', 'string', 'max:20'],
            'hemoglobin' => ['required_unless:keputusan,ditunda', 'nullable', 'numeric', 'between:0,30'],
            'catatan' => ['required_if:keputusan,ditunda,tidak_layak', 'nullable', 'string', 'max:2000'],
        ]);

        $beratBadanProfil = $pendaftaran->pendonor?->berat_badan;
        $tinggiBadanProfil = $pendaftaran->pendonor?->tinggi_badan;

        if ($validated['keputusan'] !== 'ditunda' && ! $beratBadanProfil) {
            return back()->withErrors(['screening' => 'Berat badan belum tersedia di profil pendonor. Lengkapi profil sebelum screening.']);
        }

        $berhasil = DB::transaction(function () use ($validated, $pendaftaran, $request, $beratBadanProfil, $tinggiBadanProfil) {
            $lockedPendaftaran = Pendaftaran::query()
                ->lockForUpdate()
                ->findOrFail($pendaftaran->getKey());

            if ($lockedPendaftaran->persetujuan_admin !== 'disetujui'
                || ! in_array($lockedPendaftaran->status, ['menunggu_screening', 'ditunda'], true)) {
                return false;
            }

            if ($validated['keputusan'] !== 'ditunda') {
                Pemeriksaan::create([
                    'pendonor_id' => $lockedPendaftaran->pendonor_id,
                    'pendaftaran_id' => $lockedPendaftaran->id,
                    'screened_by' => $request->user()->id,
                    'tanggal_pemeriksaan' => $validated['tanggal_pemeriksaan'],
                    'berat_badan' => $beratBadanProfil,
                    'tinggi_badan' => $tinggiBadanProfil,
                    'tekanan_darah' => $validated['tekanan_darah'],
                    'hemoglobin' => $validated['hemoglobin'],
                    'hasil' => $validated['keputusan'] === 'layak' ? 'layak' : 'tidak layak',
                ]);
            }

            $status = match ($validated['keputusan']) {
                'layak' => 'proses_donor',
                'tidak_layak' => 'gagal_screening',
                default => 'ditunda',
            };

            $lockedPendaftaran->update([
                'status' => $status,
                'keterangan_medis' => $validated['catatan'] ?? null,
            ]);

            return true;
        });

        if (! $berhasil) {
            return back()->withErrors(['screening' => 'Status pendaftaran sudah berubah. Muat ulang halaman sebelum mencoba lagi.']);
        }

        [$judulNotifikasi, $pesanNotifikasi] = match ($validated['keputusan']) {
            'layak' => ['Screening donor: layak', 'Kamu dinyatakan layak untuk melanjutkan donor pada jadwal ini.'],
            'tidak_layak' => ['Hasil screening donor', 'Kamu belum dapat melanjutkan donor. Silakan lihat informasi pendaftaran atau hubungi petugas.'],
            default => ['Screening donor ditunda', 'Petugas menunda screening dan akan mengevaluasi kembali pengajuanmu.'],
        };
        $this->notifyDonorStatus($pendaftaran, $judulNotifikasi, $pesanNotifikasi);

        return back()->with('success', 'Hasil screening berhasil dicatat.');
    }

    public function ubahStatus(Request $request, Pendaftaran $pendaftaran): RedirectResponse
    {
        $this->mergeDateParts($request);

        $validated = $request->validate([
            'status' => ['required', Rule::in(['selesai', 'tidak_hadir'])],
            'jumlah_donor' => ['required_if:status,selesai', 'nullable', 'integer', 'min:1'],
            'tanggal_kadaluarsa' => [
                'required_if:status,selesai',
                'nullable',
                'date',
                'after_or_equal:today',
                'after_or_equal:'.$pendaftaran->jadwalDonor->tanggal->toDateString(),
            ],
        ]);

        if ($pendaftaran->status !== 'proses_donor') {
            return back()->withErrors(['status' => 'Hanya pendaftar yang lolos screening dapat diselesaikan atau ditandai tidak hadir.']);
        }

        $jadwalDonor = $pendaftaran->jadwalDonor;
        $waktuSelesai = $jadwalDonor->tanggal->copy()
            ->setTimeFromTimeString($jadwalDonor->waktu_selesai);

        if ($waktuSelesai->isFuture()) {
            return back()->withErrors(['status' => 'Hasil kehadiran hanya dapat dicatat setelah jadwal donor selesai.']);
        }

        $hasil = DB::transaction(function () use ($pendaftaran, $validated) {
            $lockedPendaftaran = Pendaftaran::query()
                ->lockForUpdate()
                ->findOrFail($pendaftaran->getKey());

            if ($lockedPendaftaran->status !== 'proses_donor') {
                return false;
            }

            if ($validated['status'] === 'selesai') {
                $pemeriksaan = $lockedPendaftaran->pemeriksaans()
                    ->where('hasil', 'layak')
                    ->latest('id_pemeriksaan')
                    ->firstOrFail();
                $jadwalDonor = $lockedPendaftaran->jadwalDonor;
                $pendonor = $lockedPendaftaran->pendonor;

                if (! $pendonor || ! $pendonor->golongan_darah || ! $pendonor->rhesus) {
                    return 'profil_tidak_lengkap';
                }

                RiwayatDonor::create([
                    'pendonor_id' => $lockedPendaftaran->pendonor_id,
                    'pendaftaran_id' => $lockedPendaftaran->id,
                    'pemeriksaan_id' => $pemeriksaan->id_pemeriksaan,
                    'tanggal_donor' => $jadwalDonor->tanggal,
                    'jumlah_donor' => $validated['jumlah_donor'],
                    'status' => 'sukses',
                ]);

                StokDarah::create([
                    'pendonor_id' => $pendonor->id,
                    'pendaftaran_id' => $lockedPendaftaran->id,
                    'lokasi' => $jadwalDonor->lokasi,
                    'golongan_darah' => $pendonor->golongan_darah,
                    'rhesus' => $pendonor->rhesus,
                    'jumlah_kantong' => $validated['jumlah_donor'],
                    'tanggal_masuk' => $jadwalDonor->tanggal,
                    'tanggal_kadaluarsa' => $validated['tanggal_kadaluarsa'],
                ]);

                $lockedPendaftaran->update([
                    'status' => 'selesai',
                    'tanggal_selesai_donor' => $jadwalDonor->tanggal,
                ]);

                return [
                    'status' => 'selesai',
                    'jumlah' => (int) $validated['jumlah_donor'],
                    'golongan' => $pendonor->golongan_darah.$pendonor->rhesus,
                ];
            } else {
                $lockedPendaftaran->update(['status' => $validated['status']]);

                return ['status' => 'tidak_hadir'];
            }
        });

        if ($hasil === false) {
            return back()->withErrors(['status' => 'Status pendaftaran sudah berubah. Muat ulang halaman sebelum mencoba lagi.']);
        }

        if ($hasil === 'profil_tidak_lengkap') {
            return back()->withErrors(['status' => 'Golongan darah pendonor belum lengkap. Perbarui profil sebelum menambahkan stok.']);
        }

        $this->notifyDonorStatus(
            $pendaftaran,
            $hasil['status'] === 'selesai' ? 'Donor selesai' : 'Status pendaftaran donor diperbarui',
            $hasil['status'] === 'selesai'
                ? 'Donormu tercatat selesai. Terima kasih sudah mendonorkan darah.'
                : 'Pendaftaranmu ditandai tidak hadir oleh petugas.',
        );

        $pesan = $hasil['status'] === 'selesai'
            ? "Donor selesai. {$hasil['jumlah']} kantong golongan {$hasil['golongan']} otomatis ditambahkan ke stok."
            : 'Pendonor berhasil ditandai tidak hadir.';

        return back()->with('success', $pesan);
    }

    private function notifyDonorStatus(Pendaftaran $pendaftaran, string $title, string $message): void
    {
        $user = $pendaftaran->loadMissing('pendonor.user')->pendonor?->user;

        $user?->notify(new StatusPendaftaranDonorNotification(
            $title,
            $message,
            (int) $pendaftaran->getKey(),
        ));
    }
}
