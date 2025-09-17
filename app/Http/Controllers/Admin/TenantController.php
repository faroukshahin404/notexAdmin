<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Repositories\TenantRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Traits\Tenant\DatabaseCreator;
use App\Traits\Tenant\WebsiteCreator;
use App\Traits\Tenant\TenantRegistrar;
use App\Services\TenantMigrationRunner;

class TenantController extends Controller
{
    use DatabaseCreator, WebsiteCreator, TenantRegistrar;

    public function __construct(protected TenantRepository $tenants, protected TenantMigrationRunner $migrator)
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

        $result = DB::transaction(function () use ($data) {
            $db = $this->createTenantDatabase($data['database'], $data['username'], $data['password']);
            $site = $this->setupWebsite($data['host']);

            $tenant = $this->registerTenant(array_merge($data, [
                'database' => $db['database'],
                'username' => $db['username'],
                'password' => $db['password'],
            ]));

            $migration = $this->migrator->runMigrationsForTenant($tenant->id);

            $tenant->is_installed = $migration['success'];
            if ($tenant->is_installed) {
                $tenant->installation_date = \Carbon\CarbonImmutable::today();
            }
            $tenant->save();

            return [
                'tenant' => $tenant,
                'migration' => $migration,
                'site' => $site,
            ];
        });

        return redirect()
            ->route('admin.tenants.index')
            ->with('status', 'Tenant created. Migration: ' . ($result['migration']['success'] ? 'completed' : 'failed'));
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


