<?php

namespace App\Services;

use App\Models\Module;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class ModuleRegistry
{
    public function all(): Collection
    {
        return Cache::remember('modules.registry', 300, fn () => Module::query()->orderBy('sort_order')->get());
    }

    public function enabled(): Collection
    {
        return $this->all()->where('is_enabled', true)->values();
    }

    public function isEnabled(string $key): bool
    {
        $module = $this->all()->firstWhere('key', $key);

        return $module?->is_enabled ?? false;
    }

    public function toggle(string $key, bool $enabled): ?Module
    {
        $module = Module::query()->where('key', $key)->first();

        if (! $module) {
            return null;
        }

        if ($module->is_core && ! $enabled) {
            return $module;
        }

        $module->update(['is_enabled' => $enabled]);
        Cache::forget('modules.registry');

        return $module->fresh();
    }

    public function weeklyHoursSaved(): int
    {
        return (int) $this->enabled()->sum('estimated_hours_saved_weekly');
    }

    public function clearCache(): void
    {
        Cache::forget('modules.registry');
    }
}
