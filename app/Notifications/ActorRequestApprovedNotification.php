<?php

namespace App\Notifications;

use App\Models\ActorRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ActorRequestApprovedNotification extends Notification
{
    use Queueable;

    public function __construct(public ActorRequest $actorRequest, public string $tempPassword)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $roleLabels = [
            'guide' => 'Guide touristique',
            'institution' => 'Institution culturelle',
            'researcher' => 'Chercheur',
            'partner' => 'Partenaire tourisme',
        ];

        return (new MailMessage)
            ->subject("Bienvenue sur ƉƆKUN — Votre compte {$roleLabels[$this->actorRequest->role]} est prêt")
            ->greeting("Bonjour {$this->actorRequest->name} !")
            ->line("Votre demande pour rejoindre ƉƆKUN en tant que {$roleLabels[$this->actorRequest->role]} a été acceptée.")
            ->line("Votre mot de passe temporaire : **{$this->tempPassword}**")
            ->line("Vous devez d'abord vérifier votre email, puis vous connecter et changer votre mot de passe.")
            ->action('Se connecter', route('login'))
            ->line("Conservez ce mot de passe et changez-le dès votre première connexion.");
    }
}
