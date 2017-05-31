<?php
namespace PHPRed;

abstract class PHPRed
{
    protected $mysqli;

    public function __construct(\mysqli $mysqli)
    {
        $this->mysqli = $mysqli;
    }

    protected function query(string $query) : array
    {
        $results = $this->mysqli->query($query);
        if ($results->num_rows == 0) {
            throw new \Exception("No records found.");
        }
        return $results->fetch_array(MYSQLI_ASSOC);
    }
}
