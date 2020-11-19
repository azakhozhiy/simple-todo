<?php

declare(strict_types=1);

namespace App\Packages\Core\Abstracts;

use App\Packages\Core\Contracts\ManagerContract;
use App\Packages\Core\Models\BaseModel;
use PDO;

abstract class CoreManager implements ManagerContract
{
    protected BaseModel $model;

    public function setModel(BaseModel $model): self
    {
        $this->model = $model;

        return $this;
    }

    public function getConnection(): PDO
    {
        return $this->model->getConnection();
    }

    public function getTable(): string
    {
        return $this->model->getTable();
    }

    public function record(callable $callback)
    {
        $db = $this->getConnection();
        $table = $this->getTable();
        return $callback($db, $table);
    }
}
