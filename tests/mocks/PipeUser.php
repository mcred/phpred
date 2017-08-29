<?php
namespace PHPRed\Models;

class PipeUser extends Model
{
    public function __construct(\MysqliDb $mysql)
    {
        $this->model = 'PipeUser';
        $this->table = 'pipes_users';
        $this->primaryKey = 'id';
        $this->belongsTo = ['Pipe','User'];

        parent::__construct($mysql);
    }
}
