<?php
namespace PHPRed;

abstract class Model extends PHPRed
{
    public $model;
    public $table;

    public function __construct(\mysqli $mysqli)
    {
        parent::__construct($mysqli);
    }

    public function getAll()
    {
        $query = "SELECT * FROM $this->table;";
        $results = $this->mysqli->query($query);
        return $this->toArray($results);
    }
}
