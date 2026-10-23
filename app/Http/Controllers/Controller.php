<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

abstract class Controller
{
    protected function mergeDateParts(Request $request): void
    {
        foreach (array_keys($request->all()) as $key) {
            if (! str_ends_with($key, '_hari')) {
                continue;
            }

            $field = substr($key, 0, -5);
            if (! $request->has([$field.'_bulan', $field.'_tahun'])) {
                continue;
            }

            $day = (int) $request->input($field.'_hari');
            $month = (int) $request->input($field.'_bulan');
            $year = (int) $request->input($field.'_tahun');

            $request->merge([
                $field => checkdate($month, $day, $year)
                    ? sprintf('%04d-%02d-%02d', $year, $month, $day)
                    : 'tanggal-tidak-valid',
            ]);
        }
    }

    protected function authenticatedDestination(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($user?->role === 'admin') {
            return redirect()->route('dashboard');
        }

        if ($user?->role === 'pendonor') {
            return $user->pendonor
                ? redirect()->route('dashboard.pendonor')
                : redirect()->route('pendonor.profile.create');
        }

        return redirect()->route('home');
    }
}
