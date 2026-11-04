<?php

namespace App\Http\Controllers;

use App\Models\Pemeriksaan;
use App\Models\Pendonor;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminLookupController extends Controller
{
    public function pendonors(Request $request): JsonResponse
    {
        $search = trim((string) $request->validate([
            'q' => ['required', 'string', 'min:2', 'max:100'],
        ])['q']);

        $pendonors = Pendonor::query()
            ->where(function ($query) use ($search): void {
                $query->where('nama_lengkap', 'like', $search.'%')
                    ->orWhere('nik', 'like', $search.'%')
                    ->orWhere('no_hp', 'like', $search.'%');
            })
            ->orderBy('nama_lengkap')
            ->limit(20)
            ->get(['id', 'nama_lengkap', 'nik', 'golongan_darah', 'rhesus'])
            ->map(fn (Pendonor $pendonor): array => [
                'id' => $pendonor->id,
                'label' => $pendonor->nama_lengkap.' — '.$pendonor->golongan_darah.$pendonor->rhesus.' · NIK '.$pendonor->nik,
            ]);

        return response()->json($pendonors->values());
    }

    public function availableDonorUsers(Request $request): JsonResponse
    {
        $search = trim((string) $request->validate([
            'q' => ['required', 'string', 'min:2', 'max:100'],
        ])['q']);

        $users = User::query()
            ->where('role', 'pendonor')
            ->whereDoesntHave('pendonor')
            ->where(function ($query) use ($search): void {
                $query->where('username', 'like', $search.'%')
                    ->orWhere('email', 'like', $search.'%');
            })
            ->orderBy('username')
            ->limit(20)
            ->get(['id', 'username', 'email'])
            ->map(fn (User $user): array => [
                'id' => $user->id,
                'label' => $user->username.' ('.$user->email.')',
            ]);

        return response()->json($users->values());
    }

    public function pemeriksaans(Request $request): JsonResponse
    {
        $search = trim((string) $request->validate([
            'q' => ['required', 'string', 'min:2', 'max:100'],
        ])['q']);

        $pemeriksaans = Pemeriksaan::query()
            ->with('pendonor:id,nama_lengkap')
            ->where(function ($query) use ($search): void {
                $query->where('hasil', 'like', $search.'%')
                    ->orWhere('tanggal_pemeriksaan', 'like', $search.'%')
                    ->orWhereHas('pendonor', fn ($pendonorQuery) => $pendonorQuery
                        ->where('nama_lengkap', 'like', $search.'%'));
            })
            ->latest('id_pemeriksaan')
            ->limit(20)
            ->get(['id_pemeriksaan', 'pendonor_id', 'tanggal_pemeriksaan', 'hasil'])
            ->map(fn (Pemeriksaan $pemeriksaan): array => [
                'id' => $pemeriksaan->id_pemeriksaan,
                'label' => ($pemeriksaan->pendonor?->nama_lengkap ?? 'Pendonor tidak ditemukan')
                    .' — '.ucfirst($pemeriksaan->hasil)
                    .' · '.optional($pemeriksaan->tanggal_pemeriksaan)->format('d-m-Y'),
            ]);

        return response()->json($pemeriksaans->values());
    }
}
