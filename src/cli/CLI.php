<?php
namespace PHPRed\CLI;

abstract class CLI extends \PHPRed\PHPRed
{
    protected $mysqli;
    private $stdout;
    private $stderr;
    private $stdin;

    public function __construct(\mysqli $mysqli, $stdout = '', $stderr = '', $stdin = '')
    {
        parent::__construct($mysqli);

        $this->stdout = $stdout ? $stdout : STDOUT;
        $this->stderr = $stderr ? $stderr : STDERR;
        $this->stdin = $stdin ? $stdin : STDIN;
    }

    protected function shutDown()
    {
        fclose($this->stdout);
        fclose($this->stderr);
        fclose($this->stdin);
    }

    protected function output(string $statement)
    {
        fwrite($this->stdout, $statement . "\n");
    }

    protected function prompt(string $question)
    {
        $this->output($question);
        return trim(fgets($this->stdin));
    }
}
