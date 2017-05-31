<?php
namespace PHPRed;

abstract class PHPRed
{
    protected $mysqli;

    public function __construct(\mysqli $mysqli)
    {
        $this->mysqli = $mysqli;
    }

    protected function toArray(\mysqli_result $results) : array
    {
        return $results->fetch_array(MYSQLI_ASSOC);
    }
}
