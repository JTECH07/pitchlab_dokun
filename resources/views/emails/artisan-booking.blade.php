@php
    $reservation = $reservation;
    $artisan = $reservation->artisan;
    $isExperience = !empty($reservation->experience_id);
    $typeLabel = $isExperience
        ? 'Expérience pratique : ' . $reservation->experience_type
        : 'Visite d\'atelier libre';
@endphp
Bonjour {{ $artisan->first_name }},

Vous avez reçu une nouvelle réservation ƉƆKUN.

═══════════════════════════════
RÉFÉRENCE   : {{ $reservation->reference }}
TYPE        : {{ $typeLabel }}
VISITEUR    : {{ $reservation->visitor_name }}
TÉLÉPHONE   : {{ $reservation->visitor_phone }}
DATE        : {{ $reservation->requested_date }}
PERSONNES   : {{ $reservation->guests_count }}
PAIEMENT    : {{ $reservation->payment_method === 'mobile_money' ? 'Mobile Money (payé)' : 'À régler à l\'atelier' }}
MONTANT     : {{ $reservation->total_amount ? number_format($reservation->total_amount, 0, ',', ' ') . ' FCFA' : 'Visite libre' }}
═══════════════════════════════

@if($reservation->message)
Message du visiteur :
"{{ $reservation->message }}"

@endifConnectez-vous sur ƉƆKUN pour confirmer ou refuser cette réservation.
URL : {{ route('artisan-space.index') }}

— L'équipe ƉƆKUN
