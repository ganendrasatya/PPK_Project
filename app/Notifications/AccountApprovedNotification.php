<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AccountApprovedNotification extends Notification
{
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->success()
            ->subject('Akun Anda Telah Disetujui')
            ->greeting("Halo, {$notifiable->name}!")
            ->line('Kabar baik! Akun Anda di ' . config('app.name') . ' telah **disetujui oleh admin**.')
            ->line('Sekarang Anda sudah dapat login dan mulai mereservasi fasilitas kampus.')
            ->action('Login Sekarang', route('login'));
    }
}
