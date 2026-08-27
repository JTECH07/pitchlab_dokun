<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Modèle économique ƉƆKUN
    |--------------------------------------------------------------------------
    |
    | Commission prélevée par la plateforme sur les réservations d'expériences
    | et sur les ventes marketplace. Ces taux sont configurables depuis
    | l'administration / variables d'environnement.
    |
    | Modèle : freemium + abonnements + commissions (10% réservation,
    | 5% marketplace).
    |
    */

    // Commission sur les réservations d'expériences (fraction décimale).
    'commission_reservation_rate' => (float) env('DOKUN_COMMISSION_RESERVATION', 0.10),

    // Commission sur les ventes marketplace (fraction décimale).
    'commission_marketplace_rate' => (float) env('DOKUN_COMMISSION_MARKETPLACE', 0.05),

    // Frais de service minimum sur une réservation (XOF).
    'min_service_fee' => (int) env('DOKUN_MIN_SERVICE_FEE', 500),
];
