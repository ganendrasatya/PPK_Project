<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['user_id', 'facility_id', 'purpose', 'proposal_kegiatan_path', 'proposal_permohonan_path', 'start_time', 'end_time', 'status', 'cancel_reason'])]
class Reservation extends Model
{
    protected function casts(): array
    {
        return [
            'start_time' => 'datetime',
            'end_time' => 'datetime',
        ];
    }

    public const EXPIRED_REASON = 'Kedaluwarsa: tidak diproses petugas sebelum waktu mulai.';

    /**
     * Reservasi pending yang waktu mulainya sudah lewat tidak mungkin lagi disetujui;
     * tandai sebagai ditolak agar tidak menumpuk di antrean petugas.
     * Mengembalikan jumlah reservasi yang dikedaluwarsakan.
     */
    public static function expireStalePending(): int
    {
        return static::where('status', 'pending')
            ->where('start_time', '<=', now())
            ->update(['status' => 'rejected', 'cancel_reason' => self::EXPIRED_REASON, 'updated_at' => now()]);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function facility(): BelongsTo
    {
        return $this->belongsTo(Facility::class);
    }
}
