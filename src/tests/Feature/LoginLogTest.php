<?php

namespace Tests\Feature;

use App\Models\LoginLog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class LoginLogTest extends TestCase
{
    use RefreshDatabase;

    public function test_logging_in_creates_a_login_log(): void
    {
        $user = User::factory()->create();

        Auth::login($user);

        $this->assertDatabaseHas("login_logs", [
            "user_id" => $user->id,
        ]);
    }

    public function test_non_admin_only_sees_their_own_login_logs(): void
    {
        $user = User::factory()->create(["is_admin" => false]);
        $otherUser = User::factory()->create(["is_admin" => false]);

        LoginLog::create(["user_id" => $user->id, "ip_address" => "1.1.1.1"]);
        LoginLog::create(["user_id" => $otherUser->id, "ip_address" => "2.2.2.2"]);

        $response = $this->actingAs($user)->get("/admin/login-logs");

        $response->assertOk();
        $response->assertSee("1.1.1.1");
        $response->assertDontSee("2.2.2.2");
    }

    public function test_admin_sees_every_users_login_logs(): void
    {
        $admin = User::factory()->create(["is_admin" => true]);
        $otherUser = User::factory()->create(["is_admin" => false]);

        LoginLog::create(["user_id" => $admin->id, "ip_address" => "1.1.1.1"]);
        LoginLog::create(["user_id" => $otherUser->id, "ip_address" => "2.2.2.2"]);

        $response = $this->actingAs($admin)->get("/admin/login-logs");

        $response->assertOk();
        $response->assertSee("1.1.1.1");
        $response->assertSee("2.2.2.2");
    }
}
