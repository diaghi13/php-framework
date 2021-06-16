<?php


namespace application\core\database;


use application\core\Application;
use application\core\database\query\Builder;
use PDO;

abstract class DbModel extends Model {

    abstract public static function tableName(): string;

    public function primaryKey(): string {
        return 'id';
    }

    public function insert(): DbModel {
        $query = new Builder();
        $data = $this->dataParsing();
        $query->table(static::tableName())->insert($data);
    }

    public function update(array $data) {

    }

    public function delete(int $id) {

    }

    public static function query(string $sql, array $attributes = []): array {
        $sth = self::prepare($sql);
        if ($attributes) {
            foreach ($attributes as $key => $value) {
                $sth->bindValue(":$key", $value);
            }
        }
        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function findOne(array $where = []) {
        $query = new Builder();
        $query->table(static::tableName())->model(static::class);
        foreach ($where as $key => $value) {
            $query->where($key, $value);
        }
        return self::objectParser($query->get()[0]);
    }

    public static function findMany(array $where = []) {
        $query = new Builder();
        $query->table(static::tableName())->model(static::class);
        foreach ($where as $key => $value) {
            $query->where($key, $value);
        }
        $results = $query->get();
        $objects = [];
        foreach ($results as $result) {
            $objects[] = self::objectParser($result);
        }
        return $objects;
    }

    public static function hasOne() {

    }

    public static function hasMany() {

    }

    public static function belongToOne() {

    }

    public static function belongToMany() {

    }

    public static function prepare($sql): bool|\PDOStatement {
        return Application::$app->database->prepare($sql);
    }

    public function getColumns(string $tableName): array {
        $sql = "SELECT `COLUMN_NAME` 
                FROM `information_schema`.`COLUMNS` 
                WHERE `TABLE_SCHEMA`='{$_ENV['DB_NAME']}' AND `TABLE_NAME`='$tableName'";
        $sth = self::prepare($sql);
        $sth->execute();
        return $sth->fetchAll(PDO::FETCH_COLUMN);
    }

    protected static function objectParser(DbModel $model): DbModel {
        foreach (get_object_vars($model) as $key => $value) {
            if (strpos($key, '_')) {
                $prop = (new static)->db2ClassName($key);
                $model->{$prop} = $value;
                unset($model->$key);
            }
        }
        return $model;
    }

    private function paramsFormat(string $tableName): array {
        $columns = $this->getColumns($tableName);
        $props = [];
        foreach ($columns as $column) {
            $property = $this->db2ClassName($column);
            if (property_exists($this, $property))
                $props[$property] = $column;
        }
        unset($props[$this->primaryKey()]);
        return $props;
    }

    private function dbParamsFormat() {}

    public function db2ClassName(string $property): string {
        $words = explode('_', $property);
        for ($i = 0; $i < count($words); $i++) {
            if ($i > 0) {
                $words[$i] = ucfirst($words[$i]);
            }
        }
        return implode('', $words);
    }

    public function class2DbName(string $property): string {
        $property = lcfirst($property);
        $parts = preg_split('/(?=[A-Z])/', $property);
        $parts = array_map('strtolower', $parts);
        return implode("_", $parts);
    }

    private function dataParsing(): array {
        $data = get_object_vars($this);
        $columns = $this->getColumns(static::tableName());
        $reformatData = [];
        foreach ($data as $key => $value) {
            if (in_array($this->class2DbName($key), $columns)) {
                $reformatData[$this->class2DbName($key)] = $value;
            }
        }
        return $reformatData;
    }
}