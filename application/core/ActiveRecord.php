<?php

namespace application\core;

use \PDO;

class ActiveRecord
{
    private $isNewRecord = TRUE;
    private $fields = [];
    private $dirty = [];

    public function __construct()
    {
        foreach ($this->getTableScheme() as $field) {
            $this->dirty[$field['Field']] = $field['Default'];
        }
    }

    public function __set($name, $value)
    {
        if (!(array_key_exists($name, $this->fields)
            && $this->fields[$name] == $value)
        ) {
            $this->dirty[$name] = $value;
        }
    }

    public function __get($name)
    {
        if (array_key_exists($name, $this->dirty) && !empty($this->dirty[$name])) {
            $value = $this->dirty[$name];
        }
        else {
            if (array_key_exists($name, $this->fields)) {
                $value = $this->fields[$name];
            }
            else {
                trigger_error(
                    'Undefined property: ' . get_called_class() . '::' . $name .
                    ' in ' . __FILE__ .
                    ' on line ' . __LINE__,
                    E_USER_NOTICE);
                $value = NULL;
            }
        }

        return $value;
    }

    public function __isset($name)
    {
        return isset($this->dirty[$name]) || isset($this->fields[$name]);
    }

    public function __unset($name)
    {
        unset($this->fields[$name]);
        unset($this->dirty[$name]);
    }

    public static function getTableName()
    {
        return strtolower(basename(str_replace('\\', '/', get_called_class())));
    }

    public static function meta()
    {
        return [];
    }

