<?php

namespace Tests\Feature;

use Tests\TestCase;

class JointDivorceKnowledgeCentreTest extends TestCase
{
    private array $pages = [
        '/joint-divorce' => 'Thinking About Divorcing Together?',
        '/joint-divorce/suitability' => 'Can We Divorce Together?',
        '/joint-divorce/joint-petition' => 'What Is a Joint Petition Divorce?',
        '/joint-divorce/decisions' => 'What Do We Need to Agree On?',
        '/joint-divorce/children' => 'Children: What Do We Need to Decide Together?',
        '/joint-divorce/maintenance' => 'Maintenance: What Do We Need to Decide Together?',
        '/joint-divorce/property' => 'Property: What Do We Need to Decide Together?',
        '/joint-divorce/agreement' => 'Do You Really Have an Agreement?',
        '/joint-divorce/readiness' => 'Are We Ready to Proceed Together?',
        '/joint-divorce/process' => 'How Does a Joint Petition Divorce Work?',
        '/joint-divorce/court' => 'What Happens at Court in a Joint Petition Divorce?',
        '/joint-divorce/finalisation' => 'When Is Our Divorce Final?',
        '/joint-divorce/not-suitable' => 'When Joint Petition May Not Be Suitable',
    ];

    public function test_all_master_public_pages_are_published(): void
    {
        foreach ($this->pages as $path => $heading) {
            $this->get($path)
                ->assertOk()
                ->assertSee($heading)
                ->assertSee('"@context":"https://schema.org"', false)
                ->assertSee('role="progressbar"', false)
                ->assertSee('/images/legaldiy-mark.png', false)
                ->assertSee('<meta name="theme-color" content="#12395d">', false)
                ->assertSee('Important Information');
        }
    }

    public function test_master_learning_journey_and_public_boundary_are_present(): void
    {
        $this->get('/joint-divorce')
            ->assertSee('Do Both of You Want to Divorce?')
            ->assertSee('Your Joint Petition Learning Journey')
            ->assertSee('/joint-divorce/suitability', false)
            ->assertSee('/joint-divorce/not-suitable', false);

        $this->get('/joint-divorce/readiness')
            ->assertSee('Start the Assessment')
            ->assertSee('/assessment', false)
            ->assertSee('does not mean that LegalDIY has determined your legal eligibility');

        $this->get('/joint-divorce/not-suitable')
            ->assertSee('This Does Not Mean You Cannot Get Divorced')
            ->assertSee('independent advice from a qualified legal professional');
    }

    public function test_finalisation_uses_verified_current_official_terms_and_sources(): void
    {
        $this->get('/joint-divorce/finalisation')
            ->assertSee('Provisional Court Order: Decree Nisi')
            ->assertSee('after three months')
            ->assertSee('Decree Nisi Absolute')
            ->assertSee('Updating Divorce Information With JPN')
            ->assertSee('https://www.malaysia.gov.my/en/topics/prosedur-perceraian-pasangan-bukan-islam', false)
            ->assertSee('https://www.jpn.gov.my/en/core-business/marriage/divorce', false);
    }

    public function test_temporary_joint_petition_urls_redirect_to_master_routes(): void
    {
        $redirects = [
            '/joint-petition' => '/joint-divorce',
            '/joint-petition/readiness' => '/joint-divorce/readiness',
            '/joint-petition/what-we-need-to-agree' => '/joint-divorce/decisions',
            '/joint-petition/not-suitable' => '/joint-divorce/not-suitable',
            '/joint-petition/why-agreement-matters' => '/joint-divorce/agreement',
        ];

        foreach ($redirects as $from => $to) {
            $this->get($from)->assertStatus(301)->assertRedirect($to);
        }
    }

    public function test_removed_general_divorce_content_remains_unpublished(): void
    {
        foreach (['/children', '/maintenance', '/property', '/documents', '/court-process', '/questions', '/resources', '/contested-divorce', '/divorce-process'] as $path) {
            $this->get($path)->assertNotFound();
        }
    }

    public function test_assessment_entry_is_retained(): void
    {
        $this->get('/assessment')
            ->assertOk()
            ->assertSee('Divorce Suitability Assessment')
            ->assertSee('does not create a solicitor-client relationship');
    }

    public function test_unknown_joint_divorce_page_returns_not_found(): void
    {
        $this->get('/joint-divorce/not-an-approved-page')->assertNotFound();
    }
}
