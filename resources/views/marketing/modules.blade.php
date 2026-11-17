@extends('layouts.marketing')

@section('title', 'Modules')
@section('content')
<section class="section">
    <div class="eyebrow">Modular runtime</div>
    <h2>Turn modules on or off per business</h2>
    <p style="color:var(--muted)">Core modules stay protected. Optional bridges (PEPPOL, attendance, AI assist) can be disabled without redeploying.</p>
    <table style="margin-top:1.25rem">
        <thead>
            <tr>
                <th>Module</th>
                <th>Key</th>
                <th>Category</th>
                <th>Core</th>
                <th>Status</th>
                <th>Hrs/wk</th>
            </tr>
        </thead>
        <tbody>
            @foreach($modules as $module)
                <tr>
                    <td>{{ $module->name }}</td>
                    <td><code>{{ $module->key }}</code></td>
                    <td>{{ $module->category }}</td>
                    <td>{{ $module->is_core ? 'Yes' : 'No' }}</td>
                    <td><span class="pill {{ $module->is_enabled ? '' : 'off' }}">{{ $module->is_enabled ? 'ON' : 'OFF' }}</span></td>
                    <td>{{ $module->estimated_hours_saved_weekly }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</section>
@endsection
