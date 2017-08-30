<?php
namespace PHPRed\Models;

abstract class Model
{
    protected $mysql;
    protected $alias;
    protected $data;
    public $model;
    public $table;
    public $primaryKey;
    public $foreignKey;
    public $fields;
    public $requiredFields;
    public $uniqueFields;
    public $hasMany;
    public $belongsTo;
    public $hasAndBelongsToMany;

    public function __construct(\MysqliDb $mysql)
    {
        $this->mysql = $mysql;
        $this->alias = $this->table . ' ' . $this->model;
        if (!$this->fields) {
            $this->fields = [];
        }
    }

    protected function filterByFields(array $results, array $fields) : array
    {
        if (!$fields) {
            return $results;
        }
        $filtered = [];
        foreach ($results as $result) {
            foreach ($result as $key => $value) {
                if (!in_array($key, $fields) && !is_array($value)) {
                    unset($result[$key]);
                }
            }
            array_push($filtered, $result);
        }
        return $filtered;
    }

    protected function validate(array $data)
    {
        $this->validateRequiredFields($data);
        $this->validateUniqueFields($data);
    }

    protected function validateRequiredFields(array $data)
    {
        if ($this->requiredFields) {
            foreach ($this->requiredFields as $requiredField) {
                if (!array_key_exists($requiredField, $data)) {
                    throw new \InvalidArgumentException(
                        $requiredField . ' is required for ' . $this->model . '.'
                    );
                }
            }
        }
    }

    protected function validateUniqueFields(array $data)
    {
        if ($this->uniqueFields) {
            foreach ($this->uniqueFields as $uniqueField) {
                $check = $this->getBySearch([$uniqueField => $data[$uniqueField]]);
                if ($check) {
                    throw new \InvalidArgumentException(
                        $uniqueField . ' must be unique for ' . $this->model . '.'
                    );
                }
            }
        }
    }

    protected function attachRelated()
    {
        $this->attachHasMany();
        $this->attachBelongsTo();
        $this->attachHABTM();
    }

    protected function attachHasMany()
    {
        $this->attachByMethod('hasMany');
    }

    protected function attachBelongsTo()
    {
        $this->attachByMethod('belongsTo');
    }

    protected function attachByMethod(string $method)
    {
        if ($this->$method) {
            foreach ($this->$method as $attachModel) {
                $class = new \ReflectionClass($this);
                $attachModel = $class->getNamespaceName() . '\\' . $attachModel;
                $attach = new $attachModel($this->mysql);
                foreach ($this->data as $key => $model) {
                    switch ($method) {
                        case 'hasMany':
                            $this->data[$key][$attach->model] = $attach->getBySearch(
                                [$this->foreignKey => $model[$this->primaryKey]]
                            );
                            break;
                        case 'belongsTo':
                            $this->data[$key][$attach->model] = $attach->getBySearch(
                                [$attach->primaryKey => $model[$attach->foreignKey]]
                            );
                            break;
                    }
                }
            }
        }
    }

    protected function getJoinTable(array $tables) : string
    {
        sort($tables);
        return $tables[0] . '_' . $tables[1];
    }

    protected function setHABTMFields(string $model, array $fields) : array
    {
        $joined = [];
        foreach ($fields as $field) {
            array_push($joined, $model . '.' . $field);
        }
        return $joined;
    }

    protected function attachHABTM()
    {
        if ($this->hasAndBelongsToMany) {
            foreach ($this->hasAndBelongsToMany as $habtmModel) {
                $class = new \ReflectionClass($this);
                $habtmModel = $class->getNamespaceName() . '\\' . $habtmModel;
                $habtm = new $habtmModel($this->mysql);
                foreach ($this->data as $key => $model) {
                    $alias = $habtm->table . ' ' . $habtm->model;
                    $joinTable = $this->getJoinTable([$habtm->table,$this->table]);
                    $join =  $habtm->model . '.' . $habtm->primaryKey . ' = ' . $joinTable . ' . ' . $habtm->foreignKey;
                    $this->mysql->join($alias, $join, 'LEFT');
                    $this->mysql->where($joinTable . '.' . $this->foreignKey, $model[$this->primaryKey]);
                    $this->data[$key][$habtm->model] = $this->mysql->get(
                         $joinTable . ' ' . $joinTable,
                         null,
                         $this->setHABTMFields($habtm->model, $habtm->fields)
                     );
                }
            }
        }
    }

    public function getAll() : array
    {
        $this->data = $this->mysql->get($this->alias);
        $this->attachRelated();
        return $this->filterByFields($this->data, $this->fields);
    }

    public function getById(int $modelId) : array
    {
        $this->mysql->where($this->primaryKey, $modelId);
        $this->data = $this->mysql->get($this->alias);
        $this->attachRelated();
        $result = $this->filterByFields($this->data, $this->fields);
        return $result[0];
    }

    public function getBySearch(array $search) : array
    {
        foreach ($search as $key => $value) {
            $this->mysql->where($this->model . '.' . $key, $value);
        }
        $this->data = $this->mysql->get($this->alias);
        return $this->filterByFields($this->data, $this->fields);
    }

    public function insert(array $data) : array
    {
        $this->validate($data);
        $this->mysql->startTransaction();
        if (!$this->mysql->insert($this->table, $data)) {
            $this->mysql->rollback();
            throw new \InvalidArgumentException(
                'Failed to create ' . $this->model . '.' . $this->mysql->getLastError()
            );
        }
        $data['id'] = $this->mysql->getInsertId();
        $this->mysql->commit();
        return $data;
    }

    public function updateById(int $modelId, array $data) : array
    {
        $this->validate($data);
        $this->mysql->startTransaction();
        $this->mysql->where($this->primaryKey, $modelId);
        if (!$this->mysql->update($this->table, $data)) {
            $this->mysql->rollback();
            throw new \InvalidArgumentException(
                'Failed to update ' . $this->model . '.' . $this->mysql->getLastError()
            );
        }
        $this->mysql->commit();
        return $data;
    }

    public function deleteById(int $modelId)
    {
        $this->mysql->startTransaction();
        $this->mysql->where($this->primaryKey, $modelId);
        if (!$this->mysql->delete($this->table)) {
            $this->mysql->rollback();
            throw new \InvalidArgumentException(
                'Failed to delete ' . $this->model . '.' . $this->mysql->getLastError()
            );
        }
        $this->mysql->commit();
    }
}
