<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * SetCurrency middleware — reads currency from session, defaults to XOF.
 * Shares $currentCurrency and $currencyInfo with all Blade views.
 */
class SetCurrency
{
    const CURRENCIES = [
        'XOF' => ['label' => 'FCFA',      'symbol' => 'FCFA', 'rate' => 1,        'flag' => '🇧🇯'],
        'EUR' => ['label' => 'Euro',       'symbol' => '€',    'rate' => 0.001524, 'flag' => '🇪🇺'],
        'USD' => ['label' => 'Dollar US',  'symbol' => '$',    'rate' => 0.001644, 'flag' => '🇺🇸'],
        'GBP' => ['label' => 'Livre',      'symbol' => '£',    'rate' => 0.001293, 'flag' => '🇬🇧'],
        'GHS' => ['label' => 'Cedi',       'symbol' => 'GH₵',  'rate' => 0.02490,  'flag' => '🇬🇭'],
        'NGN' => ['label' => 'Naira',      'symbol' => '₦',    'rate' => 2.476,    'flag' => '🇳🇬'],
        'MAD' => ['label' => 'Dirham',     'symbol' => 'MAD',  'rate' => 0.01641,  'flag' => '🇲🇦'],
    ];

    public function handle(Request $request, Closure $next): Response
    {
        // If ?currency= param, save to session
        if ($request->has('currency') && isset(self::CURRENCIES[$request->query('currency')])) {
            session(['dokun_currency' => $request->query('currency')]);
        }

        $code = session('dokun_currency', 'XOF');
        if (!isset(self::CURRENCIES[$code])) $code = 'XOF';

        $currencyInfo = self::CURRENCIES[$code];

        // Share with all Blade views
        view()->share('currentCurrency',   $code);
        view()->share('currencyInfo',      $currencyInfo);
        view()->share('currencyRate',      $currencyInfo['rate']);
        view()->share('allCurrencies',     self::CURRENCIES);

        return $next($request);
    }
}
