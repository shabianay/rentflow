<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Unit;
use App\Models\Booking;
use App\Models\Review;
use Carbon\Carbon;

class ReviewTest extends TestCase
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

    protected function createCompletedBooking(): Booking
    {
        $startDate = Carbon::today()->addDays(5);
        $endDate = Carbon::today()->addDays(7);

        $this->actingAs($this->customer)->post('/bookings', [
            'unit_id' => $this->unit->id,
            'start_date' => $startDate->format('Y-m-d'),
            'end_date' => $endDate->format('Y-m-d'),
            'terms_accepted' => '1',
        ]);

        $booking = Booking::where('user_id', $this->customer->id)->first();
        $booking->update(['status' => 'completed']);

        return $booking;
    }

    public function test_customer_can_create_review(): void
    {
        $booking = $this->createCompletedBooking();

        $response = $this->actingAs($this->customer)->post('/reviews', [
            'booking_id' => $booking->id,
            'unit_id' => $this->unit->id,
            'rating' => 5,
            'review' => 'Bagus sekali!',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('reviews', [
            'booking_id' => $booking->id,
            'unit_id' => $this->unit->id,
            'user_id' => $this->customer->id,
            'rating' => 5,
            'review' => 'Bagus sekali!',
        ]);
    }

    public function test_cannot_review_non_completed_booking(): void
    {
        $startDate = Carbon::today()->addDays(5);
        $endDate = Carbon::today()->addDays(7);

        $this->actingAs($this->customer)->post('/bookings', [
            'unit_id' => $this->unit->id,
            'start_date' => $startDate->format('Y-m-d'),
            'end_date' => $endDate->format('Y-m-d'),
            'terms_accepted' => '1',
        ]);

        $booking = Booking::where('user_id', $this->customer->id)->first();

        $response = $this->actingAs($this->customer)->post('/reviews', [
            'booking_id' => $booking->id,
            'unit_id' => $this->unit->id,
            'rating' => 4,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');
    }

    public function test_cannot_review_other_users_booking(): void
    {
        $booking = $this->createCompletedBooking();
        $other = User::factory()->create(['role' => 'customer']);

        $response = $this->actingAs($other)->post('/reviews', [
            'booking_id' => $booking->id,
            'unit_id' => $this->unit->id,
            'rating' => 3,
        ]);

        $response->assertStatus(404);
    }

    public function test_cannot_create_duplicate_review(): void
    {
        $booking = $this->createCompletedBooking();

        Review::create([
            'booking_id' => $booking->id,
            'user_id' => $this->customer->id,
            'unit_id' => $this->unit->id,
            'rating' => 4,
        ]);

        $response = $this->actingAs($this->customer)->post('/reviews', [
            'booking_id' => $booking->id,
            'unit_id' => $this->unit->id,
            'rating' => 5,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');
    }

    public function test_customer_can_update_own_review(): void
    {
        $booking = $this->createCompletedBooking();
        $review = Review::create([
            'booking_id' => $booking->id,
            'user_id' => $this->customer->id,
            'unit_id' => $this->unit->id,
            'rating' => 3,
            'review' => 'Biasa saja.',
        ]);

        $response = $this->actingAs($this->customer)->put('/reviews/' . $review->id, [
            'rating' => 5,
            'review' => 'Ternyata bagus!',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('reviews', [
            'id' => $review->id,
            'rating' => 5,
            'review' => 'Ternyata bagus!',
        ]);
    }

    public function test_cannot_update_others_review(): void
    {
        $booking = $this->createCompletedBooking();
        $review = Review::create([
            'booking_id' => $booking->id,
            'user_id' => $this->customer->id,
            'unit_id' => $this->unit->id,
            'rating' => 3,
        ]);

        $other = User::factory()->create(['role' => 'customer']);

        $response = $this->actingAs($other)->put('/reviews/' . $review->id, [
            'rating' => 5,
        ]);

        $response->assertStatus(403);
    }

    public function test_customer_can_delete_own_review(): void
    {
        $booking = $this->createCompletedBooking();
        $review = Review::create([
            'booking_id' => $booking->id,
            'user_id' => $this->customer->id,
            'unit_id' => $this->unit->id,
            'rating' => 4,
        ]);

        $response = $this->actingAs($this->customer)->delete('/reviews/' . $review->id);
        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('reviews', ['id' => $review->id]);
    }

    public function test_admin_can_view_all_reviews(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $booking = $this->createCompletedBooking();
        Review::create([
            'booking_id' => $booking->id,
            'user_id' => $this->customer->id,
            'unit_id' => $this->unit->id,
            'rating' => 4,
        ]);

        $response = $this->actingAs($admin)->get('/admin/reviews');
        $response->assertStatus(200);
        $response->assertViewIs('admin.reviews.index');
    }

    public function test_admin_can_delete_any_review(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $booking = $this->createCompletedBooking();
        $review = Review::create([
            'booking_id' => $booking->id,
            'user_id' => $this->customer->id,
            'unit_id' => $this->unit->id,
            'rating' => 4,
        ]);

        $response = $this->actingAs($admin)->delete('/admin/reviews/' . $review->id);
        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('reviews', ['id' => $review->id]);
    }

    public function test_guest_cannot_create_review(): void
    {
        $response = $this->post('/reviews', [
            'booking_id' => 1,
            'unit_id' => 1,
            'rating' => 5,
        ]);

        $response->assertRedirect('/login');
    }

    public function test_public_unit_reviews_endpoint(): void
    {
        $booking = $this->createCompletedBooking();
        Review::create([
            'booking_id' => $booking->id,
            'user_id' => $this->customer->id,
            'unit_id' => $this->unit->id,
            'rating' => 5,
            'review' => 'Keren!',
        ]);

        $response = $this->getJson('/units/' . $this->unit->id . '/reviews');
        $response->assertOk();
        $response->assertJsonCount(1);
        $response->assertJson([['rating' => 5, 'review' => 'Keren!']]);
    }
}
