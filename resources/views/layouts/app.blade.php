<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        @yield('title', 'Blodora')
    </title>

    @vite([
        'resources/css/app.css',
        'resources/css/admin.css',
        'resources/js/app.js'
    ])

</head>

<body>

    <a class="app-skip-link" href="#main-content">Lewati ke konten utama</a>

    <button class="mobile-menu-trigger" type="button" data-sidebar-open aria-controls="app-sidebar" aria-expanded="false">
        <i class="bi bi-list" aria-hidden="true"></i>
        <span>Menu</span>
    </button>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const mainContent = document.querySelector('main.admin-main, main.donor-main, main.dashboard-main');
            if (mainContent) {
                mainContent.id = 'main-content';
                mainContent.tabIndex = -1;
            }
        });
        try {
            if (window.matchMedia('(min-width: 768px)').matches && localStorage.getItem('blodora-sidebar-collapsed') === 'true') {
                document.body.classList.add('sidebar-collapsed');
            }
        } catch (error) {
            // Keep the default expanded sidebar if browser storage is unavailable.
        }
    </script>

    @yield('content')

</body>

</html>
