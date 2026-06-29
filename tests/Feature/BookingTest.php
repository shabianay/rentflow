<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Unit;
use App\Models\Payment;
use Carbon\Carbon;

class BookingTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->customer = User::factory()->create(['role' => 'customer']);
        $this->unit = Unit::factory()->create([
            'price_per_day' => 100000,
            'deposit_amount' => 50000,
            'status' => 'ready',
        ]);
    }

    public function test_customer_can_create_booking(): void
    {
        $startDate = Carbon::today()->addDays(5);
        $endDate = Carbon::today()->addDays(7); // 2 days booking

        $response = $this->actingAs($this->customer)->post('/bookings', [
            'unit_id' => $this->unit->id,
            'start_date' => $startDate->format('Y-m-d'),
            'end_date' => $endDate->format('Y-m-d'),
            'terms_accepted' => '1',
        ]);

        $response->assertStatus(302);
        $this->assertStringContainsString('/payment', $response->headers->get('Location'));
        $booking = \App\Models\Booking::where('user_id', $this->customer->id)->first();
        $this->assertNotNull($booking);
        $this->assertEquals($this->unit->id, $booking->unit_id);
        $this->assertEquals($startDate->format('Y-m-d'), $booking->start_date->format('Y-m-d'));
        $this->assertEquals($endDate->format('Y-m-d'), $booking->end_date->format('Y-m-d'));
        $this->assertEquals(350000, $booking->total_amount); // 3 days * 100000 + 50000 deposit
        $this->assertEquals('pending', $booking->status);
        $this->assertDatabaseHas('units', [
            'id' => $this->unit->id,
            'status' => 'booked',
        ]);
    }

    public function test_booking_requires_authentication(): void
    {
        $startDate = Carbon::today()->addDays(5);
        $endDate = Carbon::today()->addDays(7);

        $response = $this->post('/bookings', [
            'unit_id' => $this->unit->id,
            'start_date' => $startDate->format('Y-m-d'),
            'end_date' => $endDate->format('Y-m-d'),
            'terms_accepted' => '1',
        ]);

        $response->assertStatus(302);
        $this->assertStringContainsString('/login', $response->headers->get('Location'));
    }

    public function test_booking_creates_payment_record(): void
    {
        $startDate = Carbon::today()->addDays(5);
        $endDate = Carbon::today()->addDays(7);

        $response = $this->actingAs($this->customer)->post('/bookings', [
            'unit_id' => $this->unit->id,
            'start_date' => $startDate->format('Y-m-d'),
            'end_date' => $endDate->format('Y-m-d'),
            'terms_accepted' => '1',
        ]);

        $booking = \App\Models\Booking::where('user_id', $this->customer->id)->first();

        $this->assertNotNull($booking);
        $this->assertDatabaseHas('payments', [
            'booking_id' => $booking->id,
            'amount' => $booking->total_amount,
            'status' => 'pending',
            'snap_token' => null,
        ]);
    }
}
