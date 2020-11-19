<?php

declare(strict_types=1);

namespace App\Packages\Files\Managers;

use App\Packages\Core\Abstracts\CoreManager;
use App\Packages\Core\Models\BaseModel;
use App\Packages\Files\Models\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileManager extends CoreManager
{
    public function upload(UploadedFile $file)
    {
    }

    public function create()
    {
    }

    public function getModel(): BaseModel
    {
        return new File();
    }
}
