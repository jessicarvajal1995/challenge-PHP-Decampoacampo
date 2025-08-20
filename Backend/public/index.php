<?php

// Cargo variables de entorno
require __DIR__ . '/../config/env_loader.php';

// Autoload de clases
spl_autoload_register(function ($class) {
    $paths = [
        __DIR__ . '/../src/Controllers/',
        __DIR__ . '/../src/Models/',
        __DIR__ . '/../src/Services/',
        __DIR__ . '/../src/Database/',
        __DIR__ . '/../src/Utils/'
    ];
    
    foreach ($paths as $path) {
        $file = $path . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

// CORS
Response::handleCors();

$router = new Router();

$productoController = new ProductoController();

$router->get('/productos', [$productoController, 'index']);
$router->get('/productos/(\d+)', [$productoController, 'show']);
$router->post('/productos', [$productoController, 'store']);
$router->put('/productos/(\d+)', [$productoController, 'update']);
$router->delete('/productos/(\d+)', [$productoController, 'destroy']);

// health check
$router->get('/health', function() {
    Response::success(['status' => 'OK', 'timestamp' => date('Y-m-d H:i:s')], 'API funcionando correctamente');
});

try {
    $router->dispatch();
} catch (Exception $e) {
    Response::error('Error interno del servidor: ' . $e->getMessage(), 500);
} 