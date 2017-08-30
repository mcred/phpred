<?php
namespace Custom;

class PipeUser extends \PHPRed\Models\Model
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
