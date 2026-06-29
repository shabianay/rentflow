<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Notification;

class NotificationControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->customer = User::factory()->create(['role' => 'customer']);
    }

    public function test_guest_unread_count_returns_zero(): void
    {
        $response = $this->getJson('/notifications/unread-count');
        $response->assertOk();
        $response->assertJson(['count' => 0]);
    }

    public function test_guest_latest_returns_empty(): void
    {
        $response = $this->getJson('/notifications/latest');
        $response->assertOk();
        $response->assertJson([]);
    }

    public function test_admin_can_view_notifications_index(): void
    {
        Notification::create([
            'user_id' => $this->admin->id,
            'type' => 'booking',
            'title' => 'Test Notif',
            'message' => 'Test message',
        ]);

        $response = $this->actingAs($this->admin)->get('/admin/notifications');
        $response->assertStatus(200);
        $response->assertViewIs('notifications.index');
        $response->assertSee('Test Notif');
    }

    public function test_admin_can_mark_notification_as_read(): void
    {
        $notif = Notification::create([
            'user_id' => $this->admin->id,
            'type' => 'booking',
            'title' => 'Test',
        ]);

        $this->actingAs($this->admin)
            ->postJson('/admin/notifications/' . $notif->id . '/read')
            ->assertOk()
            ->assertJson(['status' => 'ok']);

        $this->assertTrue($notif->fresh()->is_read);
    }

    public function test_cannot_mark_others_notification_as_read(): void
    {
        $notif = Notification::create([
            'user_id' => $this->admin->id,
            'type' => 'booking',
            'title' => 'Test',
        ]);

        $otherAdmin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($otherAdmin)
            ->postJson('/admin/notifications/' . $notif->id . '/read')
            ->assertStatus(403);
    }

    public function test_admin_can_mark_all_as_read(): void
    {
        Notification::create(['user_id' => $this->admin->id, 'type' => 'booking', 'title' => 'A']);
        Notification::create(['user_id' => $this->admin->id, 'type' => 'payment', 'title' => 'B']);

        $this->actingAs($this->admin)
            ->post('/admin/notifications/read-all')
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertEquals(0, Notification::where('user_id', $this->admin->id)->unread()->count());
    }

    public function test_unread_count_returns_correct_number(): void
    {
        Notification::create(['user_id' => $this->admin->id, 'type' => 'booking', 'title' => 'A', 'is_read' => false]);
        Notification::create(['user_id' => $this->admin->id, 'type' => 'payment', 'title' => 'B', 'is_read' => false]);
        Notification::create(['user_id' => $this->admin->id, 'type' => 'review', 'title' => 'C', 'is_read' => true]);

        $response = $this->actingAs($this->admin)->getJson('/notifications/unread-count');
        $response->assertOk();
        $response->assertJson(['count' => 2]);
    }

    public function test_latest_returns_most_recent_notifications(): void
    {
        Notification::create(['user_id' => $this->admin->id, 'type' => 'booking', 'title' => 'Old', 'created_at' => now()->subMinute(2)]);
        Notification::create(['user_id' => $this->admin->id, 'type' => 'payment', 'title' => 'Recent', 'created_at' => now()]);

        $response = $this->actingAs($this->admin)->getJson('/notifications/latest');
        $response->assertOk();
        $this->assertCount(2, $response->json());
        $titles = array_column($response->json(), 'title');
        $this->assertContains('Old', $titles);
        $this->assertContains('Recent', $titles);
    }

    public function test_customer_cannot_access_admin_notifications(): void
    {
        $response = $this->actingAs($this->customer)->get('/admin/notifications');
        $response->assertStatus(403);
    }
}
