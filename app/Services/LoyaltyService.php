<?php

namespace App\Services;

use App\Models\Badge;
use App\Models\LearnProgress;
use App\Models\LoyaltyEvent;
use App\Models\Review;
use App\Models\ReservationRequest;
use Illuminate\Support\Facades\Auth;

/**
 * Moteur de fidélisation ƉƆKUN.
 *
 * Points (code → valeur) :
 *   daily_visit        +5   (1×/jour, bonus streak)
 *   lesson_completed   +15
 *   perfect_quiz       +25  (score 100)
 *   reservation_made   +100
 *   review_published   +30
 *   favorite_added     +5   (max 10 favoris récompensés)
 *   bridge_chat        +5   (max 3/jour)
 *
 * Niveaux : 0 Découvreur · 200 Explorateur · 500 Voyageur · 1200 Ambassadeur · 2500 Gardien du Patrimoine
 */
class LoyaltyService
{
    public const POINTS = [
        'daily_visit'      => 5,
        'lesson_completed' => 15,
        'perfect_quiz'     => 25,
        'reservation_made' => 100,
        'review_published' => 30,
        'favorite_added'   => 5,
        'bridge_chat'      => 5,
        'play_win'         => 15,
    ];

    public const LEVELS = [
        ['threshold' => 0,    'fr' => 'Découvreur',            'en' => 'Discoverer'],
        ['threshold' => 200,  'fr' => 'Explorateur',           'en' => 'Explorer'],
        ['threshold' => 500,  'fr' => 'Voyageur',              'en' => 'Traveller'],
        ['threshold' => 1200, 'fr' => 'Ambassadeur',           'en' => 'Ambassador'],
        ['threshold' => 2500, 'fr' => 'Gardien du Patrimoine', 'en' => 'Heritage Keeper'],
    ];

    /**
     * Attribue des points (idempotent par jour pour les codes quotidiens).
     * @return array{points:int,total:int,new_badges:array}
     */
    public function award($user, string $code, array $meta = []): array
    {
        if (!isset(self::POINTS[$code])) return ['points' => 0, 'total' => $this->total($user), 'new_badges' => []];

        // Anti-abus par code
        if (!$this->passesGuards($user, $code, $meta)) {
            return ['points' => 0, 'total' => $this->total($user), 'new_badges' => []];
        }

        LoyaltyEvent::create([
            'user_id' => $user->id,
            'code'    => $code,
            'points'  => self::POINTS[$code],
            'meta'    => $meta ?: null,
        ]);

        $this->touchStreak($user);

        return [
            'points'     => self::POINTS[$code],
            'total'      => $this->total($user),
            'new_badges' => $this->evaluateBadges($user),
        ];
    }

    /** Visite quotidienne : +5 et streak. À appeler sur les pages membres. */
    public function touchDailyVisit($user): void
    {
        $summary = $this->summary($user);
        $today = now()->toDateString();

        if ($summary?->last_activity_date?->toDateString() === $today) return;

        LoyaltyEvent::create(['user_id' => $user->id, 'code' => 'daily_visit', 'points' => self::POINTS['daily_visit']]);
        $this->touchStreak($user);
        $this->evaluateBadges($user);
    }

    private function passesGuards($user, string $code, array $meta): bool
    {
        $q = LoyaltyEvent::where('user_id', $user->id)->where('code', $code);

        return match ($code) {
            'favorite_added'  => $q->count() < 10,
            'bridge_chat'     => $q->whereDate('created_at', today())->count() < 3,
            'lesson_completed'=> !LoyaltyEvent::where('user_id', $user->id)
                                  ->where('code', $code)
                                  ->whereJsonContains('meta->lesson_id', $meta['lesson_id'] ?? 0)->exists(),
            default           => true,
        };
    }

    private function touchStreak($user): void
    {
        $summary = $this->ensureSummary($user);
        $today = now()->toDateString();
        $lastRaw = $summary->last_activity_date;
        $last = $lastRaw ? (method_exists($lastRaw, 'toDateString') ? $lastRaw->toDateString() : substr((string) $lastRaw, 0, 10)) : null;

        if ($last === $today) { $summary->touch(); return; }

        $yesterday = now()->subDay()->toDateString();

        $summary->update([
            'streak_days'       => $last === $yesterday ? $summary->streak_days + 1 : 1,
            'last_activity_date'=> now(),
            'streak_updated_at' => now(),
        ]);
    }

    private function evaluateBadges($user): array
    {
        $earned = [];
        $has = fn (string $c) => $user->badges()->where('badges.code', $c)->exists();
        $grant = function (string $c) use ($user, &$earned) {
            $badge = Badge::where('code', $c)->first();
            if ($badge && !$user->badges()->where('badge_id', $badge->id)->exists()) {
                $user->badges()->attach($badge->id, ['earned_at' => now()]);
                $earned[] = $badge;
            }
        };

        $lessonsDone = LearnProgress::where('user_id', $user->id)->whereNotNull('completed_at')->count();
        $favs = \App\Models\ArtisanFavorite::where('user_id', $user->id)->count();
        $reviews = Review::where('user_id', $user->id)->count();
        $reservations = ReservationRequest::where('user_id', $user->id)->count();

        if (!Auth::check() || Auth::id() !== $user->id) Auth::setUser($user);
        $streak = $this->ensureSummary($user)->streak_days;
        $total = $this->total($user);

        !$has('first_steps') && $lessonsDone >= 1 && $grant('first_steps');
        !$has('golden_voice') && LearnProgress::where('user_id', $user->id)->where('best_score', 100)->exists() && $grant('golden_voice');
        !$has('scholar') && $lessonsDone >= 5 && $grant('scholar');
        !$has('collector') && $favs >= 3 && $grant('collector');
        !$has('critic') && $reviews >= 1 && $grant('critic');
        !$has('explorer') && $reservations >= 1 && $grant('explorer');
        !$has('on_fire') && $streak >= 7 && $grant('on_fire');
        !$has('ambassador') && $total >= 1200 && $grant('ambassador');

        return $earned;
    }

    public function total($user): int
    {
        return (int) (LoyaltyEvent::where('user_id', $user->id)->sum('points'));
    }

    public function summary($user)
    {
        return \App\Models\LoyaltySummary::find($user->id);
    }

    public function ensureSummary($user)
    {
        return \App\Models\LoyaltySummary::firstOrCreate(['user_id' => $user->id]);
    }

    public static function levelFor(int $points): array
    {
        $level = self::LEVELS[0];
        foreach (self::LEVELS as $l) {
            if ($points >= $l['threshold']) $level = $l;
        }
        $next = collect(self::LEVELS)->first(fn ($l) => $l['threshold'] > $points);

        return ['current' => $level, 'next' => $next];
    }
}
