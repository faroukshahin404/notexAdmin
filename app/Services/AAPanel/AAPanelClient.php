<?php

namespace App\Services\AAPanel;

use App\Services\Remote\SSHService;
use RuntimeException;

class AAPanelClient
{
    public function __construct(protected SSHService $ssh = new SSHService()) {}

    /**
     * Check if a website exists in nginx configs.
     */
    public function getWebsite(string $domain): array
    {
        $domainArg = escapeshellarg($domain);
        $cmd = "grep -rl $domainArg /www/server/panel/vhost/nginx/ || true";
        $result = $this->ssh->run($cmd);

        $result['exists'] = !empty(trim($result['output'] ?? ''));
        return $result;
    }

    /**
     * Create website: folder, vhost config, reload nginx.
     */
    public function createWebsite(string $domain, string $path, string $runDir = '/public'): array
    {
        $root = rtrim($path, '/');
        $publicPath = rtrim($root . '/' . ltrim($runDir, '/'), '/');
        $domainArg = escapeshellarg($domain);

        // Build config file paths
        $vhostPath = "/www/server/panel/vhost/nginx/$domain.conf";
        $wellknown = "/www/server/panel/vhost/nginx/well-known/$domain.conf";
        $logDir = "/www/wwwlogs";

        $phpSock = (string) config('aapanel.php_socket', '/tmp/php-cgi-83.sock');

        // Command script
        $cmd = <<<BASH
mkdir -p $publicPath $logDir
mkdir -p /www/server/panel/vhost/nginx/well-known
echo "" > $wellknown

cat > $vhostPath <<EOF
server {
    listen 80;
    server_name $domain;
    root $publicPath;
    index index.php index.html index.htm;

    include $wellknown;

    location / {
        try_files \$uri \$uri/ /index.php?\$query_string;
    }

    location ~ \\.php\$ {
        include fastcgi_params;
        fastcgi_pass unix:$phpSock;
        fastcgi_param SCRIPT_FILENAME \$document_root\$fastcgi_script_name;
        include pathinfo.conf;
    }

    location ~ /\\.well-known {
        allow all;
    }

    access_log $logDir/$domain.log;
    error_log $logDir/$domain.error.log;
}
EOF

nginx -t && /etc/init.d/nginx reload
BASH;

        return $this->ssh->run($cmd);
    }

    /**
     * Apply SSL using certbot (webroot mode).
     */
    public function applySSL(string $domain, string $docRoot): array
    {
        $domainArg = escapeshellarg($domain);
        $docRootArg = escapeshellarg($docRoot);

        $cmd = <<<BASH
certbot certonly --webroot -w $docRootArg -d $domainArg --agree-tos -m admin@$domain --non-interactive --force-renewal || true
BASH;

        return $this->ssh->run($cmd);
    }

    /**
     * Finalize vhost to enable HTTPS redirect + SSL.
     */
    public function enableSSLInVhost(string $domain, string $docRoot): array
    {
        $vhostPath = "/www/server/panel/vhost/nginx/$domain.conf";
        $wellknown = "/www/server/panel/vhost/nginx/well-known/$domain.conf";
        $logDir = "/www/wwwlogs";
        $phpSock = (string) config('aapanel.php_socket', '/tmp/php-cgi-83.sock');

        $cmd = <<<BASH
cat > $vhostPath <<EOF
server {
    listen 80;
    server_name $domain;
    return 301 https://\$host\$request_uri;
}

server {
    listen 443 ssl http2;
    server_name $domain;
    root $docRoot;
    index index.php index.html index.htm;

    ssl_certificate     /etc/letsencrypt/live/$domain/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/$domain/privkey.pem;

    include $wellknown;

    location / {
        try_files \$uri \$uri/ /index.php?\$query_string;
    }

    location ~ \\.php\$ {
        include fastcgi_params;
        fastcgi_pass unix:$phpSock;
        fastcgi_param SCRIPT_FILENAME \$document_root\$fastcgi_script_name;
        include pathinfo.conf;
    }

    location ~ /\\.well-known {
        allow all;
    }

    access_log $logDir/$domain.log;
    error_log $logDir/$domain.error.log;
}
EOF

nginx -t && /etc/init.d/nginx reload
BASH;

        return $this->ssh->run($cmd);
    }
}
