<?php

namespace App\Http\Controllers;

use App\Services\ActiveSessionService;
use Illuminate\Http\Request;

class ActiveSessionController extends Controller
{
    private ActiveSessionService $sessions;

    public function __construct(ActiveSessionService $sessions)
    {
        $this->sessions = $sessions;
    }

    /**
     * Daftar perangkat yang sedang login (user yang sedang login).
     * GET /perangkat
     */
    public function index()
    {
        $data = $this->sessions->listForUser(auth()->id());

        return view('active-sessions.index', [
            'sessions'           => $data['list'],
            'currentSessionId'   => $data['current_session_id'],
        ]);
    }

    /**
     * Logout satu perangkat berdasarkan session ID.
     * POST /perangkat/revoke/{sessionId}
     */
    public function revoke(Request $request, string $sessionId)
    {
        $ok = $this->sessions->revoke(auth()->id(), $sessionId);

        if ($ok) {
            return back()->with('success', 'Perangkat berhasil dilakukan logout.');
        }

        return back()->with('error', 'Tidak dapat logout perangkat ini. Mungkin itu perangkat Anda saat ini, atau sesi sudah tidak ada.');
    }

    /**
     * Logout semua perangkat lain kecuali yang saat ini.
     * POST /perangkat/revoke-others
     */
    public function revokeOthers(Request $request)
    {
        $count = $this->sessions->revokeOthers(auth()->id());

        return back()->with('success', $count . ' perangkat lain berhasil dilakukan logout.');
    }

    /**
     * Tandai perangkat (device_fingerprint) sebagai trusted.
     * POST /perangkat/trust/{fingerprintId}
     */
    public function trust(Request $request, int $fingerprintId)
    {
        $ok = $this->sessions->trustDevice(auth()->id(), $fingerprintId);

        return back()->with($ok ? 'success' : 'error',
            $ok ? 'Perangkat ditandai sebagai tepercaya.' : 'Perangkat tidak dikenali.');
    }

    /**
     * Cabut status trusted.
     * POST /perangkat/untrust/{fingerprintId}
     */
    public function untrust(Request $request, int $fingerprintId)
    {
        $ok = $this->sessions->untrustDevice(auth()->id(), $fingerprintId);

        return back()->with($ok ? 'success' : 'error',
            $ok ? 'Status tepercaya dicabut.' : 'Perangkat tidak dikenali.');
    }
}