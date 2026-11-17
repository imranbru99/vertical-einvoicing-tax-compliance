@extends('layouts.marketing')

@section('title', 'Tax-compliant e-invoicing for global businesses')
@section('meta_description', 'Vertical e-invoicing and tax compliance platform for multi-country invoices, VAT/GST rules, digital signatures, and audit-ready fiscal reporting.')

@section('content')
<section class="hero">
    <div class="eyebrow">FinTech · Tax · Accent #B45309</div>
    <h1>Vertical E-Invoicing</h1>
    <p>Issue multi-country invoices, apply VAT/GST rules, apply digital signatures, and export audit-ready fiscal reports — then switch modules on or off for your exact compliance footprint.</p>
    <div class="cta-row">
        <a class="btn" href="/admin">Open Admin</a>
        <a class="btn secondary" href="{{ route('demo') }}">Demo credentials</a>
        <a class="btn secondary" href="{{ route('modules') }}">Browse modules</a>
    </div>
</section>

<section class="section">
    <div class="grid">
        <div class="card"><div class="kpi">{{ $enabledCount }}</div><p>Modules enabled right now</p></div>
        <div class="card"><div class="kpi">{{ $weeklyHours }}h</div><p>Estimated weekly hours unlocked</p></div>
        <div class="card"><div class="kpi">{{ $jurisdictions->count() }}</div><p>Active tax jurisdictions</p></div>
        <div class="card"><div class="kpi">{{ $roi['roi_percent'] }}%</div><p>Modeled ROI vs platform cost</p></div>
    </div>
</section>

<section class="section">
    <div class="eyebrow">Product snapshot</div>
    <h2>One fiscal control plane</h2>
    <div class="grid" style="margin-top:1rem">
        @foreach($modules->take(6) as $module)
            <div class="card">
                <span class="pill {{ $module->is_enabled ? '' : 'off' }}">{{ $module->is_enabled ? 'ON' : 'OFF' }}</span>
                <h3>{{ $module->name }}</h3>
                <p>{{ $module->description }}</p>
            </div>
        @endforeach
    </div>
</section>

<section class="section">
    <div class="eyebrow">Live demo invoices</div>
    <h2>Multi-country tax lines</h2>
    <table style="margin-top:1rem">
        <thead>
            <tr>
                <th>Number</th>
                <th>Customer</th>
                <th>Country</th>
                <th>Status</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoices as $invoice)
                <tr>
                    <td>{{ $invoice->number }}</td>
                    <td>{{ $invoice->customer?->name }}</td>
                    <td>{{ $invoice->jurisdiction?->country_code }}</td>
                    <td>{{ $invoice->status->label() }}</td>
                    <td>{{ $invoice->currency }} {{ number_format((float) $invoice->total, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</section>
@endsection
