<?php

declare(strict_types=1);

namespace App\Packages\Files\Repositories;

use App\Packages\Core\Abstracts\CoreRepository;
use App\Packages\Core\Models\BaseModel;
use App\Packages\Files\Models\File;

class FileRepository extends CoreRepository
{
    public function getModel(): BaseModel
    {
        return new File();
    }
}
