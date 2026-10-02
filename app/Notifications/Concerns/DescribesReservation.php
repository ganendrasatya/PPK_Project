<?php

namespace App\Notifications\Concerns;

use App\Models\Reservation;
use Illuminate\Notifications\Messages\MailMessage;

trait DescribesReservation
{
    protected function reservationCode(Reservation $reservation): string
    {
        return 'RSV-' . str_pad($reservation->id, 5, '0', STR_PAD_LEFT);
    }

    protected function withReservationDetails(MailMessage $mail, Reservation $reservation): MailMessage
    {
        $reservation->loadMissing('facility');

        return $mail
            ->line('**Kode Reservasi:** #' . $this->reservationCode($reservation))
            ->line('**Fasilitas:** ' . ($reservation->facility->nama_fasilitas ?? '-') . ' (' . ($reservation->facility->lokasi ?? '-') . ')')
            ->line('**Tanggal:** ' . $reservation->start_time->locale('id')->translatedFormat('l, d F Y'))
            ->line('**Waktu:** ' . $reservation->start_time->format('H:i') . ' – ' . $reservation->end_time->format('H:i') . ' WIB')
            ->line('**Keperluan:** ' . $reservation->purpose);
    }
}
