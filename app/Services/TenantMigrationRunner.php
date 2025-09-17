<?php

namespace App\Services;

use Symfony\Component\Process\Process;

class TenantMigrationRunner
{
    public function runMigrationsForTenant(int $tenantId): array
    {
        $projectPath = base_path();
        $command = [
            PHP_BINARY,
            'artisan',
            'tenant:migrate',
            "--tenant={$tenantId}",
        ];

        $process = new Process($command, $projectPath, null, null, 600);
        $process->run();

        return [
            'success' => $process->isSuccessful(),
            'output' => $process->getOutput(),
            'error' => $process->getErrorOutput(),
            'exit_code' => $process->getExitCode(),
        ];
    }
}


