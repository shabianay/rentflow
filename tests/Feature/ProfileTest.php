<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->customer = User::factory()->create(['role' => 'customer']);
    }

    public function test_guest_cannot_access_profile(): void
    {
        $response = $this->get('/profile');
        $response->assertRedirect('/login');
    }

    public function test_customer_can_view_profile_page(): void
    {
        $response = $this->actingAs($this->customer)->get('/profile');
        $response->assertStatus(200);
        $response->assertViewIs('profile.edit');
        $response->assertSee($this->customer->name);
    }

    public function test_customer_can_update_profile(): void
    {
        $response = $this->actingAs($this->customer)->put('/profile', [
            'name' => 'Updated Name',
            'email' => $this->customer->email,
            'phone' => '081234567890',
            'address' => 'New Address',
            'place_of_birth' => 'Bandung',
            'date_of_birth' => '1999-12-31',
            'id_card_number' => $this->customer->id_card_number,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'id' => $this->customer->id,
            'name' => 'Updated Name',
            'phone' => '081234567890',
            'address' => 'New Address',
        ]);
    }

    public function test_customer_can_update_password(): void
    {
        $response = $this->actingAs($this->customer)->put('/profile', [
            'name' => $this->customer->name,
            'email' => $this->customer->email,
            'current_password' => 'password',
            'new_password' => 'new-password-123',
            'new_password_confirmation' => 'new-password-123',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
    }

    public function test_password_update_fails_with_wrong_current_password(): void
    {
        $response = $this->actingAs($this->customer)->put('/profile', [
            'name' => $this->customer->name,
            'email' => $this->customer->email,
            'current_password' => 'wrong-password',
            'new_password' => 'new-password-123',
            'new_password_confirmation' => 'new-password-123',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors(['current_password']);
    }

    public function test_email_must_be_unique(): void
    {
        User::factory()->create(['email' => 'taken@example.com']);

        $response = $this->actingAs($this->customer)->put('/profile', [
            'name' => $this->customer->name,
            'email' => 'taken@example.com',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors(['email']);
    }
}
