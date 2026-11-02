<?php

namespace App\Database;

use App\Core\Database;

class Schema
{
    private Database $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    // Comprova el catàleg de MySQL dins de la BBDD de la connexió actual.
    public function productsTableExists(): bool
    {
        $table = $this->db->fetchOne(
            'SELECT TABLE_NAME
             FROM information_schema.TABLES
             WHERE TABLE_SCHEMA = DATABASE()
               AND TABLE_NAME = :name',
            ['name' => 'products']
        );

        return $table !== null;
    }

    // La decisió de quan crear la taula i carregar dades correspon a init.php.
    public function createProductsTable(): void
    {
        $this->db->execute(
            'CREATE TABLE products (
                id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(150) NOT NULL,
                price DECIMAL(10, 2) NOT NULL,
                category VARCHAR(100) NOT NULL,
                CONSTRAINT chk_products_price CHECK (price >= 0)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4'
        );
    }
}
