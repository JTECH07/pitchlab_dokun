<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentCallbackTest extends TestCase
{
    use RefreshDatabase;

    public function test_callback_without_id_redirects_home_with_error(): void
    {
        $response = $this->get(route('payment.callback'))
            ->assertRedirect(route('home'));

        $response->assertSessionHas('error');
    }
}
