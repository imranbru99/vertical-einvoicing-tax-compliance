<?php

namespace App\Filament\Widgets;

use App\Enums\InvoiceStatus;
use App\Enums\SignatureStatus;
use App\Models\DigitalSignature;
use App\Models\FiscalReport;
use App\Models\Invoice;
use App\Models\Module;
use App\Models\TaxJurisdiction;
use App\Services\ModuleRegistry;
use App\Services\RoiCalculator;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class EinvoicingStatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $roi = app(RoiCalculator::class)->dashboardSummary();
        $hours = app(ModuleRegistry::class)->weeklyHoursSaved();

        return [
            Stat::make('Invoices', Invoice::query()->count())
                ->description('Issued across jurisdictions')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('primary'),
            Stat::make('VAT / Tax Collected', number_format((float) Invoice::query()->sum('tax_amount'), 2))
                ->description('Sum of tax lines')
                ->color('warning'),
            Stat::make('Signed', DigitalSignature::query()->where('status', SignatureStatus::Signed)->count())
                ->description('Digital signatures applied')
                ->color('success'),
            Stat::make('Jurisdictions', TaxJurisdiction::query()->where('is_active', true)->count())
                ->description('Active tax countries')
                ->color('info'),
            Stat::make('Fiscal Reports', FiscalReport::query()->count())
                ->description('Audit-ready periods')
                ->color('primary'),
            Stat::make('Modules On', Module::query()->where('is_enabled', true)->count())
                ->description("{$hours} hrs/week estimated capacity · ROI {$roi['roi_percent']}%")
                ->color('success'),
            Stat::make('Accepted / Paid', Invoice::query()->whereIn('status', [InvoiceStatus::Accepted, InvoiceStatus::Paid])->count())
                ->description('Cleared fiscal flow')
                ->color('success'),
            Stat::make('Cost Saved', '$'.number_format((float) $roi['cost_saved'], 0))
                ->description((string) $roi['hours_saved'].' hours tracked')
                ->color('warning'),
        ];
    }
}
