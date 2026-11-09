<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;
use App\Database\Schema;
use App\Database\Seeder;
use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        // Permet accedir directament a /products en una base de dades nova.
        $database = new Database();
        $schema = new Schema($database);

        if (!$schema->productsTableExists()) {
            $schema->createProductsTable();
            (new Seeder($database))->insertProducts();
        }

        $product = new Product();

        $this->render('products/index', [
            'title' => 'Productes',
            'products' => $product->getAll(),
        ], 'app');
    }
}
