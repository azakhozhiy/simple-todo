<?php

declare(strict_types=1);

namespace App\Packages\Core\Repositories;

use App\Packages\Core\Abstracts\CoreRepository;
use App\Packages\Core\Models\User;

class UserRepository extends CoreRepository
{
    public function getOneById(int $id): User
    {

    }
}
