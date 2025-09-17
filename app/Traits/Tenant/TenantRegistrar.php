<?php

namespace App\Traits\Tenant;

use App\Models\Tenant;

trait TenantRegistrar
{
    protected function registerTenant(array $data): Tenant
    {
        return Tenant::create([
            'name' => $data['name'],
            'host' => $data['host'],
            'port' => $data['port'] ?? '80',
            'database' => $data['database'],
            'username' => $data['username'],
            'password' => $data['password'],
            'email' => $data['email'] ?? null,
            'phone' => $data['phone'] ?? null,
            'type' => $data['type'] ?? 'demo',
            'monthly_payment' => $data['monthly_payment'] ?? null,
            'is_installed' => $data['is_installed'] ?? 0,
            'installation_date' => $data['installation_date'] ?? null,
            'expired_date' => $data['expired_date'] ?? null,
        ]);
    }
}


