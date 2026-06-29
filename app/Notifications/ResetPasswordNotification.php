<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetPasswordNotification extends Notification
{
    use Queueable;

    public function __construct(public string $url) {}

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Reset Password - ' . config('app.name'))
            ->line('Anda menerima email ini karena kami menerima permintaan reset password untuk akun Anda.')
            ->action('Reset Password', $this->url)
            ->line('Tautan ini akan kedaluwarsa dalam ' . config('auth.passwords.users.expire', 60) . ' menit.')
            ->line('Jika Anda tidak meminta reset password, abaikan email ini.');
    }
}
