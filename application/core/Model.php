<?php

namespace application\core;

use application\lib\DataBase;

abstract class Model
{
    protected $db;

    public function __construct(DataBase $db = null)
    {
        if (!$db) {
            $this->db = new DataBase();
        } else {
            $this->db = $db;
        }
    }

    // public function lastInsertId()
    // {
    //     return $this->db->lastInsertId();
    // }
}