@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>Create Tenant</h1>
    <form method="POST" action="{{ route('admin.tenants.store') }}">
        @csrf
        <div class="mb-3">
            <label class="form-label">Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Host</label>
            <input type="text" name="host" class="form-control" placeholder="sub.example.com" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Database</label>
            <input type="text" name="database" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">DB Username</label>
            <input type="text" name="username" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">DB Password</label>
            <input type="text" name="password" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control">
        </div>
        <div class="mb-3">
            <label class="form-label">Phone</label>
            <input type="text" name="phone" class="form-control">
        </div>
        <div class="mb-3">
            <label class="form-label">Type</label>
            <select name="type" class="form-select">
                <option value="demo">Demo</option>
                <option value="paid">Paid</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Monthly Payment</label>
            <input type="number" step="0.01" name="monthly_payment" class="form-control">
        </div>
        <button class="btn btn-primary" type="submit">Save</button>
    </form>
</div>
@endsection


