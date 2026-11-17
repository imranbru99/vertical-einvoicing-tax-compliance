<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ComplianceMetric extends Model
{
    protected $fillable = [
        'module_id',
        'tax_jurisdiction_id',
        'period_week',
        'hours_saved',
        'invoices_processed',
        'estimated_cost_saved',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'period_week' => 'date',
            'hours_saved' => 'decimal:2',
            'invoices_processed' => 'integer',
            'estimated_cost_saved' => 'decimal:2',
        ];
    }

    public function module(): BelongsTo
    {
        return $this->belongsTo(Module::class);
    }

    public function jurisdiction(): BelongsTo
    {
        return $this->belongsTo(TaxJurisdiction::class, 'tax_jurisdiction_id');
    }
}
