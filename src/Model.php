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

    public function get() : array
    {
        $query = "SELECT * FROM $this->table ";
        $query .= $this->conditions;
        $query .= $this->order;
        return $this->query($query);
    }
}
