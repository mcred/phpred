<?php
namespace PHPRed\CLI;

abstract class CLI
{
    private $stdout;
    private $stderr;
    private $stdin;

    public function __construct($stdout = STDOUT, $stderr = STDERR, $stdin = STDIN)
    {
        $this->stdout = $stdout;
        $this->stderr = $stderr;
        $this->stdin = $stdin;
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
