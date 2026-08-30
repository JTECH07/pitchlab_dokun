<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class WhatsAppService
{
    public static function send(string $phone, string $message): void
    {
        $apiToken = config('services.whatsapp.token');
        $phoneNumberId = config('services.whatsapp.phone_number_id');

        if (! $apiToken || ! $phoneNumberId) {
            throw new \RuntimeException('WhatsApp API credentials not configured.');
        }

        $phone = preg_replace('/[^0-9]/', '', $phone);

        $response = Http::withToken($apiToken)
            ->post("https://graph.facebook.com/v18.0/{$phoneNumberId}/messages", [
                'messaging_product' => 'whatsapp',
                'to' => $phone,
                'type' => 'text',
                'text' => ['body' => $message],
            ]);

        if ($response->failed()) {
            throw new \RuntimeException('WhatsApp API error: ' . $response->body());
        }
    }
}
