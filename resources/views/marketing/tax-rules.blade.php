@extends('layouts.marketing')

@section('title', 'Tax Rules')
@section('content')
<section class="section">
    <div class="eyebrow">VAT / GST catalog</div>
    <h2>Jurisdiction tax rules</h2>
    <div class="grid" style="margin:1rem 0 1.5rem">
        @foreach($jurisdictions as $j)
            <div class="card">
                <h3>{{ $j->name }} ({{ $j->country_code }})</h3>
                <p>{{ $j->authority_name }} · {{ strtoupper($j->default_scheme) }} · {{ $j->tax_rules_count }} rules · format {{ $j->einvoice_format }}</p>
            </div>
        @endforeach
    </div>
    <table>
        <thead>
            <tr>
                <th>Code</th>
                <th>Name</th>
                <th>Country</th>
                <th>Scheme</th>
                <th>Rate</th>
                <th>Default</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rules as $rule)
                <tr>
                    <td>{{ $rule->code }}</td>
                    <td>{{ $rule->name }}</td>
                    <td>{{ $rule->jurisdiction?->country_code }}</td>
                    <td>{{ $rule->scheme->label() }}</td>
                    <td>{{ rtrim(rtrim(number_format((float) $rule->rate_percent, 2), '0'), '.') }}%</td>
                    <td>{{ $rule->is_default ? 'Yes' : 'No' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</section>
@endsection
