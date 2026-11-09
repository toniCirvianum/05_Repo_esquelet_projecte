<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;
use Google\Client;
use Google\Service\Oauth2;

class LoginController extends Controller
{
    //**** Mostra la pàgina de login ****//
    public function login()
    {
        $this->render('login/index', ['title' => 'Iniciar sessió']);
    }

    //**** Redirigeix a Google ****//
    public function redirectToGoogle()
    {
        // Creem el client de Google per preparar la petició d'autenticació.
        $client = $this->createGoogleClient();

        // Demanem identificar l'usuari (openid), el correu i el perfil bàsic.
        $client->addScope(['openid', 'email', 'profile']);

        // Generem l'URL de Google on l'usuari podrà identificar-se.
        $url = $client->createAuthUrl();

        // Redirigim a Google amb el metode redirect dle Core/Controller
        $this->redirect($url);
    }

    //**** Processa el retorn de Google i identifica l'usuari ****//
    public function googleCallback()
    {
        // Si l'usuari cancel·la o no rebem un codi vàlid, mostrem el login.
        if (isset($_GET['error']) || !isset($_GET['code']) ||
            !is_string($_GET['code']) || $_GET['code'] === '') {
            $this->render('login/index', [
                'title' => 'Iniciar sessió',
                'error' => 'No s’ha completat l’autenticació amb Google.',
            ]);
            return;
        }

        try {
            // Utilitzem les mateixes credencials i URI que a la redirecció.
            $client = $this->createGoogleClient();

            // Intercanviem el codi d'autorització per un token d'accés.
            $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);

            // Consultem a Google les dades de l'usuari amb aquest token.
            $client->setAccessToken($token);
            $oauth = new Oauth2($client);
            $user = $oauth->userinfo->get();

        } catch (\Exception $e) {
            // Mostrem un missatge genèric sense exposar tokens ni credencials.
            $this->render('login/index', [
                'title' => 'Iniciar sessió',
                'error' => 'No s’ha pogut iniciar sessió. Torna-ho a provar.',
            ]);
            return;
        }

        // Convertim les dades del perfil de Google en un array i les guardem amb el model.
        $userModel = new User();
        // convertim l'objecte que ens passa google en un array associatiu
        $userLogged = get_object_vars($user->toSimpleObject());
        $userModel->setUserLogged($userLogged);

        // Un cop identificat, l'usuari entra a la llista de productes.
        $this->redirect('/products');
    }

    //**** Mostra el perfil de l'usuari identificat ****//
    public function profile()
    {
        $userModel = new User();
        $user = $userModel->getUserLogged();

        // Només mostrem el perfil si l'usuari ha iniciat sessió.
        if ($user == null) {
            $this->redirect('/login');
            return;
        }

        // Passem a la vista les dades de Google guardades al callback.
        $this->render('profile/index', [
            'title' => 'Perfil',
            'user' => $user,
        ], 'app');
    }

    //**** Tanca la sessió de l'usuari ****//
    public function logout()
    {
        // Deleguem al model l'eliminació de l'usuari de la sessió.
        $userModel = new User();
        $userModel->logout();

        $this->redirect('/login');
    }

    //**** Crea el client de Google amb les credencials del .env ****//
    private function createGoogleClient(): Client
    {
        // Creem el client i identifiquem l'aplicació amb les credencials del .env.
        $client = new Client();
        $client->setClientId($_ENV['GOOGLE_CLIENT_ID']);
        $client->setClientSecret($_ENV['GOOGLE_CLIENT_SECRET']);

        // Indiquem l'adreça on Google retornarà després de l'autenticació.
        $client->setRedirectUri($_ENV['GOOGLE_REDIRECT_URI']);

        return $client;
    }
}
