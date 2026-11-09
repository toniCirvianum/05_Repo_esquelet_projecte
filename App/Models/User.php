<?php

namespace App\Models;

class User
{
    public function getUserLogged(): ?array
    {
        // Iniciem la sessió si encara no està activa.
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Si no hi ha cap usuari identificat, retornem null.
        return $_SESSION['user_logged'] ?? null;
    }

    public function setUserLogged(array $user): void
    {
        // Iniciem la sessió si encara no està activa.
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Guardem les dades de l'usuari identificat a la sessió.
        $_SESSION['user_logged'] = $user;
    }

    public function logout(): void
    {
        // Iniciem la sessió si encara no està activa.
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Eliminem l'usuari identificat de la sessió.
        unset($_SESSION['user_logged']);
    }
}
