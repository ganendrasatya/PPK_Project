<?php

namespace App\Models;

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

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    public function reports(): HasMany
    {
        return $this->hasMany(Report::class);
    }
}
