<?php

namespace App\Core;

use PDO;
use PDOStatement;

class Database
{
    private PDO $connection;

    public function __construct()
    {
        // Dotenv ha de carregar el .env abans de crear aquesta classe.
        $host = $_ENV['DB_HOST'];
        $port = $_ENV['DB_PORT'];
        $name = $_ENV['DB_NAME'];

        $this->connection = new PDO(
            "mysql:host={$host};port={$port};dbname={$name};charset=utf8mb4",
            $_ENV['DB_USER'],
            $_ENV['DB_PASSWORD'],
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]
        );
    }

    // Els valors s'envien separats del SQL mitjançant consultes preparades.
    public function execute(string $sql, array $params = []): PDOStatement
    {
        $statement = $this->connection->prepare($sql);
        $statement->execute($params);

        return $statement;
    }

    // Retorna l'identificador autogenerat per l'últim INSERT d'aquesta connexió.
    public function lastInsertId(): string
    {
        return $this->connection->lastInsertId();
    }

    // Retorna una fila associativa o null si no hi ha resultats.
    public function fetchOne(string $sql, array $params = []): ?array
    {
        $row = $this->execute($sql, $params)->fetch();

        return $row === false ? null : $row;
    }

    // Retorna totes les files; si no n'hi ha, retorna un array buit.
    public function fetchAll(string $sql, array $params = []): array
    {
        return $this->execute($sql, $params)->fetchAll();
    }
}
