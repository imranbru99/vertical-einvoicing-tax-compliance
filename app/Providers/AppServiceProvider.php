<?php

namespace App\Providers;

use App\Models\Invoice;
use App\Models\Module;
use App\Policies\InvoicePolicy;
use App\Policies\ModulePolicy;
use App\Services\FiscalReportService;
use App\Services\InvoiceService;
use App\Services\ModuleRegistry;
use App\Services\RoiCalculator;
use App\Services\SignatureService;
use App\Services\TaxEngine;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(ModuleRegistry::class);
        $this->app->singleton(RoiCalculator::class);
        $this->app->singleton(TaxEngine::class);
        $this->app->singleton(InvoiceService::class);
        $this->app->singleton(SignatureService::class);
        $this->app->singleton(FiscalReportService::class);
    }

    public function boot(): void
    {
        Gate::policy(Module::class, ModulePolicy::class);
        Gate::policy(Invoice::class, InvoicePolicy::class);
    }
}
