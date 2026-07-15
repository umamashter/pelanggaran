<?php

namespace App\Http\Controllers;

use App\Models\LoginHistory;
use Illuminate\Http\Request;

class LoginHistoryController extends Controller
{
    /**
     * Riwayat login milik user yang sedang login (semua role).
     * GET /riwayat-login
     */
    public function index(Request $request)
    {
        $histories = LoginHistory::forUser($request->user()->id)
            ->latest('login_at')
            ->paginate(15)
            ->withQueryString();

        return view('login-history.index', compact('histories'));
    }

    /**
     * Riwayat login SELURUH user (admin only).
     * GET /admin/riwayat-login
     */
    public function adminIndex(Request $request)
    {
        $query = LoginHistory::with('user')->latest('login_at');

        // Filter opsional: status login
        if ($request->filled('status') && in_array($request->status, ['success', 'failed', 'throttled'])) {
            $query->where('login_status', $request->status);
        }

        // Filter opsional: pencarian nama/username/email user
        if ($search = trim($request->search ?? '')) {
            $query->whereHas('user', function ($u) use ($search) {
                $u->where('name', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $perPage = (int) $request->input('per_page', 10);
        if (!in_array($perPage, [10, 15, 25, 50, 100], true)) {
            $perPage = 10;
        }

        $histories = $query->paginate($perPage)->withQueryString();

        return view('admin.login-history.index', compact('histories', 'perPage'));
    }
}