<?php

declare(strict_types=1);

namespace App\Packages\Core\Contracts;

use App\Packages\Core\Models\BaseModel;

interface RepositoryContract
{
    /**
     * @return BaseModel
     */
    public function getModel(): BaseModel;
}
