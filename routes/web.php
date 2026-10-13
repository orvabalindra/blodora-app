<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\JadwalDonorController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\PemeriksaanController;
use App\Http\Controllers\PendaftaranDonorController;
use App\Http\Controllers\PendonorController;
use App\Http\Controllers\PengaturanController;
use App\Http\Controllers\PermintaanDarahController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\RiwayatDonorController;
use App\Http\Controllers\RumahSakitController;
use App\Http\Controllers\StokDarahController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| HOME & LANDING PAGE
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])
    ->name('home');

Route::get('/donor', function () {
    return view('landingpage.donor');
})->name('donor');

Route::get('/stok-darah-info', function () {
    return view('landingpage.stok-darah');
})->name('stok.darah');

/*
|--------------------------------------------------------------------------
| AUTHENTICATION
|--------------------------------------------------------------------------
*/

Route::get('/login', [AuthController::class, 'showLogin'])
    ->name('login');

Route::post('/login', [AuthController::class, 'login'])
    ->middleware('throttle:login')
    ->name('login.process');

Route::get('/register', [RegisterController::class, 'showRegister'])
    ->name('register');

Route::post('/register', [RegisterController::class, 'register'])
    ->name('register.process');

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

/*
|--------------------------------------------------------------------------
| PASSWORD RESET
|--------------------------------------------------------------------------
*/

Route::get('/forgot-password', [
    PasswordResetController::class,
    'request',
])->name('password.request');

Route::post('/forgot-password', [
    PasswordResetController::class,
    'sendResetLink',
])->middleware('throttle:password-reset')->name('password.email');

Route::get('/reset-password/{token}', [
    PasswordResetController::class,
    'resetForm',
])->name('password.reset');

Route::post('/reset-password', [
    PasswordResetController::class,
    'reset',
])->middleware('throttle:password-reset')->name('password.update');

/*
|--------------------------------------------------------------------------
| DASHBOARD ADMIN & ADMIN CRUD
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'admin'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Dashboard Admin
    |--------------------------------------------------------------------------
    */

    Route::get('/dashboard/admin', [
        DashboardController::class,
        'index',
    ])->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | CRUD Pendonor
    |--------------------------------------------------------------------------
    */

    Route::resource('pendonor', PendonorController::class);

    /*
    |--------------------------------------------------------------------------
    | CRUD Stok Darah
    |--------------------------------------------------------------------------
    */

    Route::resource(
        'stok-darah',
        StokDarahController::class
    );

    /*
    |--------------------------------------------------------------------------
    | CRUD Pemeriksaan
    |--------------------------------------------------------------------------
    */

    Route::resource(
        'pemeriksaan',
        PemeriksaanController::class
    );

    /*
    |--------------------------------------------------------------------------
    | CRUD Rumah Sakit / Faskes
    |--------------------------------------------------------------------------
    */

    Route::resource(
        'rumah-sakit',
        RumahSakitController::class
    );

    /*
    |--------------------------------------------------------------------------
    | CRUD Riwayat Donor
    |--------------------------------------------------------------------------
    */

    Route::resource(
        'riwayat-donor',
        RiwayatDonorController::class
    );

    /*
    |--------------------------------------------------------------------------
    | CRUD Permintaan Darah
    |--------------------------------------------------------------------------
    */

    Route::resource(
        'permintaan-darah',
        PermintaanDarahController::class
    );
    Route::patch(
        '/permintaan-darah/{permintaanDarah}/status',
        [PermintaanDarahController::class, 'updateStatus']
    )->name('permintaan-darah.status');
    Route::post(
        '/permintaan-darah/{permintaanDarah}/penyaluran',
        [PermintaanDarahController::class, 'storePenyaluran']
    )->name('permintaan-darah.penyaluran');

    Route::resource('jadwal-donor', JadwalDonorController::class)
        ->only(['index', 'create', 'store', 'edit', 'update']);

    Route::get('/pendaftaran-donor', [PendaftaranDonorController::class, 'adminIndex'])
        ->name('pendaftaran-donor.admin.index');
    Route::put('/pendaftaran-donor/{pendaftaran}/screening', [PendaftaranDonorController::class, 'screening'])
        ->name('pendaftaran-donor.screening');
    Route::patch('/pendaftaran-donor/{pendaftaran}/status', [PendaftaranDonorController::class, 'ubahStatus'])
        ->name('pendaftaran-donor.status');

    /*
    |--------------------------------------------------------------------------
    | Pengaturan Admin
    |--------------------------------------------------------------------------
    */

    Route::get('/pengaturan', [
        PengaturanController::class,
        'index',
    ])->name('pengaturan');

});

/*
|--------------------------------------------------------------------------
| DASHBOARD PENDONOR
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'pendonor'])->group(function () {

    Route::get('/dashboard/pendonor', [
        DashboardController::class,
        'index',
    ])->name('dashboard.pendonor');

    Route::get('/donor/jadwal', [PendaftaranDonorController::class, 'jadwal'])
        ->name('donor.jadwal');
    Route::post('/donor/jadwal/{jadwalDonor}/pendaftaran', [PendaftaranDonorController::class, 'daftar'])
        ->name('donor.pendaftaran.store');
    Route::get('/donor/pendaftaran', [PendaftaranDonorController::class, 'riwayat'])
        ->name('donor.pendaftaran.index');
    Route::delete('/donor/pendaftaran/{pendaftaran}', [PendaftaranDonorController::class, 'batal'])
        ->name('donor.pendaftaran.destroy');

});

/*
|--------------------------------------------------------------------------
| PROFIL PENDONOR
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'pendonor'])->group(function () {

    Route::get('/profil/lengkapi', [
        PendonorController::class,
        'createProfile',
    ])->name('pendonor.profile.create');

    Route::post('/profil/lengkapi', [
        PendonorController::class,
        'storeProfile',
    ])->name('pendonor.profile.store');

    Route::get('/profil', [
        PendonorController::class,
        'profile',
    ])->name('pendonor.profile');

});
