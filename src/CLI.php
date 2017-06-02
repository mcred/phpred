<?php
namespace PHPRed;

class CLI
{
    private $mysqli;

    public function __construct(\mysqli $mysqli)
    {
        $this->mysqli = $mysqli;
        $this->welcome();
    }

    private function welcome()
    {
        fwrite(STDERR, 'Welcome' . "\n\n");
    }
}
