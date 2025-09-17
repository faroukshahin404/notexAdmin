<?php

return [
    'base_url' => env('AAPANEL_BASE_URL', 'https://your-aapanel-host:7800'),
    'api_key' => env('AAPANEL_API_KEY', ''),
    'php_version' => env('AAPANEL_PHP_VERSION', '80'),
    'site_root' => env('AAPANEL_SITE_ROOT', '/www/wwwroot/NoteX'),

    // SSH settings (preferred over HTTP API)
    'ssh_host' => env('AAPANEL_SSH_HOST'),
    'ssh_port' => env('AAPANEL_SSH_PORT', 22),
    'ssh_user' => env('AAPANEL_SSH_USER', 'root'),
    'ssh_password' => env('AAPANEL_SSH_PASSWORD'),
    'use_sshpass' => env('AAPANEL_USE_SSHPASS', true),
];


