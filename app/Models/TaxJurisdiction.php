<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TaxJurisdiction extends Model
{
    protected $fillable = [
        'country_code',
        'name',
        'currency',
        'default_scheme',
        'authority_name',
        'requires_digital_signature',
        'requires_clearance',
        'is_active',
        'einvoice_format',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'requires_digital_signature' => 'boolean',
            'requires_clearance' => 'boolean',
            'is_active' => 'boolean',
            'metadata' => 'array',
        ];
    }

    public function taxRules(): HasMany
    {
        return $this->hasMany(TaxRule::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    public function fiscalReports(): HasMany
    {
        return $this->hasMany(FiscalReport::class);
    }
}
