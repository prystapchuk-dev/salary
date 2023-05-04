<?php

namespace Engine\Core\Database;

use \ReflectionClass;
use \ReflectionProperty;
trait ActiveRecord
{
    protected $db;

    protected $queryBuilder;

    public function __construct($id = 0)
    {
        global $id;

        $this->db = $di->get('db');
        $this->queryBuilder = new QueryBuilder();

        if ($id) {
            $this->setId($id);
        }
    }

    /**
     * @return mixed
     */
    public function getTable()
    {
        return $this->table;
    }

    public function findOne()
    {
        $find = $this->db->query(
            $this->queryBuilder
                ->select()
                ->from($this->getTable())
                ->where('id', $this->id)
                ->sql(),
            $this->queryBuilder->values
        );

        return $find[0] ?? null;
    }

}