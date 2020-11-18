<?php

namespace App\Packages\Core;

class Collection
{
    protected array $records;

    /**
     * @return array
     */
    public function getRecords(): array
    {
        return $this->records;
    }

    public function __construct(array $records)
    {
        $this->records = $records;
    }

    public function first(callable $callback){

    }

    public function each(callable $callback){
        foreach($this->getRecords() as $key => $record){
            $callback();
        }
    }
}
