<?php

declare(strict_types=1);

namespace App\Packages\Tasks\Repositories;

use App\Packages\Core\Abstracts\CoreRepository;
use App\Packages\Core\Models\BaseModel;
use App\Packages\Tasks\Models\Task;

class TaskRepository extends CoreRepository
{
    public function getModel(): BaseModel
    {
        return new Task();
    }
}
