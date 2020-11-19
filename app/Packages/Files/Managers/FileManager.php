<?php

declare(strict_types=1);

namespace App\Packages\Files\Managers;

use App\Packages\Core\Abstracts\CoreManager;

class FileManager extends CoreManager
{
    public function upload($file)
    {
        print_r($file);
    }

    public function create()
    {
    }
}
