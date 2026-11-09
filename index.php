<?php

// La sessió conserva les dades entre l'anada a Google i el retorn.
session_start();

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/autoload.php';

// Carreguem el .env a $_ENV abans d'inicialitzar l'aplicació.
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

use App\Core\Router;
use App\Controllers\LoginController;
use App\Controllers\HomeController;
use App\Controllers\ProductController;


$router = new Router();

//**
//** RUTES
//

//ruta arrel
$router->get('/', [HomeController::class, 'index']);

//mostra error
$router->get('/error',[HomeController::class,'notFound']);


// Rutes públiques d'autenticació.
$router->get('/login', [LoginController::class, 'login']);
$router->get('/auth/google', [LoginController::class, 'redirectToGoogle']);
$router->get('/auth/google/callback', [LoginController::class, 'googleCallback']);

// El controlador haurà de comprovar la sessió per accedir al perfil.
$router->get('/profile', [LoginController::class, 'profile']);
$router->post('/logout', [LoginController::class, 'logout']);

// Part interna de l'aplicació: pendent de protegir amb la sessió.
$router->get('/products', [ProductController::class, 'index']);



//En el cas de passar PUT o DELTE enganyem al router i li passem el mètode
//PUT o DELTE enlloc de POST
$method = $_SERVER['REQUEST_METHOD'];
//strtoupper --> converteix a majuscules
if ($method === 'POST' && 
    isset($_POST['_method']) && 
    in_array(strtoupper($_POST['_method']), ['PUT', 'DELETE'], true)) {
    $method = strtoupper($_POST['_method']);
}

//de la url extraiem els parametres
//si la ruta és http://localhost/products?param=4
//ens quedem amb http://localhost/products
$url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH); 

//cridem al router amb la ruta i el mètode perquè la gestioni 
$router->dispatch($method, $url);
