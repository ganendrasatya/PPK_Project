<?php

use App\Models\Reservation;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('reservations:expire', function () {
    $count = Reservation::expireStalePending();
    $this->info("{$count} reservasi pending yang sudah lewat ditandai kedaluwarsa.");
})->purpose('Tandai reservasi pending yang waktu mulainya sudah lewat sebagai kedaluwarsa');

// Berjalan jika scheduler aktif (php artisan schedule:work / cron); halaman terkait juga memanggilnya
Schedule::command('reservations:expire')->everyFifteenMinutes();
