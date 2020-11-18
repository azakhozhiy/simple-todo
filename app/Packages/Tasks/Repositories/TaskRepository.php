<?php

namespace App\Packages\Tasks\Repositories;

use App\Packages\Core\Abstracts\CoreRepository;
use App\Packages\Tasks\Task;

class TaskRepository extends CoreRepository
{
    public function query()
    {
        return Task::query();
    }

    public function getOne(){

    }
}
