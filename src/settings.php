<?php
return [
    'settings' => [
        'displayErrorDetails' => true,

        // Renderer settings
        'renderer' => [
            'template_path' => __DIR__ . '/../templates/',
        ],

        // Monolog settings
        'logger' => [
            'name' => 'slim-app',
            'path' => __DIR__ . '/../logs/app.log',
        ],

        'languages' => [
            'es' => 'es_ES.UTF-8',
            'en' => 'en_GB.UTF-8',
        ],
        
        // Configuración de mi APP
        'app_token_name'   => 'GRANADA-TOKEN',
        'connectionString' => [
            'dns'  => 'mysql:host=localhost;dbname=granada;charset=utf8',
            'user' => 'granada',
            'pass' => 'granada'
        ],
        'emailString' => [
            'host'          => '',
            'smtp_auth'     => '',
            'smtp_secure'   => '',
            'port'          => '',
            'username'      => '',
            'password'      => '',
        ]
    ],
];
