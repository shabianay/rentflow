<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Unit;
use App\Models\Category;

class LandingTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_sees_landing_page(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertViewIs('landing');
    }

    public function test_landing_shows_ready_units(): void
    {
        $category = Category::factory()->create(['name' => 'Villa']);
        Unit::factory()->create([
            'name' => 'Villa Test',
            'is_active' => true,
            'status' => 'ready',
            'category_id' => $category->id,
        ]);

        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('Villa Test');
    }

    public function test_landing_does_not_show_inactive_units(): void
    {
        Unit::factory()->create([
            'name' => 'Hidden Unit',
            'is_active' => false,
            'status' => 'ready',
        ]);

        $response = $this->get('/');
        $response->assertDontSee('Hidden Unit');
    }

    public function test_landing_does_not_show_non_ready_units(): void
    {
        Unit::factory()->create([
            'name' => 'Booked Unit',
            'is_active' => true,
            'status' => 'booked',
        ]);

        $response = $this->get('/');
        $response->assertDontSee('Booked Unit');
    }

    public function test_authenticated_user_redirected_to_catalog(): void
    {
        $user = User::factory()->create(['role' => 'customer']);
        $response = $this->actingAs($user)->get('/');
        $response->assertRedirect('/catalog');
    }

    public function test_landing_shows_categories(): void
    {
        Category::factory()->create(['name' => 'Apartemen']);

        $response = $this->get('/');
        $response->assertSee('Apartemen');
    }

    public function test_landing_limits_units_to_four(): void
    {
        Unit::factory(5)->create(['is_active' => true, 'status' => 'ready']);

        $response = $this->get('/');
        $response->assertStatus(200);
    }

    public function test_privacy_policy_page(): void
    {
        $response = $this->get('/privacy-policy');
        $response->assertStatus(200);
    }

    public function test_terms_of_service_page(): void
    {
        $response = $this->get('/terms-of-service');
        $response->assertStatus(200);
    }
}
