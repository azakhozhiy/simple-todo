<?php

declare(strict_types=1);

namespace App\Packages\Core\Models;

use PDO;
use PDOStatement;

abstract class BaseModel
{
    protected PDO $connection;

    public function __construct()
    {
        $this->connection = app()->getDatabase()->getConnection();
    }

    public static function getConnection(): PDO
    {
        return (new static)->connection;
    }

    /**
     * @param  string|null  $sql
     * @return false|PDOStatement
     */
    public static function query(?string $sql = null)
    {
        $model = new static;

        if ($sql) {
            return $model->connection->query($sql);
        }

        return false;
    }
}
