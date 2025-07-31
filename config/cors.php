<?php
return [

    'paths' => ['api/*', 'api/v1/*', 'admin/*', 'login', 'logout', 'csrf-token', 'admin/auth/code/captcha/*'],

    'allowed_methods' => ['*'],

    'allowed_origins' => ['http://localhost:5173', 'http://localhost:5174'],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => true,
];

