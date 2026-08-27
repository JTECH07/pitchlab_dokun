<?php

namespace App\Http\Controllers;

use App\Models\Artisan;
use App\Models\ArtisanFavorite;
use App\Models\LearnProgress;
use App\Models\ReservationRequest;
use App\Services\LoyaltyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VisitorController extends Controller
{
    public function profile(Request $request)
    {
        $user = $request->user() ?? auth()->user();
        abort_unless($user, 401);

        // Visite quotidienne → points + streak
        $loyalty = app(\App\Services\LoyaltyService::class);
        $loyalty->touchDailyVisit($user);
        $summary = $loyalty->ensureSummary($user);
        $totalPoints = $loyalty->total($user);
        [$level, $nextLevel] = array_values(LoyaltyService::levelFor($totalPoints));
        $allBadges = \App\Models\Badge::orderBy('id')->get();
        $earnedBadgeIds = $user->badges()->pluck('badge_id')->flip();

        // ─── Réservations ───────────────────────────────────────
        $reservations = ReservationRequest::with(['artisan', 'experience'])
            ->where('user_id', $user->id)
            ->latest('requested_date')
            ->get();
        $upcoming = $reservations->filter(
            fn ($r) => in_array($r->status, ['pending', 'accepted', 'confirmed'])
                && $r->requested_date >= now()->startOfDay()
        );
        $past = $reservations->except($upcoming->pluck('id')->all());

        // ─── Favoris ────────────────────────────────────────────
        $favorites = Artisan::with(['savoirFaires'])
            ->whereIn('id', ArtisanFavorite::where('user_id', $user->id)->pluck('artisan_id'))
            ->where('status', 'published')
            ->get()
            ->each(fn ($a) => $a->append('image_url'));

        // ─── Progression Learn ──────────────────────────────────
        $progress = LearnProgress::with('lesson.course')->where('user_id', $user->id)->get();
        $learnStats = [
            'lessons_done'  => $progress->whereNotNull('completed_at')->count(),
            'avg_score'     => round((clone $progress)->avg('best_score') ?? 0),
            'best_score'    => (int) ($progress->max('best_score') ?? 0),
            'recent'        => $progress->sortByDesc('updated_at')->take(4),
        ];

        // ─── Avis ───────────────────────────────────────────────
        $reviewsCount = \App\Models\Review::where('user_id', $user->id)->count();

        return view('visitor.profile', compact(
            'upcoming', 'past', 'favorites', 'learnStats', 'reviewsCount',
            'summary', 'totalPoints', 'level', 'nextLevel', 'allBadges', 'earnedBadgeIds'
        ));
    }

    /**
     * ƉƆKUN Passport — progression culturelle personnelle.
     * Trace les savoir-faire découverts, mots appris, points, niveau,
     * badges et série. Alimente la gamification et la fidélisation.
     */
    public function passport(Request $request)
    {
        $user = $request->user() ?? auth()->user();
        abort_unless($user, 401);

        $loyalty  = app(LoyaltyService::class);
        $loyalty->touchDailyVisit($user);
        $summary     = $loyalty->ensureSummary($user);
        $totalPoints = $loyalty->total($user);
        [$level, $nextLevel] = array_values(LoyaltyService::levelFor($totalPoints));
        $allBadges    = \App\Models\Badge::orderBy('id')->get();
        $earnedBadgeIds = $user->badges()->pluck('badge_id')->flip();

        // ─── Savoir-faire découverts (réservations + favoris) ─────
        $favoriteSF = \App\Models\SavoirFaire::whereHas('artisans', fn ($q) =>
            $q->whereIn('id', \App\Models\ArtisanFavorite::where('user_id', $user->id)->pluck('artisan_id')))
            ->with('category')->get();

        $reservedSF = \App\Models\SavoirFaire::whereHas('artisans.experiences.reservations', fn ($q) =>
            $q->where('user_id', $user->id))->with('category')->get();

        $discovered = $favoriteSF->merge($reservedSF)->unique('id')->values();

        // ─── Mots appris (vitrine) ────────────────────────────────
        $progress = LearnProgress::with('lesson.course')->where('user_id', $user->id)->get();
        $learnStats = [
            'lessons_done' => $progress->whereNotNull('completed_at')->count(),
            'best_score'   => (int) ($progress->max('best_score') ?? 0),
        ];

        $events = \App\Models\LoyaltyEvent::where('user_id', $user->id)
            ->orderByDesc('created_at')->take(12)->get();

        $labels = app()->getLocale() === 'fr' ? [
            'daily_visit' => 'Visite quotidienne', 'lesson_completed' => 'Leçon terminée',
            'perfect_quiz' => 'Quiz parfait', 'reservation_made' => 'Réservation confirmée',
            'review_published' => 'Témoignage publié', 'favorite_added' => 'Artisan favori',
            'bridge_chat' => 'Discussion Bridge',
        ] : [
            'daily_visit' => 'Daily visit', 'lesson_completed' => 'Lesson completed',
            'perfect_quiz' => 'Perfect quiz', 'reservation_made' => 'Reservation confirmed',
            'review_published' => 'Review published', 'favorite_added' => 'Artisan added to favorites',
            'bridge_chat' => 'Bridge chat',
        ];
        $eventLabel = fn (string $code) => $labels[$code] ?? ucfirst(str_replace('_', ' ', $code));

        return view('visitor.passport', compact(
            'summary', 'totalPoints', 'level', 'nextLevel',
            'allBadges', 'earnedBadgeIds', 'discovered', 'learnStats', 'events', 'eventLabel'
        ));
    }

    public function toggleFavorite(Request $request, Artisan $artisan)
    {
        $fav = ArtisanFavorite::where('user_id', $request->user()->id)
            ->where('artisan_id', $artisan->id)
            ->first();

        if ($fav) {
            $fav->delete();
            return response()->json(['status' => 'removed']);
        }

        ArtisanFavorite::create([
            'user_id' => $request->user()->id,
            'artisan_id' => $artisan->id,
        ]);

        app(\App\Services\LoyaltyService::class)->award($request->user(), 'favorite_added', ['artisan_id' => $artisan->id]);

        return response()->json(['status' => 'added']);
    }
}
