<?php

namespace App\Services\AAPanel;

use GuzzleHttp\Client;

class AAPanelClient
{
    protected Client $http;
    protected string $baseUrl;
    protected string $apiKey;

    public function __construct()
    {
        $this->baseUrl = rtrim(config('aapanel.base_url'), '/');
        $this->apiKey = (string) config('aapanel.api_key');
        $this->http = new Client([
            'base_uri' => $this->baseUrl,
            'timeout' => 60,
            'verify' => false,
        ]);
    }

    public function getWebsite(string $domain): array
    {
        $response = $this->http->get('/site?action=GetSiteList');
        return json_decode((string) $response->getBody(), true);
    }

    public function createWebsite(string $domain, string $path, string $runDir = '/public'): array
    {
        $payload = [
            'domain' => $domain,
            'path' => $path,
            'type' => 'PHP',
            'run_path' => $runDir,
            'php_version' => config('aapanel.php_version', '80'),
            'access_log' => true,
            'auth' => $this->apiKey,
        ];

        $response = $this->http->post('/api/website/create', ['form_params' => $payload]);
        return json_decode((string) $response->getBody(), true);
    }

    public function applySSL(string $domain): array
    {
        $payload = [
            'domain' => $domain,
            'ssl_type' => 'lets',
            'auth' => $this->apiKey,
        ];

        $response = $this->http->post('/api/ssl/apply', ['form_params' => $payload]);
        return json_decode((string) $response->getBody(), true);
    }
}


