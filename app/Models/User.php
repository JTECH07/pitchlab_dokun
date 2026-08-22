<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'role'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements \Illuminate\Contracts\Auth\MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    public const ROLES = ['tourist', 'artisan', 'guide', 'institution', 'researcher', 'partner', 'admin'];

    /** Rôles autorisés à l'inscription publique */
    public const PUBLIC_ROLES = ['tourist', 'artisan'];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function artisan() { return $this->hasOne(Artisan::class); }
    public function reservations() { return $this->hasMany(ReservationRequest::class); }
    public function favorites() { return $this->hasMany(ArtisanFavorite::class); }
    public function badges()
    {
        return $this->belongsToMany(Badge::class, 'badge_user')->withPivot('earned_at');
    }
    public function loyaltyEvents() { return $this->hasMany(LoyaltyEvent::class); }
    public function loyaltySummary() { return $this->hasOne(LoyaltySummary::class); }

    public function isAdmin(): bool { return $this->role === 'admin'; }
    public function isArtisan(): bool { return $this->role === 'artisan'; }
    public function isTourist(): bool { return $this->role === 'tourist'; }

    /** Destination après connexion/inscription selon le rôle */
    public function homeRoute(): string
    {
        return match (true) {
            $this->isAdmin()   => route('dashboard'),
            $this->isArtisan() => route('artisan-space.index'),
            default            => route('visitor.profile'),
        };
    }
}
