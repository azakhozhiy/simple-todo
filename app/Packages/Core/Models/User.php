<?php

namespace App\Packages\Core\Models;

class User extends BaseModel
{
    protected string $table = 'users';

    protected ?int $id = null;
    protected ?string $name = null;
    protected ?string $email = null;
    protected ?string $login = null;

}
