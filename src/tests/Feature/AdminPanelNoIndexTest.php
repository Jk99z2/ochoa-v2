<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminPanelNoIndexTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_login_page_is_noindex(): void
    {
        $response = $this->get('/admin/login');

        $response->assertHeader('X-Robots-Tag', 'noindex, nofollow');
    }

    public function test_authenticated_admin_pages_are_noindex_too(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/admin');

        $response->assertHeader('X-Robots-Tag', 'noindex, nofollow');
    }

    public function test_public_site_is_not_affected(): void
    {
        $response = $this->get('/');

        $response->assertHeaderMissing('X-Robots-Tag');
    }
}
