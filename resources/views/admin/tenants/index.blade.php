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
                <td>
                    <a href="https://{{ $tenant->host }}" target="_blank" rel="noopener">{{ $tenant->host }}</a>
                </td>
                <td>{{ $tenant->type }}</td>
                <td>{{ $tenant->is_installed ? 'Yes' : 'No' }}</td>
                <td>
                    <button type="button" class="btn btn-sm btn-outline-primary me-1 copy-db"
                        data-db="{{ $tenant->database }}"
                        data-user="{{ $tenant->username }}"
                        data-pass="{{ $tenant->password }}"
                        title="Copy DB info to clipboard">Copy DB</button>
                    <button type="button" class="btn btn-sm btn-outline-success me-1 migrate-btn" data-id="{{ $tenant->id }}">
                        <span class="migrate-label">Run Migrate</span>
                        <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                    </button>
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

    <script>
        document.addEventListener('click', function(e) {
            const btn = e.target.closest('.copy-db');
            if (!btn) return;
            const db = btn.getAttribute('data-db');
            const user = btn.getAttribute('data-user');
            const pass = btn.getAttribute('data-pass');
            const text = `DB: ${db}\nUser: ${user}\nPass: ${pass}`;
            navigator.clipboard.writeText(text).then(() => {
                btn.textContent = 'Copied';
                setTimeout(() => { btn.textContent = 'Copy DB'; }, 1500);
            }).catch(() => {
                alert('Failed to copy');
            });
        });

        document.addEventListener('click', async function(e) {
            const btn = e.target.closest('.migrate-btn');
            if (!btn) return;
            const id = btn.getAttribute('data-id');
            const label = btn.querySelector('.migrate-label');
            const spinner = btn.querySelector('.spinner-border');
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            btn.disabled = true;
            spinner.classList.remove('d-none');
            label.textContent = 'Running...';
            try {
                const res = await fetch(`/admin/tenants/${id}/migrate`, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': token, 'Accept': 'application/json' }
                });
                const data = await res.json();
                label.textContent = data.success ? 'Done' : 'Failed';
            } catch (err) {
                label.textContent = 'Failed';
            } finally {
                setTimeout(() => {
                    spinner.classList.add('d-none');
                    btn.disabled = false;
                    label.textContent = 'Run Migrate';
                }, 2000);
            }
        });
    </script>
</div>
@endsection


