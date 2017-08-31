# PHPRed
[![Build Status](https://travis-ci.org/mcred/phpred.svg?branch=master)](https://travis-ci.org/mcred/phpred)
[![Code Climate](https://codeclimate.com/github/mcred/phpred/badges/gpa.svg)](https://codeclimate.com/github/mcred/phpred)
[![Test Coverage](https://codeclimate.com/github/mcred/phpred/badges/coverage.svg)](https://codeclimate.com/github/mcred/phpred/coverage)
[![Issue Count](https://codeclimate.com/github/mcred/phpred/badges/issue_count.svg)](https://codeclimate.com/github/mcred/phpred)

### Description
<p>PHPRed is an opinionated light weight ORM. While there are many available ORMs for PHP, many contain features that I have never used. PHPRed contains very basic methods and usage.</p>

### Requirements
* PHP 7.1+
* Composer
* Mysqli

### Installation

```
composer require mcred/phpred
```

### Setup
<p>In addition to the example below, there are examples available in the `tests/mocks` folder. Setting up a model is very easy: create a model class that extends the `PHPRed/Models/Model` class then define the properties of that model in the constructor. Such as: </p>

```php
<?php
class MyClass extends \PHPRed\Models\Model
{
    public function __construct(\MysqliDb $mysql)
    {
        $this->model = 'MyClass';
        $this->table = 'my_class';
        $this->primaryKey = 'id';
        $this->foreignKey = 'my_class_id';
        $this->fields = ['id', 'name'];
        $this->requiredFields = ['name'];
        $this->uniqueFields = ['name'];
        $this->hasMany = ['MyClassProperties'];
        $this->hasAndBelongsToMany = ['Users'];

        parent::__construct($mysql);
    }
}

```

### Constructor Properties
* model: string
* table: string
* primaryKey: string
* foreignKey: string
* fields: array
* requiredFields: array
* uniqueFields: array
* hasMany: array
* belongsTo: array
* hasAndBelongsToMany: array

### Methods
* getAll() : array
* getById(int $modelId) : array
* getBySearch(array ['key' => 'value']) : array
* insert(array $data) : array
* updateById(int $modelId, array $data) : array
* deleteById(int $modelId): void
