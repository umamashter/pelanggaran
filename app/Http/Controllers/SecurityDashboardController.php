<?php

namespace App\Http\Controllers;

use App\Models\LoginHistory;
use App\Models\DeviceFingerprint;
use App\Models\User;
use App\Models\RoleTwoFaRequirement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SecurityDashboardController extends Controller
{
    /**
     * Dashboard keamanan enterprise (admin only).
     * GET /admin/keamanan
     */
    public function index(Request $request)
    {
        $today = now()->startOfDay();

        // --- Summary cards ---
        $stats = [
            'logins_today' => LoginHistory::where('login_status', 'success')
                ->where('login_at', '>=', $today)->count(),

            'failed_today' => LoginHistory::where('login_status', 'failed')
                ->where('login_at', '>=', $today)->count(),

            'active_sessions' => DB::table('sessions')->whereNotNull('user_id')->count(),

            'two_fa_users' => User::whereNotNull('google2fa_secret')->count(),

            'total_users' => User::count(),

            'new_devices_today' => LoginHistory::where('is_new_device', true)
                ->where('login_at', '>=', $today)->count(),

            'new_ips_today' => LoginHistory::where('is_new_ip', true)
                ->where('login_at', '>=', $today)->count(),

            'fingerprints' => DeviceFingerprint::count(),
        ];

        // --- Charts: login success/fail 7 hari terakhir ---
        $days = collect();
        for ($i = 6; $i >= 0; $i--) {
            $day = now()->subDays($i)->startOfDay();
            $end = $day->copy()->endOfDay();

            $success = LoginHistory::where('login_status', 'success')
                ->whereBetween('login_at', [$day, $end])->count();
            $failed = LoginHistory::where('login_status', 'failed')
                ->whereBetween('login_at', [$day, $end])->count();

            $days->push([
                'label'  => $day->format('D'),
                'date'   => $day->format('d M'),
                'success'=> $success,
                'failed' => $failed,
            ]);
        }

        // --- Activity feed: login terbaru (paginated + filterable) ---
        $feedQuery = LoginHistory::with('user')->latest('login_at');

        if ($request->filled('status') && in_array($request->status, ['success', 'failed', 'throttled'])) {
            $feedQuery->where('login_status', $request->status);
        }

        if ($search = trim($request->search ?? '')) {
            $feedQuery->whereHas('user', function ($u) use ($search) {
                $u->where('name', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $perPage = (int) $request->input('per_page', 10);
        if (!in_array($perPage, [10, 15, 25, 50, 100], true)) {
            $perPage = 10;
        }

        $recentLogins = $feedQuery->paginate($perPage)->withQueryString();

        $twoFaPct = $stats['total_users'] > 0
            ? round(($stats['two_fa_users'] / $stats['total_users']) * 100, 1)
            : 0;

        return view('admin.security-dashboard.index', compact('stats', 'days', 'recentLogins', 'twoFaPct', 'perPage'));
    }
}