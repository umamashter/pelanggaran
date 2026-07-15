<?php

namespace App\Http\Controllers;

use App\Models\RoleTwoFaRequirement;
use Illuminate\Http\Request;

class TwoFactorPolicyController extends Controller
{
    /**
     * Tampilan kebijakan 2FA per role (admin only).
     * GET /admin/kebijakan-2fa
     */
    public function index()
    {
        $policies = RoleTwoFaRequirement::orderBy('role')->get();
        $roleLabels = RoleTwoFaRequirement::roleLabels();

        return view('admin.2fa-policy.index', compact('policies', 'roleLabels'));
    }

    /**
     * Toggle kebijakan 2FA untuk role tertentu.
     * POST /admin/kebijakan-2fa/toggle/{role}
     */
    public function toggle(Request $request, int $role)
    {
        $validRoles = array_keys(RoleTwoFaRequirement::roleLabels());
        if (!in_array($role, $validRoles, true)) {
            return back()->with('error', 'Role tidak valid.');
        }

        $policy = RoleTwoFaRequirement::find($role);
        if (!$policy) {
            return back()->with('error', 'Kebijakan role tersebut tidak ditemukan.');
        }

        $policy->update(['require_2fa' => !$policy->require_2fa]);

        $label = $policy->role_label;
        $state = $policy->require_2fa ? 'WAJIB' : 'TIDAK wajib';

        return back()->with('success', "Kebijakan 2FA untuk {$label} kini: {$state}.");
    }
}