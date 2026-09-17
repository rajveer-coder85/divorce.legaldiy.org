<?php

namespace Tests\Feature;

use App\Models\Inquiry;
use App\Models\User;
use App\Mail\CaseAccessTacMail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class DashboardAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_requires_login(): void
    {
        $this->get(route('journey.dashboard'))->assertRedirect(route('dashboard.login'));
    }

    public function test_valid_user_can_login_and_logout(): void
    {
        $user = User::factory()->create(['email' => 'admin@example.com', 'password' => 'A-secure-password-123']);
        $this->post(route('dashboard.login.submit'), ['email' => $user->email, 'password' => 'A-secure-password-123'])
            ->assertRedirect(route('journey.dashboard'));
        $this->assertAuthenticatedAs($user);
        $this->post(route('dashboard.logout'))->assertRedirect(route('dashboard.login'));
        $this->assertGuest();
    }

    public function test_invalid_credentials_are_rejected(): void
    {
        User::factory()->create(['email' => 'admin@example.com']);
        $this->post(route('dashboard.login.submit'), ['email' => 'admin@example.com', 'password' => 'wrong-password'])
            ->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_authenticated_dashboard_displays_verified_inquiry(): void
    {
        $inquiry = Inquiry::create(['reference' => 'INQ-260904-ABC123', 'full_name' => 'Jane Smith', 'email' => 'jane@example.com', 'topic' => 'costs', 'message' => 'Please explain the filing charges.', 'email_verified_at' => now(), 'submitted_at' => now(), 'status' => 'new']);
        $this->actingAs(User::factory()->create())->get(route('journey.dashboard'))
            ->assertOk()->assertSee($inquiry->reference)->assertSee($inquiry->message)->assertSee('Reply by email');
    }

    public function test_authenticated_user_can_create_a_private_submission_url(): void
    {
        $submission = \App\Models\JourneyVettingSubmission::create([
            'reference' => 'LD-260904-ABC123', 'full_name' => 'Jane Smith', 'email' => 'jane@example.com',
            'phone' => '+60123456789', 'identity_type' => 'nric', 'identity_number' => '900101145678',
            'education_level' => 'secondary', 'employment_status' => 'employed', 'preferred_language' => 'english',
            'court_experience' => 'none', 'legal_document_confidence' => 3, 'agreement_status' => 'agree',
            'selected_topics' => ['children'], 'email_verified_at' => now(), 'submitted_at' => now(), 'status' => 'pending_review',
        ]);
        $this->post(route('journey.dashboard.slug', $submission))->assertRedirect(route('dashboard.login'));
        $this->actingAs(User::factory()->create())->post(route('journey.dashboard.slug', $submission))
            ->assertRedirect(route('journey.dashboard', ['tab' => 'slugs']));
        $submission->refresh();
        $this->assertNotNull($submission->access_slug);
        $this->get(route('journey.access', $submission->access_slug))->assertOk()->assertSee('Access is not yet available')->assertDontSee($submission->reference);
        Mail::fake();
        $this->post(route('journey.dashboard.access', $submission), ['enable' => 1])
            ->assertRedirect(route('journey.dashboard', ['tab' => 'slugs']));
        $submission->refresh();
        $this->assertNotNull($submission->access_enabled_at);
        $this->get(route('journey.access', $submission->access_slug))->assertOk()->assertSee('Create your account')->assertDontSee($submission->reference);
        $this->post(route('journey.access.tac', $submission->access_slug), ['email' => $submission->email])
            ->assertRedirect(route('journey.access', $submission->access_slug));
        Mail::assertSent(CaseAccessTacMail::class, fn ($mail) => $mail->hasTo($submission->email));
        $code = Mail::sent(CaseAccessTacMail::class)->first()->code;
        $password = 'A-secure-access-password-123';
        $this->post(route('journey.access.unlock', $submission->access_slug), ['code' => '999999', 'identity_suffix' => '5678', 'password' => $password, 'password_confirmation' => $password, 'terms' => 1, 'privacy' => 1])->assertSessionHasErrors('code');
        $this->post(route('journey.access.unlock', $submission->access_slug), ['code' => $code, 'identity_suffix' => '5678', 'password' => $password, 'password_confirmation' => $password, 'terms' => 1, 'privacy' => 1])
            ->assertRedirect(route('journey.access', $submission->access_slug));
        $this->get(route('journey.access', $submission->access_slug))->assertOk()->assertSee('Private homepage')->assertSee($submission->reference)->assertSee('Close and terminate')->assertDontSee('Log out');
        $this->post(route('journey.access.terminate', $submission->access_slug))->assertRedirect(route('journey.access', $submission->access_slug));
        $this->get(route('journey.access', $submission->access_slug))->assertSee('Sign in to your private page')->assertDontSee($submission->reference);
        $this->post(route('journey.access.login', $submission->access_slug), ['email' => $submission->email, 'password' => $password])->assertRedirect(route('journey.access', $submission->access_slug));
    }

    public function test_case_tac_is_valid_for_15_minutes_and_wrong_email_gets_a_generic_response(): void
    {
        Mail::fake();
        $submission = \App\Models\JourneyVettingSubmission::create([
            'reference' => 'LD-260905-TAC123', 'access_slug' => 'case-securetestslug', 'access_enabled_at' => now(), 'full_name' => 'Jane Smith', 'email' => 'jane@example.com', 'phone' => '+60123456789', 'identity_type' => 'nric', 'identity_number' => '900101145678', 'education_level' => 'secondary', 'employment_status' => 'employed', 'preferred_language' => 'english', 'court_experience' => 'none', 'legal_document_confidence' => 3, 'agreement_status' => 'agree', 'selected_topics' => [], 'email_verified_at' => now(), 'submitted_at' => now(), 'status' => 'pending_review',
        ]);
        $this->post(route('journey.access.tac', $submission->access_slug), ['email' => 'wrong@example.com'])
            ->assertRedirect(route('journey.access', $submission->access_slug))->assertSessionHas('tac_request_received');
        $this->post(route('journey.access.tac', $submission->access_slug), ['email' => $submission->email]);
        $expiresAt = session('case_access_tac.'.$submission->id.'.expires_at');
        $this->assertEqualsWithDelta(now()->addMinutes(15)->timestamp, $expiresAt, 2);
    }

    public function test_authorised_email_can_skip_tac_but_still_requires_identity_digits(): void
    {
        Mail::fake();
        config(['app.case_access_tac_exempt_emails' => ['rajveer@gneplt.com.my']]);
        $submission = \App\Models\JourneyVettingSubmission::create([
            'reference' => 'LD-260905-OWNER1', 'access_slug' => 'case-owneraccess', 'access_enabled_at' => now(), 'full_name' => 'Rajveer', 'email' => 'rajveer@gneplt.com.my', 'phone' => '+60123456789', 'identity_type' => 'nric', 'identity_number' => '900101145678', 'education_level' => 'secondary', 'employment_status' => 'employed', 'preferred_language' => 'english', 'court_experience' => 'none', 'legal_document_confidence' => 3, 'agreement_status' => 'agree', 'selected_topics' => [], 'email_verified_at' => now(), 'submitted_at' => now(), 'status' => 'pending_review',
        ]);
        $this->post(route('journey.access.tac', $submission->access_slug), ['email' => $submission->email])
            ->assertRedirect(route('journey.access', $submission->access_slug))->assertSessionHas('access_identity_ready');
        Mail::assertNothingSent();
        $password = 'A-secure-access-password-123';
        $this->post(route('journey.access.unlock', $submission->access_slug), ['identity_suffix' => '0000', 'password' => $password, 'password_confirmation' => $password, 'terms' => 1, 'privacy' => 1])->assertSessionHasErrors('code');
        $this->post(route('journey.access.unlock', $submission->access_slug), ['identity_suffix' => '5678', 'password' => $password, 'password_confirmation' => $password, 'terms' => 1, 'privacy' => 1])
            ->assertRedirect(route('journey.access', $submission->access_slug));
        $this->get(route('journey.access', $submission->access_slug))->assertSee('Private homepage');
    }

}
