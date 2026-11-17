@extends('layouts.marketing')

@section('title', 'Features')
@section('content')
<section class="section">
    <div class="eyebrow">Capabilities</div>
    <h2>Everything a tax-compliant invoice stack needs</h2>
    <p style="color:var(--muted);max-width:640px">From jurisdiction catalogs to digital signatures and fiscal packs — features are grouped by module category so finance teams can adopt only what auditors require.</p>
</section>
@foreach($modules as $category => $items)
<section class="section">
    <h2 style="font-size:1.6rem;text-transform:capitalize">{{ str_replace('_', ' ', $category) }}</h2>
    <div class="grid" style="margin-top:1rem">
        @foreach($items as $module)
            <div class="card">
                <span class="pill {{ $module->is_enabled ? '' : 'off' }}">{{ $module->is_enabled ? 'Enabled' : 'Disabled' }}</span>
                <h3>{{ $module->name }}</h3>
                <p>{{ $module->description }}</p>
            </div>
        @endforeach
    </div>
</section>
@endforeach
@endsection
