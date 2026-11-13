@extends('layouts.app')

@section('title', 'Edit booking ' . $booking->kode . ' - Sewa Moge Surabaya')

@section('content')
<main class="container halaman">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="d-flex flex-wrap align-items-center gap-3 mb-4">
                <h2 class="mb-0">Edit booking</h2>
                <span class="plat">{{ $booking->kode }}</span>
            </div>

            <form action="{{ route('bookings.update', $booking) }}" method="POST" class="panel p-4">
                @csrf
                @method('PUT')
                @include('bookings._form')

                <div class="d-flex justify-content-between pt-3 border-top">
                    <a href="{{ route('bookings.index') }}" class="btn btn-garis">Batal</a>
                    <button type="submit" class="btn btn-utama px-4">Simpan perubahan</button>
                </div>
            </form>
        </div>
    </div>
</main>
@endsection
