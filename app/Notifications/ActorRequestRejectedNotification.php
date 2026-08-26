<?php

namespace App\Notifications;

use App\Models\ActorRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ActorRequestRejectedNotification extends Notification
{
    use Queueable;

    public function __construct(public ActorRequest $actorRequest)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Votre demande — ƉƆKUN')
            ->greeting("Bonjour {$this->actorRequest->name},")
            ->line("Nous avons examiné votre demande pour rejoindre ƉƆKUN.")
            ->line("Malheureusement, nous ne pouvons pas la valider pour le moment.");

        if ($this->actorRequest->admin_notes) {
            $mail->line("Motif : " . $this->actorRequest->admin_notes);
        }

        return $mail
            ->line("N'hésitez pas à nous contacter pour plus d'informations.");
    }
}
