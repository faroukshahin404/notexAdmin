<?php

namespace App\Traits\Tenant;

use App\Services\AAPanel\AAPanelClient;

trait WebsiteCreator
{
    protected function setupWebsite(string $domain): array
    {
        $client = new AAPanelClient();
        // $path = rtrim(config('aapanel.site_root'), '/');

        $result = $client->createFullWebsite($domain, '/www/wwwroot/NoteX');
        // $ssl = $client->applySSL($domain, $path);
        
        return [
            'create_website' => $result,
        ];
    }
}


