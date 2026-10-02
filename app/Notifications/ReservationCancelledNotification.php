<?php

namespace App\Notifications;

use App\Models\Reservation;
use App\Notifications\Concerns\DescribesReservation;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

// Dipakai saat petugas membatalkan reservasi dan saat reservasi dibatalkan otomatis
// karena fasilitas dinonaktifkan / masuk perbaikan.
class ReservationCancelledNotification extends Notification
{
    use DescribesReservation;

    public function __construct(public Reservation $reservation)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $mail = (new MailMessage)
            ->error()
            ->subject('Reservasi #' . $this->reservationCode($this->reservation) . ' Dibatalkan')
            ->greeting("Halo, {$notifiable->name}.")
            ->line('Mohon maaf, reservasi Anda berikut **telah dibatalkan** dan tidak dapat digunakan.');

        return $this->withReservationDetails($mail, $this->reservation)
            ->line('**Alasan pembatalan:** ' . ($this->reservation->cancel_reason ?: '-'))
            ->line('Silakan ajukan reservasi baru pada waktu atau fasilitas lain. Mohon maaf atas ketidaknyamanannya.')
            ->action('Cari Fasilitas Lain', route('catalog.index'));
    }
}
