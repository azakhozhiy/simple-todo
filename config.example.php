<?php

const ENV_PROD = 'prod';
const ENV_DEV = 'dev';

return [
    'env' => 'dev',
    'dev' => [
        'db_host' => 'localhost',
        'db_port' => 5432,
        'db_database' => 'unlimint',
        'db_username' => 'homestead',
        'db_password' => 'secret',
    ],
    'prod' => [
        'db_host' => 'localhost',
        'db_port' => 5432,
        'db_database' => 'unlimint',
        'db_username' => 'homestead',
        'db_password' => 'secret',
    ],
];
