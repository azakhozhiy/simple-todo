<?php

namespace App\Packages\Core\Models;

use App\Packages\Core\Database;

abstract class BaseModel
{
    protected string $table;

    protected Database $connection;

    public function __construct()
    {
        $this->connection = app()->getDatabase()->getConnection();
    }

    public function createQuery()
    {
    }
}
