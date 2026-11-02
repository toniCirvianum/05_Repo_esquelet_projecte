<?php

namespace App\Core;

use App\Models\User;

class Controller
{
    protected function render($path, $params = [], $layout = 'main')
    {
        extract($params);

        ob_start();
        $path2 = __DIR__ . '/../Views/' . $path . '.view.php';
        require __DIR__ . '/../Views/' . $path . '.view.php';
        $content = ob_get_clean();
        $path3 = __DIR__ . '/../Views/layouts/' . $layout . '.layout.php';
        require __DIR__ . '/../Views/layouts/' . $layout . '.layout.php';
    }

    //redirigeix a la ruta que passem per parametre
    protected function redirect($url)
    {
        header('Location: ' . $url, true, 303);
        exit;
    }
}
