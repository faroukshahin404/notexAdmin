<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Repositories\TenantRepository;
use Illuminate\Http\Request;

class TenantController extends Controller
{
    public function __construct(protected TenantRepository $tenants)
    {
    }

    public function index(Request $request)
    {
        $list = $this->tenants->list($request->only(['type', 'is_installed', 'search']), 20);
        return view('admin.tenants.index', compact('list'));
    }

    public function create()
    {
        return view('admin.tenants.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:191'],
            'host' => ['required', 'string', 'max:191', 'unique:tenants,host'],
            'database' => ['required', 'string', 'max:191'],
            'username' => ['required', 'string', 'max:191'],
            'password' => ['required', 'string', 'max:191'],
            'email' => ['nullable', 'email'],
            'phone' => ['nullable', 'string', 'max:50'],
            'type' => ['nullable', 'in:demo,paid'],
            'monthly_payment' => ['nullable', 'numeric', 'min:0'],
        ]);

        $this->tenants->create($data);
        return redirect()->route('admin.tenants.index')->with('status', 'Tenant created.');
    }

    public function edit(Tenant $tenant)
    {
        return view('admin.tenants.edit', compact('tenant'));
    }

    public function update(Request $request, Tenant $tenant)
    {
        $data = $request->validate([
            'name' => ['sometimes', 'string', 'max:191'],
            'host' => ['sometimes', 'string', 'max:191', "unique:tenants,host,{$tenant->id}"],
            'port' => ['sometimes', 'string', 'max:10'],
            'email' => ['nullable', 'email'],
            'phone' => ['nullable', 'string', 'max:50'],
            'type' => ['nullable', 'in:demo,paid'],
            'monthly_payment' => ['nullable', 'numeric', 'min:0'],
            'expired_date' => ['nullable', 'date'],
        ]);
        $this->tenants->update($tenant, $data);
        return redirect()->route('admin.tenants.index')->with('status', 'Tenant updated.');
    }

    public function destroy(Tenant $tenant)
    {
        $this->tenants->delete($tenant);
        return redirect()->route('admin.tenants.index')->with('status', 'Tenant deleted.');
    }
}


