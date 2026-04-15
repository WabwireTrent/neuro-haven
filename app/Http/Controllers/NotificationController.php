<?php

namespace App\Http\Controllers;

use App\Models\SystemNotification;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class NotificationController extends Controller
{
    /**
     * Get all notifications for the authenticated user
     */
    public function index()
    {
        $notifications = auth()->user()
            ->notifications()
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('notifications.index', compact('notifications'));
    }

    /**
     * Get unread notifications count
     */
    public function unreadCount(): JsonResponse
    {
        $count = auth()->user()->unreadNotifications()->count();
        return response()->json(['unread_count' => $count]);
    }

    /**
     * Get recent unread notifications (for dashboard widget)
     */
    public function recent(): JsonResponse
    {
        $notifications = auth()->user()
            ->unreadNotifications()
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return response()->json($notifications);
    }

    /**
     * Mark a notification as read
     */
    public function markAsRead(SystemNotification $notification)
    {
        // Authorization check
        if ($notification->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $notification->markAsRead();

        return response()->json(['success' => true]);
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead()
    {
        auth()->user()->unreadNotifications()->update(['read_at' => now()]);

        return response()->json(['success' => true]);
    }

    /**
     * Delete a notification
     */
    public function destroy(SystemNotification $notification)
    {
        // Authorization check
        if ($notification->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $notification->delete();

        return response()->json(['success' => true]);
    }

    /**
     * Delete all read notifications
     */
    public function deleteAllRead()
    {
        auth()->user()->notifications()->whereNotNull('read_at')->delete();

        return response()->json(['success' => true]);
    }
}
