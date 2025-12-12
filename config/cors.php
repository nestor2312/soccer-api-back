<?php

return [

    'paths' => ['api/*', 'sanctum/csrf-cookie'],
    'allowed_methods' => ['*'],
    'allowed_origins' => ['http://localhost:5173','http://192.168.56.1:5173', 'https://*.loca.lt','https://mldcbh3p-8000.use2.devtunnels.ms', 'https://soccer-react-api-test.vercel.app', 'https://demo.fubolzona.com'],  // La dirección de tu aplicación React

    'allowed_origins_patterns' => [],
    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => true,

];
