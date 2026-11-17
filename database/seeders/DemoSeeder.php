<?php

namespace Database\Seeders;

use App\Enums\FiscalReportStatus;
use App\Enums\InvoiceStatus;
use App\Enums\SignatureStatus;
use App\Enums\TaxScheme;
use App\Enums\UserRole;
use App\Models\AuditEvent;
use App\Models\ComplianceMetric;
use App\Models\Customer;
use App\Models\DigitalSignature;
use App\Models\FiscalReport;
use App\Models\Invoice;
use App\Models\InvoiceLine;
use App\Models\Module;
use App\Models\TaxJurisdiction;
use App\Models\TaxRule;
use App\Models\User;
use App\Services\ModuleRegistry;
use App\Services\TaxEngine;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        $users = $this->seedUsers();
        $modules = $this->seedModules();
        $jurisdictions = $this->seedJurisdictions();
        $rules = $this->seedTaxRules($jurisdictions);
        $customers = $this->seedCustomers();
        $invoices = $this->seedInvoices($users, $jurisdictions, $rules, $customers);
        $this->seedSignatures($users, $invoices);
        $this->seedFiscalReports($jurisdictions, $invoices);
        $this->seedAuditAndMetrics($users, $modules, $jurisdictions, $invoices);

        app(ModuleRegistry::class)->clearCache();
    }

    /** @return array<string, User> */
    protected function seedUsers(): array
    {
        $defs = [
            'super' => ['name' => 'Imran Ahmed', 'email' => 'admin@einvoice.test', 'role' => UserRole::SuperAdmin, 'company' => 'ImranDev BD', 'job_title' => 'Platform Architect'],
            'admin' => ['name' => 'Fatima Karim', 'email' => 'tax-admin@einvoice.test', 'role' => UserRole::Admin, 'company' => 'FiscalOps Ltd', 'job_title' => 'Tax Admin'],
            'manager' => ['name' => 'Rafiul Hasan', 'email' => 'manager@einvoice.test', 'role' => UserRole::Manager, 'company' => 'FiscalOps Ltd', 'job_title' => 'Compliance Manager'],
            'staff' => ['name' => 'Lina Ahmed', 'email' => 'staff@einvoice.test', 'role' => UserRole::Staff, 'company' => 'FiscalOps Ltd', 'job_title' => 'Invoice Specialist'],
            'client' => ['name' => 'Portal Customer', 'email' => 'client@einvoice.test', 'role' => UserRole::Client, 'company' => 'Acme Trading', 'job_title' => 'AP Lead'],
            'demo' => ['name' => 'Demo User', 'email' => config('einvoicing.demo.email'), 'role' => UserRole::Demo, 'company' => 'Demo Co', 'job_title' => 'Evaluator'],
        ];

        $users = [];
        foreach ($defs as $key => $def) {
            $users[$key] = User::query()->updateOrCreate(
                ['email' => $def['email']],
                [
                    'name' => $def['name'],
                    'password' => config('einvoicing.demo.password'),
                    'role' => $def['role'],
                    'company' => $def['company'],
                    'job_title' => $def['job_title'],
                    'is_active' => true,
                    'email_verified_at' => now(),
                ]
            );
        }

        return $users;
    }

    /** @return array<string, Module> */
    protected function seedModules(): array
    {
        $catalog = [
            ['key' => 'multi_country_invoicing', 'name' => 'Multi-Country Invoicing', 'category' => 'invoicing', 'hours' => 30, 'core' => true, 'enabled' => true],
            ['key' => 'vat_gst_engine', 'name' => 'VAT / GST Tax Engine', 'category' => 'tax', 'hours' => 26, 'core' => true, 'enabled' => true],
            ['key' => 'digital_signatures', 'name' => 'Digital Signatures', 'category' => 'compliance', 'hours' => 14, 'core' => false, 'enabled' => true],
            ['key' => 'fiscal_reporting', 'name' => 'Fiscal Reporting', 'category' => 'reporting', 'hours' => 18, 'core' => true, 'enabled' => true],
            ['key' => 'customer_portal', 'name' => 'Customer Portal', 'category' => 'portal', 'hours' => 12, 'core' => false, 'enabled' => true],
            ['key' => 'audit_trail', 'name' => 'Audit Trail', 'category' => 'compliance', 'hours' => 10, 'core' => false, 'enabled' => true],
            ['key' => 'peppol_bridge', 'name' => 'PEPPOL / Clearance Bridge', 'category' => 'interoperability', 'hours' => 16, 'core' => false, 'enabled' => false],
            ['key' => 'tax_rule_catalog', 'name' => 'Tax Rule Catalog', 'category' => 'tax', 'hours' => 11, 'core' => false, 'enabled' => true],
            ['key' => 'unicode_pdf_invoices', 'name' => 'Unicode PDF Invoices', 'category' => 'reporting', 'hours' => 9, 'core' => false, 'enabled' => true],
            ['key' => 'ai_invoice_assist', 'name' => 'AI Invoice Assist', 'category' => 'ai', 'hours' => 15, 'core' => false, 'enabled' => true],
            ['key' => 'attendance_bridge', 'name' => 'Attendance Machine Bridge', 'category' => 'workforce', 'hours' => 8, 'core' => false, 'enabled' => false],
            ['key' => 'roi_analytics', 'name' => 'ROI & Compliance Analytics', 'category' => 'analytics', 'hours' => 7, 'core' => true, 'enabled' => true],
        ];

        $modules = [];
        foreach ($catalog as $i => $item) {
            $modules[$item['key']] = Module::query()->updateOrCreate(
                ['key' => $item['key']],
                [
                    'name' => $item['name'],
                    'category' => $item['category'],
                    'description' => "Enable or disable {$item['name']} for your fiscal stack. Estimated {$item['hours']} hours saved weekly when active.",
                    'icon' => 'heroicon-o-receipt-percent',
                    'is_enabled' => $item['enabled'],
                    'is_core' => $item['core'],
                    'sort_order' => $i + 1,
                    'settings' => ['notify_ops' => true, 'sla_hours' => 24],
                    'estimated_hours_saved_weekly' => $item['hours'],
                ]
            );
        }

        return $modules;
    }

    /** @return array<string, TaxJurisdiction> */
    protected function seedJurisdictions(): array
    {
        $items = [
            ['code' => 'BD', 'name' => 'Bangladesh', 'currency' => 'BDT', 'scheme' => 'vat', 'authority' => 'NBR', 'sign' => true, 'clearance' => false, 'format' => 'UBL-BD'],
            ['code' => 'AE', 'name' => 'United Arab Emirates', 'currency' => 'AED', 'scheme' => 'vat', 'authority' => 'FTA', 'sign' => true, 'clearance' => true, 'format' => 'PEPPOL-UBL'],
            ['code' => 'IN', 'name' => 'India', 'currency' => 'INR', 'scheme' => 'gst', 'authority' => 'GSTN', 'sign' => true, 'clearance' => true, 'format' => 'IRN-JSON'],
            ['code' => 'GB', 'name' => 'United Kingdom', 'currency' => 'GBP', 'scheme' => 'vat', 'authority' => 'HMRC', 'sign' => false, 'clearance' => false, 'format' => 'UBL'],
            ['code' => 'SA', 'name' => 'Saudi Arabia', 'currency' => 'SAR', 'scheme' => 'vat', 'authority' => 'ZATCA', 'sign' => true, 'clearance' => true, 'format' => 'ZATCA-XML'],
            ['code' => 'SG', 'name' => 'Singapore', 'currency' => 'SGD', 'scheme' => 'gst', 'authority' => 'IRAS', 'sign' => false, 'clearance' => false, 'format' => 'PEPPOL'],
        ];

        $jurisdictions = [];
        foreach ($items as $item) {
            $jurisdictions[$item['code']] = TaxJurisdiction::query()->updateOrCreate(
                ['country_code' => $item['code']],
                [
                    'name' => $item['name'],
                    'currency' => $item['currency'],
                    'default_scheme' => $item['scheme'],
                    'authority_name' => $item['authority'],
                    'requires_digital_signature' => $item['sign'],
                    'requires_clearance' => $item['clearance'],
                    'is_active' => true,
                    'einvoice_format' => $item['format'],
                    'metadata' => ['timezone' => 'UTC'],
                ]
            );
        }

        return $jurisdictions;
    }

    /**
     * @param  array<string, TaxJurisdiction>  $jurisdictions
     * @return array<string, TaxRule>
     */
    protected function seedTaxRules(array $jurisdictions): array
    {
        $defs = [
            ['j' => 'BD', 'code' => 'BD-STD', 'name' => 'Bangladesh Standard VAT', 'scheme' => TaxScheme::Vat, 'rate' => 15, 'default' => true],
            ['j' => 'BD', 'code' => 'BD-ZERO', 'name' => 'Bangladesh Zero-Rated', 'scheme' => TaxScheme::ZeroRated, 'rate' => 0, 'default' => false],
            ['j' => 'AE', 'code' => 'AE-STD', 'name' => 'UAE Standard VAT', 'scheme' => TaxScheme::Vat, 'rate' => 5, 'default' => true],
            ['j' => 'IN', 'code' => 'IN-GST18', 'name' => 'India GST 18%', 'scheme' => TaxScheme::Gst, 'rate' => 18, 'default' => true],
            ['j' => 'IN', 'code' => 'IN-GST12', 'name' => 'India GST 12%', 'scheme' => TaxScheme::Gst, 'rate' => 12, 'default' => false],
            ['j' => 'GB', 'code' => 'GB-STD', 'name' => 'UK Standard VAT', 'scheme' => TaxScheme::Vat, 'rate' => 20, 'default' => true],
            ['j' => 'SA', 'code' => 'SA-STD', 'name' => 'KSA Standard VAT', 'scheme' => TaxScheme::Vat, 'rate' => 15, 'default' => true],
            ['j' => 'SG', 'code' => 'SG-GST', 'name' => 'Singapore GST', 'scheme' => TaxScheme::Gst, 'rate' => 9, 'default' => true],
        ];

        $rules = [];
        foreach ($defs as $def) {
            $key = $def['code'];
            $rules[$key] = TaxRule::query()->updateOrCreate(
                [
                    'tax_jurisdiction_id' => $jurisdictions[$def['j']]->id,
                    'code' => $def['code'],
                ],
                [
                    'name' => $def['name'],
                    'scheme' => $def['scheme'],
                    'rate_percent' => $def['rate'],
                    'category' => 'standard',
                    'is_default' => $def['default'],
                    'is_active' => true,
                    'effective_from' => now()->subYear()->toDateString(),
                    'notes' => 'Seeded compliance catalog rule',
                ]
            );
        }

        return $rules;
    }

    /** @return array<string, Customer> */
    protected function seedCustomers(): array
    {
        $defs = [
            ['key' => 'acme', 'name' => 'Acme Trading LLC', 'tax' => '100123456700003', 'country' => 'AE', 'email' => 'ap@acme.example'],
            ['key' => 'delta', 'name' => 'Delta Manufacturing', 'tax' => '29AABCU9603R1ZM', 'country' => 'IN', 'email' => 'finance@delta.example'],
            ['key' => 'north', 'name' => 'Northwind Retail BD', 'tax' => 'BIN-234567890', 'country' => 'BD', 'email' => 'accounts@northwind.example'],
            ['key' => 'orchid', 'name' => 'Orchid Services UK', 'tax' => 'GB123456789', 'country' => 'GB', 'email' => 'billing@orchid.example'],
            ['key' => 'najd', 'name' => 'Najd Commerce', 'tax' => '300012345600003', 'country' => 'SA', 'email' => 'ap@najd.example'],
        ];

        $customers = [];
        foreach ($defs as $def) {
            $customers[$def['key']] = Customer::query()->updateOrCreate(
                ['tax_id' => $def['tax']],
                [
                    'name' => $def['name'],
                    'legal_name' => $def['name'],
                    'email' => $def['email'],
                    'country_code' => $def['country'],
                    'address_line' => '100 Commerce Avenue',
                    'city' => 'Business City',
                    'postal_code' => '1000',
                    'portal_token' => Str::uuid()->toString(),
                    'portal_enabled' => true,
                    'is_active' => true,
                ]
            );
        }

        return $customers;
    }

    /**
     * @param  array<string, User>  $users
     * @param  array<string, TaxJurisdiction>  $jurisdictions
     * @param  array<string, TaxRule>  $rules
     * @param  array<string, Customer>  $customers
     * @return array<int, Invoice>
     */
    protected function seedInvoices(array $users, array $jurisdictions, array $rules, array $customers): array
    {
        $engine = app(TaxEngine::class);
        $defs = [
            ['number' => 'INV-2026-0001', 'customer' => 'acme', 'country' => 'AE', 'rule' => 'AE-STD', 'status' => InvoiceStatus::Paid, 'lines' => [['Consulting retainer', 1, 4200]]],
            ['number' => 'INV-2026-0002', 'customer' => 'delta', 'country' => 'IN', 'rule' => 'IN-GST18', 'status' => InvoiceStatus::Signed, 'lines' => [['ERP licenses', 10, 180], ['Support pack', 1, 900]]],
            ['number' => 'INV-2026-0003', 'customer' => 'north', 'country' => 'BD', 'rule' => 'BD-STD', 'status' => InvoiceStatus::Accepted, 'lines' => [['Warehouse scanners', 5, 220]]],
            ['number' => 'INV-2026-0004', 'customer' => 'orchid', 'country' => 'GB', 'rule' => 'GB-STD', 'status' => InvoiceStatus::Validated, 'lines' => [['Managed services', 1, 3100]]],
            ['number' => 'INV-2026-0005', 'customer' => 'najd', 'country' => 'SA', 'rule' => 'SA-STD', 'status' => InvoiceStatus::Submitted, 'lines' => [['Phase-1 delivery', 1, 8500]]],
            ['number' => 'INV-2026-0006', 'customer' => 'acme', 'country' => 'AE', 'rule' => 'AE-STD', 'status' => InvoiceStatus::Draft, 'lines' => [['Add-on seats', 20, 45]]],
        ];

        $invoices = [];
        foreach ($defs as $def) {
            $rule = $rules[$def['rule']];
            $rate = (float) $rule->rate_percent;
            $linePayload = [];
            foreach ($def['lines'] as $i => $line) {
                $linePayload[] = [
                    'description' => $line[0],
                    'quantity' => $line[1],
                    'unit_price' => $line[2],
                    'tax_rate_percent' => $rate,
                ];
            }
            $totals = $engine->calculateTotals($linePayload, $rate);
            $jurisdiction = $jurisdictions[$def['country']];

            $invoice = Invoice::query()->updateOrCreate(
                ['number' => $def['number']],
                [
                    'customer_id' => $customers[$def['customer']]->id,
                    'tax_jurisdiction_id' => $jurisdiction->id,
                    'tax_rule_id' => $rule->id,
                    'created_by' => $users['staff']->id,
                    'status' => $def['status'],
                    'currency' => $jurisdiction->currency,
                    'issue_date' => now()->subDays(rand(2, 40))->toDateString(),
                    'due_date' => now()->addDays(20)->toDateString(),
                    'subtotal' => $totals['subtotal'],
                    'tax_amount' => $totals['tax_amount'],
                    'total' => $totals['total'],
                    'tax_rate_percent' => $rate,
                    'buyer_tax_id' => $customers[$def['customer']]->tax_id,
                    'seller_tax_id' => 'SELLER-TAX-001',
                    'clearance_reference' => $jurisdiction->requires_clearance ? 'CLR-'.Str::upper(Str::random(8)) : null,
                    'notes' => 'Demo multi-country tax-compliant invoice',
                ]
            );

            InvoiceLine::query()->where('invoice_id', $invoice->id)->delete();
            foreach ($linePayload as $i => $line) {
                $lineSub = round($line['quantity'] * $line['unit_price'], 2);
                $lineTax = round($lineSub * ($rate / 100), 2);
                InvoiceLine::query()->create([
                    'invoice_id' => $invoice->id,
                    'description' => $line['description'],
                    'sku' => 'SKU-'.($i + 1),
                    'quantity' => $line['quantity'],
                    'unit_price' => $line['unit_price'],
                    'tax_rate_percent' => $rate,
                    'line_subtotal' => $lineSub,
                    'line_tax' => $lineTax,
                    'line_total' => $lineSub + $lineTax,
                    'sort_order' => $i + 1,
                ]);
            }

            $invoices[] = $invoice;
        }

        return $invoices;
    }

    /**
     * @param  array<string, User>  $users
     * @param  array<int, Invoice>  $invoices
     */
    protected function seedSignatures(array $users, array $invoices): void
    {
        foreach ($invoices as $invoice) {
            if (! in_array($invoice->status, [InvoiceStatus::Signed, InvoiceStatus::Submitted, InvoiceStatus::Accepted, InvoiceStatus::Paid], true)) {
                continue;
            }

            DigitalSignature::query()->updateOrCreate(
                ['invoice_id' => $invoice->id],
                [
                    'signed_by' => $users['manager']->id,
                    'status' => SignatureStatus::Signed,
                    'algorithm' => 'RSA-SHA256',
                    'certificate_subject' => 'CN=FiscalOps Demo CA',
                    'signature_hash' => hash('sha256', $invoice->number.$invoice->total),
                    'signed_at' => now()->subDays(1),
                    'expires_at' => now()->addYear(),
                    'payload' => ['invoice' => $invoice->number],
                ]
            );
        }
    }

    /**
     * @param  array<string, TaxJurisdiction>  $jurisdictions
     * @param  array<int, Invoice>  $invoices
     */
    protected function seedFiscalReports(array $jurisdictions, array $invoices): void
    {
        foreach (['AE', 'IN', 'BD', 'SA'] as $code) {
            $set = collect($invoices)->filter(fn (Invoice $i) => $i->tax_jurisdiction_id === $jurisdictions[$code]->id);
            FiscalReport::query()->updateOrCreate(
                [
                    'tax_jurisdiction_id' => $jurisdictions[$code]->id,
                    'period_label' => '2026-Q1 '.$code,
                ],
                [
                    'period_start' => now()->startOfQuarter()->toDateString(),
                    'period_end' => now()->endOfQuarter()->toDateString(),
                    'status' => FiscalReportStatus::Ready,
                    'invoice_count' => $set->count(),
                    'taxable_base' => $set->sum('subtotal'),
                    'tax_collected' => $set->sum('tax_amount'),
                    'tax_payable' => $set->sum('tax_amount'),
                    'file_path' => "reports/fiscal/{$code}-2026Q1.pdf",
                    'summary' => ['authority' => $jurisdictions[$code]->authority_name],
                ]
            );
        }
    }

    /**
     * @param  array<string, User>  $users
     * @param  array<string, Module>  $modules
     * @param  array<string, TaxJurisdiction>  $jurisdictions
     * @param  array<int, Invoice>  $invoices
     */
    protected function seedAuditAndMetrics(array $users, array $modules, array $jurisdictions, array $invoices): void
    {
        foreach ($invoices as $invoice) {
            AuditEvent::query()->create([
                'invoice_id' => $invoice->id,
                'user_id' => $users['staff']->id,
                'event_type' => 'invoice.seeded',
                'message' => "Seeded invoice {$invoice->number}",
                'ip_address' => '127.0.0.1',
                'context' => ['status' => $invoice->status->value],
            ]);
        }

        foreach (array_values($modules) as $i => $module) {
            ComplianceMetric::query()->create([
                'module_id' => $module->id,
                'tax_jurisdiction_id' => array_values($jurisdictions)[$i % count($jurisdictions)]->id,
                'period_week' => now()->startOfWeek()->subWeeks($i % 4)->toDateString(),
                'hours_saved' => $module->estimated_hours_saved_weekly * 0.85,
                'invoices_processed' => 8 + ($i * 3),
                'estimated_cost_saved' => $module->estimated_hours_saved_weekly * (float) config('einvoicing.hourly_cost_rate', 55),
                'notes' => 'Demo compliance ROI ledger',
            ]);
        }
    }
}
