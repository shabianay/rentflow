<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Unit;
use App\Models\Booking;
use Carbon\Carbon;

class ReportTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->customer = User::factory()->create(['role' => 'customer']);
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

        $booking = Booking::where('user_id', $this->customer->id)->first();
        $booking->update(['status' => 'active']);

        return $booking;
    }

    public function test_guest_cannot_access_reports(): void
    {
        $response = $this->get('/admin/reports');
        $response->assertRedirect('/login');
    }

    public function test_customer_cannot_access_reports(): void
    {
        $response = $this->actingAs($this->customer)->get('/admin/reports');
        $response->assertStatus(403);
    }

    public function test_admin_can_view_reports(): void
    {
        $response = $this->actingAs($this->admin)->get('/admin/reports');
        $response->assertStatus(200);
        $response->assertViewIs('admin.reports.index');
    }

    public function test_reports_shows_revenue_stats(): void
    {
        $this->createBooking();

        $response = $this->actingAs($this->admin)->get('/admin/reports');
        $response->assertStatus(200);
        $response->assertSee('Total Pendapatan');
    }

    public function test_reports_with_period_filter_today(): void
    {
        $this->createBooking();

        $response = $this->actingAs($this->admin)->get('/admin/reports?period=today');
        $response->assertStatus(200);
        $response->assertViewIs('admin.reports.index');
    }

    public function test_reports_with_period_filter_week(): void
    {
        $response = $this->actingAs($this->admin)->get('/admin/reports?period=week');
        $response->assertStatus(200);
    }

    public function test_reports_with_period_filter_month(): void
    {
        $response = $this->actingAs($this->admin)->get('/admin/reports?period=month');
        $response->assertStatus(200);
    }

    public function test_reports_with_period_filter_year(): void
    {
        $response = $this->actingAs($this->admin)->get('/admin/reports?period=year');
        $response->assertStatus(200);
    }

    public function test_admin_can_export_csv(): void
    {
        $this->createBooking();

        $response = $this->actingAs($this->admin)->get('/admin/reports/export');
        $response->assertStatus(200);
        $this->assertStringContainsString('text/csv', $response->headers->get('Content-Type'));
        $this->assertStringContainsString('attachment', $response->headers->get('Content-Disposition'));
    }

    public function test_export_with_period_filter(): void
    {
        $response = $this->actingAs($this->admin)->get('/admin/reports/export?period=year');
        $response->assertStatus(200);
        $this->assertStringContainsString('text/csv', $response->headers->get('Content-Type'));
    }

    public function test_customer_cannot_export_reports(): void
    {
        $response = $this->actingAs($this->customer)->get('/admin/reports/export');
        $response->assertStatus(403);
    }
}
