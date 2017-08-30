<?php
namespace Custom;

class Service extends \PHPRed\Models\Model
{
    public function __construct(\MysqliDb $mysql)
    {
        $this->model = 'Service';
        $this->table = 'services';
        $this->primaryKey = 'id';
        $this->foreignKey = 'service_id';
        $this->fields = ['id','display_name','class_name'];
        $this->hasAndBelongsToMany = ['Pipe', 'User'];

        parent::__construct($mysql);
    }
}
