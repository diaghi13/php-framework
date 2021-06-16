<?php


namespace application\core\support\facades;


use application\core\database\query\Builder;

class DB {
    public static function table(string $table): Builder {
        $db = new Builder();
        $db->table($table);
        return $db;
    }
}