@extends('layouts.app')

@section('content')
<header class="hero">
    <div class="container">
        <div class="row align-items-center g-4 g-lg-5">
            <div class="col-lg-6">
                <h1 class="mb-3">Moge siap jalan, tinggal pilih tanggal.</h1>
                <p class="mb-4">
                    Sewa motor gede di Surabaya untuk touring akhir pekan, sunmori,
                    atau sekadar mencoba dulu sebelum membeli. Ada {{ count(config('moge')) }} unit
                    yang bisa dipesan lewat halaman ini.
                </p>
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('bookings.create') }}" class="btn btn-marka btn-lg px-4">Booking sekarang</a>
                    <a href="#armada" class="btn btn-garis btn-lg px-4">Lihat armada</a>
                </div>
            </div>
            <div class="col-lg-6">
                <img src="{{ asset('images/iron883.jpg') }}" alt="Harley-Davidson Iron 883 warna hitam doff terparkir di pinggir jalan" class="hero-foto" width="900" height="563">
            </div>
        </div>
    </div>
</header>

<section class="bagian pt-0" id="armada">
    <div class="container">
        <h2 class="mb-1">Armada</h2>
        <p class="mb-4">Harga sewa per hari.</p>

        <div class="row g-4">
            @foreach(config('moge') as $motor)
                <div class="col-sm-6 col-lg-4">
                    <article class="motor">
                        <img src="{{ asset('images/' . $motor['gambar']) }}" alt="{{ $motor['nama'] }}" loading="lazy" width="900" height="600">
                        <div class="motor-isi">
                            <h3 class="mb-1">{{ $motor['nama'] }}</h3>
                            <p class="text-secondary mb-3">{{ $motor['tipe'] }}, {{ $motor['cc'] }} cc</p>
                            <div class="d-flex justify-content-between align-items-end mt-auto">
                                <div>
                                    <div class="motor-harga">Rp {{ number_format($motor['harga'], 0, ',', '.') }}</div>
                                    <small class="text-secondary">per hari</small>
                                </div>
                                <a href="{{ route('bookings.create', ['moge' => $motor['nama']]) }}" class="btn btn-petrol">Sewa motor ini</a>
                            </div>
                        </div>
                    </article>
                </div>
            @endforeach
        </div>
    </div>
</section>

<section class="bagian bagian-gelap">
    <div class="container">
        <h2 class="mb-4">Cara sewa</h2>
        <ol class="langkah row g-4">
            <li class="col-md-4">
                <h3>Pilih motor dan tanggal</h3>
                <p>Isi form booking dengan jadwal ambil dan jadwal kembali. Kalau motornya sudah dipesan orang lain di jam itu, form akan langsung memberi tahu.</p>
            </li>
            <li class="col-md-4">
                <h3>Tunggu konfirmasi</h3>
                <p>Admin menghubungi nomor WhatsApp yang kamu isi untuk memastikan jadwal dan pembayaran.</p>
            </li>
            <li class="col-md-4">
                <h3>Ambil motornya</h3>
                <p>Datang sesuai jadwal dengan membawa KTP dan SIM yang masih berlaku. Motor dikembalikan sesuai jam yang tertulis di booking.</p>
            </li>
        </ol>
    </div>
</section>
@endsection

@section('kaki')
    <span>
        Foto dari Wikimedia Commons: Sound Media (CC0), Alek Kirstein (CC BY-SA 2.0),
        Terragio67, Dennis Bratland, Chanokchon (CC BY-SA 4.0).
    </span>
@endsection
