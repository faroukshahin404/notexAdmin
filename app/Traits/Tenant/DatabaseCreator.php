<?php

namespace App\Traits\Tenant;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

trait DatabaseCreator
{
    protected function createTenantDatabase(string $databaseName, string $username, string $password): array
    {
        $databaseName = preg_replace('/[^a-zA-Z0-9_]/', '_', $databaseName);
        $username = substr(preg_replace('/[^a-zA-Z0-9_]/', '_', $username), 0, 16);
        $password = $password ?: Str::random(24);

        DB::statement("CREATE DATABASE IF NOT EXISTS `{$databaseName}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        DB::statement("CREATE USER IF NOT EXISTS '{$username}'@'%' IDENTIFIED BY :password", ['password' => $password]);
        DB::statement("GRANT ALL PRIVILEGES ON `{$databaseName}`.* TO '{$username}'@'%'");
        DB::statement('FLUSH PRIVILEGES');

        return [
            'database' => $databaseName,
            'username' => $username,
            'password' => $password,
        ];
    }
}


