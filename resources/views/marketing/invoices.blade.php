@extends('layouts.marketing')

@section('title', 'Invoices')
@section('content')
<section class="section">
    <div class="eyebrow">Multi-country ledger</div>
    <h2>Invoices with jurisdiction-aware tax</h2>
    <table style="margin-top:1.25rem">
        <thead>
            <tr>
                <th>Number</th>
                <th>Customer</th>
                <th>Country</th>
                <th>Rule</th>
                <th>Tax</th>
                <th>Total</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoices as $invoice)
                <tr>
                    <td>{{ $invoice->number }}</td>
                    <td>{{ $invoice->customer?->name }}</td>
                    <td>{{ $invoice->jurisdiction?->country_code }}</td>
                    <td>{{ $invoice->taxRule?->code }}</td>
                    <td>{{ number_format((float) $invoice->tax_amount, 2) }}</td>
                    <td>{{ $invoice->currency }} {{ number_format((float) $invoice->total, 2) }}</td>
                    <td>{{ $invoice->status->label() }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</section>
@endsection
