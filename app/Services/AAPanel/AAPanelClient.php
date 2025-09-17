<?php

namespace App\Services\AAPanel;

use App\Services\Remote\SSHService;

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
     * Create aaPanel-style website vhost exactly like exodus-erp.
     */
    public function createWebsite(string $domain, string $path, string $runDir = '/public'): array
{
    $root       = rtrim($path, '/');
    $publicPath = rtrim($root . '/' . ltrim($runDir, '/'), '/');

    $vhostPath  = "/www/server/panel/vhost/nginx/$domain.conf";
    $wellknown  = "/www/server/panel/vhost/nginx/well-known/$domain.conf";
    $rewrite    = "/www/server/panel/vhost/rewrite/$domain.conf";
    $logDir     = "/www/wwwlogs";

    $phpConf    = (string) config('aapanel.php_conf', 'enable-php-83.conf');

    // prepare the command with real values already substituted
    $cmd = <<<BASH
mkdir -p "$publicPath" "$logDir"
mkdir -p /www/server/panel/vhost/nginx/well-known
mkdir -p /www/server/panel/vhost/rewrite
touch "$wellknown"
touch "$rewrite"

cat > "$vhostPath" <<EOF
server
{
    listen 80;
    listen 443 ssl http2 ;
    server_name $domain;
    index index.php index.html index.htm default.php default.htm default.html;
    root $publicPath;

    #CERT-APPLY-CHECK--START
    include $wellknown;
    #CERT-APPLY-CHECK--END

    #SSL-START
    ssl_certificate    /www/server/panel/vhost/cert/$domain/fullchain.pem;
    ssl_certificate_key    /www/server/panel/vhost/cert/$domain/privkey.pem;
    #SSL-END

    error_page 404 /404.html;
    error_page 502 /502.html;

    include $phpConf;
    include $rewrite;

    location ~ ^/(\\.user.ini|\\.htaccess|\\.git|\\.env|\\.svn|\\.project|LICENSE|README.md) {
        return 404;
    }

    location ~ \\.well-known { allow all; }

    location / {
        try_files \$uri \$uri/ /index.php?\$query_string;
    }

    access_log  $logDir/$domain.log;
    error_log   $logDir/$domain.error.log;
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
    public function createFullWebsite(string $domain, string $path, string $runDir = '/public'): array
    {
       

        return [];
    }
}
