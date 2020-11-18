<?php

declare(strict_types=1);

namespace App\Packages\Core\Engine;

use PDO;

class Database
{
    protected PDO $connection;

    public function __construct(string $host, int $port, string $db_name, string $db_user, string $password)
    {
        $this->connection = new PDO("pgsql:host=$host;port=$port;dbname=$db_name;user=$db_user;password=$password");

        $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function getConnection(): PDO
    {
        return $this->connection;
    }
}
