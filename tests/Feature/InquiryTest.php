<?php

namespace Tests\Feature;

use App\Mail\InquiryTacMail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class InquiryTest extends TestCase
{
    use RefreshDatabase;

    public function test_an_inquiry_verification_code_can_be_sent(): void
    {
        Mail::fake();
        $this->postJson(route('inquiry.tac.send'), ['full_name' => 'Jane Smith', 'email' => 'Jane@example.com'])->assertOk();
        Mail::assertSent(InquiryTacMail::class);
        $this->assertSame('jane@example.com', session('inquiry_tac.email'));
    }

    public function test_an_unverified_email_cannot_send_an_inquiry(): void
    {
        $this->postJson(route('inquiry.submit'), $this->payload())->assertUnprocessable()->assertJsonValidationErrors('email');
        $this->assertDatabaseCount('inquiries', 0);
    }

    public function test_a_verified_email_can_send_an_inquiry(): void
    {
        $this->withSession(['inquiry_verified_email' => 'jane@example.com'])
            ->postJson(route('inquiry.submit'), $this->payload())->assertCreated()->assertJsonStructure(['reference']);
        $this->assertDatabaseHas('inquiries', ['email' => 'jane@example.com', 'topic' => 'costs', 'status' => 'new']);
    }

    private function payload(): array
    {
        return ['full_name' => 'Jane Smith', 'email' => 'jane@example.com', 'topic' => 'costs', 'message' => 'Please explain the progressive court filing charges.', 'privacy_consent' => '1'];
    }
}
