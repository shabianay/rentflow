<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Unit;
use App\Models\Booking;
use App\Models\Invoice;

class InvoiceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->customer = User::factory()->create(['role' => 'customer']);
        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->unit = Unit::factory()->create(['status' => 'ready']);
    }

    protected function createInvoice(): Invoice
    {
        $startDate = now()->addDays(5);
        $endDate = now()->addDays(7);

        $this->actingAs($this->customer)->post('/bookings', [
            'unit_id' => $this->unit->id,
            'start_date' => $startDate->format('Y-m-d'),
            'end_date' => $endDate->format('Y-m-d'),
            'terms_accepted' => '1',
        ]);

        $booking = Booking::where('user_id', $this->customer->id)->first();
        $booking->update(['status' => 'active']);

        return Invoice::create([
            'booking_id' => $booking->id,
            'invoice_number' => 'INV-TEST-001',
            'user_id' => $this->customer->id,
            'amount' => $booking->total_amount,
            'status' => 'paid',
        ]);
    }

    public function test_guest_cannot_access_invoices(): void
    {
        $response = $this->get('/invoices');
        $response->assertRedirect('/login');
    }

    public function test_customer_can_view_own_invoices_list(): void
    {
        $this->createInvoice();

        $response = $this->actingAs($this->customer)->get('/invoices');
        $response->assertStatus(200);
        $response->assertViewIs('invoices.index');
        $response->assertSee('INV-TEST-001');
    }

    public function test_customer_cannot_view_others_invoice(): void
    {
        $invoice = $this->createInvoice();
        $other = User::factory()->create(['role' => 'customer']);

        $response = $this->actingAs($other)->get('/invoices/' . $invoice->uuid);
        $response->assertStatus(403);
    }

    public function test_customer_can_view_own_invoice_detail(): void
    {
        $invoice = $this->createInvoice();

        $response = $this->actingAs($this->customer)->get('/invoices/' . $invoice->uuid);
        $response->assertStatus(200);
        $response->assertViewIs('invoices.show');
        $response->assertSee('INV-TEST-001');
    }

    public function test_customer_can_download_own_invoice(): void
    {
        $invoice = $this->createInvoice();

        $response = $this->actingAs($this->customer)->get('/invoices/' . $invoice->uuid . '/download');
        $response->assertStatus(200);
        $this->assertStringContainsString('application/pdf', $response->headers->get('Content-Type'));
    }

    public function test_admin_can_view_all_invoices(): void
    {
        $this->createInvoice();

        $response = $this->actingAs($this->admin)->get('/admin/invoices');
        $response->assertStatus(200);
        $response->assertViewIs('admin.invoices.index');
        $response->assertSee('INV-TEST-001');
    }

    public function test_admin_can_view_invoice_detail(): void
    {
        $invoice = $this->createInvoice();

        $response = $this->actingAs($this->admin)->get('/admin/invoices/' . $invoice->uuid);
        $response->assertStatus(200);
        $response->assertViewIs('admin.invoices.show');
    }

    public function test_admin_can_download_any_invoice(): void
    {
        $invoice = $this->createInvoice();

        $response = $this->actingAs($this->admin)->get('/admin/invoices/' . $invoice->uuid . '/download');
        $response->assertStatus(200);
        $this->assertStringContainsString('application/pdf', $response->headers->get('Content-Type'));
    }

    public function test_guest_cannot_download_invoice(): void
    {
        $invoice = $this->createInvoice();
        $this->app['auth']->logout();

        $response = $this->get('/invoices/' . $invoice->uuid . '/download');
        $response->assertRedirect('/login');
    }

    public function test_invoice_list_shows_only_own_invoices(): void
    {
        $invoice = $this->createInvoice();
        $other = User::factory()->create(['role' => 'customer']);

        $response = $this->actingAs($other)->get('/invoices');
        $response->assertStatus(200);
        $response->assertDontSee('INV-TEST-001');
    }
}
