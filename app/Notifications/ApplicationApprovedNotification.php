<?php

namespace App\Notifications;

use App\Models\ArtisanApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ApplicationApprovedNotification extends Notification
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

        return (new MailMessage)
            ->subject('🎉 Votre candidature a été approuvée — ƉƆKUN')
            ->greeting("Félicitations {$name} !")
            ->line("Votre candidature pour rejoindre la communauté des artisans ƉƆKUN a été approuvée par notre équipe.")
            ->line("Vous pouvez maintenant compléter votre profil et publier vos savoir-faire.")
            ->action('Accéder à mon espace artisan', route('artisan-space.index'))
            ->line('Si vous n\'avez pas encore défini votre mot de passe, cliquez sur "Mot de passe oublié" sur la page de connexion.');
    }
}
