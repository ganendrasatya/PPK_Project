<?php

namespace App\Notifications;

use App\Models\Reservation;
use App\Notifications\Concerns\DescribesReservation;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReservationRejectedNotification extends Notification
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
            ->subject('Reservasi #' . $this->reservationCode($this->reservation) . ' Ditolak')
            ->greeting("Halo, {$notifiable->name}.")
            ->line('Mohon maaf, reservasi Anda **ditolak oleh petugas**.');

        return $this->withReservationDetails($mail, $this->reservation)
            ->line('**Alasan penolakan:** ' . ($this->reservation->cancel_reason ?: '-'))
            ->line('Anda dapat mengajukan reservasi baru pada waktu atau fasilitas lain.')
            ->action('Cari Fasilitas Lain', route('catalog.index'));
    }
}
