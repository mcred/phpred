<?php
namespace PHPRed;

abstract class Model extends PHPRed
{
    private $alias;

    public $model;
    public $table;
    public $primaryKey;

    public function __construct(\mysqli $mysqli)
    {
        $this->setAlias();
        parent::__construct($mysqli);
    }

    private function setAlias()
    {
        $this->alias = $this->table . ' ' . $this->model;
    }

    public function getAll() : array
    {
        $query = "SELECT $this->model.* FROM $this->alias;";
        return $this->query($query);
    }

    public function getById(int $modelId) : array
    {
        $query = "SELECT $this->model.* FROM $this->alias WHERE $this->model.$this->primaryKey = $modelId;";
        return $this->query($query);
    }

    public function get() : array
    {
        $query = "SELECT $this->model.* FROM $this->alias";
        $query .= $this->conditions;
        $query .= $this->group;
        $query .= $this->order;
        return $this->query($query);
    }
}
