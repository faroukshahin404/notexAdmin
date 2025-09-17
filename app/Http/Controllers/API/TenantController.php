<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Repositories\TenantRepository;
use App\Services\TenantMigrationRunner;
use App\Services\TenantStatisticsService;
use App\Traits\Tenant\DatabaseCreator;
use App\Traits\Tenant\WebsiteCreator;
use App\Traits\Tenant\TenantRegistrar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class TenantController extends Controller
{
    use DatabaseCreator, WebsiteCreator, TenantRegistrar;

    public function __construct(
        protected TenantRepository $tenants,
        protected TenantStatisticsService $stats,
        protected TenantMigrationRunner $migrator
    ) {
    }

    public function index(Request $request)
    {
        $list = $this->tenants->list($request->only(['type', 'is_installed', 'search']), (int) $request->get('per_page', 15));
        return response()->json($list);
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
            'type' => ['nullable', Rule::in(['demo', 'paid'])],
            'monthly_payment' => ['nullable', 'numeric', 'min:0'],
        ]);

        return DB::transaction(function () use ($data) {
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

            return response()->json([
                'success' => true,
                'tenant_id' => $tenant->id,
                'domain' => $tenant->host,
                'db' => $tenant->database,
                'ssl' => 'applied',
                'migration' => $migration['success'] ? 'completed' : 'failed',
            ]);
        });
    }

    public function show(int $id)
    {
        $tenant = $this->tenants->find($id);
        if (!$tenant) {
            return response()->json(['message' => 'Not found'], 404);
        }
        return response()->json($tenant);
    }

    public function update(Request $request, int $id)
    {
        $tenant = Tenant::findOrFail($id);
        $data = $request->validate([
            'name' => ['sometimes', 'string', 'max:191'],
            'host' => ['sometimes', 'string', 'max:191', Rule::unique('tenants', 'host')->ignore($tenant->id)],
            'port' => ['sometimes', 'string', 'max:10'],
            'email' => ['nullable', 'email'],
            'phone' => ['nullable', 'string', 'max:50'],
            'type' => ['nullable', Rule::in(['demo', 'paid'])],
            'monthly_payment' => ['nullable', 'numeric', 'min:0'],
            'expired_date' => ['nullable', 'date'],
        ]);
        $this->tenants->update($tenant, $data);
        return response()->json($tenant);
    }

    public function destroy(int $id)
    {
        $tenant = Tenant::find($id);
        if (!$tenant) {
            return response()->json(['message' => 'Not found'], 404);
        }
        $this->tenants->delete($tenant);
        return response()->json(['success' => true]);
    }

    public function stats()
    {
        return response()->json($this->stats->getOverview());
    }
}


