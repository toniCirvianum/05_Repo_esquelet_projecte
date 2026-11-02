<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;
use App\Database\Schema;
use App\Database\Seeder;
use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        // La primera visita a / prepara la taula i les dades de prova.
        $database = new Database();
        $schema = new Schema($database);
        //Crea la taula productes i l'omple amb dades del Seeder
        if (!$schema->productsTableExists()) {
            $schema->createProductsTable();

            $seeder = new Seeder($database);
            $seeder->insertProducts();
        }

        //recuperem els productes de la base de dades
        $p = new Product();
        $products = $p->getAll();
        //els passem a la vista amb el render
        $this->render('home/index', [
            'title' => 'Productes',
            'products' => $products
        ]);


    }
    public function notFound()
    {
        //mostra pàgina d'error 404
        http_response_code(404);
        $this->render('errors/404', ['title' => 'Pàgina no trobada']);
    }
}
