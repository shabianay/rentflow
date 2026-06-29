<?php
namespace App\Services;

use App\Models\Notification;
use App\Models\User;

class NotificationService
{
    public static function send(User $user, string $type, string $title, ?string $message = null, ?string $url = null): Notification
    {
        return Notification::create([
            'user_id' => $user->id,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'url' => $url,
        ]);
    }

    public static function sendToAdmins(string $type, string $title, ?string $message = null, ?string $url = null): void
    {
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            self::send($admin, $type, $title, $message, $url);
        }
    }

    public static function markAsRead(Notification $notification): void
    {
        $notification->update(['is_read' => true]);
    }

    public static function markAllAsRead(User $user): void
    {
        Notification::where('user_id', $user->id)->unread()->update(['is_read' => true]);
    }
}
