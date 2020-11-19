<?php

declare(strict_types=1);

namespace App\Packages\Tasks\Models;

use App\Packages\Core\Models\BaseModel;
use App\Packages\Files\Models\File;

class Task extends BaseModel
{
    protected string $table = 'tasks';

    public static function pictureFolder(int $id): string
    {
        return File::uploadsPath('tasks/'.$id.'/');
    }
}
