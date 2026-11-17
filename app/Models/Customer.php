<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    protected $fillable = [
        'name',
        'legal_name',
        'email',
        'tax_id',
        'country_code',
        'address_line',
        'city',
        'postal_code',
        'portal_token',
        'portal_enabled',
        'is_active',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'portal_enabled' => 'boolean',
            'is_active' => 'boolean',
            'metadata' => 'array',
        ];
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }
}
