<?php

namespace Tests\Feature;

use App\Mail\JourneyTacMail;
use App\Models\JourneyVettingSubmission;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class JourneyVettingTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_tac_can_be_sent_to_the_applicant_email(): void
    {
        Mail::fake();

        $this->postJson(route('journey.tac.send'), [
            'email' => 'applicant@example.com',
            'full_name' => 'Nur Aisyah Ahmad',
        ])->assertOk()->assertJsonPath('resend_after', 60);

        Mail::assertSent(JourneyTacMail::class, fn (JourneyTacMail $mail) => $mail->hasTo('applicant@example.com'));
        $this->assertSame('applicant@example.com', session('journey_vetting_tac.email'));
        $this->assertArrayNotHasKey('code', session('journey_vetting_tac'));
    }

    public function test_the_tac_is_limited_to_five_failed_attempts(): void
    {
        $response = $this->withSession([
            'journey_vetting_tac' => [
                'email' => 'applicant@example.com',
                'code_hash' => hash('sha256', '123456'),
                'expires_at' => now()->addMinutes(10)->timestamp,
                'resend_available_at' => now()->addMinute()->timestamp,
                'attempts' => 5,
            ],
        ])->postJson(route('journey.tac.verify'), [
            'email' => 'applicant@example.com',
            'code' => '000000',
        ]);

        $this->assertSame(422, $response->getStatusCode(), $response->getContent());
        $this->assertSame('Too many attempts. Request a new code.', $response->json('errors.code.0'));

        $this->assertNull(session('journey_vetting_tac'));
    }

    public function test_a_verified_applicant_can_submit_details_with_an_encrypted_nric(): void
    {
        $response = $this
            ->withSession(['journey_vetting_verified_email' => 'applicant@example.com'])
            ->postJson(route('journey.submit'), $this->validPayload())
            ->assertCreated()
            ->assertJsonStructure(['message', 'reference']);

        $submission = JourneyVettingSubmission::firstOrFail();
        $this->assertSame('900101-14-5678', $submission->identity_number);
        $this->assertSame(['children', 'property'], $submission->selected_topics);
        $this->assertStringStartsWith('LD-', $response->json('reference'));

        $rawIdentity = DB::table('journey_vetting_submissions')->value('identity_number');
        $this->assertStringNotContainsString('900101', $rawIdentity);
    }

    public function test_an_unverified_email_cannot_submit_the_vetting_form(): void
    {
        $response = $this->postJson(route('journey.submit'), $this->validPayload());
        $this->assertSame(422, $response->getStatusCode(), $response->getContent());
        $this->assertSame('Verify this email address before submitting the form.', $response->json('errors.email.0'));

        $this->assertDatabaseCount('journey_vetting_submissions', 0);
    }

    private function validPayload(): array
    {
        return [
            'full_name' => 'Nur Aisyah Ahmad',
            'email' => 'applicant@example.com',
            'phone' => '+60 12-345 6789',
            'identity_type' => 'nric',
            'identity_number' => '900101145678',
            'education_level' => 'bachelors',
            'employment_status' => 'employed',
            'preferred_language' => 'english',
            'court_experience' => 'none',
            'legal_document_confidence' => 3,
            'support_needs' => 'Please use plain language.',
            'agreement_status' => 'agree',
            'selected_topics' => ['children', 'property'],
            'privacy_consent' => true,
        ];
    }
}
