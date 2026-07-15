<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Tandai satu notifikasi sebagai dibaca.
     * POST /notifikasi/{id}/read
     */
    public function markRead(Request $request, string $id)
    {
        $user = $request->user();
        if (!$user) return back();

        $user->notifications()->where('id', $id)->update(['read_at' => now()]);

        return back();
    }

    /**
     * Tandai semua notifikasi sebagai dibaca.
     * POST /notifikasi/read-all
     */
    public function markAllRead(Request $request)
    {
        $user = $request->user();
        if (!$user) return back();

        $user->unreadNotifications->markAsRead();

        return back()->with('success', 'Semua notifikasi ditandai dibaca.');
    }
}