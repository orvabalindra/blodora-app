<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Dashboard Admin - Blodora</title>

    <link rel="preconnect"
          href="https://fonts.googleapis.com">

    <link rel="preconnect"
          href="https://fonts.gstatic.com"
          crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">

    <link
        rel="stylesheet"
        href="{{ asset('css/dashboard.css') }}">

</head>


<body>

<div class="app">


    {{-- ======================================================
        SIDEBAR
    ======================================================= --}}

    <aside class="sidebar">

        <div class="sidebar-top">

            {{-- LOGO --}}

            <div class="brand">

                <div class="brand-mark">

                    <svg
                        viewBox="0 0 50 60"
                        fill="none"
                        xmlns="http://www.w3.org/2000/svg">

                        <path
                            d="M25 2
                               C25 2 6 24 6 38
                               C6 49 14.5 57 25 57
                               C35.5 57 44 49 44 38
                               C44 24 25 2 25 2Z"
                            fill="currentColor"/>

                        <path
                            d="M25 17
                               C25 17 15 29 15 37
                               C15 42.7 19.5 47 25 47
                               C30.5 47 35 42.7 35 37
                               C35 29 25 17 25 17Z"
                            fill="white"
                            fill-opacity=".18"/>

                    </svg>

                </div>


                <div class="brand-text">

                    <h2>Blodora</h2>

                    <span>
                        Setetes Darah, Sejuta Harapan
                    </span>

                </div>

            </div>


            {{-- MENU --}}

            <nav class="menu">

                <a
                    href="{{ route('dashboard') }}"
                    class="menu-link active">

                    <span class="menu-icon">

                        <svg viewBox="0 0 24 24">
                            <path d="M4 13h6V4H4v9Zm0 7h6v-4H4v4Zm10 0h6v-9h-6v9Zm0-16v4h6V4h-6Z"/>
                        </svg>

                    </span>

                    <span>Dashboard</span>

                </a>


                <a href="#" class="menu-link">

                    <span class="menu-icon">

                        <svg viewBox="0 0 24 24">
                            <path d="M16 11a4 4 0 1 0-8 0 4 4 0 0 0 8 0ZM4 20c0-3 3-5 6-5s6 2 6 5H4Zm13-9a3 3 0 1 0 0-6 3 3 0 0 0 0 6Zm0 2c2.5 0 4 1.5 4 4h-3c0-1.5-.4-2.7-1.2-4Z"/>
                        </svg>

                    </span>

                    <span>Pendonor</span>

                </a>


                <a href="#" class="menu-link">

                    <span class="menu-icon">

                        <svg viewBox="0 0 24 24">
                            <path d="M7 3v4M17 3v4M4 9h16M5 5h14a1 1 0 0 1 1 1v13a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1Zm3 8h3m-3 4h3m3-4h3m-3 4h3"/>
                        </svg>

                    </span>

                    <span>Jadwal Donor</span>

                </a>


                <a href="#" class="menu-link">

                    <span class="menu-icon">

                        <svg viewBox="0 0 24 24">
                            <path d="m9 12 2 2 4-5M12 3l7 3v5c0 4.8-2.8 8.5-7 10-4.2-1.5-7-5.2-7-10V6l7-3Z"/>
                        </svg>

                    </span>

                    <span>Screening</span>

                    <span class="menu-badge">
                        {{ $dashboard['menunggu_screening'] }}
                    </span>

                </a>


                <a href="#" class="menu-link">

                    <span class="menu-icon">

                        <svg viewBox="0 0 24 24">
                            <path d="M12 3C9.5 7 6 10.5 6 14a6 6 0 0 0 12 0c0-3.5-3.5-7-6-11Z"/>
                        </svg>

                    </span>

                    <span>Stok Darah</span>

                </a>


                <a href="#" class="menu-link">

                    <span class="menu-icon">

                        <svg viewBox="0 0 24 24">
                            <path d="M4 7h16M4 12h16M4 17h10M7 4v16"/>
                        </svg>

                    </span>

                    <span>Permintaan Darah</span>

                </a>


                <a href="#" class="menu-link">

                    <span class="menu-icon">

                        <svg viewBox="0 0 24 24">
                            <path d="M6 3h12v18l-3-2-3 2-3-2-3 2V3Zm3 5h6M9 12h6M9 16h4"/>
                        </svg>

                    </span>

                    <span>Riwayat Donor</span>

                </a>


                <a href="#" class="menu-link">

                    <span class="menu-icon">

                        <svg viewBox="0 0 24 24">
                            <path d="M4 20V10m5 10V4m6 16v-7m5 7V7"/>
                        </svg>

                    </span>

                    <span>Laporan</span>

                </a>


                <div class="menu-separator"></div>


                <a href="#" class="menu-link">

                    <span class="menu-icon">

                        <svg viewBox="0 0 24 24">
                            <path d="M12 15.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7Zm0-12v2M12 18.5v2M4.93 4.93l1.42 1.42m11.3 11.3 1.42 1.42M3 12h2m14 0h2M4.93 19.07l1.42-1.42m11.3-11.3 1.42-1.42"/>
                        </svg>

                    </span>

                    <span>Pengaturan</span>

                </a>

            </nav>

        </div>


        {{-- LOGOUT --}}

        <div class="sidebar-bottom">

            <form
                action="{{ route('logout') }}"
                method="POST">

                @csrf

                <button type="submit"
                        class="logout-btn">

                    <svg viewBox="0 0 24 24">
                        <path d="M10 17l5-5-5-5M15 12H3m8-7h8a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2h-8"/>
                    </svg>

                    <span>Logout</span>

                </button>

            </form>

        </div>

    </aside>



    {{-- ======================================================
        MAIN
    ======================================================= --}}

    <main class="main-content">


        {{-- BACKGROUND DECORATION --}}

        <div class="decor decor-1"></div>

        <div class="decor decor-2"></div>

        <div class="decor-drop drop-one"></div>

        <div class="decor-drop drop-two"></div>

        <div class="decor-drop drop-three"></div>



        {{-- ==================================================
            TOP HEADER
        =================================================== --}}

        <header class="top-header">

            <div class="header-left">

                <h1>
                    Halo, Admin
                    <span class="wave">👋</span>
                </h1>

                <p>
                    Selamat datang kembali di sistem manajemen donor darah Blodora.
                </p>

                <div class="today">

                    <svg viewBox="0 0 24 24">
                        <path d="M7 3v4M17 3v4M4 9h16M5 5h14a1 1 0 0 1 1 1v13a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1Z"/>
                    </svg>

                    <span>
                        {{ now()->translatedFormat('l, d F Y') }}
                    </span>

                </div>

            </div>


            <div class="header-right">

                {{-- NOTIFICATION --}}

                <button class="icon-button">

                    <svg viewBox="0 0 24 24">
                        <path d="M18 8a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9M10 21h4"/>
                    </svg>

                    <span class="notification-dot"></span>

                </button>


                {{-- PROFILE --}}

                <div class="profile">

                    <div class="avatar">

                        A

                    </div>

                    <div class="profile-text">

                        <strong>Admin</strong>

                        <span>Administrator</span>

                    </div>

                    <svg class="profile-chevron"
                         viewBox="0 0 24 24">

                        <path d="m6 9 6 6 6-6"/>

                    </svg>

                </div>

            </div>

        </header>



        {{-- ==================================================
            STATISTIC CARDS
        =================================================== --}}

        <section class="stats-grid">


            {{-- TOTAL PENDONOR --}}

            <article class="stat-card">

                <div class="stat-icon red">

                    <svg viewBox="0 0 24 24">

                        <path d="M12 3C9 7.2 6 10.3 6 14a6 6 0 0 0 12 0c0-3.7-3-6.8-6-11Z"/>

                    </svg>

                </div>


                <div class="stat-info">

                    <span>Total Pendonor</span>

                    <strong>
                        {{ number_format($dashboard['total_pendonor'], 0, ',', '.') }}
                    </strong>

                    <small class="positive">
                        <b>↑ 5.2%</b> dari bulan lalu
                    </small>

                </div>

            </article>



            {{-- JADWAL --}}

            <article class="stat-card">

                <div class="stat-icon pink">

                    <svg viewBox="0 0 24 24">

                        <path d="M7 3v4M17 3v4M4 9h16M5 5h14a1 1 0 0 1 1 1v13a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1Zm4 8h3m-3 4h3"/>

                    </svg>

                </div>


                <div class="stat-info">

                    <span>Jadwal Aktif</span>

                    <strong>
                        {{ $dashboard['jadwal_aktif'] }}
                    </strong>

                    <small class="positive">
                        <b>↑ 12%</b> bulan ini
                    </small>

                </div>

            </article>



            {{-- SCREENING --}}

            <article class="stat-card">

                <div class="stat-icon orange">

                    <svg viewBox="0 0 24 24">

                        <path d="M12 3v4m0 10v4M3 12h4m10 0h4M5.6 5.6l2.8 2.8m7.2 7.2 2.8 2.8m0-12.8-2.8 2.8m-7.2 7.2-2.8 2.8"/>

                        <circle cx="12"
                                cy="12"
                                r="5"/>

                    </svg>

                </div>


                <div class="stat-info">

                    <span>Menunggu Screening</span>

                    <strong>
                        {{ $dashboard['menunggu_screening'] }}
                    </strong>

                    <small class="warning-text">
                        Perlu segera diperiksa
                    </small>

                </div>

            </article>



            {{-- STOK --}}

            <article class="stat-card">

                <div class="stat-icon purple">

                    <svg viewBox="0 0 24 24">

                        <path d="M12 3C9 7.2 6 10.3 6 14a6 6 0 0 0 12 0c0-3.7-3-6.8-6-11Z"/>

                    </svg>

                </div>


                <div class="stat-info">

                    <span>Total Stok Darah</span>

                    <strong>
                        {{ number_format($dashboard['total_stok'], 0, ',', '.') }}
                    </strong>

                    <small class="positive">
                        <b>↑ 8.4%</b> dari bulan lalu
                    </small>

                </div>

            </article>

        </section>



        {{-- ==================================================
            CHART + STOCK
        =================================================== --}}

        <section class="middle-grid">


            {{-- TREND CHART --}}

            <article class="panel chart-panel">

                <div class="panel-header">

                    <div>

                        <h2>Tren Stok Darah</h2>

                        <p>Perubahan stok dalam 7 hari terakhir</p>

                    </div>


                    <select id="chartFilter">

                        <option>Semua Golongan</option>

                        <option>A</option>

                        <option>B</option>

                        <option>AB</option>

                        <option>O</option>

                    </select>

                </div>


                <div class="chart-wrapper">

                    <canvas id="stockChart"></canvas>

                </div>

            </article>



            {{-- STOCK --}}

            <article class="panel stock-panel">

                <div class="panel-header">

                    <div>

                        <h2>Stok Darah</h2>

                        <p>Ketersediaan per golongan & rhesus</p>

                    </div>

                    <a href="#">Lihat semua</a>

                </div>


                <div class="stock-list">

                    @php

                        $stockTotal = max($dashboard['total_stok'], 1);

                        $stockItems = [
                            'A+' => $dashboard['stok']['A+'],
                            'A-' => $dashboard['stok']['A-'],
                            'B+' => $dashboard['stok']['B+'],
                            'B-' => $dashboard['stok']['B-'],
                            'AB+' => $dashboard['stok']['AB+'],
                            'AB-' => $dashboard['stok']['AB-'],
                            'O+' => $dashboard['stok']['O+'],
                            'O-' => $dashboard['stok']['O-'],
                        ];

                    @endphp


                    @foreach($stockItems as $type => $amount)

                        <div class="stock-row">

                            <div class="blood-label">

                                {{ $type }}

                            </div>


                            <div class="stock-progress">

                                <span
                                    style="
                                        width:
                                        {{ min(($amount / $stockTotal) * 100, 100) }}%;
                                    ">
                                </span>

                            </div>


                            <strong>

                                {{ $amount }}

                            </strong>

                        </div>

                    @endforeach

                </div>


                <div class="stock-total">

                    <div class="stock-total-icon">

                        <svg viewBox="0 0 24 24">

                            <path d="M12 3C9 7.2 6 10.3 6 14a6 6 0 0 0 12 0c0-3.7-3-6.8-6-11Z"/>

                        </svg>

                    </div>


                    <div>

                        <span>Total Stok</span>

                        <strong>
                            {{ number_format($dashboard['total_stok'], 0, ',', '.') }}
                            <small>kantong</small>
                        </strong>

                    </div>

                </div>

            </article>

        </section>



        {{-- ==================================================
            BOTTOM
        =================================================== --}}

        <section class="bottom-grid">


            {{-- AKTIVITAS --}}

            <article class="panel">

                <div class="panel-header">

                    <div>

                        <h2>Aktivitas Terbaru</h2>

                        <p>Aktivitas terbaru sistem</p>

                    </div>

                    <a href="#">Lihat semua</a>

                </div>


                <div class="activity-list">


                    <div class="activity-item">

                        <div class="activity-icon red">

                            <svg viewBox="0 0 24 24">
                                <path d="M12 3C9 7.2 6 10.3 6 14a6 6 0 0 0 12 0c0-3.7-3-6.8-6-11Z"/>
                            </svg>

                        </div>


                        <div class="activity-content">

                            <strong>
                                Donor baru terdaftar
                            </strong>

                            <span>
                                Andi Pratama melakukan pendaftaran donor
                            </span>

                        </div>


                        <time>
                            10 menit
                        </time>

                    </div>



                    <div class="activity-item">

                        <div class="activity-icon green">

                            <svg viewBox="0 0 24 24">
                                <path d="m5 12 4 4L19 6"/>
                            </svg>

                        </div>


                        <div class="activity-content">

                            <strong>
                                Screening selesai
                            </strong>

                            <span>
                                Siti Rahma lolos screening medis
                            </span>

                        </div>


                        <time>
                            24 menit
                        </time>

                    </div>



                    <div class="activity-item">

                        <div class="activity-icon blue">

                            <svg viewBox="0 0 24 24">
                                <path d="M7 3v4M17 3v4M4 9h16M5 5h14a1 1 0 0 1 1 1v13a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1Z"/>
                            </svg>

                        </div>


                        <div class="activity-content">

                            <strong>
                                Jadwal donor dibuat
                            </strong>

                            <span>
                                Donor Darah PMI Kota Malang
                            </span>

                        </div>


                        <time>
                            1 jam
                        </time>

                    </div>



                    <div class="activity-item">

                        <div class="activity-icon purple">

                            <svg viewBox="0 0 24 24">
                                <path d="M12 3C9 7.2 6 10.3 6 14a6 6 0 0 0 12 0c0-3.7-3-6.8-6-11Z"/>
                            </svg>

                        </div>


                        <div class="activity-content">

                            <strong>
                                Stok darah bertambah
                            </strong>

                            <span>
                                1 kantong O+ ditambahkan otomatis
                            </span>

                        </div>


                        <time>
                            2 jam
                        </time>

                    </div>

                </div>

            </article>



            {{-- PERMINTAAN DARAH --}}

            <article class="panel request-panel">

                <div class="panel-header">

                    <div>

                        <h2>Permintaan Darah</h2>

                        <p>Bulan September 2026</p>

                    </div>

                    <a href="#">Lihat semua</a>

                </div>


                <div class="request-content">

                    <div class="donut">

                        <div class="donut-center">

                            <strong>32</strong>

                            <span>Total</span>

                        </div>

                    </div>


                    <div class="request-legend">

                        <div>

                            <span class="legend-dot a"></span>

                            <span>A</span>

                            <strong>8</strong>

                        </div>


                        <div>

                            <span class="legend-dot b"></span>

                            <span>B</span>

                            <strong>7</strong>

                        </div>


                        <div>

                            <span class="legend-dot ab"></span>

                            <span>AB</span>

                            <strong>5</strong>

                        </div>


                        <div>

                            <span class="legend-dot o"></span>

                            <span>O</span>

                            <strong>12</strong>

                        </div>

                    </div>

                </div>

            </article>



            {{-- DISTRIBUSI --}}

            <article class="panel distribution-panel">

                <div class="panel-header">

                    <div>

                        <h2>Distribusi Donor</h2>

                        <p>Berdasarkan lokasi kegiatan</p>

                    </div>

                    <select>

                        <option>Bulan ini</option>

                        <option>Minggu ini</option>

                    </select>

                </div>


                <div class="location-list">


                    <div class="location-row">

                        <div class="location-info">

                            <span>
                                <i></i>
                                Malang
                            </span>

                            <strong>34%</strong>

                        </div>

                        <div class="location-progress">
                            <span style="width: 82%"></span>
                        </div>

                    </div>


                    <div class="location-row">

                        <div class="location-info">

                            <span>
                                <i></i>
                                Surabaya
                            </span>

                            <strong>25%</strong>

                        </div>

                        <div class="location-progress">
                            <span style="width: 66%"></span>
                        </div>

                    </div>


                    <div class="location-row">

                        <div class="location-info">

                            <span>
                                <i></i>
                                Batu
                            </span>

                            <strong>18%</strong>

                        </div>

                        <div class="location-progress">
                            <span style="width: 49%"></span>
                        </div>

                    </div>


                    <div class="location-row">

                        <div class="location-info">

                            <span>
                                <i></i>
                                Blitar
                            </span>

                            <strong>13%</strong>

                        </div>

                        <div class="location-progress">
                            <span style="width: 37%"></span>
                        </div>

                    </div>

                </div>

            </article>

        </section>


    </main>

