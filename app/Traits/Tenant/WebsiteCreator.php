<?php

namespace App\Traits\Tenant;

use App\Services\AAPanel\AAPanelClient;

trait WebsiteCreator
{
    protected function setupWebsite(string $domain): array
    {
        $client = new AAPanelClient();
        $path = rtrim(config('aapanel.site_root'), '/');

        $create = $client->createWebsite($domain, $path, '/public');
        $ssl = $client->applySSL($domain);

        return [
            'create_website' => $create,
            'ssl' => $ssl,
        ];
    }
}


