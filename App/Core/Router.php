<?php

namespace App\Core;

class Router
{
    private array $routes = [];

    public function get(string $path, array $action)
    {
        $this->add('GET', $path, $action);
    }

    public function post(string $path, array $action)
    {
        $this->add('POST', $path, $action);
    }

    public function put(string $path, array $action)
    {
        $this->add('PUT', $path, $action);
    }

    public function delete(string $path, array $action)
    {
        $this->add('DELETE', $path, $action);
    }

    private function add(string $method, string $path, array $action)
    {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'action' => $action,
        ];
    }

    public function dispatch(string $method, string $url)
    {
        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }

            $params = $this->match($route['path'], $url);

            if ($params !== false) {
                [$controllerClass, $controllerMethod] = $route['action'];

                $controller = new $controllerClass();

                // Els paràmetres extrets de la URL s'envien al mètode del controlador.
                $controller->$controllerMethod(...$params);
                return;
            }
        }

        http_response_code(404);
        (new \App\Controllers\HomeController())->notFound();
    }

    private function match(string $routePath, string $url)
    {
        // Convertim /products/{id} en una expressió regular.
        // Cada {parametre} captura una part de la URL que no contingui '/'.
        $pattern = preg_replace('/\{[^\/]+\}/', '([^/]+)', $routePath);
        $pattern = '#^' . $pattern . '$#';

        if (!preg_match($pattern, $url, $matches)) {
            return false;
        }

        // Eliminem la coincidència completa i conservem només els paràmetres.
        array_shift($matches);

        return $matches;
    }
}
