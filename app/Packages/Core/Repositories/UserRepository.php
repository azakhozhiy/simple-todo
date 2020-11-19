<?php

declare(strict_types=1);

namespace App\Packages\Core\Repositories;

use App\Packages\Core\Abstracts\CoreRepository;
use App\Packages\Core\Models\BaseModel;
use App\Packages\Core\Models\User;
use PDO;

class UserRepository extends CoreRepository
{
    public function getModel(): BaseModel
    {
        return (new User());
    }

    public function findByLoginAndPassword(string $login, string $hash)
    {
        $table = $this->getModel()->getTable();
        $db = $this->getModel()->getConnection();

        $sth = $db->prepare("SELECT * FROM $table WHERE login=:login");
        $sth->bindParam(':login', $login);
        $sth->execute();

        return $sth->fetch(PDO::FETCH_ASSOC);
    }
}
