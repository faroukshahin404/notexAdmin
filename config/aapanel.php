<?php

return [
    'base_url' => env('AAPANEL_BASE_URL', 'http://31.97.78.171:31423'),
    'api_key' => env('AAPANEL_API_KEY', 'rEujraRHzGSH3hn3UsRyjfdXyM6aomdO'),
    'php_version' => env('AAPANEL_PHP_VERSION', '80'),
    'site_root' => env('AAPANEL_SITE_ROOT', '/www/wwwroot/NoteX'),

    // SSH settings (preferred over HTTP API)
    'ssh_host' => env('AAPANEL_SSH_HOST'),
    'ssh_port' => env('AAPANEL_SSH_PORT', 22),
    'ssh_user' => env('AAPANEL_SSH_USER', 'root'),
    'ssh_password' => env('AAPANEL_SSH_PASSWORD' , "d(/+G@QUeuU20,sER&G9"),
    'use_sshpass' => env('AAPANEL_USE_SSHPASS', true),
    'use_sudo' => env('AAPANEL_SSH_USE_SUDO', false),

    // MySQL root credentials for remote DB/user creation over SSH
    'mysql_root_password' => env('AAPANEL_MYSQL_ROOT_PASSWORD', '2e3421504169b994'),
    'mysql_root_user' => env('AAPANEL_MYSQL_ROOT_USER', 'root'),
    'mysql_user_host' => env('AAPANEL_MYSQL_USER_HOST', 'localhost'),
];


