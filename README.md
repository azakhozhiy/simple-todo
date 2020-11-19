# Simple TODO application

Requirements: 
- PostgreSQL
- PHP 7.4

PHP extensions requirement:
- ext-pdo
- ext-json
- ext-gd
- ext-mbstring

## Installation

Clone repo.
```
git clone https://github.com/azakhozhy/unlimint-todo
```

Install composer packages

```
composer install
```

Setting up connection to database, copy base config phinx and app config. 

Don't forget to set your values for connecting to the database.
```
cat config.example.php >> config.php
cat phinx.example.php >> phinx.php
```

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

Start migrations
```
vendor/bin/phinx migrate
```

Install NPM dependencies
```
npm i
```

Build styles and scripts.
```
npm run production
```


