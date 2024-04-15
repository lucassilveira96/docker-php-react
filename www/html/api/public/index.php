<?php

use App\Routes\Router;

require_once __DIR__ . '/../vendor/autoload.php';

$router = new Router();
$router->routeRequest();