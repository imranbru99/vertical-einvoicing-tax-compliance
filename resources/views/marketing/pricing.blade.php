@extends('layouts.marketing')

@section('title', 'Pricing & ROI')
@section('content')
<section class="section">
    <div class="eyebrow">Business case</div>
    <h2>ROI that finance leaders can defend</h2>
    <div class="grid" style="margin-top:1.25rem">
        <div class="card"><div class="kpi">{{ $weeklyHours }}h</div><p>Weekly capacity from enabled modules</p></div>
        <div class="card"><div class="kpi">${{ number_format($roi['cost_saved'], 0) }}</div><p>Estimated cost saved in ledger</p></div>
        <div class="card"><div class="kpi">{{ $roi['roi_percent'] }}%</div><p>ROI vs ${{ number_format($roi['platform_cost'], 0) }}/mo platform cost</p></div>
        <div class="card"><div class="kpi">{{ $moduleCount }}</div><p>Toggleable modules · {{ $reports }} fiscal reports</p></div>
    </div>
</section>
<section class="section">
    <h2>Plans</h2>
    <div class="grid" style="margin-top:1rem">
        <div class="card">
            <h3>Compliance Starter</h3>
            <p>Multi-country invoices + VAT/GST engine + audit trail. Ideal for finance teams entering e-invoicing mandates.</p>
        </div>
        <div class="card">
            <h3>Clearance Pro</h3>
            <p>Adds digital signatures, fiscal reporting packs, and PEPPOL/clearance bridge when authorities require it.</p>
        </div>
        <div class="card">
            <h3>Enterprise Fiscal</h3>
            <p>Customer portal, AI invoice assist, Unicode PDF, ROI analytics, and workforce attendance bridge for field hybrids.</p>
        </div>
    </div>
</section>
@endsection
