<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookingRequest;
use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function index(Request $request): View
    {
        Booking::updateExpiredStatus();

        $query = Booking::query();

        if ($request->filled('q')) {
            $cari = '%'.$request->q.'%';
            $query->where(function ($q) use ($cari) {
                $q->where('nama', 'like', $cari)
                    ->orWhere('no_hp', 'like', $cari)
                    ->orWhere('moge', 'like', $cari);
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $bookings = $query->latest()->paginate(10)->withQueryString();

        $jumlah = Booking::selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        return view('bookings.index', compact('bookings', 'jumlah'));
    }

    public function create(): View
    {
        return view('bookings.create');
    }

    public function store(BookingRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $mulai = Carbon::parse($data['tanggal'].' '.$data['jam']);
        $selesai = Carbon::parse($data['tanggal_selesai'].' '.$data['jam_selesai']);
        $now = Carbon::now();

        if ($now->gte($selesai)) {
            $data['status'] = 'selesai';
        } elseif ($now->gte($mulai)) {
            $data['status'] = 'disewa';
        } else {
            $data['status'] = 'menunggu';
        }

        $booking = Booking::create($data);
        $pesan = 'Booking '.$booking->kode.' berhasil disimpan.';

        // pengunjung tidak bisa membuka daftar booking, jadi tetap di halaman form
        if (! auth()->check()) {
            return redirect()->route('bookings.create')
                ->with('success', $pesan.' Admin akan menghubungi nomor WhatsApp kamu untuk konfirmasi.');
        }

        return redirect()->route('bookings.index')->with('success', $pesan);
    }

    public function edit(Booking $booking): View
    {
        Booking::updateExpiredStatus();
        $booking->refresh();

        return view('bookings.edit', compact('booking'));
    }

    public function update(BookingRequest $request, Booking $booking): RedirectResponse
    {
        $booking->update($request->validated());

        return redirect()->route('bookings.index')
            ->with('success', 'Booking '.$booking->kode.' berhasil diperbarui.');
    }

    public function destroy(Booking $booking): RedirectResponse
    {
        $kode = $booking->kode;
        $booking->delete();

        return redirect()->route('bookings.index')
            ->with('success', 'Booking '.$kode.' berhasil dihapus.');
    }
}
