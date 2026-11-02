<?php

namespace App\Database;

use App\Core\Database;

class Seeder
{
    private Database $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    // La taula products ha d'existir abans d'executar aquest mètode.
    // Cada execució afegeix els set productes; no comprova si ja existeixen.
    public function insertProducts(): void
    {
        $products = [
            ['name' => 'Mechanical Keyboard', 'price' => '59.90', 'category' => 'Peripherals'],
            ['name' => 'Gaming Mouse', 'price' => '29.90', 'category' => 'Peripherals'],
            ['name' => 'Monitor 24"', 'price' => '149.90', 'category' => 'Monitors'],
            ['name' => 'USB-C Hub', 'price' => '34.90', 'category' => 'Accessories'],
            ['name' => 'Webcam Full HD', 'price' => '49.90', 'category' => 'Peripherals'],
            ['name' => 'SSD 1TB', 'price' => '89.90', 'category' => 'Storage'],
            ['name' => 'Laptop Stand', 'price' => '24.90', 'category' => 'Accessories'],
        ];

        foreach ($products as $product) {
            $this->db->execute(
                'INSERT INTO products (name, price, category)
                 VALUES (:name, :price, :category)',
                $product
            );
        }
    }
}
