<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function index(Request $request): View
    {
        Booking::updateExpiredStatus();

        $query = Booking::query();

        if ($request->filled('q')) {
            $cari = '%' . $request->q . '%';
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

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate($this->rules());

        if ($error = $this->cekJadwal($validated)) {
            return back()->withErrors($error)->withInput();
        }

        $mulai = Carbon::parse($validated['tanggal'] . ' ' . $validated['jam']);
        $selesai = Carbon::parse($validated['tanggal_selesai'] . ' ' . $validated['jam_selesai']);
        $now = Carbon::now();

        if ($now->gte($selesai)) {
            $validated['status'] = 'selesai';
        } elseif ($now->gte($mulai)) {
            $validated['status'] = 'disewa';
        } else {
            $validated['status'] = 'menunggu';
        }

        $booking = Booking::create($validated);

        return redirect()->route('bookings.index')
            ->with('success', 'Booking ' . $booking->kode . ' berhasil disimpan.');
    }

    public function edit(Booking $booking): View
    {
        Booking::updateExpiredStatus();
        $booking->refresh();

        return view('bookings.edit', compact('booking'));
    }

    public function update(Request $request, Booking $booking): RedirectResponse
    {
        $validated = $request->validate($this->rules() + [
            'status' => ['required', Rule::in(array_keys(Booking::STATUS))],
        ]);

        if ($error = $this->cekJadwal($validated, $booking)) {
            return back()->withErrors($error)->withInput();
        }

        $booking->update($validated);

        return redirect()->route('bookings.index')
            ->with('success', 'Booking ' . $booking->kode . ' berhasil diperbarui.');
    }

    public function destroy(Booking $booking): RedirectResponse
    {
        $kode = $booking->kode;
        $booking->delete();

        return redirect()->route('bookings.index')
            ->with('success', 'Booking ' . $kode . ' berhasil dihapus.');
    }

    private function rules(): array
    {
        return [
            'nama'            => 'required|string|max:255',
            'no_hp'           => ['required', 'string', 'max:20', 'regex:/^[0-9+\-\s]+$/'],
            'moge'            => ['required', Rule::in(array_column(config('moge'), 'nama'))],
            'tanggal'         => 'required|date',
            'jam'             => 'required|date_format:H:i',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal',
            'jam_selesai'     => 'required|date_format:H:i',
            'catatan'         => 'nullable|string|max:1000',
        ];
    }

    // Mengembalikan array error kalau jadwalnya tidak masuk akal atau motornya sudah dipesan.
    private function cekJadwal(array $data, ?Booking $booking = null): ?array
    {
        $mulai = Carbon::parse($data['tanggal'] . ' ' . $data['jam']);
        $selesai = Carbon::parse($data['tanggal_selesai'] . ' ' . $data['jam_selesai']);

        if ($selesai->lte($mulai)) {
            return ['jam_selesai' => 'Waktu pengembalian harus setelah waktu mulai sewa.'];
        }

        // Booking yang sudah selesai atau batal tidak memakai motor lagi.
        if (! in_array($data['status'] ?? 'menunggu', Booking::STATUS_AKTIF)) {
            return null;
        }

        $query = Booking::where('moge', $data['moge'])
            ->whereIn('status', Booking::STATUS_AKTIF)
            ->where('tanggal', '<=', $data['tanggal_selesai'])
            ->where('tanggal_selesai', '>=', $data['tanggal']);

        if ($booking) {
            $query->where('id', '!=', $booking->id);
        }

        foreach ($query->get() as $lain) {
            if ($mulai->lt($lain->selesai()) && $selesai->gt($lain->mulai())) {
                return ['moge' => $data['moge'] . ' sudah dipesan ' . $lain->nama . ' sampai '
                    . $lain->selesai()->format('d/m/Y H:i') . '. Pilih jadwal atau motor lain.'];
            }
        }

        return null;
    }
}
