<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPasswordNotification extends Notification
{

    public string $token;

    public function __construct(string $token)
    {
        $this->token = $token;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return string[]
     */
    public function via(mixed $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(mixed $notifiable): MailMessage
	{
        return (new MailMessage)
            ->view('email')
            ->subject('Resetowanie hasła w portalu ' . config('app.name'))
            ->line('Otrzymujesz tę wiadomość, ponieważ otrzymaliśmy prośbę o zresetowanie hasła do twojego konta.')
            ->action('Zresetuj hasło', url('password/reset', $this->token))
            ->line('Jeśli nie poprosiłeś o zresetowanie hasła, zignoruj tą wiadomość.');
    }

}
