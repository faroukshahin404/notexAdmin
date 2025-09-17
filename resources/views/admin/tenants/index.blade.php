@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Tenants</h1>
        <a class="btn btn-primary" href="{{ route('admin.tenants.create') }}">Create Tenant</a>
    </div>

    @if(session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Host</th>
                <th>Type</th>
                <th>Installed</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($list as $tenant)
            <tr>
                <td>{{ $tenant->id }}</td>
                <td>{{ $tenant->name }}</td>
                <td>{{ $tenant->host }}</td>
                <td>{{ $tenant->type }}</td>
                <td>{{ $tenant->is_installed ? 'Yes' : 'No' }}</td>
                <td>
                    <a href="{{ route('admin.tenants.edit', $tenant) }}" class="btn btn-sm btn-secondary">Edit</a>
                    <form action="{{ route('admin.tenants.destroy', $tenant) }}" method="POST" style="display:inline-block">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger" onclick="return confirm('Delete?')">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{ $list->links() }}
</div>
@endsection


