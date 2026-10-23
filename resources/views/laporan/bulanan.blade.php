@extends('layouts.app')

@section('title', 'Laporan Bulanan')

@section('content')
@php([$tahunLaporan, $bulanLaporan] = explode('-', $bulan))
<div class="admin-dashboard">
    @include('components.admin-sidebar')
    <main class="admin-main">
        <div class="admin-topbar">
            <div><span class="admin-page-label">LAPORAN</span><h1>Laporan Bulanan</h1><p>Ringkasan stok donor dan penyaluran darah untuk {{ $labelBulan }}.</p></div>
        </div>
        <section class="admin-panel admin-report-filter mb-4">
            <form method="GET" action="{{ route('laporan.bulanan') }}" class="d-flex flex-wrap align-items-end gap-3" data-month-report-filter>
                <div class="admin-report-month-fields">
                    <div><label class="admin-form-label" for="report-month">Bulan</label><select class="form-select" id="report-month" data-report-month>@foreach (['01'=>'Januari','02'=>'Februari','03'=>'Maret','04'=>'April','05'=>'Mei','06'=>'Juni','07'=>'Juli','08'=>'Agustus','09'=>'September','10'=>'Oktober','11'=>'November','12'=>'Desember'] as $angka => $nama)<option value="{{ $angka }}" @selected((int) $bulanLaporan === (int) $angka)>{{ $nama }}</option>@endforeach</select></div>
                    <div><label class="admin-form-label" for="report-year">Tahun</label><select class="form-select" id="report-year" data-report-year>@for ($tahun = now()->year - 5; $tahun <= now()->year + 5; $tahun++)<option value="{{ $tahun }}" @selected((int) $tahunLaporan === $tahun)>{{ $tahun }}</option>@endfor</select></div>
                </div>
                <input type="hidden" name="bulan" value="{{ $bulan }}">
                <button type="submit" class="admin-btn-primary">Tampilkan</button>
        </form>
        </section>

        <div class="row g-3 mb-4">
            <div class="col-12 col-md-4">
                <section class="admin-stat-card">
                    <div class="admin-stat-content"><span>Stok masuk dari donor</span><h2>{{ $stokMasuk }}</h2><small>kantong pada {{ $labelBulan }}</small></div>
                </section>
            </div>
            <div class="col-12 col-md-4">
                <section class="admin-stat-card">
                    <div class="admin-stat-content"><span>Stok keluar disalurkan</span><h2>{{ $stokKeluar }}</h2><small>kantong pada {{ $labelBulan }}</small></div>
                </section>
            </div>
            <div class="col-12 col-md-4">
                <section class="admin-stat-card">
                    <div class="admin-stat-content"><span>Penyaluran tercatat</span><h2>{{ $jumlahPenyaluran }}</h2><small>transaksi pada {{ $labelBulan }}</small></div>
                </section>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-12 col-xl-7">
                <section class="admin-panel admin-report-panel">
                    <div class="admin-panel-header">
                        <div><span class="admin-section-label">RINCIAN STOK</span><h3>Masuk dan keluar per golongan darah</h3><p>Jumlah kantong yang dicatat selama {{ $labelBulan }}.</p></div>
                    </div>
                    <div class="table-responsive">
                        <table class="table admin-data-table align-middle">
                            <thead><tr><th>Golongan</th><th>Masuk</th><th>Keluar</th><th>Selisih</th></tr></thead>
                            <tbody>
                                @foreach ($ringkasanGolongan as $baris)
                                    <tr>
                                        <td><strong>{{ $baris['golongan'] }}</strong></td>
                                        <td>{{ $baris['masuk'] }} kantong</td>
                                        <td>{{ $baris['keluar'] }} kantong</td>
                                        <td>{{ $baris['masuk'] - $baris['keluar'] }} kantong</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>
            <div class="col-12 col-xl-5">
                <section class="admin-panel admin-report-panel">
                    <div class="admin-panel-header">
                        <div><span class="admin-section-label">FASKES</span><h3>Rumah sakit yang paling sering meminta</h3><p>Diurutkan berdasarkan jumlah permintaan yang dibuat pada {{ $labelBulan }}.</p></div>
                    </div>
                    <div class="table-responsive">
                        <table class="table admin-data-table align-middle">
                            <thead><tr><th>Rumah sakit</th><th>Permintaan</th><th>Diminta</th><th>Disalurkan</th></tr></thead>
                            <tbody>
                                @forelse ($rumahSakitTeratas as $rumahSakit)
                                    <tr><td><strong>{{ $rumahSakit->nama_rumah_sakit }}</strong></td><td>{{ $rumahSakit->jumlah_permintaan }}</td><td>{{ $rumahSakit->jumlah_diminta }} kantong</td><td>{{ $rumahSakit->jumlah_disalurkan }} kantong</td></tr>
                                @empty
                                    <tr><td colspan="4" class="text-center text-muted py-4">Belum ada permintaan pada bulan ini.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>
        </div>

        <div class="admin-footer"><span>Panel Admin Blodora</span><span>Periode: {{ $labelBulan }}</span></div>
    </main>
</div>
@endsection
