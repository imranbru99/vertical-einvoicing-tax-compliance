@extends('layouts.marketing')

@section('title', 'Demo Access')
@section('content')
<section class="section">
    <div class="eyebrow">Try the stack</div>
    <h2>Demo credentials</h2>
    <div class="card" style="max-width:520px;margin-top:1rem">
        <p><strong>Email:</strong> <code>{{ $email }}</code></p>
        <p style="margin-top:.6rem"><strong>Password:</strong> <code>{{ $password }}</code></p>
        <p style="margin-top:1rem;color:var(--muted)">Use the Demo role in Filament at <a href="/admin">/admin</a>. Additional seeded roles: Super Admin, Admin, Manager, Staff, Client.</p>
        <div class="cta-row" style="margin-top:1rem">
            <a class="btn" href="/admin">Go to Admin</a>
            <a class="btn secondary" href="{{ route('portal') }}">Open customer portal</a>
        </div>
    </div>
</section>
@endsection
