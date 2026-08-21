<?php

namespace App\Http\Controllers;

use App\Models\Experience;
use App\Models\SavoirFaire;
use Illuminate\Http\Request;

class ExperienceController extends Controller
{
    /**
     * Taux de conversion fixes (base XOF / FCFA).
     * En production, ces taux seraient mis à jour via une API de change.
     */
    const CURRENCIES = [
        'XOF' => ['label' => 'FCFA',  'symbol' => 'FCFA', 'rate' => 1,        'flag' => '🇧🇯'],
        'EUR' => ['label' => 'Euro',   'symbol' => '€',    'rate' => 0.001524, 'flag' => '🇪🇺'],
        'USD' => ['label' => 'Dollar', 'symbol' => '$',    'rate' => 0.001644, 'flag' => '🇺🇸'],
        'GBP' => ['label' => 'Livre',  'symbol' => '£',    'rate' => 0.001293, 'flag' => '🇬🇧'],
        'GHS' => ['label' => 'Cedi',   'symbol' => 'GH₵',  'rate' => 0.02490,  'flag' => '🇬🇭'],
        'NGN' => ['label' => 'Naira',  'symbol' => '₦',    'rate' => 2.476,    'flag' => '🇳🇬'],
        'XAF' => ['label' => 'FCFA (CAF)', 'symbol' => 'XAF', 'rate' => 1,    'flag' => '🌍'],
        'MAD' => ['label' => 'Dirham', 'symbol' => 'MAD',  'rate' => 0.01641,  'flag' => '🇲🇦'],
    ];

    public function index(Request $request)
    {
        // ── Devise sélectionnée ──────────────────────────────────────────
        $currency     = $request->get('currency', 'XOF');
        $currencies   = self::CURRENCIES;
        if (!isset($currencies[$currency])) $currency = 'XOF';
        $currencyInfo = $currencies[$currency];
        $convRate     = $currencyInfo['rate'];

        // ── Budget en XOF (converti depuis la devise de l'utilisateur) ──
        $budgetMin = null;
        $budgetMax = null;
        if ($request->filled('budget_max') && $request->budget_max > 0) {
            $budgetMax = (int) round($request->budget_max / $convRate);
        }
        if ($request->filled('budget_min') && $request->budget_min > 0) {
            $budgetMin = (int) round($request->budget_min / $convRate);
        }

        // ── Catégories / intérêts ────────────────────────────────────────
        $savoirFaires     = SavoirFaire::orderBy('name')->get();
        $selectedSf       = $request->array('savoir_faire'); // array of IDs

        // ── Type d'expérience ───────────────────────────────────────────
        $selectedTypes    = $request->array('type');
        $availableTypes   = ['atelier', 'visite', 'creation', 'degustation', 'cérémonie'];

        // ── Durée ───────────────────────────────────────────────────────
        $maxDuration = $request->filled('duration') ? (int) $request->duration : null;

        // ── Requête principale ──────────────────────────────────────────
        $query = Experience::with(['artisan.media', 'artisan.savoirFaires'])
            ->where('is_published', true)
            // Recherche texte
            ->when($request->filled('q'), function ($q) use ($request) {
                $term = '%' . $request->q . '%';
                $q->where(function ($inner) use ($term) {
                    $inner->where('title', 'like', $term)
                          ->orWhere('summary', 'like', $term)
                          ->orWhereHas('artisan', fn ($a) =>
                              $a->where('professional_name', 'like', $term)
                                ->orWhere('first_name', 'like', $term)
                                ->orWhere('address', 'like', $term)
                          );
                });
            })
            // Filtres savoir-faire / intérêts
            ->when(!empty($selectedSf), function ($q) use ($selectedSf) {
                $q->whereHas('artisan.savoirFaires', fn ($sf) => $sf->whereIn('savoir_faires.id', $selectedSf));
            })
            // Type d'expérience
            ->when(!empty($selectedTypes), function ($q) use ($selectedTypes) {
                $q->whereIn('experience_type', $selectedTypes);
            })
            // Budget XOF
            ->when($budgetMin, fn ($q) => $q->where('price', '>=', $budgetMin))
            ->when($budgetMax, fn ($q) => $q->where('price', '<=', $budgetMax))
            // Durée
            ->when($maxDuration, fn ($q) => $q->where('duration_minutes', '<=', $maxDuration));

        // Tri
        $sort = $request->get('sort', 'price_asc');
        match ($sort) {
            'price_desc' => $query->orderByDesc('price'),
            'duration'   => $query->orderBy('duration_minutes'),
            'newest'     => $query->latest(),
            default      => $query->orderBy('price'),
        };

        $experiences = $query->paginate(12)->withQueryString();

        // ── Recommandations ─────────────────────────────────────────────
        // Si budget ou intérêts définis, proposer 3 expériences similaires populaires
        $recommended = collect();
        if (!empty($selectedSf) || $budgetMax) {
            $recommended = Experience::with(['artisan.media'])
                ->where('is_published', true)
                ->when(!empty($selectedSf), fn ($q) =>
                    $q->whereHas('artisan.savoirFaires', fn ($sf) => $sf->whereIn('savoir_faires.id', $selectedSf))
                )
                ->when($budgetMax, fn ($q) => $q->where('price', '<=', $budgetMax))
                ->inRandomOrder()
                ->take(3)
                ->get();
        }

        return view('experiences.index', compact(
            'experiences', 'savoirFaires', 'selectedSf', 'selectedTypes',
            'availableTypes', 'currencies', 'currency', 'currencyInfo',
            'convRate', 'recommended', 'sort'
        ));
    }
}
