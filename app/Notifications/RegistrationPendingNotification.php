<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RegistrationPendingNotification extends Notification
{
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Pendaftaran Akun Diterima – Menunggu Persetujuan Admin')
            ->greeting("Halo, {$notifiable->name}!")
            ->line('Terima kasih telah mendaftar di ' . config('app.name') . '.')
            ->line('Akun Anda dengan email **' . $notifiable->email . '** sedang **menunggu persetujuan admin**.')
            ->line('Anda belum dapat login sampai akun disetujui. Kami akan mengirim email lagi setelah akun Anda diverifikasi.');
    }
}
