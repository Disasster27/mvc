<?php

namespace application\lib;

use PDO;

class DataBase
{
    protected $db;

    public function __construct()
    {
        $config = require DIR . 'application/config/db.php';
        $this->db = new PDO(
            'mysql:host='.$config['host'].';dbname='.$config['dbname'],
            $config['user'],
            $config['password'],
            [
                PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => true
            ]
        );
    }

    public function query($sql, $params = [], $returnBoolean = true)
    {
        $query = $this->db->prepare($sql);
        if (!empty($params)) {
            foreach ($params as $key => $val) {
                $query->bindValue(":".$key, $val);
            }
        }

        $query->execute();
        if ($returnBoolean) {
            if (gettype($query) === 'boolean') {
                return $query;
            } else {
                if ($query->errorCode() == '00000') {
                    return true;
                } else {
                    return false;
                }
            }
        } else {
            return $query;
        }

    }

    public function row($sql, $params = [])
    {
        $result = $this->query($sql, $params, false);
        if ($result)
            return $result->fetchAll(PDO::FETCH_ASSOC);
        else return [];
    }

    public function firstRow(string $sql, array $params = []): ?array
    {
        $result = $this->query($sql, $params, false);
        if ($result)
            return $result->fetchAll(PDO::FETCH_ASSOC)[0];
        else return null;
    }

    public function column($sql, $params = [])
    {
        $result = $this->query($sql, $params, false);
        if ($result)
            return $result-> fetchColumn();
        else return null;
    }

    public function lastInsertId()
    {
        return $this->db->lastInsertId();
    }

    public function object($class, $sql, $params = [], $args = []) {
        $result = $this->query($sql, $params, false);
        if ($result) {
            return $result->fetchObject($class, $args);
        }
        return null;
    }
}
