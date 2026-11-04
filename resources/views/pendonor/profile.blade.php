@extends('layouts.app')

@section('title', 'Lengkapi Profil Pendonor')

@section('content')
<main class="profile-setup-page">
    <div class="profile-setup-card">
        <header class="profile-setup-header">
            <span class="profile-setup-brand">BLODORA</span>
            <h1>Lengkapi Profil Pendonor</h1>
            <p>Isi beberapa informasi singkat agar kamu dapat menggunakan layanan donor.</p>
        </header>

        @if ($errors->any())
            <div class="alert alert-danger" role="alert">
                <strong>Periksa kembali data berikut.</strong>
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @php
            $stepAwal = $errors->hasAny(['nik', 'nama_lengkap']) ? 1
                : ($errors->hasAny(['no_hp', 'alamat']) ? 2 : 3);
        @endphp

        <div class="profile-stepper" aria-label="Tahapan pengisian profil">
            <div class="profile-stepper-item is-active" data-step-indicator="1"><span>1</span><small>Data diri</small></div>
            <div class="profile-stepper-line"></div>
            <div class="profile-stepper-item" data-step-indicator="2"><span>2</span><small>Kontak</small></div>
            <div class="profile-stepper-line"></div>
            <div class="profile-stepper-item" data-step-indicator="3"><span>3</span><small>Data donor</small></div>
        </div>

        <form id="donor-profile-wizard" action="{{ route('pendonor.profile.store') }}" method="POST" data-start-step="{{ $stepAwal }}">
            @csrf

            <section class="profile-wizard-step" data-step="1" aria-labelledby="profile-step-title-1">
                <div class="profile-step-heading">
                    <span>LANGKAH 1 DARI 3</span>
                    <h2 id="profile-step-title-1">Data diri</h2>
                    <p>Masukkan identitas sesuai kartu identitasmu.</p>
                </div>
                <div class="mb-3">
                    <label for="nik" class="form-label fw-semibold">NIK</label>
                    <input id="nik" type="text" name="nik" class="form-control @error('nik') is-invalid @enderror" value="{{ old('nik') }}" placeholder="Masukkan NIK" required>
                    @error('nik')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-4">
                    <label for="nama_lengkap" class="form-label fw-semibold">Nama Lengkap</label>
                    <input id="nama_lengkap" type="text" name="nama_lengkap" class="form-control @error('nama_lengkap') is-invalid @enderror" value="{{ old('nama_lengkap') }}" placeholder="Masukkan nama lengkap" required>
                    @error('nama_lengkap')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <button type="button" class="btn btn-danger w-100 py-3 rounded-3 fw-semibold" data-next-step>Lanjut ke Kontak <i class="bi bi-arrow-right ms-1" aria-hidden="true"></i></button>
            </section>

            <section class="profile-wizard-step" data-step="2" aria-labelledby="profile-step-title-2">
                <div class="profile-step-heading">
                    <span>LANGKAH 2 DARI 3</span>
                    <h2 id="profile-step-title-2">Kontak dan alamat</h2>
                    <p>Informasi ini membantu petugas menghubungimu.</p>
                </div>
                <div class="mb-3">
                    <label for="no_hp" class="form-label fw-semibold">Nomor HP</label>
                    <input id="no_hp" type="tel" name="no_hp" class="form-control @error('no_hp') is-invalid @enderror" value="{{ old('no_hp') }}" placeholder="Contoh: 081234567890" required>
                    @error('no_hp')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-4">
                    <label for="alamat" class="form-label fw-semibold">Alamat</label>
                    <textarea id="alamat" name="alamat" class="form-control @error('alamat') is-invalid @enderror" rows="4" placeholder="Masukkan alamat lengkap" required>{{ old('alamat') }}</textarea>
                    @error('alamat')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="profile-wizard-actions">
                    <button type="button" class="btn btn-outline-secondary py-3 rounded-3 fw-semibold" data-previous-step><i class="bi bi-arrow-left me-1" aria-hidden="true"></i> Kembali</button>
                    <button type="button" class="btn btn-danger py-3 rounded-3 fw-semibold" data-next-step>Lanjut ke Data Donor <i class="bi bi-arrow-right ms-1" aria-hidden="true"></i></button>
                </div>
            </section>

            <section class="profile-wizard-step" data-step="3" aria-labelledby="profile-step-title-3">
                <div class="profile-step-heading">
                    <span>LANGKAH 3 DARI 3</span>
                    <h2 id="profile-step-title-3">Informasi donor</h2>
                    <p>Data ini digunakan untuk memastikan kesiapan dan kecocokan donor.</p>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="golongan_darah" class="form-label fw-semibold">Golongan Darah</label>
                        <select id="golongan_darah" name="golongan_darah" class="form-select @error('golongan_darah') is-invalid @enderror" required>
                            <option value="">Pilih golongan</option>
                            @foreach (['A', 'B', 'AB', 'O'] as $golongan)
                                <option value="{{ $golongan }}" @selected(old('golongan_darah') === $golongan)>{{ $golongan }}</option>
                            @endforeach
                        </select>
                        @error('golongan_darah')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="rhesus" class="form-label fw-semibold">Rhesus</label>
                        <select id="rhesus" name="rhesus" class="form-select @error('rhesus') is-invalid @enderror" required>
                            <option value="">Pilih rhesus</option>
                            <option value="+" @selected(old('rhesus') === '+')>Positif (+)</option>
                            <option value="-" @selected(old('rhesus') === '-')>Negatif (-)</option>
                        </select>
                        @error('rhesus')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="mb-3">
                    <label for="berat_badan" class="form-label fw-semibold">Berat Badan</label>
                    <div class="input-group">
                        <input id="berat_badan" type="number" name="berat_badan" class="form-control @error('berat_badan') is-invalid @enderror" value="{{ old('berat_badan') }}" placeholder="Contoh: 60" min="1" required>
                        <span class="input-group-text">kg</span>
                        @error('berat_badan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="mb-4">
                    <label for="tinggi_badan" class="form-label fw-semibold">Tinggi Badan <span class="text-muted fw-normal">(opsional)</span></label>
                    <div class="input-group">
                        <input id="tinggi_badan" type="number" step="0.1" min="50" max="250" name="tinggi_badan" class="form-control @error('tinggi_badan') is-invalid @enderror" value="{{ old('tinggi_badan') }}" placeholder="Contoh: 165">
                        <span class="input-group-text">cm</span>
                        @error('tinggi_badan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="profile-wizard-actions">
                    <button type="button" class="btn btn-outline-secondary py-3 rounded-3 fw-semibold" data-previous-step><i class="bi bi-arrow-left me-1" aria-hidden="true"></i> Kembali</button>
                    <button type="submit" class="btn btn-danger py-3 rounded-3 fw-semibold">Simpan Profil Pendonor <i class="bi bi-check2 ms-1" aria-hidden="true"></i></button>
                </div>
            </section>
        </form>
    </div>
</main>

<script>
    (() => {
        const form = document.getElementById('donor-profile-wizard');
        if (!form) return;

        const steps = [...form.querySelectorAll('[data-step]')];
        const indicators = [...document.querySelectorAll('[data-step-indicator]')];
        let currentStep = Number(form.dataset.startStep || 1);

        const showStep = (stepNumber) => {
            currentStep = stepNumber;
            steps.forEach((step) => {
                const active = Number(step.dataset.step) === stepNumber;
                step.hidden = !active;
                step.setAttribute('aria-hidden', String(!active));
            });
            indicators.forEach((indicator) => {
                const number = Number(indicator.dataset.stepIndicator);
                indicator.classList.toggle('is-active', number === stepNumber);
                indicator.classList.toggle('is-complete', number < stepNumber);
            });
        };

        form.addEventListener('click', (event) => {
            const next = event.target.closest('[data-next-step]');
            const previous = event.target.closest('[data-previous-step]');
            if (next) {
                const activeStep = steps.find((step) => Number(step.dataset.step) === currentStep);
                const invalidField = [...activeStep.querySelectorAll('input, select, textarea')]
                    .find((field) => !field.checkValidity());
                if (invalidField) {
                    invalidField.reportValidity();
                    return;
                }
                showStep(Math.min(currentStep + 1, steps.length));
            } else if (previous) {
                showStep(Math.max(currentStep - 1, 1));
            }
        });

        showStep(currentStep);
    })();
</script>
@endsection
