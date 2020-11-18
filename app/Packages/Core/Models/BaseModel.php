<?php

namespace App\Packages\Core\Models;

use PDO;

abstract class BaseModel
{
    protected string $table;

    protected PDO $connection;

    public function __construct()
    {
        $this->connection = app()->getDatabase()->getConnection();
    }

    public function getConnection()
    {
        return $this->connection;
    }

    public static function query(): PDO
    {
        return (new static)->getConnection();
    }
}
