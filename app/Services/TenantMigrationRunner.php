<?php

namespace App\Services;

use Symfony\Component\Process\Process;

class TenantMigrationRunner
{
    public function runMigrationsForTenant(int $tenantId): array
    {
        $projectPath = base_path();
                // cd /www/wwwroot/NoteX && php artisan tenant:migrate 

        $command = [
            'cd /www/wwwroot/NoteX &&',
            PHP_BINARY,
            'artisan',
            'tenant:migrate',
        ];
        // command 

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


