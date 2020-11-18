<?php

namespace App\Packages\Core;

class Database
{
    protected $connection;

    public function __construct(string $host, int $port, string $db_name, string $db_user, string $password)
    {
        $this->connection = pg_connect("host=$host port=$port dbname=$db_name user=$db_user password=$password");
    }

    public function getConnection()
    {
        return $this->connection;
    }
}
