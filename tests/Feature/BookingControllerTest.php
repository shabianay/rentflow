<?php

namespace Tests\Feature;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Unit;
use App\Models\Booking;
use Carbon\Carbon;

class BookingControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->customer = User::factory()->create(['role' => 'customer']);
        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->unit = Unit::factory()->create([
            'price_per_day' => 100000,
            'deposit_amount' => 50000,
            'status' => 'ready',
        ]);
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

    public function test_customer_can_view_own_bookings_list(): void
    {
        $this->createBooking();

        $response = $this->actingAs($this->customer)->get('/bookings');
        $response->assertStatus(200);
        $response->assertViewIs('bookings.index');
    }

    public function test_guest_cannot_view_bookings_list(): void
    {
        $this->app['auth']->logout();
        $response = $this->get('/bookings');
        $response->assertRedirect('/login');
    }

    public function test_customer_can_view_own_booking_detail(): void
    {
        $booking = $this->createBooking();

        $response = $this->actingAs($this->customer)->get('/bookings/' . $booking->uuid);
        $response->assertStatus(200);
        $response->assertViewIs('bookings.show');
    }

    public function test_customer_cannot_view_others_booking(): void
    {
        $booking = $this->createBooking();
        $other = User::factory()->create(['role' => 'customer']);

        $response = $this->actingAs($other)->get('/bookings/' . $booking->uuid);
        $response->assertStatus(403);
    }

    public function test_admin_can_view_all_bookings(): void
    {
        $this->createBooking();

        $response = $this->actingAs($this->admin)->get('/admin/bookings');
        $response->assertStatus(200);
        $response->assertViewIs('admin.bookings.index');
    }

    public function test_admin_can_view_booking_detail(): void
    {
        $booking = $this->createBooking();

        $response = $this->actingAs($this->admin)->get('/admin/bookings/' . $booking->uuid);
        $response->assertStatus(200);
        $response->assertViewIs('admin.bookings.show');
    }

    public function test_admin_can_update_booking_status(): void
    {
        $booking = $this->createBooking();

        $response = $this->actingAs($this->admin)->patch('/admin/bookings/' . $booking->uuid . '/status', [
            'status' => 'active',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'status' => 'active',
        ]);
    }

    public function test_admin_can_cancel_booking_status(): void
    {
        $booking = $this->createBooking();

        $this->actingAs($this->admin)->patch('/admin/bookings/' . $booking->uuid . '/status', [
            'status' => 'cancelled',
        ]);

        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'status' => 'cancelled',
        ]);

        $this->assertDatabaseHas('units', [
            'id' => $this->unit->id,
            'status' => 'ready',
        ]);
    }

    public function test_customer_can_cancel_own_pending_booking(): void
    {
        $booking = $this->createBooking();

        $response = $this->actingAs($this->customer)->post('/bookings/' . $booking->uuid . '/cancel');
        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'status' => 'cancelled',
        ]);

        $this->assertDatabaseHas('units', [
            'id' => $this->unit->id,
            'status' => 'ready',
        ]);
    }

    public function test_customer_cannot_cancel_non_pending_booking(): void
    {
        $booking = $this->createBooking();
        $booking->update(['status' => 'active']);

        $response = $this->actingAs($this->customer)->post('/bookings/' . $booking->uuid . '/cancel');
        $response->assertRedirect();
        $response->assertSessionHas('error');
    }

    public function test_customer_cannot_cancel_others_booking(): void
    {
        $booking = $this->createBooking();
        $other = User::factory()->create(['role' => 'customer']);

        $response = $this->actingAs($other)->post('/bookings/' . $booking->uuid . '/cancel');
        $response->assertStatus(403);
    }

    public function test_get_booked_dates_returns_json(): void
    {
        $this->createBooking();

        $response = $this->getJson('/units/' . $this->unit->id . '/booked-dates');
        $response->assertOk();
        $response->assertJsonIsArray();
    }

    public function test_calculate_price_returns_pricing(): void
    {
        $startDate = Carbon::today()->addDays(5);
        $endDate = Carbon::today()->addDays(7);

        $response = $this->actingAs($this->customer)->postJson('/calculate-price', [
            'unit_id' => $this->unit->id,
            'start_date' => $startDate->format('Y-m-d'),
            'end_date' => $endDate->format('Y-m-d'),
            'terms_accepted' => '1',
        ]);

        $response->assertOk();
        $response->assertJsonStructure(['subtotal', 'deposit', 'total', 'days']);
    }

    public function test_customer_can_access_create_multi_page(): void
    {
        session()->put('cart', [
            $this->unit->id => ['id' => $this->unit->id, 'name' => $this->unit->name],
        ]);

        $response = $this->actingAs($this->customer)->get('/bookings/create-multi');
        $response->assertStatus(200);
        $response->assertViewIs('bookings.create-multi');
    }

    public function test_create_multi_redirects_if_cart_empty(): void
    {
        $response = $this->actingAs($this->customer)->get('/bookings/create-multi');
        $response->assertRedirect('/catalog');
    }

    public function test_customer_can_access_create_single_page(): void
    {
        $response = $this->actingAs($this->customer)->get('/units/' . $this->unit->id . '/book');
        $response->assertStatus(200);
        $response->assertViewIs('bookings.create');
    }

    public function test_admin_can_view_calendar(): void
    {
        $response = $this->actingAs($this->admin)->get('/admin/calendar');
        $response->assertStatus(200);
        $response->assertViewIs('admin.bookings.calendar');
    }

    public function test_admin_can_navigate_calendar_months(): void
    {
        $response = $this->actingAs($this->admin)->get('/admin/calendar?month=1&year=2026');
        $response->assertStatus(200);
        $response->assertViewHas('month', 1);
        $response->assertViewHas('year', 2026);
    }

    public function test_customer_cannot_access_calendar(): void
    {
        $response = $this->actingAs($this->customer)->get('/admin/calendar');
        $response->assertStatus(403);
    }

    public function test_admin_can_access_create_booking_page(): void
    {
        Category::factory()->create();
        Unit::factory()->create(['is_active' => true, 'status' => 'ready']);

        $response = $this->actingAs($this->admin)->get('/admin/bookings/create');
        $response->assertStatus(200);
        $response->assertViewIs('admin.bookings.create');
    }

    public function test_admin_can_create_booking(): void
    {
        $category = Category::factory()->create();
        $unit = Unit::factory()->create(['is_active' => true, 'status' => 'ready', 'price_per_day' => 100000, 'category_id' => $category->id]);
        $customer = User::factory()->create(['role' => 'customer']);

        $start = Carbon::today()->addDays(5);
        $end = Carbon::today()->addDays(7);

        $response = $this->actingAs($this->admin)->post('/admin/bookings', [
            'user_id' => $customer->id,
            'unit_ids' => [$unit->id],
            'start_date' => $start->format('Y-m-d'),
            'end_date' => $end->format('Y-m-d'),
            'terms_accepted' => '1',
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('bookings', ['user_id' => $customer->id, 'status' => 'pending']);
    }

    public function test_admin_can_create_paid_booking(): void
    {
        $category = Category::factory()->create();
        $unit = Unit::factory()->create(['is_active' => true, 'status' => 'ready', 'price_per_day' => 100000, 'category_id' => $category->id]);
        $customer = User::factory()->create(['role' => 'customer']);

        $start = Carbon::today()->addDays(10);
        $end = Carbon::today()->addDays(12);

        $response = $this->actingAs($this->admin)->post('/admin/bookings', [
            'user_id' => $customer->id,
            'unit_ids' => [$unit->id],
            'start_date' => $start->format('Y-m-d'),
            'end_date' => $end->format('Y-m-d'),
            'terms_accepted' => '1',
            'mark_paid' => '1',
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('bookings', ['user_id' => $customer->id, 'status' => 'active']);
        $this->assertDatabaseHas('payments', ['status' => 'paid']);
        $this->assertDatabaseHas('invoices', ['user_id' => $customer->id, 'status' => 'paid']);
    }

    public function test_admin_create_booking_requires_customer(): void
    {
        $response = $this->actingAs($this->admin)->post('/admin/bookings', [
            'unit_ids' => [1],
            'start_date' => Carbon::today()->addDays(1)->format('Y-m-d'),
            'end_date' => Carbon::today()->addDays(3)->format('Y-m-d'),
            'terms_accepted' => '1',
        ]);

        $response->assertSessionHasErrors('user_id');
    }
}
