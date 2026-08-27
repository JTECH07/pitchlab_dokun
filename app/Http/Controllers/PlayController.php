<?php

namespace App\Http\Controllers;

use App\Models\LoyaltyEvent;
use App\Models\SavoirFaire;
use Illuminate\Http\Request;

/**
 * ƉƆKUN Play — mini-jeu « Devine le savoir-faire ».
 * Renforce la mémorisation et rend le patrimoine accessible.
 */
class PlayController extends Controller
{
    private const POINTS_PER_WIN = 15;

    /**
     * GET /jouer — page du mini-jeu (4 savoir-faire au hasard).
     */
    public function index()
    {
        $sf = SavoirFaire::with(['category', 'artisans'])->whereHas('artisans')->inRandomOrder()->take(4)->get();
        if ($sf->count() < 2) {
            $sf = SavoirFaire::all()->take(4);
        }

        // Première carte : la cible, les autres : les pièges.
        $target = $sf->first();
        $choices = $sf->shuffle();

        return view('play.index', compact('target', 'choices', 'sf'));
    }

    /**
     * POST /jouer/guess — vérifie la réponse et récompense.
     */
    public function guess(Request $request)
    {
        $validated = $request->validate([
            'target_id' => 'required|exists:savoir_faires,id',
            'answer_id' => 'required|exists:savoir_faires,id',
        ]);

        $correct = (int) $validated['target_id'] === (int) $validated['answer_id'];
        $won = false;

        $user = $request->user();
        if ($correct && $user) {
            // Anti-abus : on ne récompense qu'une fois par partie max (5/jour).
            $todayCount = LoyaltyEvent::where('user_id', $user->id)
                ->where('code', 'play_win')->whereDate('created_at', today())->count();
            if ($todayCount < 5) {
                app(\App\Services\LoyaltyService::class)->award($user, 'play_win');
                $won = true;
            }
        }

        $target = SavoirFaire::find($validated['target_id']);

        if ($request->expectsJson()) {
            return response()->json(['correct' => $correct, 'won' => $won, 'target' => $target?->only(['id', 'name', 'description'])]);
        }

        return redirect()->route('play.index')->with(
            $correct ? 'success' : 'error',
            $correct
                ? ($won ? 'Bravo ! +' . self::POINTS_PER_WIN . ' XP' : 'Bonne réponse !')
                : 'Pas tout à fait… réessayez !'
        );
    }
}
