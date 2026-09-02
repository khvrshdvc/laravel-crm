<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class NotificationController extends Controller
{
    public function index(): View
    {
        // Fetch paginated notifications for the currently authenticated user
        $notifications = auth()->user()->notifications()->paginate(15);

        return view('notifications.index', compact('notifications'));
    }

    public function show(string $id): RedirectResponse
    {
        // Find the notification belonging to the logged-in user or throw 404
        $notification = auth()->user()->notifications()->findOrFail($id);

        // Update read_at timestamp if the notification is unread
        if (is_null($notification->read_at)) {
            $notification->markAsRead();
        }

        // Extract redirect URL from the payload
        $url = $notification->data['url'] ?? null;

        // Redirect to target model page if URL exists, otherwise go back
        if ($url) {
            return redirect($url);
        }

        return back();
    }

    public function markAllAsRead(): RedirectResponse
    {
        // Mass update all unread notifications to set read_at timestamp
        auth()->user()->unreadNotifications->markAsRead();

        return back()->with('status', 'All notifications marked as read.');
    }
}
