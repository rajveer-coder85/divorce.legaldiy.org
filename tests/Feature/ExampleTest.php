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
            ->assertSee('Thinking about')
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
            ->assertDontSee('Do both of you want to divorce?')
            ->assertDontSee('Agreement checkpoint');
    }

    public function test_each_knowledge_guide_has_a_dedicated_page(): void
    {
        $this->get(route('knowledge.show', 'children-maintenance'))
            ->assertOk()
            ->assertSee('Topic 1 of 6')
            ->assertSee(route('knowledge.children-maintenance.examples'))
            ->assertSee('whichever applicable event occurs later')
            ->assertSee('Tertiary education and training')
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
            'property' => 'Property',
            'alimony' => 'Alimony & spousal maintenance',
            'joint-petition' => 'Joint petition & court process',
        ] as $slug => $heading) {
            $this->get(route('knowledge.show', $slug))
                ->assertOk()
                ->assertSee($heading)
                ->assertSee('Example situation')
                ->assertSee('Explore other topics');
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
            ->assertSee('What applies to your family?')
            ->assertSee('Check your starting details.');

        $this->assertSame(6, substr_count($response->getContent(), 'data-tac-digit'));
    }
}
