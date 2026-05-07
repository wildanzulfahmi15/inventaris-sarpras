<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
class NotificationController extends Controller
{
    public function markAllRead()
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        $user->unreadNotifications()
             ->update(['read_at' => now()]);

        return response()->json(['ok' => true]);
    }

    public function markRead($id)
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        $user->notifications()
             ->where('id', $id)
             ->update(['read_at' => now()]);

        return response()->json(['ok' => true]);
    }
}