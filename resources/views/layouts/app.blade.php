<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sewa Moge Surabaya')</title>
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Barlow:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <script>
        // penanda bahwa JavaScript jalan, supaya animasi muncul tidak menyembunyikan isi kalau JS mati
        document.documentElement.classList.add('js');
    </script>
</head>
<body>
    <header class="kepala @yield('kepala', 'kepala-solid')">
        <div class="kepala-isi">
            <div>
                <button type="button" class="tombol-menu" data-bs-toggle="offcanvas" data-bs-target="#menu" aria-controls="menu">
                    <i class="bi bi-list" aria-hidden="true"></i><span>Menu</span>
                </button>
            </div>
            <a class="merek" href="{{ route('home') }}">Moge Surabaya</a>
            <button type="button" class="tombol-akun" data-bs-toggle="offcanvas" data-bs-target="#akun" aria-controls="akun">
                <i class="bi bi-person-circle" aria-hidden="true"></i><span>Akun</span>
            </button>
        </div>
    </header>

    <div class="offcanvas offcanvas-start menu-samping" id="menu" tabindex="-1" aria-labelledby="menu-judul">
        <div class="offcanvas-header">
            <h2 class="offcanvas-title" id="menu-judul">Menu</h2>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Tutup menu"></button>
        </div>
        <div class="offcanvas-body">
            <ul>
                <li><a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Beranda</a></li>
                <li><a href="{{ route('home') }}#armada">Armada</a></li>
                <li><a href="{{ route('bookings.create') }}" class="{{ request()->routeIs('bookings.create') ? 'active' : '' }}">Booking baru</a></li>
            </ul>
        </div>
    </div>

    <div class="offcanvas offcanvas-end menu-samping" id="akun" tabindex="-1" aria-labelledby="akun-judul">
        <div class="offcanvas-header">
            <h2 class="offcanvas-title" id="akun-judul">Akun</h2>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Tutup panel akun"></button>
        </div>
        <div class="offcanvas-body">
            @auth
                <p class="mb-3">Masuk sebagai <b>{{ auth()->user()->email }}</b></p>
                <div class="akun-ringkas">
                    <strong>{{ \App\Models\Booking::where('status', 'menunggu')->count() }}</strong>
                    booking menunggu konfirmasi
                </div>
                <ul>
                    <li><a href="{{ route('bookings.index') }}" class="{{ request()->routeIs('bookings.index', 'bookings.edit') ? 'active' : '' }}">Daftar booking</a></li>
                    <li><a href="{{ route('bookings.index', ['status' => 'menunggu']) }}">Perlu dikonfirmasi</a></li>
                    <li>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="link-menu">Keluar</button>
                        </form>
                    </li>
                </ul>
            @else
                <p class="mb-4">Login untuk melihat dan mengelola booking. Pengunjung bisa membuat booking tanpa login.</p>
                <a href="{{ route('login') }}" class="btn btn-utama w-100">Login admin</a>
            @endauth
        </div>
    </div>

    @yield('content')

    <footer class="kaki">
        <div class="lebar">
            <div class="row g-4">
                <div class="col-sm-6 col-lg-3">
                    <h2>Halaman</h2>
                    <ul>
                        <li><a href="{{ route('home') }}">Beranda</a></li>
                        <li><a href="{{ route('home') }}#armada">Armada</a></li>
                        <li><a href="{{ route('bookings.create') }}">Booking baru</a></li>
                    </ul>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <h2>Admin</h2>
                    <ul>
                        @auth
                            <li><a href="{{ route('bookings.index') }}">Daftar booking</a></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit">Keluar</button>
                                </form>
                            </li>
                        @else
                            <li><a href="{{ route('login') }}">Login admin</a></li>
                        @endauth
                    </ul>
                </div>
                <div class="col-lg-6">
                    <h2>Tentang</h2>
                    <p class="mb-2">Rental motor gede wilayah Surabaya dan sekitarnya.</p>
                    @yield('kaki')
                </div>
            </div>
            <div class="kaki-merek merek">Moge Surabaya</div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" defer></script>
    <script src="{{ asset('js/app.js') }}" defer></script>
</body>
</html>
