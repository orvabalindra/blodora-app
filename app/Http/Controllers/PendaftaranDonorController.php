<?php

namespace App\Http\Controllers;

use App\Models\JadwalDonor;
use App\Models\Pemeriksaan;
use App\Models\Pendaftaran;
use App\Models\RiwayatDonor;
use App\Models\StokDarah;
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
                ->whereNotIn('status', ['gagal_screening', 'dibatalkan', 'tidak_hadir'])
                ->count();

            if ($jumlahAktif >= $jadwal->kuota) {
                return 'kuota_penuh';
            }

            if ($pendaftaran) {
                $pendaftaran->update([
                    'status' => 'menunggu_screening',
                    'keterangan_medis' => null,
                ]);
            } else {
                Pendaftaran::create([
                    'pendonor_id' => $pendonor->id,
                    'jadwal_donor_id' => $jadwal->id,
                    'status' => 'menunggu_screening',
                ]);
            }

            return 'berhasil';
        });

        return match ($hasil) {
            'jadwal_tutup' => back()->withErrors(['jadwal' => 'Jadwal ini sudah lewat.']),
            'sudah_terdaftar' => back()->withErrors(['jadwal' => 'Anda sudah terdaftar pada jadwal ini.']),
            'kuota_penuh' => back()->withErrors(['jadwal' => 'Kuota jadwal sudah penuh.']),
            default => back()->with('success', 'Pendaftaran berhasil. Menunggu screening petugas.'),
        };
    }

    public function batal(Request $request, Pendaftaran $pendaftaran): RedirectResponse
    {
        abort_unless($request->user()->role === 'pendonor', 403);
        abort_unless($pendaftaran->pendonor?->user_id === $request->user()->id, 403);

        if ($pendaftaran->status !== 'menunggu_screening') {
            return back()->withErrors([
                'pendaftaran' => 'Pendaftaran hanya dapat dibatalkan sebelum screening dimulai.',
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

    public function adminIndex(): View
    {
        $pendaftarans = Pendaftaran::with(['pendonor', 'jadwalDonor', 'pemeriksaans'])
            ->latest()->paginate(20);

        return view('pendaftaran-donor.admin-index', compact('pendaftarans'));
    }

    public function screening(Request $request, Pendaftaran $pendaftaran): RedirectResponse
    {
        if (! in_array($pendaftaran->status, ['menunggu_screening', 'ditunda'], true)) {
            return back()->withErrors(['screening' => 'Pendaftaran ini tidak lagi menunggu keputusan screening.']);
        }

        $validated = $request->validate([
            'keputusan' => ['required', Rule::in(['layak', 'tidak_layak', 'ditunda'])],
            'tanggal_pemeriksaan' => ['required_unless:keputusan,ditunda', 'nullable', 'date'],
            'berat_badan' => ['required_unless:keputusan,ditunda', 'nullable', 'integer', 'min:1'],
            'tekanan_darah' => ['required_unless:keputusan,ditunda', 'nullable', 'string', 'max:20'],
            'hemoglobin' => ['required_unless:keputusan,ditunda', 'nullable', 'numeric', 'between:0,30'],
            'catatan' => ['required_if:keputusan,ditunda,tidak_layak', 'nullable', 'string', 'max:2000'],
        ]);

        $berhasil = DB::transaction(function () use ($validated, $pendaftaran, $request) {
            $lockedPendaftaran = Pendaftaran::query()
                ->lockForUpdate()
                ->findOrFail($pendaftaran->getKey());

            if (! in_array($lockedPendaftaran->status, ['menunggu_screening', 'ditunda'], true)) {
                return false;
            }

            if ($validated['keputusan'] !== 'ditunda') {
                Pemeriksaan::create([
                    'pendonor_id' => $lockedPendaftaran->pendonor_id,
                    'pendaftaran_id' => $lockedPendaftaran->id,
                    'screened_by' => $request->user()->id,
                    'tanggal_pemeriksaan' => $validated['tanggal_pemeriksaan'],
                    'berat_badan' => $validated['berat_badan'],
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

        return back()->with('success', 'Hasil screening berhasil dicatat.');
    }

    public function ubahStatus(Request $request, Pendaftaran $pendaftaran): RedirectResponse
    {
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

        $berhasil = DB::transaction(function () use ($pendaftaran, $validated) {
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
            } else {
                $lockedPendaftaran->update(['status' => $validated['status']]);
            }

            return true;
        });

        if (! $berhasil) {
            return back()->withErrors(['status' => 'Status pendaftaran sudah berubah. Muat ulang halaman sebelum mencoba lagi.']);
        }

        return back()->with('success', 'Status pendaftaran berhasil diperbarui.');
    }
}
