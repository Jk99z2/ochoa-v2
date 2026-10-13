<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AppleTouchIconTest extends TestCase
{
    use RefreshDatabase;

    public function test_apple_touch_icon_link_is_present_on_public_pages(): void
    {
        $response = $this->get('/');

        $response->assertSee('<link rel="apple-touch-icon" sizes="180x180" href="/logos/apple-touch-icon.png">', false);
    }

    public function test_apple_touch_icon_file_exists_and_is_a_valid_square_png(): void
    {
        // Static files under public/ are served directly by nginx (try_files)
        // before a request ever reaches Laravel, so there's nothing to hit
        // over HTTP here in tests - check the file nginx would actually serve.
        $path = public_path('logos/apple-touch-icon.png');

        $this->assertFileExists($path);

        [$width, $height] = getimagesize($path);
        $this->assertSame(180, $width);
        $this->assertSame(180, $height);
    }
}
