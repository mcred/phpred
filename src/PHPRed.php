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
        $order = 'ORDER BY ';
        foreach ($args as $key => $value) {
            $key = $this->mysqli->escape_string($key);
            $value = $this->mysqli->escape_string($value);
            $order .= '`' . $key . '` ' . strtoupper($value) . ' ';
        }
        $this->order = trim($order);
        return $this;
    }


    public function where(array $args, string $method = 'AND')
    {
        $search = 'WHERE ';
        foreach ($args as $key => $value) {
            $key = $this->mysqli->escape_string($key);
            $value = $this->mysqli->escape_string($value);
            $search .= '`' . $key . '` = "' . $value . '" ' . $method;
        }
        $this->conditions .= trim(substr($search, 0, -strlen($method)));
        return $this;
    }
}
