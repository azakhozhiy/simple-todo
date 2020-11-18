<?php

namespace App\Packages\Tasks;

use App\Packages\Core\Models\BaseModel;

class Task extends BaseModel
{
    protected string $table = 'tasks';

    public function user()
    {
    }
}
