@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>Create Tenant</h1>
    <form method="POST" action="{{ route('admin.tenants.store') }}">
        @csrf
        <div class="mb-3">
            <label class="form-label">Name</label>
            <input type="text" name="name" id="tenant-name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Host</label>
            <input type="text" name="host" id="tenant-host" class="form-control" placeholder="sub.example.com" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Database</label>
            <input type="text" name="database" id="tenant-database" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">DB Username</label>
            <input type="text" name="username" id="tenant-username" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">DB Password</label>
            <div class="input-group">
                <input type="text" name="password" id="tenant-password" class="form-control" required>
                <button class="btn btn-outline-secondary" type="button" id="generate-password">Generate</button>
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" placeholder="admin@example.com">
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
            <input type="number" step="0.01" name="monthly_payment" class="form-control" value="0">
        </div>
        <button class="btn btn-primary" type="submit" id="tenant-submit">
            <span class="submit-label">Save</span>
            <span class="spinner-border spinner-border-sm ms-2 d-none" role="status" aria-hidden="true"></span>
        </button>
    </form>

    <script>
        function slugify(text) {
            return text
                .toString()
                .normalize('NFD').replace(/[\u0300-\u036f]/g, '')
                .toLowerCase()
                .replace(/[^a-z0-9]+/g, '-')
                .replace(/(^-|-$)+/g, '');
        }

        function generatePassword(length = 16) {
            const charset = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz23456789!@#$%^&*()_+';
            let pwd = '';
            const array = new Uint32Array(length);
            if (window.crypto && window.crypto.getRandomValues) {
                window.crypto.getRandomValues(array);
                for (let i = 0; i < length; i++) {
                    pwd += charset[array[i] % charset.length];
                }
            } else {
                for (let i = 0; i < length; i++) {
                    pwd += charset[Math.floor(Math.random() * charset.length)];
                }
            }
            return pwd;
        }

        const nameInput = document.getElementById('tenant-name');
        const hostInput = document.getElementById('tenant-host');
        const dbInput = document.getElementById('tenant-database');
        const userInput = document.getElementById('tenant-username');
        const passInput = document.getElementById('tenant-password');
        const genBtn = document.getElementById('generate-password');

        function syncFromName() {
            const slug = slugify(nameInput.value || '');
            if (!slug) return;
            const domain = slug + '.inote-tech.com';
            hostInput.value = domain;
            dbInput.value = slug.replace(/-/g, '_');
            userInput.value = slug.replace(/-/g, '_');
            if (!passInput.value) {
                passInput.value = generatePassword();
            }
        }

        nameInput.addEventListener('input', syncFromName);
        genBtn.addEventListener('click', function() {
            passInput.value = generatePassword();
        });

        // Submit loading state
        const form = document.querySelector('form[action="{{ route('admin.tenants.store') }}"]');
        const submitBtn = document.getElementById('tenant-submit');
        const submitLabel = submitBtn.querySelector('.submit-label');
        const submitSpinner = submitBtn.querySelector('.spinner-border');
        form.addEventListener('submit', function() {
            submitBtn.disabled = true;
            submitSpinner.classList.remove('d-none');
            submitLabel.textContent = 'Creating...';
        });
    </script>
</div>
@endsection


