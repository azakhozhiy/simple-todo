# Installation

Requirements: 
- PostgreSQL
- PHP 7.4

PHP extensions requirement:
- ext-pdo
- ext-json
- ext-gd
- ext-mbstring

Clone repo.
```
git clone https://github.com/azakhozhy/unlimint-todo
```

Install composer packages

```
composer install
```

Setting up connection to database, edit phinx.php and config.php files in root folder.

```php
<?php

const ENV_PROD = 'prod';
const ENV_DEV = 'dev';

return [
    'app' => [
        'env' => ENV_PROD,
        'db_host' => 'localhost',
        'db_port' => 5432,
        'db_database' => 'unlimint',
        'db_username' => 'homestead',
        'db_password' => 'secret',
    ],
];
```

Install NPM dependencies
```
npm i
```

Build styles and scripts.
```
npm run production
```


