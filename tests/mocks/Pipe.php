<?php
namespace PHPRed\Models;

class Pipe extends Model
{
    public function __construct(\MysqliDb $mysql)
    {
        $this->model = 'Pipe';
        $this->table = 'pipes';
        $this->primaryKey = 'id';
        $this->foreignKey = 'pipe_id';
        $this->fields = ['id', 'name', 'pipe_name'];
        $this->requiredFields = ['name', 'pipe_name'];
        $this->uniqueFields = ['name', 'pipe_name'];
        $this->hasMany = ['PipeUser'];
        $this->hasAndBelongsToMany = ['Service'];

        parent::__construct($mysql);
    }
}
