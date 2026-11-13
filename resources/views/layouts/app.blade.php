<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sewa Moge Surabaya')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Barlow:wght@400;500;600&family=Barlow+Condensed:wght@700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <script>
        // dipasang sebelum halaman digambar supaya tidak berkedip terang dulu
        const temaAwal = localStorage.getItem('tema')
            || (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
        document.documentElement.dataset.theme = temaAwal;
        document.documentElement.dataset.bsTheme = temaAwal;
    </script>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark nav-utama">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">Moge Surabaya</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMain" aria-controls="navMain" aria-expanded="false" aria-label="Buka menu">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navMain">
                <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-2">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}#armada">Armada</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('bookings.index', 'bookings.edit') ? 'active' : '' }}" href="{{ route('bookings.index') }}">Daftar booking</a>
                    </li>
                    <li class="nav-item mt-2 mt-lg-0">
                        <a class="btn btn-marka btn-sm px-3" href="{{ route('bookings.create') }}">Booking baru</a>
                    </li>
                    <li class="nav-item mt-2 mt-lg-0">
                        <button type="button" class="tombol-tema" id="tombol-tema" aria-label="Tema gelap" aria-pressed="false">
                            <i class="bi bi-moon" aria-hidden="true"></i>
                        </button>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    @yield('content')

    <footer class="kaki">
        <div class="container d-flex flex-wrap justify-content-between gap-2">
            <span>Moge Surabaya, rental motor gede wilayah Surabaya dan sekitarnya.</span>
            @yield('kaki')
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" defer></script>
    <script src="{{ asset('js/tema.js') }}" defer></script>
</body>
</html>
