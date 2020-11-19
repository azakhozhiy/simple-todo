<?php

declare(strict_types=1);

namespace App\Packages\Tasks\Models;

use App\Packages\Core\Models\BaseModel;

class Task extends BaseModel
{
    protected string $table = 'tasks';

    public static function pictureFolder(int $id)
    {
        return storage_path("tasks/$id");
    }
}
