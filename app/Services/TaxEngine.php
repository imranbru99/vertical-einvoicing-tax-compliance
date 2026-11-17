<?php

namespace App\Services;

use App\Models\TaxJurisdiction;
use App\Models\TaxRule;
use Illuminate\Support\Carbon;

class TaxEngine
{
    /**
     * @return array{rate: float, rule: ?TaxRule, scheme: string}
     */
    public function resolveRate(TaxJurisdiction $jurisdiction, ?string $category = null, ?Carbon $on = null): array
    {
        $on ??= now();

        $query = TaxRule::query()
            ->where('tax_jurisdiction_id', $jurisdiction->id)
            ->where('is_active', true)
            ->where(function ($q) use ($on) {
                $q->whereNull('effective_from')->orWhere('effective_from', '<=', $on);
            })
            ->where(function ($q) use ($on) {
                $q->whereNull('effective_to')->orWhere('effective_to', '>=', $on);
            });

        if ($category) {
            $query->where(function ($q) use ($category) {
                $q->where('category', $category)->orWhereNull('category');
            });
        }

        $rule = $query->orderByDesc('is_default')->orderByDesc('rate_percent')->first()
            ?? TaxRule::query()
                ->where('tax_jurisdiction_id', $jurisdiction->id)
                ->where('is_default', true)
                ->where('is_active', true)
                ->first();

        return [
            'rate' => (float) ($rule?->rate_percent ?? 0),
            'rule' => $rule,
            'scheme' => $rule?->scheme?->value ?? $jurisdiction->default_scheme,
        ];
    }

    /**
     * @param  array<int, array{quantity: float|int, unit_price: float|int, tax_rate_percent?: float|null}>  $lines
     * @return array{subtotal: float, tax_amount: float, total: float}
     */
    public function calculateTotals(array $lines, float $fallbackRate = 0): array
    {
        $subtotal = 0.0;
        $tax = 0.0;

        foreach ($lines as $line) {
            $lineSub = round(((float) $line['quantity']) * ((float) $line['unit_price']), 2);
            $rate = (float) ($line['tax_rate_percent'] ?? $fallbackRate);
            $lineTax = round($lineSub * ($rate / 100), 2);
            $subtotal += $lineSub;
            $tax += $lineTax;
        }

        return [
            'subtotal' => round($subtotal, 2),
            'tax_amount' => round($tax, 2),
            'total' => round($subtotal + $tax, 2),
        ];
    }
}
