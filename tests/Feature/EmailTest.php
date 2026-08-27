<?php

namespace Tests\Feature;

use App\Mail\ContactFormMail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class EmailTest extends TestCase
{
    use RefreshDatabase;

    public function test_contact_form_mail_renders(): void
    {
        $mail = new ContactFormMail('Jean', 'jean@test.com', 'Bonjour', 'Message test');
        $html = $mail->render();

        $this->assertStringContainsString('Jean', $html);
        $this->assertStringContainsString('jean@test.com', $html);
        $this->assertStringContainsString('Bonjour', $html);
        $this->assertStringContainsString('Message test', $html);
    }

    public function test_contact_form_is_queued(): void
    {
        Mail::fake();

        $this->post(route('contact.send'), [
            'name'    => 'Jean',
            'email'   => 'jean@test.com',
            'subject' => 'Bonjour',
            'message' => 'Message test',
        ])->assertRedirect();

        Mail::assertQueued(ContactFormMail::class);
    }
}
