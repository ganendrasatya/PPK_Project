<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Validasi mengizinkan keperluan sampai 500 karakter, sedangkan kolom string hanya 255
// (di MySQL teks yang lebih panjang menyebabkan error "Data too long").
return new class extends Migration
{
    private const STATUSES = ['pending', 'approved', 'rejected', 'cancelled'];

    public function up(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->text('purpose')->change();
            // SQLite membangun ulang tabel saat change(); deklarasi ulang agar check constraint status tidak hilang
            $table->enum('status', self::STATUSES)->default('pending')->change();
        });
    }

    public function down(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->string('purpose')->change();
            $table->enum('status', self::STATUSES)->default('pending')->change();
        });
    }
};
