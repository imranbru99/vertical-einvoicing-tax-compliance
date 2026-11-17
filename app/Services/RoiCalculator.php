<?php

namespace App\Services;

use App\Models\ComplianceMetric;
use App\Models\Invoice;

class RoiCalculator
{
    public function __construct(protected ModuleRegistry $modules) {}

    /** @return array<string, float|int> */
    public function dashboardSummary(): array
    {
        $hours = (float) ComplianceMetric::query()->sum('hours_saved');
        $tasks = (int) ComplianceMetric::query()->sum('invoices_processed');
        $costSaved = (float) ComplianceMetric::query()->sum('estimated_cost_saved');

        if ($hours <= 0) {
            $hours = (float) $this->modules->weeklyHoursSaved();
            $costSaved = $hours * (float) config('einvoicing.hourly_cost_rate', 55);
            $tasks = Invoice::query()->count();
        }

        $platform = (float) config('einvoicing.monthly_platform_cost', 1490);
        $roi = $platform > 0 ? round((($costSaved - $platform) / $platform) * 100, 1) : 0;

        return [
            'hours_saved' => round($hours, 1),
            'tasks_automated' => $tasks,
            'cost_saved' => round($costSaved, 2),
            'roi_percent' => $roi,
            'platform_cost' => $platform,
        ];
    }
}
