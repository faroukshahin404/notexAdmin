@extends('layouts.admin')

@section('content')
<div class="container" style="max-width:640px;">
    <h1 class="mb-4">Change Password</h1>
    <form method="POST" action="{{ route('admin.password.update') }}">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label class="form-label">Current Password</label>
            <input type="password" name="current_password" class="form-control" required>
            @error('current_password')<div class="text-danger small">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label class="form-label">New Password</label>
            <input type="password" name="password" class="form-control" required>
            @error('password')<div class="text-danger small">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label class="form-label">Confirm Password</label>
            <input type="password" name="password_confirmation" class="form-control" required>
        </div>
        <button class="btn btn-primary" type="submit">Update Password</button>
    </form>
</div>
@endsection


