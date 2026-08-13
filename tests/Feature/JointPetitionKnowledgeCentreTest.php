<?php

namespace Tests\Feature;

use Tests\TestCase;

class JointPetitionKnowledgeCentreTest extends TestCase
{
    private array $pages = [
        '/joint-petition' => 'Thinking About Divorcing Together?',
        '/joint-petition/readiness' => 'Are You Ready to Proceed Together?',
        '/joint-petition/what-we-need-to-agree' => 'What Do We Need to Agree On?',
        '/joint-petition/not-suitable' => 'When Joint Petition May Not Be Suitable',
        '/joint-petition/why-agreement-matters' => 'Agreement Means More Than Saying',
    ];

    public function test_only_the_approved_joint_petition_pages_are_published(): void
    {
        foreach ($this->pages as $path => $heading) {
            $this->get($path)
                ->assertOk()
                ->assertSee($heading)
                ->assertSee('"@context":"https://schema.org"', false)
                ->assertSee('/images/legaldiy-mark.png', false)
                ->assertSee('<meta name="theme-color" content="#12395d">', false);
        }
    }

    public function test_supplied_content_and_routing_are_present(): void
    {
        $this->get('/joint-petition')
            ->assertSee('Do Both of You Want the Divorce?')
            ->assertSee('You May Agree to Divorce')
            ->assertSee('/joint-petition/readiness', false)
            ->assertSee('/joint-petition/what-we-need-to-agree', false);

        $this->get('/joint-petition/readiness')
            ->assertSee('1. The Decision to Divorce')
            ->assertSee('6. Is the Agreement Genuine?')
            ->assertSee('Where Are You Now?');

        $this->get('/joint-petition/not-suitable')
            ->assertSee('This Does Not Mean You Cannot Get Divorced')
            ->assertSee('independent professional advice');
    }

    public function test_root_uses_the_approved_joint_petition_canonical_page(): void
    {
        $this->get('/')->assertRedirect('/joint-petition')->assertStatus(301);
    }

    public function test_removed_public_content_is_no_longer_reachable(): void
    {
        foreach (['/children', '/maintenance', '/property', '/documents', '/court-process', '/questions', '/resources', '/contested-divorce', '/divorce-process'] as $path) {
            $this->get($path)->assertNotFound();
        }
    }

    public function test_unknown_joint_petition_page_returns_not_found(): void
    {
        $this->get('/joint-petition/not-an-approved-page')->assertNotFound();
    }

    public function test_existing_assessment_entry_is_retained(): void
    {
        $this->get('/assessment')
            ->assertOk()
            ->assertSee('Divorce Suitability Assessment')
            ->assertSee('does not create a solicitor-client relationship');
    }
}
