<?php

namespace Engine\Core\Database;

use Engine\Core\Config\Config;


class Connection
{
    private $link;

    public function __construct()
    {
        $this->connect();
    }

    private function connect() 
    {
        try {
            $config = Config::group('database');
            $dsn = 'mysql:host=' . $config['host'] . ';dbname=' . $config['db_name'];
            $this->link = new \PDO($dsn, $config['username'], $config['password']);

        } catch (\PDOException $e) {
            echo  'Error: ' . $e->getMessage();
        }

        return $this;
    }

    public function query($sql, $values = [], $statement = \PDO::FETCH_OBJ)
    {

        $sth = $this->link->prepare($sql);
        


        $sth->execute($values);

        $result = $sth->fetchAll($statement);

        if ($result === false) {
            return [];
        }
       
        return $result;
    }

    public function execute($sql, $values = [])
    {
        $sth = $this->link->prepare($sql);

        return $sth->execute($values);
    }


}