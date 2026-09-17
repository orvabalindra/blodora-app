<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <title>@yield('title', 'Blodora')</title>
</head>
<body class="auth-page">

    <div class="auth-background-effect auth-blood-left"></div>
    <div class="auth-background-effect auth-blood-right"></div>

    <main class="auth-wrapper">
        @yield('content')
    </main>

</body>
</html>