<?php
namespace PHPRed\CLI;

class Action extends \PHPRed\PHPRed
{
    public function __construct(\mysqli $mysqli)
    {
        parent::__construct($mysqli);
    }

    public function listTables()
    {
        $tables = $this->query("SHOW TABLES");
        return $tables[0]['Tables_in_' . $_ENV['db_name']];
    }
}
