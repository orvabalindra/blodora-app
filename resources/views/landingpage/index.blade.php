<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <title>Blodora - Donor Darah</title>
</head>

<body>

    <!-- =====================================================
         NAVBAR
    ====================================================== -->

    <nav class="navbar navbar-expand-lg blodora-navbar">

        <div class="container">

            <!-- LOGO -->
            <a class="navbar-brand d-flex align-items-center" href="#beranda">
                <img
                    src="{{ asset('images/blodora2.png') }}"
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
                aria-label="Toggle navigation">

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

                    Login

                </a>

            </div>

        </div>

    </nav>



    <!-- =====================================================
         HERO / BERANDA
    ====================================================== -->

    <section
        id="beranda"
        class="hero"
        style="background-image: url('{{ asset('images/blodora-hero.png') }}');">

        <div class="container">

            <div class="hero-content">

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
                    href="#donor"
                    class="btn-donor">

                    Jadi Pendonor

                    <span>→</span>

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

            <div class="section-content">

                <span class="section-label">
                    DONOR DARAH
                </span>

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
                    <div class="info-card">

                        <div class="card-icon">
                            🩸
                        </div>

                        <h3>
                            Daftar Pendonor
                        </h3>

                        <p>
                            Daftarkan dirimu sebagai pendonor
                            dan bantu memenuhi kebutuhan darah.
                        </p>

                    </div>


                    <!-- CARD 2 -->
                    <div class="info-card">

                        <div class="card-icon">
                            ❤️
                        </div>

                        <h3>
                            Bantu Sesama
                        </h3>

                        <p>
                            Setiap donor dapat menjadi bagian
                            dari harapan bagi seseorang.
                        </p>

                    </div>


                    <!-- CARD 3 -->
                    <div class="info-card">

                        <div class="card-icon">
                            ✓
                        </div>

                        <h3>
                            Mudah & Terdata
                        </h3>

                        <p>
                            Data donor dikelola dengan sistem
                            yang terorganisir.
                        </p>

                    </div>

                </div>


                <a
                    href="{{ route('login') }}"
                    class="btn-donor section-button">

                    Mulai Donor

                    <span>→</span>

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
            <div class="section-heading">
                <span class="section-label">INFORMASI</span>
                <h2>Stok <span>Darah</span></h2>
                <p>Pantau ketersediaan stok darah yang tersedia di Blodora.</p>
            </div>

            <div class="stock-grid">
                @foreach ($stokDarahs as $golonganDarah => $jumlahKantong)
                    <div class="blood-card">
                        <div class="blood-type">{{ $golonganDarah }}</div>
                        <h3>Golongan {{ $golonganDarah }}</h3>
                        <p>{{ number_format($jumlahKantong) }} kantong</p>
                        <div class="stock-status {{ $jumlahKantong > 0 ? 'is-available' : 'is-empty' }}">
                            <span class="stock-status-dot" aria-hidden="true"></span>
                            {{ $jumlahKantong > 0 ? 'Tersedia' : 'Stok kosong' }}
                        </div>
                    </div>
                @endforeach
            </div>
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

                <div>

                    <span class="section-label">
                        TENTANG BLODORA
                    </span>

                    <h2>
                        Bersama Kita
                        <span>Menyelamatkan Nyawa.</span>
                    </h2>

                </div>


                <div>

                    <p>
                        Blodora adalah sistem informasi donor darah
                        yang membantu mengelola data pendonor,
                        stok darah, dan riwayat donor secara
                        terorganisir.
                    </p>

                    <p>
                        Kami percaya bahwa sebuah tindakan kecil
                        dapat memberikan dampak besar bagi kehidupan
                        orang lain.
                    </p>

                </div>

            </div>

        </div>

    </section>



    <!-- =====================================================
         FOOTER
    ====================================================== -->

    <footer class="blodora-footer">

        <div class="container">

            <div class="footer-content">

                <img
                    src="{{ asset('images/blodora2.png') }}"
                    alt="Blodora"
                    class="footer-logo">


                <p>
                    Setetes darah, sejuta harapan.
                </p>

            </div>


            <div class="footer-bottom">

                <span>
                    © {{ date('Y') }} Blodora.
                    All rights reserved.
                </span>

            </div>

        </div>

    </footer>



</body>

</html>