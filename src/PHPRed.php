<?php
namespace PHPRed;

class PHPRed
{
    private $mysqli;

    public function __construct(\mysqli $mysqli)
    {
        $this->mysqli = $mysqli;
    }
}
