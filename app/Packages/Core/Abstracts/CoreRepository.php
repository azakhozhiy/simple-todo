<?php

declare(strict_types=1);

namespace App\Packages\Core\Abstracts;

use App\Packages\Core\Contracts\RepositoryContract;
use PDO;

abstract class CoreRepository implements RepositoryContract
{
    public function findOneById(int $id)
    {
        $table = $this->getModel()->getTable();
        $sth = $this->getModel()->getConnection()->prepare("SELECT * FROM $table WHERE id=:id");
        $sth->bindParam(':id', $id);
        $sth->execute();

        return $sth->fetch(PDO::FETCH_ASSOC);
    }

    public function getAll(array $columns = [], array $order = [], int $page = 1, int $limit = 3): array
    {
        $table = $this->getModel()->getTable();

        $columns_string = '*';
        if (count($columns)) {
            $columns_string = implode(',', $columns);
        }

        $sth = $this->getModel()->getConnection()->query("SELECT $columns_string FROM $table");

        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }


}
