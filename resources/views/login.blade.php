@extends('layouts.app')

@section('title', 'Login admin - Sewa Moge Surabaya')

@section('content')
<main class="container halaman">
    <div class="row justify-content-center">
        <div class="col-sm-9 col-md-6 col-lg-4">
            <h2 class="mb-1">Login admin</h2>
            <p class="text-secondary mb-4">Untuk mengelola daftar booking.</p>

            <form action="{{ route('login') }}" method="POST" class="panel p-4">
                @csrf

                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" name="username" id="username" class="form-control @error('username') is-invalid @enderror" value="{{ old('username') }}" autocomplete="username" required autofocus>
                    @error('username')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" autocomplete="current-password" required>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-utama w-100">Masuk</button>
            </form>
        </div>
    </div>
</main>
@endsection
