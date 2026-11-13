@extends('layouts.app')

@section('title', 'Daftar booking - Sewa Moge Surabaya')

@section('content')
<main class="container py-4 py-lg-5">
    <div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-4">
        <div>
            <h2 class="mb-1">Daftar booking</h2>
            <p class="text-secondary mb-0">Status berubah sendiri saat jadwal sewa mulai dan selesai.</p>
        </div>
        <a href="{{ route('bookings.create') }}" class="btn btn-petrol">Tambah booking</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
        </div>
    @endif

    <div class="d-flex flex-wrap justify-content-between gap-3 mb-3">
        <nav class="filter-status" aria-label="Filter status">
            <a href="{{ route('bookings.index', ['q' => request('q')]) }}" class="{{ request('status') ? '' : 'aktif' }}">
                Semua <span>{{ $jumlah->sum() }}</span>
            </a>
            @foreach(\App\Models\Booking::STATUS as $kunci => $label)
                <a href="{{ route('bookings.index', ['status' => $kunci, 'q' => request('q')]) }}" class="{{ request('status') === $kunci ? 'aktif' : '' }}">
                    {{ $label }} <span>{{ $jumlah[$kunci] ?? 0 }}</span>
                </a>
            @endforeach
        </nav>

        <form action="{{ route('bookings.index') }}" method="GET" class="d-flex gap-2" role="search">
            @if(request('status'))
                <input type="hidden" name="status" value="{{ request('status') }}">
            @endif
            <input type="search" name="q" value="{{ request('q') }}" class="form-control kolom-cari" placeholder="Cari nama, no. HP, atau motor" aria-label="Cari booking">
            <button type="submit" class="btn btn-garis">Cari</button>
        </form>
    </div>

    <div class="panel">
        <div class="table-responsive">
            <table class="table tabel-booking">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Pemesan</th>
                        <th>Motor</th>
                        <th>Mulai sewa</th>
                        <th>Kembali</th>
                        <th>Status</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bookings as $booking)
                        <tr>
                            <td data-label="Kode"><span class="plat">{{ $booking->kode }}</span></td>
                            <td data-label="Pemesan">
                                <div class="fw-semibold">{{ $booking->nama }}</div>
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $booking->no_hp) }}" target="_blank" rel="noopener" class="small text-decoration-none">
                                    <i class="bi bi-whatsapp" aria-hidden="true"></i> {{ $booking->no_hp }}
                                </a>
                            </td>
                            <td data-label="Motor">
                                <div>{{ $booking->moge ?? '-' }}</div>
                                @if($booking->catatan)
                                    <small>{{ Str::limit($booking->catatan, 60) }}</small>
                                @endif
                            </td>
                            <td data-label="Mulai sewa">
                                <div>{{ $booking->mulai()->format('d/m/Y') }}</div>
                                <small>{{ $booking->mulai()->format('H:i') }} WIB</small>
                            </td>
                            <td data-label="Kembali">
                                @if($booking->selesai())
                                    <div>{{ $booking->selesai()->format('d/m/Y') }}</div>
                                    <small>{{ $booking->selesai()->format('H:i') }} WIB</small>
                                @else
                                    -
                                @endif
                            </td>
                            <td data-label="Status">
                                <span class="status status-{{ $booking->status }}">{{ \App\Models\Booking::STATUS[$booking->status] ?? $booking->status }}</span>
                            </td>
                            <td class="text-end text-nowrap aksi">
                                <a href="{{ route('bookings.edit', $booking) }}" class="btn btn-sm btn-garis" aria-label="Edit booking {{ $booking->kode }}">
                                    <i class="bi bi-pencil" aria-hidden="true"></i>
                                </a>
                                <form action="{{ route('bookings.destroy', $booking) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus booking {{ $booking->kode }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" aria-label="Hapus booking {{ $booking->kode }}">
                                        <i class="bi bi-trash" aria-hidden="true"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 kosong">
                                @if(request('q') || request('status'))
                                    Tidak ada booking yang cocok.
                                    <a href="{{ route('bookings.index') }}">Tampilkan semua</a>
                                @else
                                    Belum ada booking.
                                    <a href="{{ route('bookings.create') }}">Buat booking pertama</a>
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">
        {{ $bookings->links() }}
    </div>
</main>
@endsection
