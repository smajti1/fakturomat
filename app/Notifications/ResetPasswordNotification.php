<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPasswordNotification extends Notification
{

    public $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->view('email')
            ->subject('Resetowanie hasła w portalu ' . config('app.name'))
            ->line('Otrzymujesz tę wiadomość, ponieważ otrzymaliśmy prośbę o zresetowanie hasła do twojego konta.')
            ->action('Zresetuj hasło', url('password/reset', $this->token))
            ->line('Jeśli nie poprosiłeś o zresetowanie hasła, zignoruj tą wiadomość.');
    }

}
