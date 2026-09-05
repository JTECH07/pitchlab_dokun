@php
 $reservation = $reservation;
 $artisan = $reservation->artisan;
 $isExperience = !empty($reservation->experience_id);
 $typeLabel = $isExperience
 ? 'Expérience pratique : ' . $reservation->experience_type
 : 'Visite d\'atelier libre';
@endphp
<!DOCTYPE html>
<html lang="fr">
<head>
 <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body style="margin:0;padding:0;background-color:#F8F6F0;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;">
 <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#F8F6F0;">
  <tr>
   <td align="center" style="padding:40px 16px;">
    <table role="presentation" width="600" cellpadding="0" cellspacing="0" style="max-width:600px;width:100%;background-color:#ffffff;border-radius:12px;overflow:hidden;box-shadow:0 2px 12px rgba(0,0,0,0.06);">

     <!-- Header -->
     <tr>
      <td style="background-color:#064E3B;padding:32px 40px;text-align:center;">
       <img src="{{ asset('public/images/dokun_logo.png') }}" alt="ƉƆKUN" width="120" style="display:block;margin:0 auto 16px;max-width:120px;">
       <p style="margin:0;color:#C99424;font-size:11px;letter-spacing:3px;text-transform:uppercase;font-weight:600;">Héritage · Artisanat · Vivant</p>
      </td>
     </tr>

     <!-- Body -->
     <tr>
      <td style="padding:40px;">
       <p style="margin:0 0 24px;font-size:16px;color:#17201D;line-height:1.6;">Bonjour {{ $artisan->first_name }},</p>

       <p style="margin:0 0 32px;font-size:16px;color:#17201D;line-height:1.6;">
        Une nouvelle réservation vient de passer sur votre atelier. Un visiteur souhaite découvrir votre univers.
       </p>

       <!-- Détails -->
       <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#F8F6F0;border-radius:8px;border-left:4px solid #C99424;margin-bottom:32px;">
        <tr>
         <td style="padding:24px 28px;">
          <p style="margin:0 0 4px;font-size:11px;color:#C99424;text-transform:uppercase;letter-spacing:2px;font-weight:700;">Nouvelle réservation</p>
          <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
           <tr>
            <td style="padding:10px 0;border-bottom:1px solid #e5e7eb;font-size:14px;color:#6b7280;">Référence</td>
            <td style="padding:10px 0;border-bottom:1px solid #e5e7eb;font-size:14px;color:#17201D;font-weight:600;text-align:right;">{{ $reservation->reference }}</td>
           </tr>
           <tr>
            <td style="padding:10px 0;border-bottom:1px solid #e5e7eb;font-size:14px;color:#6b7280;">Type</td>
            <td style="padding:10px 0;border-bottom:1px solid #e5e7eb;font-size:14px;color:#17201D;font-weight:600;text-align:right;">{{ $typeLabel }}</td>
           </tr>
           <tr>
            <td style="padding:10px 0;border-bottom:1px solid #e5e7eb;font-size:14px;color:#6b7280;">Visiteur</td>
            <td style="padding:10px 0;border-bottom:1px solid #e5e7eb;font-size:14px;color:#17201D;font-weight:600;text-align:right;">{{ $reservation->visitor_name }}</td>
           </tr>
           <tr>
            <td style="padding:10px 0;border-bottom:1px solid #e5e7eb;font-size:14px;color:#6b7280;">Téléphone</td>
            <td style="padding:10px 0;border-bottom:1px solid #e5e7eb;font-size:14px;color:#17201D;font-weight:600;text-align:right;">{{ $reservation->visitor_phone }}</td>
           </tr>
           <tr>
            <td style="padding:10px 0;border-bottom:1px solid #e5e7eb;font-size:14px;color:#6b7280;">Date</td>
            <td style="padding:10px 0;border-bottom:1px solid #e5e7eb;font-size:14px;color:#17201D;font-weight:600;text-align:right;">{{ $reservation->requested_date }}</td>
           </tr>
           <tr>
            <td style="padding:10px 0;border-bottom:1px solid #e5e7eb;font-size:14px;color:#6b7280;">Nombre de personnes</td>
            <td style="padding:10px 0;border-bottom:1px solid #e5e7eb;font-size:14px;color:#17201D;font-weight:600;text-align:right;">{{ $reservation->guests_count }}</td>
           </tr>
           <tr>
            <td style="padding:10px 0;border-bottom:1px solid #e5e7eb;font-size:14px;color:#6b7280;">Paiement</td>
            <td style="padding:10px 0;border-bottom:1px solid #e5e7eb;font-size:14px;color:#17201D;font-weight:600;text-align:right;">Mobile Money (payé en ligne via FedaPay)</td>
           </tr>
           <tr>
            <td style="padding:10px 0;font-size:14px;color:#6b7280;">Montant</td>
            <td style="padding:10px 0;font-size:14px;color:#064E3B;font-weight:700;text-align:right;">{{ $reservation->total_amount ? number_format($reservation->total_amount, 0, ',', ' ') . ' FCFA' : 'Visite libre' }}</td>
           </tr>
          </table>
         </td>
        </tr>
       </table>

       @if($reservation->message)
       <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:32px;">
        <tr>
         <td style="padding:20px 24px;background-color:#F8F6F0;border-radius:8px;">
          <p style="margin:0 0 8px;font-size:11px;color:#C99424;text-transform:uppercase;letter-spacing:2px;font-weight:700;">Message du visiteur</p>
          <p style="margin:0;font-size:14px;color:#17201D;line-height:1.6;font-style:italic;">« {{ $reservation->message }} »</p>
         </td>
        </tr>
       </table>
       @endif

       <!-- CTA -->
       <table role="presentation" cellpadding="0" cellspacing="0" style="margin:0 auto 32px;">
        <tr>
         <td style="background-color:#064E3B;border-radius:8px;">
          <a href="{{ route('artisan-space.index') }}" style="display:inline-block;padding:14px 32px;color:#ffffff;font-size:14px;font-weight:700;text-decoration:none;letter-spacing:0.5px;">Gérer cette réservation</a>
         </td>
        </tr>
       </table>

       <p style="margin:0;font-size:14px;color:#6b7280;line-height:1.6;">
        Merci pour votre confiance,<br>
        <strong style="color:#064E3B;">L'équipe ƉƆKUN</strong>
       </p>
      </td>
     </tr>

     <!-- Footer -->
     <tr>
      <td style="background-color:#064E3B;padding:28px 40px;text-align:center;">
       <p style="margin:0 0 8px;font-size:12px;color:#C99424;letter-spacing:2px;text-transform:uppercase;font-weight:600;">Héritage · Artisanat · Vivant</p>
       <p style="margin:0;font-size:11px;color:rgba(255,255,255,0.5);">© {{ date('Y') }} ƉƆKUN — Tous droits réservés</p>
      </td>
     </tr>

    </table>
   </td>
  </tr>
 </table>
</body>
</html>
