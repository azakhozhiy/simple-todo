<?php

namespace App\Packages\Core\Models;

abstract class BaseModel
{
    protected string $table;

    public function createQuery()
    {
    }

    /**
     * Short method for init query
     */
    public static function query()
    {
        return (new static)->createQuery();
    }

    public function save()
    {

    }
}
