<?php

declare(strict_types=1);

namespace App\Packages\Tasks\Repositories;

use App\Packages\Core\Abstracts\CoreRepository;
use App\Packages\Tasks\Models\Task;
use PDOStatement;

class TaskRepository extends CoreRepository
{
    /**
     * @return false|PDOStatement
     */
    public function query()
    {
        return Task::query();
    }
}
