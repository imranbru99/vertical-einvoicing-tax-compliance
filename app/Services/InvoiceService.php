<?php

namespace App\Services;

use App\Enums\InvoiceStatus;
use App\Models\AuditEvent;
use App\Models\Invoice;
use App\Models\InvoiceLine;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class InvoiceService
{
    public function __construct(
        protected TaxEngine $taxEngine,
        protected ModuleRegistry $modules,
    ) {}

    /**
     * @param  array<string, mixed>  $attributes
     * @param  array<int, array<string, mixed>>  $lines
     */
    public function createDraft(array $attributes, array $lines, ?User $actor = null): Invoice
    {
        return DB::transaction(function () use ($attributes, $lines, $actor) {
            $rate = (float) ($attributes['tax_rate_percent'] ?? 0);
            $totals = $this->taxEngine->calculateTotals($lines, $rate);

            $invoice = Invoice::query()->create([
                ...$attributes,
                'number' => $attributes['number'] ?? $this->nextNumber($attributes['tax_jurisdiction_id'] ?? null),
                'status' => InvoiceStatus::Draft,
                'subtotal' => $totals['subtotal'],
                'tax_amount' => $totals['tax_amount'],
                'total' => $totals['total'],
                'created_by' => $actor?->id,
            ]);

            foreach ($lines as $i => $line) {
                $lineSub = round(((float) $line['quantity']) * ((float) $line['unit_price']), 2);
                $lineRate = (float) ($line['tax_rate_percent'] ?? $rate);
                $lineTax = round($lineSub * ($lineRate / 100), 2);

                InvoiceLine::query()->create([
                    'invoice_id' => $invoice->id,
                    'description' => $line['description'],
                    'sku' => $line['sku'] ?? null,
                    'quantity' => $line['quantity'],
                    'unit_price' => $line['unit_price'],
                    'tax_rate_percent' => $lineRate,
                    'line_subtotal' => $lineSub,
                    'line_tax' => $lineTax,
                    'line_total' => $lineSub + $lineTax,
                    'sort_order' => $i + 1,
                ]);
            }

            $this->audit($invoice, $actor, 'invoice.created', 'Draft invoice created');

            return $invoice->fresh(['lines', 'customer', 'jurisdiction']);
        });
    }

    public function validateTax(Invoice $invoice, ?User $actor = null): Invoice
    {
        if (! $this->modules->isEnabled('vat_gst_engine')) {
            return $invoice;
        }

        $invoice->update(['status' => InvoiceStatus::Validated]);
        $this->audit($invoice, $actor, 'invoice.validated', 'Tax rules validated for invoice');

        return $invoice->fresh();
    }

    public function nextNumber(?int $jurisdictionId = null): string
    {
        $prefix = 'INV-'.now()->format('Ym').'-';
        $seq = Invoice::query()->where('number', 'like', $prefix.'%')->count() + 1;

        return $prefix.str_pad((string) $seq, 4, '0', STR_PAD_LEFT).($jurisdictionId ? '-J'.$jurisdictionId : '');
    }

    public function audit(Invoice $invoice, ?User $actor, string $type, string $message, array $context = []): void
    {
        if (! $this->modules->isEnabled('audit_trail') && $type !== 'invoice.created') {
            return;
        }

        AuditEvent::query()->create([
            'invoice_id' => $invoice->id,
            'user_id' => $actor?->id,
            'event_type' => $type,
            'message' => $message,
            'ip_address' => request()?->ip(),
            'context' => $context ?: ['number' => $invoice->number],
        ]);
    }
}
