<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Blodora membantu pendonor menemukan jadwal donor dan melihat informasi ketersediaan stok darah.">
    <meta name="theme-color" content="#ffffff">

    @vite(['resources/css/app.css', 'resources/css/landing.css', 'resources/js/app.js'])

    <title>Blodora - Donor Darah</title>
</head>

<body>

    <a class="skip-link" href="#konten-utama">Lewati ke konten utama</a>

    <!-- =====================================================
         NAVBAR
    ====================================================== -->

    <nav class="navbar navbar-expand-lg blodora-navbar">

        <div class="container">

            <!-- LOGO -->
            <a class="navbar-brand d-flex align-items-center" href="#beranda" aria-label="Blodora, ke beranda">
                <img
                    src="{{ asset('images/Blodora2.png') }}"
                    alt="Blodora"
                    class="blodora-logo">
            </a>


            <!-- MOBILE BUTTON -->
            <button
                class="navbar-toggler"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#navbarBlodora"
                aria-controls="navbarBlodora"
                aria-expanded="false"
                aria-label="Buka atau tutup navigasi utama">

                <span class="navbar-toggler-icon"></span>

            </button>


            <!-- MENU -->
            <div
                class="collapse navbar-collapse"
                id="navbarBlodora">

                <ul class="navbar-nav mx-auto gap-lg-4">

                    <!-- BERANDA -->
                    <li class="nav-item">
                        <a
                            class="nav-link active"
                            aria-current="location"
                            href="#beranda">

                            Beranda

                        </a>
                    </li>


                    <!-- DONOR -->
                    <li class="nav-item">
                        <a
                            class="nav-link"
                            href="#donor">

                            Donor

                        </a>
                    </li>


                    <!-- STOK DARAH -->
                    <li class="nav-item">
                        <a
                            class="nav-link"
                            href="#stok-darah">

                            Stok Darah

                        </a>
                    </li>


                    <!-- TENTANG -->
                    <li class="nav-item">
                        <a
                            class="nav-link"
                            href="#tentang">

                            Tentang

                        </a>
                    </li>

                </ul>


                <!-- LOGIN -->
                <a
                    href="{{ route('login') }}"
                    class="btn btn-login">

                    Masuk

                </a>

                <a href="{{ route('register') }}" class="btn btn-register">
                    Daftar
                </a>

            </div>

        </div>

    </nav>



    <!-- =====================================================
         HERO / BERANDA
    ====================================================== -->

    <main id="konten-utama">
    <section
        id="beranda"
        class="hero"
        style="background-image: url('{{ asset('images/blodora-hero.png') }}');">

        <div class="container">

            <div class="hero-content" data-hero-motion>

                <!-- SUBTITLE -->
                <span class="hero-subtitle">

                    BERSAMA KITA BISA

                </span>


                <!-- TITLE -->
                <h1 class="hero-title">

                    Setetes Darah
                    <br>

                    <span>Menyelamatkan</span>

                    <br>

                    Nyawa

                </h1>


                <!-- DESCRIPTION -->
                <p class="hero-description">

                    Donorkan darahmu dan bantu seseorang
                    mendapatkan kesempatan hidup.

                </p>


                <!-- BUTTON -->
                <a
                    href="{{ route('register') }}"
                    class="btn-donor">

                    Daftar sebagai Pendonor

                    <span aria-hidden="true">&rarr;</span>

                </a>

            </div>

        </div>

    </section>



    <!-- =====================================================
         DONOR
    ====================================================== -->

    <section
        id="donor"
        class="donor-section">

        <div class="container">

            <div class="section-content" data-reveal>

                <span class="section-label">ALUR DONOR</span>

                <h2>
                    Satu Donor,
                    <span>Banyak Harapan.</span>
                </h2>

                <p>
                    Donor darah merupakan tindakan sederhana
                    yang dapat membantu orang lain yang membutuhkan.
                    Bersama Blodora, mari berikan kesempatan hidup
                    melalui setetes darah.
                </p>


                <div class="donor-cards">

                    <!-- CARD 1 -->
                    <div class="info-card" data-reveal>

                        <div class="card-icon">
                            <i class="bi bi-person-plus-fill" aria-hidden="true"></i>
                        </div>

                        <h3>
                            Daftar Pendonor
                        </h3>

                        <p>
                            Buat akun pendonor dan lengkapi profil agar petugas dapat memproses pendaftaranmu.
                        </p>

                    </div>


                    <!-- CARD 2 -->
                    <div class="info-card" data-reveal>

                        <div class="card-icon">
                            <i class="bi bi-calendar-check" aria-hidden="true"></i>
                        </div>

                        <h3>Pilih Jadwal</h3>

                        <p>
                            Pilih jadwal donor yang tersedia, lalu datang ke lokasi sesuai waktu kegiatan.
                        </p>

                    </div>


                    <!-- CARD 3 -->
                    <div class="info-card" data-reveal>

                        <div class="card-icon">
                            <i class="bi bi-clipboard2-check" aria-hidden="true"></i>
                        </div>

                        <h3>Screening dan Donor</h3>

                        <p>
                            Petugas memeriksa kelayakan. Jika layak, donor dicatat dan stok bertambah setelah kegiatan selesai.
                        </p>

                    </div>

                </div>


                <a
                    href="{{ route('register') }}"
                    class="btn-donor section-button">

                    Buat Akun Pendonor

                    <span aria-hidden="true">&rarr;</span>

                </a>

            </div>

        </div>

    </section>



    <!-- =====================================================
         STOK DARAH
    ====================================================== -->

    <section
        id="stok-darah"
        class="stock-section">

        <div class="container">
            <div class="section-heading" data-reveal>
                <span class="section-label">INFORMASI</span>
                <h2>Stok <span>Darah</span></h2>
                <p>Jumlah kantong yang belum kedaluwarsa, dikelompokkan menurut golongan darah, rhesus, dan lokasi penyimpanan.</p>
            </div>

            <div class="stock-grid">
                @foreach (['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $jenisDarah)
                    @php
                        $stokLokasi = $stokDarahPerGolongan->get($jenisDarah, collect());
                        $jumlahKantong = $stokLokasi->sum('jumlah_kantong');
                    @endphp

                    <article class="blood-card" data-reveal>
                        <div class="blood-type" aria-hidden="true">{{ $jenisDarah }}</div>
                        <h3>Golongan {{ $jenisDarah }}</h3>
                        <p>{{ number_format($jumlahKantong) }} kantong</p>
                        <div class="stock-status {{ $jumlahKantong > 0 ? 'is-available' : 'is-empty' }}">
                            <span class="stock-status-dot" aria-hidden="true"></span>
                            {{ $jumlahKantong > 0 ? 'Tersedia' : 'Stok kosong' }}
                        </div>

                        <ul class="stock-location-list">
                            @forelse ($stokLokasi as $stok)
                                <li>
                                    <span>{{ $stok->lokasi ?: 'Lokasi belum dicatat' }}</span>
                                    <strong>{{ number_format($stok->jumlah_kantong) }} kantong</strong>
                                </li>
                            @empty
                                <li><span>Belum ada stok aktif</span></li>
                            @endforelse
                        </ul>
                    </article>
                @endforeach
            </div>

            <p class="stock-updated-at" data-reveal>
                @if ($stokTerakhirDiperbarui)
                    Pembaruan terakhir:
                    {{ \Illuminate\Support\Carbon::parse($stokTerakhirDiperbarui)->translatedFormat('d F Y, H:i') }}
                    · Waktu mengikuti zona {{ config('app.timezone') }}.
                @else
                    Belum ada stok aktif yang tercatat.
                @endif
            </p>
            <p class="stock-disclaimer">Informasi dapat berubah setelah pencatatan donor, penyaluran, atau kedaluwarsa. Konfirmasikan ketersediaan sebelum datang.</p>
        </div>
    </section>



    <!-- =====================================================
         TENTANG
    ====================================================== -->

    <section
        id="tentang"
        class="about-section">

        <div class="container">

            <div class="about-content">

                <div data-reveal>

                    <span class="section-label">
                        TENTANG BLODORA
                    </span>

                    <h2>
                        Bersama Kita
                        <span>Menyelamatkan Nyawa.</span>
                    </h2>

                </div>


                <div data-reveal>

                    <p>Blodora membantu calon pendonor mengikuti alur donor: buat akun, lengkapi profil, pilih jadwal, jalani screening petugas, lalu donor jika dinyatakan layak.</p>
                    <p>Stok bertambah setelah petugas mencatat donor selesai dan berkurang saat darah disalurkan. Informasi publik menampilkan stok yang masih aktif berdasarkan data sistem.</p>
                    <a href="{{ route('register') }}" class="btn-donor">Mulai pendaftaran <span aria-hidden="true">&rarr;</span></a>

                </div>

            </div>

        </div>

    </section>



    <!-- =====================================================
         FOOTER
    ====================================================== -->

    </main>

    <footer class="blodora-footer">

        <div class="container">

            <div class="footer-content">

                <img
                    src="{{ asset('images/Blodora2.png') }}"
                    alt="Blodora"
                    class="footer-logo">


                <p>
                    Setetes darah, sejuta harapan.
                </p>

            </div>


            <div class="footer-bottom">

                <span>
                    © {{ date('Y') }} Blodora.
                    Hak cipta dilindungi.
                </span>

            </div>

        </div>

    </footer>



</body>

</html>
