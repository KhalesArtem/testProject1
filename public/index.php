<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

$uri = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];

// Расширенный роутинг
$routes = [
    'GET /' => ['UserController', 'index'],
    'GET /api/users' => ['UserController', 'getUsers'],
    'POST /api/users' => ['UserController', 'create'],
    'PUT /api/users/{id}' => ['UserController', 'update'],
    'DELETE /api/users/{id}' => ['UserController', 'delete'],
    'GET /goods' => ['GoodController', 'index'],
    'GET /api/goods' => ['GoodController', 'getGoods']
];

// Обработка маршрута
foreach ($routes as $route => $handler) {
    [$routeMethod, $routePath] = explode(' ', $route);
    
    // Проверяем соответствие метода и пути
    if ($method === $routeMethod) {
        // Обработка параметров в URL
        $pattern = preg_replace('/\{[^}]+\}/', '([^/]+)', $routePath);
        $pattern = str_replace('/', '\/', $pattern);
        $pattern = '/^' . $pattern . '$/';
        
        if (preg_match($pattern, $uri, $matches)) {
            array_shift($matches); // Удаляем полное совпадение
            
            $controller = "App\\Controllers\\{$handler[0]}";
            $action = $handler[1];
            
            $controller = new $controller();
            call_user_func_array([$controller, $action], $matches);
            exit;
        }
    }
}

http_response_code(404);
echo 'Not Found';
