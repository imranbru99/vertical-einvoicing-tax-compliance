<?php

namespace App\Services;

use App\Enums\FiscalReportStatus;
use App\Enums\InvoiceStatus;
use App\Models\FiscalReport;
use App\Models\Invoice;
use App\Models\TaxJurisdiction;
use Illuminate\Support\Carbon;

class FiscalReportService
{
    public function __construct(protected ModuleRegistry $modules) {}

    public function generate(TaxJurisdiction $jurisdiction, Carbon $start, Carbon $end, string $label): FiscalReport
    {
        if (! $this->modules->isEnabled('fiscal_reporting')) {
            abort(403, 'Fiscal reporting module is disabled.');
        }

        $invoices = Invoice::query()
            ->where('tax_jurisdiction_id', $jurisdiction->id)
            ->whereBetween('issue_date', [$start->toDateString(), $end->toDateString()])
            ->whereIn('status', [
                InvoiceStatus::Signed->value,
                InvoiceStatus::Submitted->value,
                InvoiceStatus::Accepted->value,
                InvoiceStatus::Paid->value,
            ])
            ->get();

        return FiscalReport::query()->create([
            'tax_jurisdiction_id' => $jurisdiction->id,
            'period_label' => $label,
            'period_start' => $start,
            'period_end' => $end,
            'status' => FiscalReportStatus::Ready,
            'invoice_count' => $invoices->count(),
            'taxable_base' => $invoices->sum('subtotal'),
            'tax_collected' => $invoices->sum('tax_amount'),
            'tax_payable' => $invoices->sum('tax_amount'),
            'file_path' => 'reports/fiscal/'.$jurisdiction->country_code.'-'.$start->format('Ym').'.pdf',
            'summary' => [
                'currency' => $jurisdiction->currency,
                'authority' => $jurisdiction->authority_name,
                'statuses' => $invoices->groupBy(fn ($i) => $i->status->value)->map->count(),
            ],
        ]);
    }
}
