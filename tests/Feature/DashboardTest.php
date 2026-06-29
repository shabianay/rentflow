<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Unit;
use App\Models\Booking;
use Carbon\Carbon;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->customer = User::factory()->create(['role' => 'customer']);
        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->unit = Unit::factory()->create(['status' => 'ready']);
    }

    protected function createBooking(): Booking
    {
        $startDate = Carbon::today()->addDays(5);
        $endDate = Carbon::today()->addDays(7);

        $this->actingAs($this->customer)->post('/bookings', [
            'unit_id' => $this->unit->id,
            'start_date' => $startDate->format('Y-m-d'),
            'end_date' => $endDate->format('Y-m-d'),
            'terms_accepted' => '1',
        ]);

        return Booking::where('user_id', $this->customer->id)->first();
    }

    public function test_guest_cannot_access_customer_dashboard(): void
    {
        $response = $this->get('/dashboard');
        $response->assertRedirect('/login');
    }

    public function test_customer_can_view_dashboard(): void
    {
        $response = $this->actingAs($this->customer)->get('/dashboard');
        $response->assertStatus(200);
        $response->assertViewIs('dashboard.customer');
    }

    public function test_customer_dashboard_shows_booking_count(): void
    {
        $this->createBooking();

        $response = $this->actingAs($this->customer)->get('/dashboard');
        $response->assertStatus(200);
        $response->assertSee('1');
    }

    public function test_admin_cannot_access_customer_dashboard(): void
    {
        $response = $this->actingAs($this->admin)->get('/dashboard');
        $response->assertStatus(403);
    }

    public function test_guest_cannot_access_admin_dashboard(): void
    {
        $response = $this->get('/admin/dashboard');
        $response->assertRedirect('/login');
    }

    public function test_admin_can_view_admin_dashboard(): void
    {
        $response = $this->actingAs($this->admin)->get('/admin/dashboard');
        $response->assertStatus(200);
        $response->assertViewIs('dashboard.index');
    }

    public function test_customer_cannot_access_admin_dashboard(): void
    {
        $response = $this->actingAs($this->customer)->get('/admin/dashboard');
        $response->assertStatus(403);
    }

    public function test_admin_dashboard_shows_stats(): void
    {
        $this->createBooking();

        $response = $this->actingAs($this->admin)->get('/admin/dashboard');
        $response->assertStatus(200);
        $response->assertSee('1');
    }

    public function test_admin_can_view_customers_list(): void
    {
        $this->actingAs($this->admin)->get('/admin/customers')
            ->assertStatus(200)
            ->assertViewIs('admin.customers.index');
    }

    public function test_admin_can_view_customer_detail(): void
    {
        $response = $this->actingAs($this->admin)->get('/admin/customers/' . $this->customer->id);
        $response->assertStatus(200);
        $response->assertViewIs('admin.customers.show');
        $response->assertSee($this->customer->name);
    }

    public function test_admin_can_update_customer(): void
    {
        $response = $this->actingAs($this->admin)->put('/admin/customers/' . $this->customer->id, [
            'name' => 'Updated Customer',
            'email' => $this->customer->email,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'id' => $this->customer->id,
            'name' => 'Updated Customer',
        ]);
    }

    public function test_admin_can_toggle_customer_status(): void
    {
        $response = $this->actingAs($this->admin)->patch('/admin/customers/' . $this->customer->id . '/toggle');
        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertEquals(0, $this->customer->fresh()->is_active);
    }

    public function test_admin_cannot_access_without_admin_role(): void
    {
        $response = $this->actingAs($this->customer)->get('/admin/customers');
        $response->assertStatus(403);
    }

    public function test_customer_cannot_access_admin_customer_detail(): void
    {
        $otherCustomer = User::factory()->create(['role' => 'customer']);
        $response = $this->actingAs($otherCustomer)->get('/admin/customers/' . $this->customer->id);
        $response->assertStatus(403);
    }
}
