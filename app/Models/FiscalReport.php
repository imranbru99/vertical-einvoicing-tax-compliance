<?php

namespace App\Models;

use App\Enums\FiscalReportStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FiscalReport extends Model
{
    protected $fillable = [
        'tax_jurisdiction_id',
        'period_label',
        'period_start',
        'period_end',
        'status',
        'invoice_count',
        'taxable_base',
        'tax_collected',
        'tax_payable',
        'file_path',
        'submitted_at',
        'summary',
    ];

    protected function casts(): array
    {
        return [
            'status' => FiscalReportStatus::class,
            'period_start' => 'date',
            'period_end' => 'date',
            'invoice_count' => 'integer',
            'taxable_base' => 'decimal:2',
            'tax_collected' => 'decimal:2',
            'tax_payable' => 'decimal:2',
            'submitted_at' => 'datetime',
            'summary' => 'array',
        ];
    }

    public function jurisdiction(): BelongsTo
    {
        return $this->belongsTo(TaxJurisdiction::class, 'tax_jurisdiction_id');
    }
}
