<?php

namespace Tests\Feature\Redirects;

use App\Models\Redirect;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LegacyRedirectTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_html_redirects_to_home_with_301(): void
    {
        Redirect::create([
            'from_path' => '/index.html',
            'to_path' => '/',
            'status_code' => 301,
            'is_active' => true,
        ]);

        $this->assertDatabaseHas('redirects', ['from_path' => '/index.html', 'is_active' => true]);

        $response = $this->get('/index.html');

        $response->assertRedirect('/');
        $response->assertStatus(301);
    }

    public function test_seeded_style_legacy_path_redirects(): void
    {
        Redirect::create([
            'from_path' => '/contact.html',
            'to_path' => '/contact',
            'status_code' => 301,
            'is_active' => true,
        ]);

        $this->get('/contact.html')
            ->assertRedirect('/contact')
            ->assertStatus(301);
    }

    public function test_inactive_redirect_is_not_applied(): void
    {
        Redirect::create([
            'from_path' => '/index.html',
            'to_path' => '/',
            'status_code' => 301,
            'is_active' => false,
        ]);

        $this->get('/index.html')->assertNotFound();
    }
}
