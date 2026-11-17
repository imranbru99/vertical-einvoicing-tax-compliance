@extends('layouts.marketing')

@section('title', 'Customer Portal')
@section('content')
<section class="section">
    <div class="eyebrow">Customer portal</div>
    <h2>Self-serve invoice visibility</h2>
    @unless($moduleOn)
        <p class="pill off">Customer portal module is currently OFF</p>
    @endunless
    @if($customer)
        <div class="card" style="margin:1rem 0">
            <h3>{{ $customer->name }}</h3>
            <p>Tax ID {{ $customer->tax_id }} · {{ $customer->country_code }} · portal token ready for deep links</p>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Invoice</th>
                    <th>Country</th>
                    <th>Issue date</th>
                    <th>Status</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @forelse($invoices as $invoice)
                    <tr>
                        <td>{{ $invoice->number }}</td>
                        <td>{{ $invoice->jurisdiction?->country_code }}</td>
                        <td>{{ optional($invoice->issue_date)->toDateString() }}</td>
                        <td>{{ $invoice->status->label() }}</td>
                        <td>{{ $invoice->currency }} {{ number_format((float) $invoice->total, 2) }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5">No invoices for this portal customer yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    @else
        <p style="color:var(--muted)">No portal-enabled customer found. Seed the database to unlock the demo portal.</p>
    @endif
</section>
@endsection
