<?php
namespace PHPRed\Models;

class User extends Model
{
    public function __construct(\MysqliDb $mysql)
    {
        $this->model = 'User';
        $this->table = 'users';
        $this->primaryKey = 'id';
        $this->foreignKey = 'user_id';
        $this->fields = ['id','email'];
        $this->requiredFields = ['email'];
        $this->uniqueFields = ['email'];
        $this->hasMany = ['PipeUser'];
        $this->hasAndBelongsToMany = ['Service'];

        parent::__construct($mysql);
    }
}
