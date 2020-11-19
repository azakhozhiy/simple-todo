<?php

declare(strict_types=1);

namespace App\Packages\Core\Models;

use PDO;

abstract class BaseModel
{
    protected PDO $connection;
    protected string $table;

    public function getTable(): string
    {
        return $this->table;
    }

    public function __construct()
    {
        $this->connection = app()->getDatabase()->getConnection();
    }

    public function getConnection(): PDO
    {
        return $this->connection;
    }

    public static function connection(): PDO
    {
        return (new static)->connection;
    }
}
