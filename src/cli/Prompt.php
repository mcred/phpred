<?php
namespace PHPRed\CLI;

use PHPRed\CLI\Action;

class Prompt extends CLI
{
    private $action;

    public function __construct(\mysqli $mysqli)
    {
        parent::__construct();
        $this->action = new Action($mysqli);
    }

    private function welcome()
    {
        $this->output('Welcome');
        $response = $this->prompt('Do you want to list tables?');
        if ($response != 'yes') {
            $this->output("ABORTING!");
            exit;
        }
        $this->output($this->action->listTables());
    }

    public function run()
    {
        $this->welcome();
        $this->shutDown();
    }
}