    public static function getTableScheme()
    {
        $pdo = Application::getPDO();

        try {
            $table = self::getTableName();
            $statement = $pdo->prepare("DESCRIBE {$table}");
            $statement->execute();
            $tableScheme = $statement->fetchAll(PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            $pdo->rollBack();
            die($e->getMessage());
        }

        return $tableScheme;
    }

    public function load(array $fields)
    {
        $this->dirty = array_merge($this->dirty, $fields);
    }

    public static function findById($id)
    {
        $pdo = Application::getPDO();

        try {
            $table = self::getTableName();
            $pdo->beginTransaction();
            $statement = $pdo->prepare("SELECT * FROM {$table} WHERE id = :id");
            $statement->execute([':id' => $id]);
            $fields = $statement->fetch(PDO::FETCH_ASSOC);
            $pdo->commit();
            if (empty($fields)) {
                $instance = NULL;
            }
            else {
                $class = get_called_class();
                $instance = new $class();
                $instance->isNewRecord = FALSE;
                $instance->fields = $fields;
            }
        } catch (\Exception $e) {
            $pdo->rollBack();
            die($e->getMessage());
        }

        return $instance;
    }

    public static function findAll()
    {
        $pdo = Application::getPDO();
        $table = self::getTableName();
        $instances = [];

        try {
            $pdo->beginTransaction();
            $statement = $pdo->prepare("SELECT * FROM {$table}");
            $statement->execute();
            $pdo->commit();
            $results = $statement->fetchAll(PDO::FETCH_ASSOC);
            if (!empty($results)) {
                $class = get_called_class();
                foreach ($results as $fields) {
                    $instance = new $class();
                    $instance->fields = $fields;
                    $instance->isNewRecord = FALSE;
                    $instances[] = $instance;
                }
            }
        } catch (\Exception $e) {
            $pdo->rollBack();
            die($e->getMessage());
        }

        return $instances;
    }

    public static function findMultiple($offset = 0, $rows = 0)
    {
        $pdo = Application::getPDO();
        $offset = is_int($offset) ? $offset : intval($offset);
        $rows = is_int($rows) ? $rows : intval($rows);
        $instances = [];

        try {
            $pdo->beginTransaction();
            $table = self::getTableName();
            $statement = $pdo->prepare("SELECT * FROM {$table} LIMIT :offset, :rows");
            $statement->bindValue(':offset', $offset, PDO::PARAM_INT);
            $statement->bindValue(':rows', $rows, PDO::PARAM_INT);
            $statement->execute();
            $pdo->commit();
            $results = $statement->fetchAll(PDO::FETCH_ASSOC);
            if (!empty($results)) {
                $class = get_called_class();
                foreach ($results as $fields) {
                    $instance = new $class();
                    $instance->fields = $fields;
                    $instance->isNewRecord = FALSE;
                    $instances[] = $instance;
                }
            }
        } catch (\Exception $e) {
            $pdo->rollBack();
            die($e->getMessage());
        }

        return $instances;
    }

    public function hasMany($class, $reference, $junction_table, $junction_reference)
    {
        $pdo = Application::getPDO();

        $referredTable = $class::getTableName();
        $pdo->beginTransaction();

        try {
            $statement = $pdo->prepare("SELECT {$referredTable}.* FROM {$referredTable} JOIN {$junction_table} ON {$reference} WHERE {$junction_reference};");
            $statement->execute();
            $pdo->commit();
            $results = $statement->fetchAll(PDO::FETCH_ASSOC);
            $instances = [];
            if (!empty($results)) {
                foreach ($results as $fields) {
                    $instance = new $class();
                    $instance->fields = $fields;
                    $instance->isNewRecord = FALSE;
                    $instances[] = $instance;
                }
            }
        } catch (\Exception $e) {
            $pdo->rollBack();
            die($e->getMessage());
        }

        return $instances;
    }

    public function save()
    {
        if ($this->isNewRecord) {
            $this->insert();
        }
        else {
            $this->update();
        }
    }

    private final function insert()
    {
        $pdo = Application::getPDO();

        $properties = self::getTableScheme();
        $table = self::getTableName();
        $columns = [];
        foreach ($properties as $property) {
            if (array_key_exists($property['Field'], $this->dirty)
                && !empty($this->dirty[$property['Field']])
            ) {
                $columns[$property['Field']] = $this->dirty[$property['Field']];
            }
        }
        $columns_string = implode(', ', array_keys($columns));
        $placeholders_string = implode(', ', array_map(function ($column) {
            return ':' . $column;
        }, array_keys($columns)));

        try {
            $pdo->beginTransaction();
            $statement = $pdo->prepare("INSERT INTO {$table} ({$columns_string}) VALUES ({$placeholders_string})");
            foreach ($columns as $column => $value) {
                $statement->bindValue(':' . $column, $value, self::meta()[$column]['type']);
            }
            $statement->execute();
            $this->fields['id'] = $pdo->lastInsertId();
            $pdo->commit();
            $this->fields += $this->dirty;
            $this->dirty = [];
            $this->isNewRecord = FALSE;
        } catch (\Exception $e) {
            $pdo->rollBack();
            die($e->getMessage());
        }
    }

    private final function update()
    {
        $pdo = Application::getPDO();

        $properties = self::getTableScheme();
        $table = self::getTableName();
        $columns = [];
        foreach ($properties as $property) {
            if (array_key_exists($property['Field'], $this->dirty)
                && !empty($this->dirty[$property['Field']])
            ) {
                $columns[$property['Field']] = $this->dirty[$property['Field']];
            }
        }
        $columns_and_placeholders = implode(', ', array_map(function ($column) {
            return $column . ' = :' . $column;
        }, array_keys($columns)));

        try {
            $pdo->beginTransaction();
            $statement = $pdo->prepare("UPDATE {$table} SET {$columns_and_placeholders} WHERE id = :id");
            $statement->bindValue(':id', $this->id, PDO::PARAM_INT);
            foreach ($columns as $column => $value) {
                $statement->bindValue(':' . $column, $value, self::meta()[$column]['type']);
            }
            $statement->execute();
            $pdo->commit();
        } catch (\Exception $e) {
            $pdo->rollBack();
            die($e->getMessage());
        }
    }

    public final function delete()
    {
        $pdo = Application::getPDO();
        $table = self::getTableName();

        try {
            $pdo->beginTransaction();
            $statement = $pdo->prepare("DELETE FROM {$table} WHERE id = :id");
            $statement->bindValue(':id', $this->fields['id'], self::meta()['id']['type']);
            $statement->execute();
            $pdo->commit();
        } catch (\Exception $e) {
            $pdo->rollBack();
            die($e->getMessage());
        }
    }
}
