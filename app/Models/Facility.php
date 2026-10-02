<?php

namespace App\Models;

use App\Notifications\ReservationCancelledNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Facility extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_fasilitas',
        'tipe',
        'lokasi',
        'kapasitas',
        'deskripsi',
        'image_path',
        'jam_buka',
        'jam_tutup',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'jam_buka' => 'datetime:H:i',
            'jam_tutup' => 'datetime:H:i',
        ];
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'aktif' => 'Aktif',
            'dalam_perbaikan' => 'Dalam Perbaikan',
            default => 'Nonaktif',
        };
    }

    /**
     * Batalkan reservasi pending/disetujui yang belum selesai karena fasilitas
     * tidak bisa dipakai. Mengembalikan jumlah reservasi yang dibatalkan.
     */
    public function cancelUpcomingReservations(): int
    {
        if ($this->status === 'aktif') {
            return 0;
        }

        $reason = $this->status === 'dalam_perbaikan'
            ? 'Dibatalkan otomatis: fasilitas sedang dalam perbaikan.'
            : 'Dibatalkan otomatis: fasilitas sedang nonaktif.';

        $reservations = $this->reservations()
            ->with('user')
            ->whereIn('status', ['pending', 'approved'])
            ->where('end_time', '>', now())
            ->get();

        foreach ($reservations as $reservation) {
            $reservation->update(['status' => 'cancelled', 'cancel_reason' => $reason]);
            $reservation->user?->notifySafely(new ReservationCancelledNotification($reservation));
        }

        return $reservations->count();
    }

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    public function reports(): HasMany
    {
        return $this->hasMany(Report::class);
    }
}
