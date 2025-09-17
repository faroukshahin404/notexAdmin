@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>Edit Tenant</h1>
    <form method="POST" action="{{ route('admin.tenants.update', $tenant) }}">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label class="form-label">Name</label>
            <input type="text" name="name" class="form-control" value="{{ $tenant->name }}">
        </div>
        <div class="mb-3">
            <label class="form-label">Host</label>
            <input type="text" name="host" class="form-control" value="{{ $tenant->host }}">
        </div>
        <div class="mb-3">
            <label class="form-label">Port</label>
            <input type="text" name="port" class="form-control" value="{{ $tenant->port }}">
        </div>
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="{{ $tenant->email }}">
        </div>
        <div class="mb-3">
            <label class="form-label">Phone</label>
            <input type="text" name="phone" class="form-control" value="{{ $tenant->phone }}">
        </div>
        <div class="mb-3">
            <label class="form-label">Type</label>
            <select name="type" class="form-select">
                <option value="demo" {{ $tenant->type === 'demo' ? 'selected' : '' }}>Demo</option>
                <option value="paid" {{ $tenant->type === 'paid' ? 'selected' : '' }}>Paid</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Monthly Payment</label>
            <input type="number" step="0.01" name="monthly_payment" class="form-control" value="{{ $tenant->monthly_payment }}">
        </div>
        <div class="mb-3">
            <label class="form-label">Expired Date</label>
            <input type="date" name="expired_date" class="form-control" value="{{ $tenant->expired_date?->format('Y-m-d') }}">
        </div>
        <button class="btn btn-primary" type="submit">Update</button>
    </form>
</div>
@endsection


