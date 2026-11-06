@extends('layouts.app')

@section('title', 'Ajukan Permintaan Darah')

@section('content')
<div class="admin-dashboard">
    @include('components.hospital-sidebar')
    <main class="admin-main">
        <div class="admin-topbar"><div><span class="admin-page-label">PORTAL RUMAH SAKIT</span><h1>Ajukan Permintaan Darah</h1><p>Pengajuan dikirim ke admin untuk diperiksa dan diproses.</p></div></div>
        <div class="admin-panel">
            <div class="admin-panel-header"><div><span class="admin-section-label">FORMULIR PERMINTAAN</span><h3>{{ $rumahSakit->nama_rs }}</h3><p>Nomor telepon kontak: {{ $rumahSakit->no_telp }}</p></div></div>
            <form method="POST" action="{{ route('faskes.permintaan.store') }}" data-swal-confirm="Permintaan akan dikirim kepada admin untuk ditinjau." data-swal-title="Kirim permintaan darah?" data-swal-confirm-button="Ya, kirim">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6"><label class="admin-form-label" for="golongan_darah">Golongan darah</label><select id="golongan_darah" name="golongan_darah" class="admin-form-control" required><option value="">Pilih golongan</option>@foreach (['A', 'B', 'AB', 'O'] as $golongan)<option value="{{ $golongan }}" @selected(old('golongan_darah') === $golongan)>{{ $golongan }}</option>@endforeach</select></div>
                    <div class="col-md-6"><label class="admin-form-label" for="rhesus">Rhesus</label><select id="rhesus" name="rhesus" class="admin-form-control" required><option value="">Pilih rhesus</option><option value="+" @selected(old('rhesus') === '+')>Positif (+)</option><option value="-" @selected(old('rhesus') === '-')>Negatif (-)</option></select></div>
                    <div class="col-md-6"><label class="admin-form-label" for="jumlah_minta">Jumlah yang dibutuhkan</label><div class="admin-input-unit"><input type="number" id="jumlah_minta" name="jumlah_minta" class="admin-form-control" min="1" max="500" value="{{ old('jumlah_minta') }}" required><span class="admin-input-unit-label">kantong</span></div></div>
                    <div class="col-md-6"><label class="admin-form-label" for="tingkat_kebutuhan">Tingkat kebutuhan</label><select id="tingkat_kebutuhan" name="tingkat_kebutuhan" class="admin-form-control" required><option value="normal" @selected(old('tingkat_kebutuhan', 'normal') === 'normal')>Terencana</option><option value="mendesak" @selected(old('tingkat_kebutuhan') === 'mendesak')>Mendesak</option><option value="darurat" @selected(old('tingkat_kebutuhan') === 'darurat')>Darurat / urgent</option></select></div>
                </div>
                <div class="admin-form-alert mt-3">Tingkat darurat membantu admin menentukan prioritas. Pengajuan tetap perlu dikonfirmasi admin berdasarkan stok dan ketersediaan.</div>
                <div class="admin-form-actions"><a href="{{ route('faskes.permintaan.index') }}" class="admin-btn-secondary">Batal</a><button type="submit" class="admin-btn-primary">Kirim Permintaan</button></div>
            </form>
        </div>
        <div class="admin-footer"><span>Portal Rumah Sakit Blodora</span></div>
    </main>
</div>
@endsection
