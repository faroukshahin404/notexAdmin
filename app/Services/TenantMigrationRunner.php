<?php

namespace App\Services;

use Symfony\Component\Process\Process;

class TenantMigrationRunner
{
    public function runMigrationsForTenant(int $tenantId): array
    {
        $process = Process::fromShellCommandline(
    'cd /www/wwwroot/NoteX && php artisan tenant:migrate',
    null,
    null,
    null,
    600
);

        $process->run();

        return [
            'success' => $process->isSuccessful(),
            'output' => $process->getOutput(),
            'error' => $process->getErrorOutput(),
            'exit_code' => $process->getExitCode(),
        ];
    }
}


