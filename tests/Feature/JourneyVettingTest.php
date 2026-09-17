<?php

namespace Tests\Feature;

use App\Mail\JourneyTacMail;
use App\Models\JourneyVettingSubmission;
use App\Models\User;
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
        $this->assertSame('+60123456789', $submission->phone);
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

    public function test_a_mobile_number_must_be_in_international_format(): void
    {
        $payload = $this->validPayload();
        $payload['phone'] = '0123456789';
        $response = $this->withSession(['journey_vetting_verified_email' => 'applicant@example.com'])
            ->postJson(route('journey.submit'), $payload);
        $this->assertSame(422, $response->getStatusCode(), $response->getContent());
        $this->assertSame('Enter a valid mobile number including its country code.', $response->json('errors.phone.0'));
    }

    public function test_an_email_address_cannot_be_submitted_twice(): void
    {
        $this->withSession(['journey_vetting_verified_email' => 'applicant@example.com'])
            ->postJson(route('journey.submit'), $this->validPayload());

        $duplicate = $this->validPayload();
        $duplicate['phone'] = '+60198765432';
        $response = $this->withSession(['journey_vetting_verified_email' => 'applicant@example.com'])
            ->postJson(route('journey.submit'), $duplicate);

        $this->assertSame(422, $response->getStatusCode(), $response->getContent());
        $this->assertSame('A submission has already been received for this email address.', $response->json('errors.email.0'));
        $this->assertDatabaseCount('journey_vetting_submissions', 1);
    }

    public function test_a_mobile_number_cannot_be_submitted_twice(): void
    {
        $this->withSession(['journey_vetting_verified_email' => 'applicant@example.com'])
            ->postJson(route('journey.submit'), $this->validPayload());

        $duplicate = $this->validPayload();
        $duplicate['email'] = 'second@example.com';
        $response = $this->withSession(['journey_vetting_verified_email' => 'second@example.com'])
            ->postJson(route('journey.submit'), $duplicate);

        $this->assertSame(422, $response->getStatusCode(), $response->getContent());
        $this->assertSame('A submission has already been received for this mobile number.', $response->json('errors.phone.0'));
        $this->assertDatabaseCount('journey_vetting_submissions', 1);
    }

    public function test_the_temporary_dashboard_lists_submissions_with_full_identity_numbers(): void
    {
        JourneyVettingSubmission::create([
            ...$this->validPayload(),
            'reference' => 'LD-260904-ABC123',
            'identity_number' => '900101-14-5678',
            'email_verified_at' => now(),
            'submitted_at' => now(),
            'status' => 'pending_review',
        ]);

        $this->actingAs(User::factory()->create())->get(route('journey.dashboard', ['tab' => 'submissions']))
            ->assertOk()
            ->assertSee('LegalDIY dashboard')
            ->assertSee('Nur Aisyah Ahmad')
            ->assertSee('applicant@example.com')
            ->assertSee('900101-14-5678')
            ->assertSee('RM 4,000–RM 5,999')
            ->assertDontSee('••••••-••-5678');
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
            'monthly_income_range' => '4000_5999',
            'preferred_language' => 'english',
            'court_experience' => 'none',
            'separation_status' => 'separated_apart',
            'separation_duration' => '7_12_months',
            'divorce_stage' => 'agreed',
            'papers_filed' => 'no',
            'legal_document_confidence' => 3,
            'support_needs' => 'Please use plain language.',
            'agreement_status' => 'agree',
            'selected_topics' => ['children', 'property'],
            'privacy_consent' => true,
        ];
    }
}
