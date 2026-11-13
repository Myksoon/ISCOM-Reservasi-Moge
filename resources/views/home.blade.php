@extends('layouts.app')

@section('kepala', 'kepala-transparan')

@php
    $armada = collect(config('moge'));
    $foto = ['Sport' => 'zx6r.jpg', 'Cruiser' => 'iron883.jpg', 'Superbike' => 'r1m.jpg'];
@endphp

@section('content')
<section class="hero">
    <img src="{{ asset('images/hero.jpg') }}" alt="Ducati Panigale V2 merah terparkir di landasan" class="hero-foto" width="1920" height="1444" fetchpriority="high">
    <div class="hero-isi">
        <h1>Moge siap jalan.</h1>
        <p>Sewa motor gede di Surabaya untuk touring akhir pekan, sunmori, atau mencoba dulu sebelum membeli.</p>
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('bookings.create') }}" class="btn btn-putih">Booking sekarang</a>
            <a href="#armada" class="btn btn-kaca">Lihat armada</a>
        </div>
    </div>
</section>

<section class="bagian">
    <div class="sempit">
        <div class="row g-4">
            @foreach($armada->groupBy('tipe') as $tipe => $motor)
                <div class="col-md-4" data-muncul style="--tunda: {{ $loop->index * 0.1 }}s">
                    <a href="#armada" class="kartu-foto">
                        <img src="{{ asset('images/' . ($foto[$tipe] ?? $motor->first()['gambar'])) }}" alt="" loading="lazy" width="900" height="600">
                        <div class="kartu-bawah">
                            <div>
                                <h3>{{ $tipe }}</h3>
                                <p>{{ $motor->count() }} unit</p>
                            </div>
                            <span class="panah" aria-hidden="true"><i class="bi bi-arrow-right"></i></span>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</section>

<section class="bagian bagian-gelap" id="armada">
    <div class="lebar">
        <h2 class="mb-2" data-muncul>Armada</h2>
        <p class="redup mb-5" data-muncul style="--tunda: 0.1s">Harga sewa per hari. Pilih motornya, lalu isi jadwal di form booking.</p>

        <div class="row g-4">
            @foreach($armada as $motor)
                <div class="{{ $loop->first ? 'col-12' : 'col-md-6' }}" data-muncul style="--tunda: {{ $loop->first ? 0 : ($loop->index - 1) % 2 * 0.12 }}s">
                    <a href="{{ route('bookings.create', ['moge' => $motor['nama']]) }}" class="kartu-foto kartu-motor">
                        <img src="{{ asset('images/' . $motor['gambar']) }}" alt="{{ $motor['nama'] }}" loading="lazy" width="900" height="600">
                        <div class="pil-baris">
                            <span class="pil">{{ $motor['tipe'] }}</span>
                            <span class="pil">{{ $motor['cc'] }} cc</span>
                        </div>
                        <div class="kartu-bawah">
                            <div>
                                <h3>{{ $motor['nama'] }}</h3>
                                <p>Rp {{ number_format($motor['harga'], 0, ',', '.') }} per hari</p>
                            </div>
                            <span class="panah" aria-hidden="true"><i class="bi bi-arrow-right"></i></span>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</section>

<section class="bagian">
    <div class="bagian-belah">
        <div data-muncul>
            <h2>Cari jadwal yang masih kosong.</h2>
            <p>Isi jadwal ambil dan jadwal kembali. Kalau motornya sudah dipesan orang lain di jam itu, form langsung memberi tahu sampai kapan motor itu dipakai.</p>
            <a href="{{ route('bookings.create') }}" class="btn btn-utama">Booking sekarang</a>
        </div>
        <img src="{{ asset('images/cbr1000rr.jpg') }}" alt="Honda CBR1000RR-R merah putih biru" loading="lazy" width="900" height="675" data-muncul style="--tunda: 0.15s">
    </div>
</section>

<section class="bagian pt-0">
    <div class="sempit">
        <h2 class="text-center mb-5" data-muncul>Cara sewa</h2>
        <ol class="langkah">
            <li data-muncul style="--tunda: 0.00s">
                <h3>Pilih motor dan tanggal</h3>
                <p>Isi form booking dengan jadwal ambil dan jadwal kembali.</p>
            </li>
            <li data-muncul style="--tunda: 0.12s">
                <h3>Tunggu konfirmasi</h3>
                <p>Admin menghubungi nomor WhatsApp yang kamu isi untuk memastikan jadwal dan pembayaran.</p>
            </li>
            <li data-muncul style="--tunda: 0.24s">
                <h3>Ambil motornya</h3>
                <p>Datang sesuai jadwal dengan membawa KTP dan SIM yang masih berlaku.</p>
            </li>
        </ol>
    </div>
</section>
@endsection

@section('kaki')
    <p class="mb-0">
        Foto dari Wikimedia Commons: Sound Media (CC0), Alek Kirstein (CC BY-SA 2.0),
        Terragio67, Dennis Bratland, Chanokchon (CC BY-SA 4.0).
    </p>
@endsection
