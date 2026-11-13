@extends('layouts.app')

@section('title', 'Booking baru - Sewa Moge Surabaya')

@section('content')
<main class="container halaman">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <h2 class="mb-1">Booking baru</h2>
            <p class="text-secondary mb-4">Semua kolom wajib diisi kecuali catatan.</p>

            @if(session('success'))
                <div class="alert alert-success" role="status">{{ session('success') }}</div>
            @endif

            <form action="{{ route('bookings.store') }}" method="POST" class="panel p-4">
                @csrf
                @include('bookings._form')

                <div class="d-flex justify-content-between pt-3 border-top">
                    <a href="{{ auth()->check() ? route('bookings.index') : route('home') }}" class="btn btn-garis">Batal</a>
                    <button type="submit" class="btn btn-utama px-4">Simpan booking</button>
                </div>
            </form>
        </div>
    </div>
</main>
@endsection
