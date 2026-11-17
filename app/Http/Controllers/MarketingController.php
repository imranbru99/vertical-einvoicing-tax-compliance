<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\FiscalReport;
use App\Models\Invoice;
use App\Models\Module;
use App\Models\TaxJurisdiction;
use App\Models\TaxRule;
use App\Services\ModuleRegistry;
use App\Services\RoiCalculator;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MarketingController extends Controller
{
    public function home(ModuleRegistry $modules, RoiCalculator $roi): View
    {
        return view('marketing.home', [
            'modules' => $modules->all(),
            'enabledCount' => $modules->enabled()->count(),
            'weeklyHours' => $modules->weeklyHoursSaved(),
            'roi' => $roi->dashboardSummary(),
            'jurisdictions' => TaxJurisdiction::query()->where('is_active', true)->get(),
            'invoices' => Invoice::query()->with(['customer', 'jurisdiction'])->latest()->take(6)->get(),
        ]);
    }

    public function features(ModuleRegistry $modules): View
    {
        return view('marketing.features', [
            'modules' => $modules->all()->groupBy('category'),
        ]);
    }

    public function modules(ModuleRegistry $modules): View
    {
        return view('marketing.modules', [
            'modules' => $modules->all(),
        ]);
    }

    public function invoices(): View
    {
        return view('marketing.invoices', [
            'invoices' => Invoice::query()->with(['customer', 'jurisdiction', 'taxRule'])->latest()->get(),
        ]);
    }

    public function taxRules(): View
    {
        return view('marketing.tax-rules', [
            'rules' => TaxRule::query()->with('jurisdiction')->where('is_active', true)->get(),
            'jurisdictions' => TaxJurisdiction::query()->where('is_active', true)->withCount('taxRules')->get(),
        ]);
    }

    public function pricing(ModuleRegistry $modules, RoiCalculator $roi): View
    {
        return view('marketing.pricing', [
            'weeklyHours' => $modules->weeklyHoursSaved(),
            'roi' => $roi->dashboardSummary(),
            'moduleCount' => Module::query()->count(),
            'reports' => FiscalReport::query()->count(),
        ]);
    }

    public function demo(): View
    {
        return view('marketing.demo', [
            'email' => config('einvoicing.demo.email'),
            'password' => config('einvoicing.demo.password'),
        ]);
    }

    public function portal(Request $request): View
    {
        $token = $request->query('token');
        $customer = $token
            ? Customer::query()->where('portal_token', $token)->where('portal_enabled', true)->first()
            : Customer::query()->where('portal_enabled', true)->first();

        $invoices = $customer
            ? Invoice::query()->where('customer_id', $customer->id)->with('jurisdiction')->latest()->get()
            : collect();

        return view('marketing.portal', [
            'customer' => $customer,
            'invoices' => $invoices,
            'moduleOn' => app(ModuleRegistry::class)->isEnabled('customer_portal'),
        ]);
    }
}
