<?php
namespace PHPRed;

abstract class Model extends PHPRed
{
    public $model;
    public $table;
    public $primaryKey;

    public function __construct(\mysqli $mysqli)
    {
        parent::__construct($mysqli);
    }

    public function getAll() : array
    {
        $query = "SELECT * FROM $this->table;";
        return $this->query($query);
    }

    public function getById(int $modelId) : array
    {
        $query = "SELECT * FROM $this->table WHERE $this->primaryKey = $modelId;";
        return $this->query($query);
    }

    public function getByArgs(array $args, string $method) : array
    {
        $search = '';
        foreach ($args as $key => $value) {
            $key = $this->mysqli->escape_string($key);
            $value = $this->mysqli->escape_string($value);
            $search .= '`' . $key . '` = "' . $value . '" ' . $method;
        }
        $search = trim(substr($search, 0, -strlen($method)));
        $query = "SELECT * FROM $this->table WHERE $search;";
        var_dump($query);
        return $this->query($query);
    }
}
