<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OtpNotification extends Notification
{
    use Queueable;

    public function __construct(
        protected string $code,
        protected int $expiryMinutes,
        protected string $purpose = 'login'
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $subject = $this->purpose === 'register'
            ? 'Verifikasi Email - SIMANGAT BJM'
            : 'Kode Verifikasi Login - SIMANGAT BJM';

        $intro = $this->purpose === 'register'
            ? 'Terima kasih telah mendaftar. Gunakan kode berikut untuk memverifikasi email dan mengaktifkan akun SIMANGAT BJM Anda:'
            : 'Gunakan kode berikut untuk menyelesaikan proses login ke akun SIMANGAT BJM Anda:';

        return (new MailMessage)
            ->subject($subject)
            ->greeting('Halo, ' . $notifiable->name)
            ->line($intro)
            ->line('# ' . $this->code)
            ->line("Kode ini berlaku selama {$this->expiryMinutes} menit dan hanya bisa digunakan sekali.")
            ->line('Jika Anda tidak merasa melakukan permintaan ini, abaikan email ini.');
    }
}