<?php

declare(strict_types=1);

namespace App\Packages\Core\Abstracts;

use App\Packages\Core\Contracts\RepositoryContract;
use App\Packages\Core\Paginator;
use PDO;
use PDOStatement;
use Symfony\Component\HttpFoundation\Request;

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

    public function getSql(array $columns = [], array $wheres = [], array $order = [])
    {
        $table = $this->getModel()->getTable();

        $columns_string = '*';
        if (count($columns)) {
            $columns_string = implode(',', $columns);
        }

        $sql = "SELECT $columns_string FROM $table";

        if (count($wheres)) {
            $i = 0;
            foreach ($wheres as $where_name => $where) {
                $column = $where['column'];
                $operator = $where['operator'];
                $or_column = $where['or_column'] ?? null;
                if (!$i) {
                    $sql .= " WHERE $column $operator :$where_name";
                } else {
                    $sql .= " AND $column $operator :$where_name";
                }

                if ($or_column) {
                    $sql .= " OR $or_column $operator :$where_name";
                }

                ++$i;
            }
        }

        if (count($order)) {
            $sql .= " ORDER BY $order[0] $order[1]";
        }

        return $sql;
    }

    /**
     * @param  string  $sql
     * @param  array  $wheres
     * @param  callable|null  $callback
     * @return bool|PDOStatement
     */
    public function execute(string $sql, array $wheres = [], callable $callback = null)
    {
        $sth = $this->getModel()->getConnection()->prepare($sql);

        if (count($wheres)) {
            foreach ($wheres as $where_name => $where) {
                $where_value = $where['value'];

                if ($where['operator'] === 'ILIKE' || $where['operator'] === 'LIKE') {
                    $where_value = '%'.$where_value.'%';
                }

                $sth->bindValue($where_name, $where_value, $where['type']);
            }
        }

        if ($callback) {
            $callback($sth);
        }

        $sth->execute();

        return $sth;
    }

    public function getList(
        Request $request,
        int $per_page,
        string $base_url,
        array $columns = [],
        array $wheres = [],
        array $order = []
    ): Paginator {
        // SQL for getting total count records
        $count_sql = $this->getSql(['COUNT(*)'], $wheres);
        $total_items = $this->execute($count_sql, $wheres)->fetchColumn();

        // SQL for getting records
        $sql = $this->getSql($columns, $wheres, $order);
        $sql .= ' LIMIT :limit OFFSET :offset';

        // Getting records
        $current_page = $request->query->get(Paginator::$query_page_param, 1);
        $skip_items = ($current_page - 1) * $per_page;

        $sth = $this->execute($sql, $wheres, function (PDOStatement $sth) use ($per_page, $skip_items) {
            $sth->bindParam(':limit', $per_page, PDO::PARAM_INT);
            $sth->bindParam(':offset', $skip_items, PDO::PARAM_INT);
        });

        $records = $sth->fetchAll(PDO::FETCH_ASSOC);

        return new Paginator($request, $records, $base_url, $total_items, $per_page);
    }

    public function getAll(
        array $columns = [],
        array $wheres = [],
        array $order = []
    ): array {
        $sql = $this->getSql($columns, $wheres, $order);
        $sth = $this->execute($sql, $wheres);

        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }


}
