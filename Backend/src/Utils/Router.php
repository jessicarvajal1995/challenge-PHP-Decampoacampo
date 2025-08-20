<?php

class Router
{
    private $routes = [];

    public function addRoute($method, $pattern, $callback)
    {
        $this->routes[] = [
            'method' => strtoupper($method),
            'pattern' => $pattern,
            'callback' => $callback
        ];
    }

    public function get($pattern, $callback)
    {
        $this->addRoute('GET', $pattern, $callback);
    }

    public function post($pattern, $callback)
    {
        $this->addRoute('POST', $pattern, $callback);
    }

    public function put($pattern, $callback)
    {
        $this->addRoute('PUT', $pattern, $callback);
    }

    public function delete($pattern, $callback)
    {
        $this->addRoute('DELETE', $pattern, $callback);
    }

    public function dispatch()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        
        $uri = preg_replace('#^/[^/]*\.php#', '', $uri);
        
        foreach ($this->routes as $route) {
            if ($route['method'] === $method) {
                $pattern = '#^' . $route['pattern'] . '$#';
                if (preg_match($pattern, $uri, $matches)) {
                    array_shift($matches);
                    call_user_func_array($route['callback'], $matches);
                    return;
                }
            }
        }

        Response::error('Ruta no encontrada', 404);
    }
} 