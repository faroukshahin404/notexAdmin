<?php

namespace App\Repositories;

use App\Models\Tenant;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Services\Remote\DBProvisioner;

class TenantRepository
{
    public function __construct(protected DBProvisioner $dbProvisioner = new DBProvisioner()) {}
    public function list(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = Tenant::query();

        if (!empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }
        if (array_key_exists('is_installed', $filters)) {
            $query->where('is_installed', (bool) $filters['is_installed']);
        }
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('host', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        return $query->latest()->paginate($perPage);
    }

    public function find(int $id): ?Tenant
    {
        return Tenant::find($id);
    }

    public function create(array $data): Tenant
    {
        return Tenant::create($data);
    }

    public function update(Tenant $tenant, array $data): Tenant
    {
        $tenant->update($data);
        return $tenant;
    }

    public function delete(Tenant $tenant): bool
    {
        // delete db of the tenant
        $this->dbProvisioner->dropDatabaseAndUser($tenant->database, $tenant->username);
        return (bool) $tenant->delete();
    }
}


