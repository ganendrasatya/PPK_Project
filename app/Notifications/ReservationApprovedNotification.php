<?php

namespace App\Notifications;

use App\Models\Reservation;
use App\Notifications\Concerns\DescribesReservation;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReservationApprovedNotification extends Notification
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
            ->success()
            ->subject('Reservasi #' . $this->reservationCode($this->reservation) . ' Disetujui')
            ->greeting("Halo, {$notifiable->name}!")
            ->line('Reservasi Anda telah **disetujui oleh petugas**.');

        return $this->withReservationDetails($mail, $this->reservation)
            ->line('Unduh bukti persetujuan dan tunjukkan kepada petugas saat menggunakan fasilitas.')
            ->action('Unduh Bukti Persetujuan', route('reservations.proof', $this->reservation));
    }
}
