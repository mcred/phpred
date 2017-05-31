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

    public function getByArgs(array $args) : array
    {
        $search = '';
        foreach ($args as $key => $value) {
            $search .= '`' . $key . '` = "' . $value . '" ';
        }
        $query = "SELECT * FROM $this->table WHERE $search;";
        return $this->query($query);
    }
}
