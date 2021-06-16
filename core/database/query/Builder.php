<?php


namespace application\core\database\query;


use application\core\Application;
use application\core\database\Database;

class Builder {
    private Database $db;
    private string $table;

    private ?string $class = null;

    private array $columns = ['*'];

    private array $operators = [
        '=', '<', '>', '<=', '>=', '<>', '!=', '<=>',
        'like', 'like binary', 'not like', 'ilike',
        '&', '|', '^', '<<', '>>',
        'rlike', 'not rlike', 'regexp', 'not regexp',
        '~', '~*', '!~', '!~*', 'similar to',
        'not similar to', 'not ilike', '~~*', '!~~*',
    ];

    private array $wheres = [];

    public function __construct() {
        $this->db = Application::$app->database;
    }

    //abstract public static function tableName(): string;

    public function table($table) {
        $this->table = $table;
        return $this;
    }

    public function model(string $class): Builder {
        $this->class = $class;
        return $this;
    }

    public function select($columns = ['*']): Builder {
        $this->columns = [];
        foreach ($columns as $column) {
            $this->columns[] = $column;
        }
        return $this;
    }

    public function where(string $column, string | int | bool $operator, string | int | bool $value = null) {
        if (in_array($operator, $this->operators) && $value) {
            $value = is_bool($value) ? $this->boolParser($value) : $value;
            $this->wheres[$column] = [$column, $operator, $value];
        } else {
            $operator = is_bool($operator) ? $this->boolParser($operator) : $operator;
            $this->wheres[$column] = [$column, '=', $operator];
        }
        return $this;
    }

    public function get() {
        $tableName = $this->table;
        $columns = implode(', ', $this->columns);
        $attributes = implode(' AND ', array_map(fn($attr) => "$attr[0] $attr[1] :$attr[0]", $this->wheres));
        if ($attributes) {
            $sql = "SELECT $columns FROM $tableName WHERE $attributes";
            $sth = $this->db->prepare($sql);
            foreach ($this->wheres as $key => $value) {
                $sth->bindValue(":$key", $value[2]);
            }
        } else {
            $sql = "SELECT $columns FROM $tableName";
            $sth = $this->db->prepare($sql);
        }
        $sth->execute();
        return $sth->fetchAll(\PDO::FETCH_CLASS, $this->class);
    }

    public function insert(array $data): bool {
        $tableName = $this->table;

        if (!is_array($data)) {
            return true;
        }

        $this->db->insert($tableName, $data);
    }

    public function insertGetId(array $data): bool|string {
        $tableName = $this->table;

        if (!is_array($data)) {
            return true;
        }

        $this->db->insert($tableName, $data);
        return  $this->db->lastInsertId();
    }

    private function boolParser(bool $value): int {
        if ($value) {
            return 1;
        }
        return 0;
    }
}