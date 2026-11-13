<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    const STATUS = [
        'menunggu'   => 'Menunggu',
        'disewa'     => 'Disewa',
        'delay'      => 'Delay',
        'reschedule' => 'Reschedule',
        'selesai'    => 'Selesai',
        'batal'      => 'Batal',
    ];

    // Status yang masih memakai motor, dipakai untuk cek jadwal bentrok.
    const STATUS_AKTIF = ['menunggu', 'disewa', 'delay', 'reschedule'];

    protected $fillable = [
        'nama',
        'no_hp',
        'moge',
        'tanggal',
        'jam',
        'tanggal_selesai',
        'jam_selesai',
        'catatan',
        'status',
    ];

    // Kode booking bergaya plat nomor Surabaya, contoh: L 0012 MG
    public function getKodeAttribute(): string
    {
        return 'L ' . str_pad($this->id, 4, '0', STR_PAD_LEFT) . ' MG';
    }

    public function mulai(): Carbon
    {
        return Carbon::parse($this->tanggal . ' ' . $this->jam);
    }

    public function selesai(): ?Carbon
    {
        if (! $this->tanggal_selesai || ! $this->jam_selesai) {
            return null;
        }

        return Carbon::parse($this->tanggal_selesai . ' ' . $this->jam_selesai);
    }

    public static function updateExpiredStatus(): void
    {
        $now = Carbon::now();
        $today = $now->toDateString();
        $currentTime = $now->toTimeString();

        self::whereIn('status', ['menunggu', 'disewa'])
            ->whereNotNull('tanggal_selesai')
            ->whereNotNull('jam_selesai')
            ->where(function ($query) use ($today, $currentTime) {
                $query->where('tanggal_selesai', '<', $today)
                    ->orWhere(function ($q) use ($today, $currentTime) {
                        $q->where('tanggal_selesai', '=', $today)
                            ->where('jam_selesai', '<=', $currentTime);
                    });
            })
            ->update(['status' => 'selesai']);

        self::where('status', 'menunggu')
            ->where(function ($query) use ($today, $currentTime) {
                $query->where('tanggal', '<', $today)
                    ->orWhere(function ($q) use ($today, $currentTime) {
                        $q->where('tanggal', '=', $today)
                            ->where('jam', '<=', $currentTime);
                    });
            })
            ->where(function ($query) use ($today, $currentTime) {
                $query->whereNull('tanggal_selesai')
                    ->orWhere('tanggal_selesai', '>', $today)
                    ->orWhere(function ($q) use ($today, $currentTime) {
                        $q->where('tanggal_selesai', '=', $today)
                            ->where('jam_selesai', '>', $currentTime);
                    });
            })
            ->update(['status' => 'disewa']);
    }
}
