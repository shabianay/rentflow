<?php
namespace App\Http\Controllers;

use App\Models\Notification;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::where('user_id', Auth::id())
            ->latest()
            ->paginate(20);
        return view('notifications.index', compact('notifications'));
    }

    public function read(Notification $notification)
    {
        if ($notification->user_id !== Auth::id()) abort(403);
        NotificationService::markAsRead($notification);
        return response()->json(['status' => 'ok']);
    }

    public function readAll()
    {
        NotificationService::markAllAsRead(Auth::user());
        return redirect()->back()->with('success', 'Semua notifikasi ditandai sudah dibaca.');
    }

    public function unreadCount()
    {
        if (!Auth::check()) {
            return response()->json(['count' => 0]);
        }
        $count = Notification::where('user_id', Auth::id())->unread()->count();
        return response()->json(['count' => $count]);
    }

    public function latest()
    {
        if (!Auth::check()) {
            return response()->json([]);
        }
        $notifications = Notification::where('user_id', Auth::id())
            ->latest()
            ->take(5)
            ->get();
        return response()->json($notifications);
    }
}
