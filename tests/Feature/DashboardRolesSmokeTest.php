<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class DashboardRolesSmokeTest extends TestCase
{
    public function test_role3_dashboard_renders()
    {
        $user = User::where('role', 3)->first();
        if (!$user) {
            $this->markTestSkipped('no role 3 user in DB');
        }

        $response = $this->actingAs($user)
            ->withSession(['2fa_passed' => true, 'require_2fa_warned' => true])
            ->get('/home');

        $response->assertStatus(200);
    }

    public function test_role5_dashboard_renders()
    {
        $user = User::where('role', 5)->first();
        if (!$user) {
            $this->markTestSkipped('no role 5 user in DB');
        }

        $response = $this->actingAs($user)
            ->withSession(['2fa_passed' => true, 'require_2fa_warned' => true])
            ->get('/home');

        $response->assertStatus(200);
    }
}
