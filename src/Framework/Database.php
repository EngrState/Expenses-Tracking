<?php

declare(strict_types=1);

namespace Framework;


use PDO, PDOException, PDOStatement;

class Database
{
    public PDO $connection;
    private string $dbname;
    private PDOStatement $stmt;

    public function __construct(
        string $driver,
        array $config,
        string $username,
        string $password
    ) {
        $this->dbname = $config['dbname'];
        // $config = http_build_query([$config], arg_separator: ';');

        // $dsn = "{$driver}:{$config}";
        $dsn = "{$driver}:host={$config['host']};port={$config['port']};dbname={$this->dbname}";


        try {
            $this->connection = new PDO($dsn, $username, $password,[PDO::ATTR_DEFAULT_FETCH_MODE=> PDO::FETCH_ASSOC]);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            // echo "✅ Connected to database: {$this->dbname}\n";
        } catch (PDOException $e) {
            die("unable to connect to database");
        }
    }
    public function query(string $query, array $params = []): Database
    {
        $this->stmt = $this->connection->prepare($query);

        $this->stmt->execute($params);

        return $this;
    }

    public function count()
    {
        return $this->stmt->fetchColumn();
    }
    public function find()
    {
        return $this->stmt->fetch();
    }
    public function id()
    {
        return $this->connection->lastInsertid();
    }
    public function findAll()
    {
        return $this->stmt->fetchAll();
    }
}
