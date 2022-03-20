<?php

namespace Example\Services;

use Example\Interfaces\DbModel;
use PDO;

/**
 * Database connection service
 */
class Database
{
    private $host;
    private $port;
    private $user;
    private $pass;
    private $database;

    /** @var PDO $connection */
    private $connection;

    /**
     * @param string $host
     * @param int $port
     * @param string $user
     * @param string $pass
     * @param string $database
     */
    public function __construct($host, $port, $user, $pass, $database)
    {
        $this->host = $host;
        $this->port = $port;
        $this->user = $user;
        $this->pass = $pass;
        $this->database = $database;
    }

    /**
     * @return PDO
     */
    public function getConnection()
    {
        if (empty($this->connection)) {
            $this->connect();
        }
        return $this->connection;
    }

    /**
     * Finds a model based on given criteria
     *
     * @param DbModel $instance
     * @param array $criteria
     * @return array
     */
    public function findModels(DbModel $instance, $criteria = [])
    {
        $table = $instance::tableName();
        $columns = $instance::attributes();
        $where = ['1'];
        $params = [];
        $models = [];
        foreach ($criteria as $attr => $value) {
            if (in_array($attr, $columns)) {
                $where[] = "$attr = :$attr";
                $params[":$attr"] = $value;
            }
        }
        $query = sprintf("SELECT %s FROM %s WHERE %s", implode(',', $columns), $table, implode(' AND ', $where));
        $stmt = $this->getConnection()->prepare($query);
        $stmt->execute($params);
        while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $model = new $instance;
            $model->populate($result);
            $models[] = $model;
        }

        return $models;
    }

    /**
     * Updates a model
     *
     * @param DbModel $instance
     * @return bool
     */
    public function update(DbModel $instance)
    {
        $table = $instance::tableName();
        $cols = $instance->asArray();
        $id = $instance->primaryKey();
        $sets = [];
        $params = [];
        foreach ($cols as $col => $value) {
            $sets[] = "$col = :$col";
            $params[":$col"] = $value;
        }

        $query = sprintf('UPDATE %s SET %s WHERE id = %d', $table, implode(',', $sets), $id);
        $stmt = $this->getConnection()->prepare($query);
        return $stmt->execute($params);
    }

    /**
     * Inserts new model
     *
     * @param DbModel $instance
     * @return bool
     */
    public function insert(DbModel $instance)
    {
        $table = $instance::tableName();
        $cols = $instance::attributes();
        $params = get_object_vars($instance);
        foreach ($params as $param => $val) {
            unset($params[$param]);
            $params[":$param"] = $val;
        }
        $query = sprintf(
            "INSERT INTO %s(%s) VALUES(%s)",
            $table,
            implode(',', $cols),
            implode(',', array_keys($params))
        );

        $stmt = $this->getConnection()->prepare($query);
        return $stmt->execute($params);
    }

    /**
     * Deletes model
     *
     * @param string $table
     * @param int $id
     * @return bool
     */
    public function delete($table, $id)
    {
        $query = "DELETE FROM $table WHERE id = :id";
        $stmt = $this->getConnection()->prepare($query);
        return $stmt->execute(['id' => $id]);
    }

    /**
     * @access private
     *
     * establishes connection to the database
     */
    private function connect()
    {
        $this->connection = new PDO(
            sprintf(
                'mysql:dbname=%s;host=%s;port=%d',
                $this->database,
                $this->host,
                $this->port
            ),
            $this->user,
            $this->pass,
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );
    }
}