</div>



{{-- ========================================================
    CHART JS
========================================================= --}}

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


<script>

const labels = @json($dashboard['chart_labels']);

const chartData = @json($dashboard['chart_data']);


const canvas =
    document.getElementById('stockChart');


const context =
    canvas.getContext('2d');


const gradient =
    context.createLinearGradient(
        0,
        0,
        0,
        260
    );


gradient.addColorStop(
    0,
    'rgba(211, 36, 51, 0.20)'
);


gradient.addColorStop(
    1,
    'rgba(211, 36, 51, 0.01)'
);


new Chart(context, {

    type: 'line',

    data: {

        labels: labels,

        datasets: [

            {

                label: 'Jumlah stok',

                data: chartData,

                borderColor: '#d62436',

                backgroundColor: gradient,

                fill: true,

                tension: 0.42,

                borderWidth: 3,

                pointRadius: 4,

                pointHoverRadius: 6,

                pointBackgroundColor: '#ffffff',

                pointBorderColor: '#d62436',

                pointBorderWidth: 2

            }

        ]

    },


    options: {

        responsive: true,

        maintainAspectRatio: false,

        interaction: {
            intersect: false,
            mode: 'index'
        },

        plugins: {

            legend: {
                display: false
            },

            tooltip: {

                backgroundColor: '#1f2937',

                padding: 12,

                cornerRadius: 9,

                displayColors: false,

                callbacks: {

                    label: function(context) {

                        return context.parsed.y +
                               ' kantong';

                    }

                }

            }

        },


        scales: {

            y: {

                beginAtZero: false,

                min: 500,

                grid: {

                    color: '#edf0f5',

                    drawBorder: false

                },

                border: {
                    display: false
                },

                ticks: {

                    color: '#8a93a3',

                    font: {
                        size: 11
                    }

                }

            },


            x: {

                grid: {
                    display: false
                },

                border: {
                    display: false
                },

                ticks: {

                    color: '#8a93a3',

                    font: {
                        size: 11
                    }

                }

            }

        }

    }

});


/*
|--------------------------------------------------------------------------
| FILTER CHART
|--------------------------------------------------------------------------
*/

document
    .getElementById('chartFilter')
    .addEventListener('change', function () {

        console.log(
            'Filter:',
            this.value
        );

    });

</script>

</body>

</html>