<?php
use Dotenv\Dotenv;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

if (file_exists(__DIR__ . '/../.env')) {
    $dotenv = Dotenv::createImmutable(__DIR__ . '/../');
    $dotenv->safeLoad();
}

$app = AppFactory::create();

// Add your middleware wrappers in the correct execution order
$app->add(new App\Middleware\SecurityHeaders()); 
$app->add(new App\Middleware\JsonBodyParser());
$app->add(new App\Middleware\Cors());

$app->addRoutingMiddleware();
$app->addErrorMiddleware(true, true, true);

$routes = require __DIR__ . '/../src/routes.php';
$routes($app);

$app->run();