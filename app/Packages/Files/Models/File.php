<?php

declare(strict_types=1);

namespace App\Packages\Files\Models;

use App\Packages\Core\Models\BaseModel;

/**
 * Class File
 * @package App\Packages\Files\Models
 */
class File extends BaseModel
{
    protected string $table = 'files';

    public const MAX_WIDTH = 320;
    public const MAX_HEIGHT = 240;

    public static function uploadsPath(string $path): string
    {
        return storage_path('uploads/'.$path);
    }
}
