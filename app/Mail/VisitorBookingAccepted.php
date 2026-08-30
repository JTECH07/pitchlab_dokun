<?php

namespace App\Mail;

use App\Models\ReservationRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class VisitorBookingAccepted extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public ReservationRequest $reservation)
    {
    }

    public function envelope(): Envelope
    {
        $artisan = $this->reservation->artisan;

        return new Envelope(
            to: [$this->reservation->visitor_email => $this->reservation->visitor_name],
            from: [config('mail.from.address') => 'ƉƆKUN Réservations'],
            subject: 'Votre réservation ƉƆKUN est acceptée — ' . $this->reservation->reference,
        );
    }

    public function content(): Content
    {
        return new Content(text: 'emails.visitor-booking-accepted');
    }
}
