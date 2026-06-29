<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Notification;
use App\Services\NotificationService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class NotificationServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_send_creates_notification(): void
    {
        $user = User::factory()->create(['role' => 'admin']);

        $notification = NotificationService::send(
            $user,
            'booking',
            'Booking Baru',
            'Ada booking baru.',
            'http://example.com'
        );

        $this->assertInstanceOf(Notification::class, $notification);
        $this->assertEquals($user->id, $notification->user_id);
        $this->assertEquals('booking', $notification->type);
        $this->assertEquals('Booking Baru', $notification->title);
        $this->assertEquals('Ada booking baru.', $notification->message);
        $this->assertEquals('http://example.com', $notification->url);
        $this->assertFalse($notification->fresh()->is_read);
    }

    public function test_send_to_admins_creates_notification_for_each_admin(): void
    {
        $admin1 = User::factory()->create(['role' => 'admin']);
        $admin2 = User::factory()->create(['role' => 'admin']);
        $customer = User::factory()->create(['role' => 'customer']);

        NotificationService::sendToAdmins(
            'review',
            'Review Baru',
            'Customer memberikan review.',
            'http://example.com/reviews'
        );

        $this->assertEquals(2, Notification::count());

        $this->assertDatabaseHas('notifications', [
            'user_id' => $admin1->id,
            'type' => 'review',
            'title' => 'Review Baru',
        ]);

        $this->assertDatabaseHas('notifications', [
            'user_id' => $admin2->id,
            'type' => 'review',
            'title' => 'Review Baru',
        ]);

        $this->assertDatabaseMissing('notifications', [
            'user_id' => $customer->id,
        ]);
    }

    public function test_mark_as_read(): void
    {
        $user = User::factory()->create(['role' => 'admin']);
        $notification = NotificationService::send($user, 'booking', 'Test');

        $this->assertFalse($notification->fresh()->is_read);

        NotificationService::markAsRead($notification);

        $this->assertTrue($notification->fresh()->is_read);
    }

    public function test_mark_all_as_read(): void
    {
        $user = User::factory()->create(['role' => 'admin']);
        NotificationService::send($user, 'booking', 'Test 1');
        NotificationService::send($user, 'booking', 'Test 2');
        NotificationService::send($user, 'booking', 'Test 3');

        $this->assertEquals(3, Notification::where('user_id', $user->id)->unread()->count());

        NotificationService::markAllAsRead($user);

        $this->assertEquals(0, Notification::where('user_id', $user->id)->unread()->count());
    }

    public function test_mark_all_as_read_only_affects_given_user(): void
    {
        $user1 = User::factory()->create(['role' => 'admin']);
        $user2 = User::factory()->create(['role' => 'admin']);
        NotificationService::send($user1, 'booking', 'Test');
        NotificationService::send($user2, 'booking', 'Test');

        NotificationService::markAllAsRead($user1);

        $this->assertEquals(0, Notification::where('user_id', $user1->id)->unread()->count());
        $this->assertEquals(1, Notification::where('user_id', $user2->id)->unread()->count());
    }
}
