<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserNotificationController extends Controller
{


    public function index()
    {
        $notifications = Auth::user()
            ->notifications()
            ->orderByRaw('read_at IS NOT NULL')
            ->orderByDesc('created_at')
            ->get();

        Auth::user()->unreadNotifications->markAsRead();

        return view('auth.notifications.index', compact('notifications'));
    }

    public function markRead($id)
    {
        $notification = Auth::user()->notifications()->find($id);

        if ($notification) {
            $notification->markAsRead();
            $link = $notification->data['link'] ?? null;
            if ($link) {
                return redirect($link);
            }
        }

        return redirect()->route('notifications.index');
    }

    public function markAllRead()
    {
        Auth::user()->unreadNotifications->markAsRead();
        return back();
    }

    public function delete($notificationId)
    {
        $notification = Auth::user()->notifications()->find($notificationId);

        if ($notification) {
            $notification->delete();
        }

        return back();
    }
}
