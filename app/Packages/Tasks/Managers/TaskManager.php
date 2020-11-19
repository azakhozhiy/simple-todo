<?php

declare(strict_types=1);

namespace App\Packages\Tasks\Managers;

use App\Packages\Core\Abstracts\CoreManager;

class TaskManager extends CoreManager
{
    public function createOrUpdate(
        ?int $task_id,
        ?int $creator_id,
        string $title,
        ?string $content,
        ?int $picture_id
    ) {
    }
}
