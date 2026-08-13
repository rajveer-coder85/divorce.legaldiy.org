<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_the_application_redirects_to_the_joint_petition_centre(): void
    {
        $response = $this->get('/');

        $response->assertRedirect('/joint-divorce');
        $response->assertStatus(301);
    }
}
