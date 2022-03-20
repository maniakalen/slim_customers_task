<?php

use Example\Configs\Container;
use Example\Configs\Routes\CustomersRouter;

require __DIR__ .'/../vendor/autoload.php';

$container = new Slim\Container;
$app = new Slim\App($container);

CustomersRouter::register($app);
Container::register($app);


$app->run();
