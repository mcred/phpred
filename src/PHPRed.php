<?php
namespace PHPRed;

abstract class PHPRed
{
    protected $mysqli;
    protected $conditions;
    protected $order;

    public function __construct(\mysqli $mysqli)
    {
        $this->mysqli = $mysqli;
        $this->conditions = '';
        $this->order = '';
    }

    protected function query(string $query) : array
    {
        echo PHP_EOL . $query . PHP_EOL;
        $results = $this->mysqli->query($query);
        if ($this->mysqli->errno) {
            throw new \Exception($this->mysqli->error . $query);
        }
        if ($results->num_rows == 0) {
            throw new \Exception("No records found.");
        }
        while ($row = $results->fetch_array(MYSQLI_ASSOC)) {
            $return[] = $row;
        }
        return $return;
    }

    public function orderBy(array $args)
    {
        $order = ' ORDER BY ';
        foreach ($args as $key => $value) {
            $key = $this->mysqli->escape_string($key);
            $value = $this->mysqli->escape_string($value);
            $order .= $this->model . '.`' . $key . '` ' . strtoupper($value) . ', ';
        }
        $this->order = rtrim(substr($order, 0, -2));
        return $this;
    }


    public function where(array $args, string $method = 'AND')
    {
        $search = ' WHERE ';
        foreach ($args as $key => $value) {
            $key = $this->mysqli->escape_string($key);
            $value = $this->mysqli->escape_string($value);
            $search .= $this->model . '.`' . $key . '` = "' . $value . '" ' . $method;
        }
        $this->conditions .= rtrim(substr($search, 0, -strlen($method)));
        return $this;
    }
}
