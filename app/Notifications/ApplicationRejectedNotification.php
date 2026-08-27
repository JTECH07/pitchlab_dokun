<?php

namespace App\Notifications;

use App\Models\ArtisanApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ApplicationRejectedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public ArtisanApplication $application)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $name = $this->application->professional_name
            ?? $this->application->first_name . ' ' . $this->application->last_name;

        $mail = (new MailMessage)
            ->subject('Votre candidature — ƉƆKUN')
            ->greeting("Bonjour {$name},")
            ->line("Nous avons examiné votre candidature pour rejoindre la communauté des artisans ƉƆKUN.")
            ->line("Malheureusement, nous ne pouvons pas la valider pour le moment.");

        if ($this->application->admin_notes) {
            $mail->line("Motif : " . $this->application->admin_notes);
        }

        return $mail
            ->line("Vous pouvez modifier votre profil et soumettre une nouvelle candidature à tout moment.")
            ->action('Modifier ma candidature', route('artisan.apply'))
            ->line("N\'hésitez pas à nous contacter pour plus d\'informations.");
    }
}
