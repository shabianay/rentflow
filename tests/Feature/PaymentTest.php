<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Unit;
use App\Models\Booking;
use App\Models\Payment;
use App\Services\MidtransService;
use Carbon\Carbon;

class PaymentTest extends TestCase
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

    protected function createBooking(): Booking
    {
        $startDate = Carbon::today()->addDays(5);
        $endDate = Carbon::today()->addDays(6);

        $this->actingAs($this->customer)->post('/bookings', [
            'unit_id' => $this->unit->id,
            'start_date' => $startDate->format('Y-m-d'),
            'end_date' => $endDate->format('Y-m-d'),
            'terms_accepted' => '1',
        ]);

        return Booking::where('user_id', $this->customer->id)->first();
    }

    public function test_guest_cannot_access_payment_page(): void
    {
        $booking = $this->createBooking();
        $this->app['auth']->logout();
        $response = $this->get('/bookings/' . $booking->uuid . '/payment');
        $response->assertRedirect('/login');
    }

    public function test_customer_can_view_payment_page(): void
    {
        $booking = $this->createBooking();
        $response = $this->actingAs($this->customer)->get('/bookings/' . $booking->uuid . '/payment');
        $response->assertStatus(200);
        $response->assertViewIs('payments.show');
    }

    public function test_customer_cannot_view_others_payment(): void
    {
        $booking = $this->createBooking();
        $other = User::factory()->create(['role' => 'customer']);
        $response = $this->actingAs($other)->get('/bookings/' . $booking->uuid . '/payment');
        $response->assertStatus(403);
    }

    public function test_process_returns_existing_snap_token(): void
    {
        $booking = $this->createBooking();
        $payment = $booking->payment;
        $payment->update([
            'snap_token' => 'existing-token',
            'snap_redirect_url' => 'https://example.com',
        ]);

        $this->actingAs($this->customer)->postJson('/bookings/' . $booking->uuid . '/payment', [])
            ->assertOk()
            ->assertJson([
                'snap_token' => 'existing-token',
                'snap_redirect_url' => 'https://example.com',
            ]);
    }

    public function test_process_returns_error_if_paid(): void
    {
        $booking = $this->createBooking();
        $payment = $booking->payment;
        $payment->update(['status' => 'paid']);

        $this->actingAs($this->customer)->postJson('/bookings/' . $booking->uuid . '/payment', [])
            ->assertStatus(400)
            ->assertJson(['error' => 'Pembayaran sudah lunas.']);
    }

    public function test_confirm_payment_updates_statuses(): void
    {
        $booking = $this->createBooking();
        $payment = $booking->payment;
        $payment->update(['snap_order_id' => 'test-order-123']);

        $this->actingAs($this->customer)
            ->postJson('/bookings/' . $booking->uuid . '/payment/confirm', [
                'order_id' => 'test-order-123',
                'transaction_status' => 'settlement',
                'transaction_id' => 'trx-001',
                'payment_type' => 'qris',
            ])
            ->assertOk();

        $this->assertDatabaseHas('payments', [
            'id' => $payment->id,
            'status' => 'paid',
        ]);

        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'status' => 'active',
        ]);

        $this->assertDatabaseHas('units', [
            'id' => $this->unit->id,
            'status' => 'on_rent',
        ]);

        $this->assertDatabaseHas('invoices', [
            'booking_id' => $booking->id,
        ]);
    }

    public function test_confirm_payment_ownership(): void
    {
        $booking = $this->createBooking();
        $other = User::factory()->create(['role' => 'customer']);

        $this->actingAs($other)
            ->postJson('/bookings/' . $booking->uuid . '/payment/confirm')
            ->assertStatus(403);
    }

    public function test_check_status_returns_payment_data(): void
    {
        $booking = $this->createBooking();
        $payment = $booking->payment;
        $payment->update(['snap_order_id' => 'test-order-123']);

        $this->actingAs($this->customer)
            ->getJson('/bookings/' . $booking->uuid . '/payment/status')
            ->assertOk()
            ->assertJson([
                'success' => true,
                'status' => 'pending',
            ]);
    }

    public function test_admin_can_view_payments_index(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->createBooking();

        $response = $this->actingAs($admin)->get('/admin/payments');
        $response->assertStatus(200);
        $response->assertViewIs('admin.payments.index');
    }

    public function test_admin_can_view_payment_detail(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $booking = $this->createBooking();
        $payment = $booking->payment;

        $response = $this->actingAs($admin)->get('/admin/payments/' . $payment->uuid);
        $response->assertStatus(200);
        $response->assertViewIs('admin.payments.show');
    }

    public function test_notification_handles_get_request(): void
    {
        $response = $this->get('/payments/notification');
        $response->assertStatus(200);
    }
}
