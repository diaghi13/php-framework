<?php


namespace application\core\database;



class Database {
    public \PDO $pdo;

    public function __construct(array $config) {
        $dbType = $config['dbType'] ?? '';
        $dbHost = $config['dbHost'] ?? '';
        $dbPort = $config['dbPort'] ?? '';
        $dbName = $config['dbName'] ?? '';
        $user = $config['user'] ?? '';
        $password = $config['password'] ?? '';
        $dsn = "{$dbType}:host={$dbHost};port={$dbPort};dbname={$dbName}";

        try {
            $this->pdo = new \PDO($dsn, $user, $password);
            $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch (\Exception $exception) {
            print_r('Unable to connect');
        }
    }

    public function prepare($sql): bool|\PDOStatement {
        return $this->pdo->prepare($sql);
    }

    public function insert(string $table, array $data) {
        ksort($data);
        $fieldsName = '`' . implode('`, `', array_keys($data)) . '`';
        $fieldsValue = ':' . implode(', :', array_keys($data));
        $sql = "INSERT INTO $table ($fieldsName) VALUES ($fieldsValue)";
        $sth = $this->prepare($sql);
        foreach ($data as $key => $value) {
            $sth->bindValue(":$key", $value);
        }
        if (!$sth->execute()){
            throw new \PDOException("DB insert error: " . $this->pdo->errorInfo(), 500);
        }
    }

    public function lastInsertId(): string {
        return $this->pdo->lastInsertId();
    }

    public function update(array $data) {

    }

    public function delete(int $id) {

    }
}