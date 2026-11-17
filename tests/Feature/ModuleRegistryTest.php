<?php

namespace Tests\Feature;

use App\Models\Module;
use App\Services\ModuleRegistry;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ModuleRegistryTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_core_modules_cannot_be_disabled(): void
    {
        $registry = app(ModuleRegistry::class);
        $core = Module::query()->where('is_core', true)->firstOrFail();

        $result = $registry->toggle($core->key, false);

        $this->assertTrue($result->is_enabled);
    }

    public function test_optional_modules_can_toggle_off(): void
    {
        $registry = app(ModuleRegistry::class);
        $optional = Module::query()->where('is_core', false)->where('is_enabled', true)->firstOrFail();

        $result = $registry->toggle($optional->key, false);

        $this->assertFalse($result->is_enabled);
    }
}
