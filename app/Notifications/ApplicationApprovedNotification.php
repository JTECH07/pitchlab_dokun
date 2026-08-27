<?php

namespace App\Notifications;

use App\Models\ArtisanApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ApplicationApprovedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public ArtisanApplication $application, public string $tempPassword)
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

        return (new MailMessage)
            ->subject('Votre candidature a été approuvée — ƉƆKUN')
            ->greeting("Félicitations {$name} !")
            ->line("Votre candidature pour rejoindre la communauté des artisans ƉƆKUN a été approuvée par notre équipe.")
            ->line("Votre mot de passe temporaire : **{$this->tempPassword}**")
            ->line("Connectez-vous et changez-le dès que possible.")
            ->action('Accéder à mon espace artisan', route('artisan-space.index'))
            ->line("Conservez ce mot de passe et changez-le dès votre première connexion.");
    }
}
