<?php

namespace App\Packages\Tasks;

use App\Packages\Core\Models\BaseModel;
use App\Packages\Core\Models\User;

class Task extends BaseModel
{
    protected string $table = 'tasks';

    public function user(): ?User
    {

    }
}
