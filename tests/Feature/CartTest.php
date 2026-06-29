<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Unit;

class CartTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->customer = User::factory()->create(['role' => 'customer']);
        $this->unit = Unit::factory()->create(['status' => 'ready']);
    }

    public function test_guest_cannot_access_cart(): void
    {
        $response = $this->get('/cart');
        $response->assertRedirect('/login');
    }

    public function test_customer_can_view_empty_cart(): void
    {
        $response = $this->actingAs($this->customer)->get('/cart');
        $response->assertStatus(200);
        $response->assertViewIs('cart.index');
    }

    public function test_customer_can_add_unit_to_cart(): void
    {
        $response = $this->actingAs($this->customer)->post('/cart/' . $this->unit->id);
        $response->assertRedirect();
        $response->assertSessionHas('success');

        $cart = session('cart');
        $this->assertArrayHasKey($this->unit->id, $cart);
        $this->assertEquals($this->unit->name, $cart[$this->unit->id]['name']);
    }

    public function test_cannot_add_duplicate_unit_to_cart(): void
    {
        $this->actingAs($this->customer)->post('/cart/' . $this->unit->id);

        $response = $this->actingAs($this->customer)->post('/cart/' . $this->unit->id);
        $response->assertRedirect();
        $response->assertSessionHas('error');
    }

    public function test_customer_can_remove_unit_from_cart(): void
    {
        $this->actingAs($this->customer)->post('/cart/' . $this->unit->id);

        $response = $this->actingAs($this->customer)->post('/cart/' . $this->unit->id . '/remove');
        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertArrayNotHasKey($this->unit->id, session('cart', []));
    }

    public function test_customer_can_clear_cart(): void
    {
        session()->put('cart', [$this->unit->id => ['id' => $this->unit->id, 'name' => $this->unit->name]]);

        $response = $this->actingAs($this->customer)->post('/cart/clear');
        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertEmpty(session('cart', []));
    }

    public function test_cart_persists_across_requests(): void
    {
        $this->actingAs($this->customer)->post('/cart/' . $this->unit->id);

        $response = $this->actingAs($this->customer)->get('/cart');
        $response->assertSee($this->unit->name);
    }
}
