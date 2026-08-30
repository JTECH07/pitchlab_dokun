@php
 $reservation = $reservation;
 $artisan = $reservation->artisan;
 $artisanName = $artisan->professional_name ?? ($artisan->first_name . ' ' . $artisan->last_name);
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
       <p style="margin:0 0 24px;font-size:16px;color:#17201D;line-height:1.6;">Bonjour {{ $reservation->visitor_name }},</p>

       <p style="margin:0 0 32px;font-size:16px;color:#17201D;line-height:1.6;">
        Nous avons le plaisir de vous annoncer que votre réservation <strong style="color:#064E3B;">ƉƆKUN</strong> a été acceptée par l'artisan. Une nouvelle rencontre avec le savoir-faire vous attend.
       </p>

       <!-- Détails -->
       <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#F8F6F0;border-radius:8px;border-left:4px solid #C99424;margin-bottom:32px;">
        <tr>
         <td style="padding:24px 28px;">
          <p style="margin:0 0 4px;font-size:11px;color:#C99424;text-transform:uppercase;letter-spacing:2px;font-weight:700;">Détails de votre réservation</p>
          <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
           <tr>
            <td style="padding:10px 0;border-bottom:1px solid #e5e7eb;font-size:14px;color:#6b7280;">Référence</td>
            <td style="padding:10px 0;border-bottom:1px solid #e5e7eb;font-size:14px;color:#17201D;font-weight:600;text-align:right;">{{ $reservation->reference }}</td>
           </tr>
           <tr>
            <td style="padding:10px 0;border-bottom:1px solid #e5e7eb;font-size:14px;color:#6b7280;">Artisan</td>
            <td style="padding:10px 0;border-bottom:1px solid #e5e7eb;font-size:14px;color:#17201D;font-weight:600;text-align:right;">{{ $artisanName }}</td>
           </tr>
           <tr>
            <td style="padding:10px 0;border-bottom:1px solid #e5e7eb;font-size:14px;color:#6b7280;">Expérience</td>
            <td style="padding:10px 0;border-bottom:1px solid #e5e7eb;font-size:14px;color:#17201D;font-weight:600;text-align:right;">{{ $reservation->experience_type }}</td>
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
            <td style="padding:10px 0;font-size:14px;color:#6b7280;">Montant</td>
            <td style="padding:10px 0;font-size:14px;color:#064E3B;font-weight:700;text-align:right;">{{ $reservation->total_amount ? number_format($reservation->total_amount, 0, ',', ' ') . ' FCFA' : 'Visite libre' }}</td>
           </tr>
          </table>
         </td>
        </tr>
       </table>

       @unless($reservation->total_amount)
       <p style="margin:0 0 24px;font-size:14px;color:#6b7280;line-height:1.6;font-style:italic;">Le paiement sera à régler directement lors de votre visite à l'atelier.</p>
       @endunless

       <!-- CTA -->
       <table role="presentation" cellpadding="0" cellspacing="0" style="margin:0 auto 32px;">
        <tr>
         <td style="background-color:#064E3B;border-radius:8px;">
          <a href="{{ route('reservations.receipt', $reservation->qr_code_token) }}" style="display:inline-block;padding:14px 32px;color:#ffffff;font-size:14px;font-weight:700;text-decoration:none;letter-spacing:0.5px;">Consulter mon billet QR</a>
         </td>
        </tr>
       </table>

       <p style="margin:0;font-size:14px;color:#6b7280;line-height:1.6;">
        À bientôt dans l'atelier,<br>
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
