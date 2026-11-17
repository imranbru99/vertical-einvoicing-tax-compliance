<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MarketingPagesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_marketing_routes_render(): void
    {
        foreach (['/', '/features', '/modules', '/invoices', '/tax-rules', '/pricing', '/demo', '/portal'] as $uri) {
            $this->get($uri)->assertOk();
        }
    }

    public function test_demo_page_shows_credentials(): void
    {
        $this->get('/demo')
            ->assertOk()
            ->assertSee('demo@einvoice.test')
            ->assertSee('password');
    }
}
