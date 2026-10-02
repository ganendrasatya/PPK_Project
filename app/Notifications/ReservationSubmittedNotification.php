<?php

namespace App\Notifications;

use App\Models\Reservation;
use App\Notifications\Concerns\DescribesReservation;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReservationSubmittedNotification extends Notification
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
            ->subject('Reservasi #' . $this->reservationCode($this->reservation) . ' Diterima – Menunggu Persetujuan')
            ->greeting("Halo, {$notifiable->name}!")
            ->line('Pengajuan reservasi Anda telah kami terima dan sedang **menunggu persetujuan petugas**.');

        return $this->withReservationDetails($mail, $this->reservation)
            ->line('Kami akan mengirim email lagi setelah reservasi Anda diproses.')
            ->action('Lihat Reservasi Saya', route('reservations.index'));
    }
}
