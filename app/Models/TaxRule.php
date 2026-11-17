<?php

namespace App\Models;

use App\Enums\TaxScheme;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TaxRule extends Model
{
    protected $fillable = [
        'tax_jurisdiction_id',
        'code',
        'name',
        'scheme',
        'rate_percent',
        'category',
        'is_default',
        'is_active',
        'effective_from',
        'effective_to',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'scheme' => TaxScheme::class,
            'rate_percent' => 'decimal:4',
            'is_default' => 'boolean',
            'is_active' => 'boolean',
            'effective_from' => 'date',
            'effective_to' => 'date',
        ];
    }

    public function jurisdiction(): BelongsTo
    {
        return $this->belongsTo(TaxJurisdiction::class, 'tax_jurisdiction_id');
    }
}
