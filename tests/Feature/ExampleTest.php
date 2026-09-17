<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    public function test_the_homepage_presents_the_personal_and_court_journeys(): void
    {
        $response = $this->get('/');

        $response
            ->assertOk()
            ->assertSee('Joint Petition divorce education')
            ->assertSee('Understand the journey')
            ->assertSee('Subsidised legal support')
            ->assertSee('If approved, you pay a maximum of')
            ->assertSee('engages legal counsel')
            ->assertSee('Submitting an application does not guarantee approval')
            ->assertSee('Your Personal Journey')
            ->assertSee('Your Court Journey')
            ->assertSee('From agreement to')
            ->assertSee('Children &amp; Child Maintenance', false)
            ->assertSee('Property')
            ->assertSee('Alimony')
            ->assertSee(route('knowledge.show', 'children-maintenance'))
            ->assertSee(route('knowledge.show', 'property'))
            ->assertSee(route('knowledge.show', 'alimony'))
            ->assertSee(route('knowledge.show', 'joint-petition'))
            ->assertSee(route('journey'))
            ->assertSee('Joint Petition')
            ->assertSee('Divorce Cost')
            ->assertSee('RM 2,856–RM 2,872')
            ->assertSee('RM 2,000.00')
            ->assertSee('pay a maximum of')
            ->assertSee('remaining legal fees')
            ->assertSee('Subject to eligibility')
            ->assertSee('Petisyen Perceraian Bersama')
            ->assertSee('RM160.00')
            ->assertSee('Notis Permohonan Menjadikan Decree Nisi Mutlak')
            ->assertSee('Paid progressively')
            ->assertDontSee('Click what applies to you')
            ->assertSee('qualify for subsidised legal fees')
            ->assertSee('payment-card-grid', false)
            ->assertSee('data-inquiry-form', false)
            ->assertSee('Contact Us Now')
            ->assertSee('Verify &amp; Submit', false)
            ->assertSee('Penyata Kanak-Kanak')
            ->assertDontSee('Do both of you want to divorce?')
            ->assertDontSee('Agreement checkpoint');
    }

    public function test_the_limited_contested_divorce_link_opens_a_coming_soon_page(): void
    {
        $this->get(route('contested-divorce.support'))->assertOk()->assertSee('Detailed guidance is coming soon.')->assertSee('Send an enquiry')->assertSee('data-inquiry-modal', false)->assertSee(route('inquiry.tac.send'));
    }

    public function test_each_knowledge_guide_has_a_dedicated_page(): void
    {
        $this->get(route('knowledge.show', 'children-maintenance'))
            ->assertOk()
            ->assertSee('Topic 1 of 6')
            ->assertSee(route('knowledge.children-maintenance.examples'))
            ->assertSee('whichever applicable event occurs later')
            ->assertSee('Tertiary education and training')
            ->assertSee('Sections 87 and 88(1)')
            ->assertSee('Sections 92–94')
            ->assertSee('Sections 95 and 96')
            ->assertDontSee('Sources used for this guide')
            ->assertSee('data-access-example="weekdays"', false)
            ->assertSee('data-access-example="returns"', false)
            ->assertSee('data-expense-example="accommodation"', false)
            ->assertSee('data-expense-example="other-needs"', false)
            ->assertSee('data-access-modal', false)
            ->assertDontSee('Start with the child’s welfare.')
            ->assertDontSee('Joint custody does not necessarily mean equal time.');

        $this->get(route('knowledge.children-maintenance.examples'))
            ->assertOk()
            ->assertSee('Stories from different families.')
            ->assertSee('Emily, David, and one familiar home')
            ->assertSee('Questions to take into your story');

        foreach ([
            'property' => ['Property', 'Section 76(1) and (5)'],
            'alimony' => ['Alimony & spousal maintenance', 'Sections 83 and 84'],
            'joint-petition' => ['Joint petition & court process', 'Sections 52 and 106'],
        ] as $slug => [$heading, $reference]) {
            $this->get(route('knowledge.show', $slug))
                ->assertOk()
                ->assertSee($heading)
                ->assertSee('Topic 1 of 6')
                ->assertSee($reference)
                ->assertSee(route('knowledge.examples', $slug))
                ->assertDontSee('Sources used for this guide');

            $this->get(route('knowledge.examples', $slug))
                ->assertOk()
                ->assertSee('How to use these stories')
                ->assertSee('Questions to take into your story')
                ->assertSee(route('knowledge.show', $slug));
        }
    }

    public function test_the_journey_page_starts_with_the_agreement_question(): void
    {
        $response = $this->get(route('journey'))
            ->assertOk()
            ->assertSee('Do both of you want to divorce?')
            ->assertSee('Tell us who you are.')
            ->assertSee('Enter your six-digit TAC.')
            ->assertSee('Help us tailor the guidance.')
            ->assertSee('Your approximate monthly income')
            ->assertSee('already separated')
            ->assertSee('What stage are you at?')
            ->assertSee('browser cookie')
            ->assertSee('What applies to your family?')
            ->assertSee('Check your starting details.');

        $this->assertSame(6, substr_count($response->getContent(), 'data-tac-digit'));
    }
}
