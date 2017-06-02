<?php
namespace PHPREd\CLI;

class Prompt extends CLI
{
    public function __construct(\mysqli $mysqli)
    {
        parent::__construct($mysqli);
    }

    private function welcome()
    {
        $this->output('Welcome');
        $response = $this->prompt('What do you want to do? Type "yes".');
        if ($response != 'yes') {
            $this->output("ABORTING!");
            exit;
        }
        $this->output("Thank you, continuing");
    }

    public function run()
    {
        $this->welcome();
        $this->shutDown();
    }
}
