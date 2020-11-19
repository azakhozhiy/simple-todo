<?php

declare(strict_types=1);

namespace App\Packages\Core\Contracts;

use App\Packages\Core\Models\BaseModel;

interface ManagerContract
{
    public function setModel(BaseModel $model): self;

    public function getTable(): string;
}
