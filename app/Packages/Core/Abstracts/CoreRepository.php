<?php

declare(strict_types=1);

namespace App\Packages\Core\Abstracts;

use App\Packages\Core\Contracts\RepositoryContract;
use PDO;

abstract class CoreRepository implements RepositoryContract
{
    public function getOneById(int $id)
    {
        $table = $this->getModel()->getTable();
        $sth = $this->getModel()->getConnection()->prepare("SELECT * FROM $table WHERE id=:id");
        $sth->bindParam(':id', $id);
        $sth->execute();

        return $sth->fetch(PDO::FETCH_ASSOC);
    }

    public function genUniqName(string $title, int $level = 0)
    {
        $str = $level ? $title.'-'.$level : $title;
        $name = str2translit($str);

        $model = $this->getByField('name', $name);

        if ($model) {
            return $this->genUniqName($title, $level + 1);
        }

        return $name;
    }

    public function getByField(string $field, $value)
    {
        $table = $this->getModel()->getTable();
        $sth = $this->getModel()->getConnection()->prepare("SELECT * FROM $table WHERE $field=:field_value");
        $sth->bindParam(':field_value', $value);
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

        $sql = "SELECT $columns_string FROM $table";

        if (count($order)) {
            $sql .= " ORDER BY $order[0] $order[1]";
        }

        $sth = $this->getModel()->getConnection()->query($sql);

        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }


}
