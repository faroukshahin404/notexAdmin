<?php

namespace App\Services\AAPanel;

use App\Services\Remote\SSHService;

class AAPanelClient
{
    public function __construct(protected SSHService $ssh = new SSHService()) {}

    public function getWebsite(string $domain): array
    {
        $domainArg = escapeshellarg($domain);
        $cmd = "bt site list | grep -i $domainArg || true";
        return $this->ssh->run($cmd);
    }

    public function createWebsite(string $domain, string $path, string $runDir = '/public'): array
    {
        $root = rtrim($path, '/');
        $publicPath = rtrim($root . '/' . ltrim($runDir, '/'), '/');
        $phpVersion = escapeshellarg((string) config('aapanel.php_version', '80'));
        $domainArg = escapeshellarg($domain);
        $rootArg = escapeshellarg($root);
        $publicArg = escapeshellarg($publicPath);

        $cmd = "mkdir -p $root && mkdir -p $publicPath && chown -R www:www $root; btcli site add --domain=$domainArg --path=$rootArg --php=$phpVersion --run=$publicArg || true";
        return $this->ssh->run($cmd);
    }

    public function applySSL(string $domain): array
    {
        $domainArg = escapeshellarg($domain);
        $cmd = "btcli ssl apply --domain=$domainArg --type=letsencrypt || bt ssl --domain $domainArg";
        return $this->ssh->run($cmd);
    }
}


