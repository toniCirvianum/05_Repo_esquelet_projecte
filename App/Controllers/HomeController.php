<?php

namespace App\Controllers;

use App\Core\Controller;

class HomeController extends Controller
{
    public function index()
    {
        $this->render('login/index', ['title' => 'Iniciar sessió']);
    }

    public function notFound()
    {
        //mostra pàgina d'error 404
        http_response_code(404);
        $this->render('errors/404', ['title' => 'Pàgina no trobada']);
    }
}
