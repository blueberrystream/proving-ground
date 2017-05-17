<?php
$dotenv = new Dotenv\Dotenv(__DIR__);
$dotenv->load();

$credentials = parse_url(getenv('DATABASE_URL'));
$host = $credentials['host'];
$dbname = substr($credentials['path'], 1);
$user = $credentials['user'];
$password = $credentials['pass'];

return [
    'propel' => [
        'database' => [
            'connections' => [
                'default' => [
                    'adapter' => 'pgsql',
                    'dsn' => "pgsql:host=${host};port=5432;dbname=${dbname}",
                    'user' => $user,
                    'password' => $password,
                    'settings' => [
                        'charset' => 'utf8'
                    ]
                ]
            ]
        ],
        'paths' => [
            'schemaDir' => 'database',
            'phpConfDir' => 'database',
            'phpDir' => 'src',
            'migrationDir' => 'database/migration',
            'sqlDir' => 'database/sql',
        ],
        'generator' => [
            'schema' => [
                'autoPackage' => true,
            ],
        ],
    ],
];
