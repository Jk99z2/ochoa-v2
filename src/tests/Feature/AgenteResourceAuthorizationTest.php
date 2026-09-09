<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AgenteResourceAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_non_admin_cannot_view_agentes_list(): void
    {
        $user = User::factory()->create(['is_admin' => false]);

        $response = $this->actingAs($user)->get('/admin/agentes');

        $response->assertForbidden();
    }

    public function test_non_admin_cannot_view_agente_create_page(): void
    {
        $user = User::factory()->create(['is_admin' => false]);

        $response = $this->actingAs($user)->get('/admin/agentes/create');

        $response->assertForbidden();
    }

    public function test_admin_can_view_agentes_list(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $response = $this->actingAs($admin)->get('/admin/agentes');

        $response->assertOk();
    }

    public function test_admin_can_view_agente_create_page(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $response = $this->actingAs($admin)->get('/admin/agentes/create');

        $response->assertOk();
    }
}
