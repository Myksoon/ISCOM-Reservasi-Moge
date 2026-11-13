<?php

namespace App\Http\Requests;

use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class BookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'nama' => 'required|string|max:255',
            'no_hp' => ['required', 'string', 'max:20', 'regex:/^[0-9+\-\s]+$/'],
            'moge' => ['required', Rule::in(array_column(config('moge'), 'nama'))],
            'tanggal' => 'required|date',
            'jam' => 'required|date_format:H:i',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal',
            'jam_selesai' => 'required|date_format:H:i',
            'catatan' => 'nullable|string|max:1000',
        ];

        // status hanya bisa diubah admin dari halaman edit
        if ($this->isMethod('put')) {
            $rules['status'] = ['required', Rule::in(array_keys(Booking::STATUS))];
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'required' => ':attribute wajib diisi.',
            'max' => ':attribute maksimal :max karakter.',
            'date' => ':attribute bukan tanggal yang benar.',
            'date_format' => ':attribute harus berformat jam:menit, misalnya 09:00.',
            'moge.required' => 'Pilih motor yang mau disewa.',
            'moge.in' => 'Pilih motor dari daftar yang tersedia.',
            'no_hp.regex' => 'Nomor WhatsApp hanya boleh berisi angka, spasi, + atau -.',
            'tanggal_selesai.after_or_equal' => 'Tanggal kembali tidak boleh sebelum tanggal mulai.',
            'status.in' => 'Status tidak dikenal.',
        ];
    }

    public function attributes(): array
    {
        return [
            'nama' => 'Nama pemesan',
            'no_hp' => 'Nomor WhatsApp',
            'moge' => 'Motor',
            'tanggal' => 'Tanggal mulai',
            'jam' => 'Jam mulai',
            'tanggal_selesai' => 'Tanggal kembali',
            'jam_selesai' => 'Jam kembali',
            'catatan' => 'Catatan',
            'status' => 'Status',
        ];
    }

    // Dicek setelah aturan di atas lolos, karena butuh tanggal dan jam yang sudah valid.
    public function after(): array
    {
        return [
            function (Validator $validator) {
                if ($validator->errors()->isNotEmpty()) {
                    return;
                }

                $mulai = Carbon::parse($this->tanggal.' '.$this->jam);
                $selesai = Carbon::parse($this->tanggal_selesai.' '.$this->jam_selesai);

                if ($selesai->lte($mulai)) {
                    $validator->errors()->add('jam_selesai', 'Jam kembali harus setelah jam mulai sewa.');

                    return;
                }

                // booking yang selesai atau batal tidak memakai motor lagi
                if (! in_array($this->input('status', 'menunggu'), Booking::STATUS_AKTIF)) {
                    return;
                }

                $bentrok = Booking::jadwalBentrok($this->moge, $mulai, $selesai, $this->route('booking')?->id);

                if ($bentrok) {
                    $validator->errors()->add('moge', $this->moge.' sudah dipesan sampai '
                        .$bentrok->selesai()->format('d/m/Y H:i').'. Pilih jadwal atau motor lain.');
                }
            },
        ];
    }
}
