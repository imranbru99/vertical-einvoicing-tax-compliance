<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    protected $fillable = [
        'key',
        'name',
        'category',
        'description',
        'icon',
        'is_enabled',
        'is_core',
        'sort_order',
        'settings',
        'estimated_hours_saved_weekly',
    ];

    protected function casts(): array
    {
        return [
            'is_enabled' => 'boolean',
            'is_core' => 'boolean',
            'settings' => 'array',
            'estimated_hours_saved_weekly' => 'integer',
        ];
    }

    public function scopeEnabled(Builder $query): Builder
    {
        return $query->where('is_enabled', true);
    }

    public function enable(): void
    {
        $this->update(['is_enabled' => true]);
    }

    public function disable(): void
    {
        if ($this->is_core) {
            return;
        }

        $this->update(['is_enabled' => false]);
    }
}
