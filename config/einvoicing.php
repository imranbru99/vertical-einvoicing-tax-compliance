<?php

return [
    'name' => 'Vertical E-Invoicing',
    'tagline' => 'Tax-compliant e-invoicing for global businesses',
    'accent' => '#B45309',
    'hourly_cost_rate' => env('EINVOICE_HOURLY_RATE', 55),
    'monthly_platform_cost' => env('EINVOICE_MONTHLY_COST', 1490),
    'demo' => [
        'email' => env('DEMO_USER_EMAIL', 'demo@einvoice.test'),
        'password' => env('DEMO_USER_PASSWORD', 'password'),
    ],
    'modules' => [
        'multi_country_invoicing' => ['name' => 'Multi-Country Invoicing', 'category' => 'invoicing'],
        'vat_gst_engine' => ['name' => 'VAT / GST Tax Engine', 'category' => 'tax'],
        'digital_signatures' => ['name' => 'Digital Signatures', 'category' => 'compliance'],
        'fiscal_reporting' => ['name' => 'Fiscal Reporting', 'category' => 'reporting'],
        'customer_portal' => ['name' => 'Customer Portal', 'category' => 'portal'],
        'audit_trail' => ['name' => 'Audit Trail', 'category' => 'compliance'],
        'peppol_bridge' => ['name' => 'PEPPOL / Clearance Bridge', 'category' => 'interoperability'],
        'tax_rule_catalog' => ['name' => 'Tax Rule Catalog', 'category' => 'tax'],
        'unicode_pdf_invoices' => ['name' => 'Unicode PDF Invoices', 'category' => 'reporting'],
        'ai_invoice_assist' => ['name' => 'AI Invoice Assist', 'category' => 'ai'],
        'attendance_bridge' => ['name' => 'Attendance Machine Bridge', 'category' => 'workforce'],
        'roi_analytics' => ['name' => 'ROI & Compliance Analytics', 'category' => 'analytics'],
    ],
];
