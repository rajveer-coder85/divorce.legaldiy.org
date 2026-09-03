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
            ->assertSee('Have you reached an')
            ->assertSee('From agreement to')
            ->assertSee('Children & Maintenance')
            ->assertSee('Property & Alimony')
            ->assertSee(route('journey'))
            ->assertDontSee('Do both of you want to divorce?');
    }

    public function test_the_journey_page_starts_with_the_agreement_question(): void
    {
        $this->get(route('journey'))
            ->assertOk()
            ->assertSee('Do both of you want to divorce?')
            ->assertSee('Tell us who you are.')
            ->assertSee('Enter your six-digit TAC.')
            ->assertSee('Help us tailor the guidance.')
            ->assertSee('What applies to your family?')
            ->assertSee('Check your starting details.');
    }
}
