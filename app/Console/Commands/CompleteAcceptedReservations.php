<?php

namespace App\Console\Commands;

use App\Models\ReservationRequest;
use App\Services\WhatsAppService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class CompleteAcceptedReservations extends Command
{
    protected $signature = 'reservations:auto-complete';
    protected $description = 'Mark accepted reservations as completed after their visit date';

    public function handle(): int
    {
        $yesterday = now()->subDay()->toDateString();

        $reservations = ReservationRequest::with('artisan')
            ->where('status', 'accepted')
            ->where('requested_date', '<=', $yesterday)
            ->get();

        $count = 0;

        foreach ($reservations as $reservation) {
            $reservation->update(['status' => 'completed']);
            $count++;

            $this->notifyVisitorCompleted($reservation);
        }

        $this->info("{$count} reservation(s) auto-complétée(s).");
        return Command::SUCCESS;
    }

    private function notifyVisitorCompleted(ReservationRequest $reservation): void
    {
        if (! empty($reservation->visitor_email)) {
            try {
                $artisanName = $reservation->artisan?->professional_name
                    ?? ($reservation->artisan?->first_name . ' ' . $reservation->artisan?->last_name);

                Mail::to($reservation->visitor_email)->raw(
                    "Votre réservation {$reservation->reference} chez {$artisanName} est terminée. "
                    . "Merci pour votre visite ! Laissez un avis sur ƉƆKUN : "
                    . route('reviews.create', $reservation->qr_code_token),
                    fn ($message) => $message
                        ->from(config('mail.from.address'), 'ƉƆKUN Réservations')
                        ->subject('Réservation ƉƆKUN complétée — ' . $reservation->reference)
                );
            } catch (\Throwable $e) {
                \Log::warning('Failed to send completion email: ' . $e->getMessage());
            }
        }

        if (! empty($reservation->visitor_phone)) {
            try {
                $phone = preg_replace('/\D/', '', $reservation->visitor_phone);
                $artisanName = $reservation->artisan?->professional_name
                    ?? ($reservation->artisan?->first_name . ' ' . $reservation->artisan?->last_name);
                $message = "Votre réservation {$reservation->reference} chez {$artisanName} est terminée. "
                    . "Merci pour votre visite ! Laissez un avis : "
                    . route('reviews.create', $reservation->qr_code_token);

                WhatsAppService::send($phone, $message);
            } catch (\Throwable $e) {
                \Log::warning('Failed to send completion WhatsApp: ' . $e->getMessage());
            }
        }
    }
}
