<?php

namespace App\Services\Remote;

use Symfony\Component\Process\Process;

class SSHService
{
    public function run(string $command): array
    {
        $host = config('aapanel.ssh_host');
        $port = (string) config('aapanel.ssh_port', '22');
        $user = config('aapanel.ssh_user', 'root');
        $password = config('aapanel.ssh_password');
        $useSshpass = (bool) config('aapanel.use_sshpass', true);

        $sshBase = [
            'ssh',
            '-p', $port,
            '-o', 'StrictHostKeyChecking=no',
            sprintf('%s@%s', $user, $host),
            $command,
        ];

        if ($useSshpass && !empty($password)) {
            $full = array_merge(['sshpass', '-p', $password], $sshBase);
        } else {
            $full = $sshBase;
        }

        $process = new Process($full, null, null, null, 300);
        $process->run();

        return [
            'success' => $process->isSuccessful(),
            'output' => $process->getOutput(),
            'error' => $process->getErrorOutput(),
            'exit_code' => $process->getExitCode(),
        ];
    }
}


