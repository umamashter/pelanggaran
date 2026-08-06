<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class DashboardAdminSmokeTest extends TestCase
{
    public function test_admin_dashboard_renders_with_real_data()
    {
        $admin = User::where('role', 1)->first();
        if (!$admin) {
            $this->markTestSkipped('no admin user (role 1) in DB');
        }

        $response = $this->actingAs($admin)
            ->withSession(['2fa_passed' => true, 'require_2fa_warned' => true])
            ->get('/home');

        $response->assertStatus(200);
        $response->assertSee('dash-dashboard', false);
        $response->assertSee('abs-mod', false);
    }
}
