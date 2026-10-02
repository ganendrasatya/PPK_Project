<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AccountRejectedNotification extends Notification
{
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->error()
            ->subject('Pendaftaran Akun Ditolak')
            ->greeting("Halo, {$notifiable->name}.")
            ->line('Mohon maaf, pendaftaran akun Anda dengan email **' . $notifiable->email . '** di ' . config('app.name') . ' **ditolak oleh admin**.')
            ->line('Anda tidak dapat login menggunakan akun ini. Jika menurut Anda ini sebuah kekeliruan, silakan hubungi admin atau petugas kampus.');
    }
}
