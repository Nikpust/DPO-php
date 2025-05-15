<?php

    require_once __DIR__ . '/../vendor/autoload.php';

    use App\core\Router;

    $router = new Router();
    require_once __DIR__ . '/../routes/web.php';
    $router->dispatch();

?>