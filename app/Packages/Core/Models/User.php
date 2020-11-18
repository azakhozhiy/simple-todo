<?php

declare(strict_types=1);

namespace App\Packages\Core\Models;

/**
 * Class User
 *
 * @property-read int $id
 * @property string $name имя
 * @property string $email почта
 * @property string $login логин
 * @property string $password пароль
 * @property string $created_at дата создания
 * @property string $updated_at дата обновления
 *
 * @package App\Packages\Core\Models
 */
class User extends BaseModel
{
    protected string $table = 'users';
}
