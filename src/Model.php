<?php
namespace PHPRed;

class Model extends PHPRed
{
    public function __construct(\mysqli $mysqli)
    {
        parent::__construct($mysqli);
    }
}
