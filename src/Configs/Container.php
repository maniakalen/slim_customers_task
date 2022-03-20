<?php

namespace Example\Configs;


use Example\Interfaces\AppModificators;
use Example\Services\Database;
use Example\Services\Customer;
use Slim\App;

/**
 * Container modification class
 */
class Container implements AppModificators
{
    /**
     * @param App $app
     */
    public static function register(App $app)
    {
        $container = $app->getContainer();

        $container['db'] = function($container) {
            $db = new Database('127.0.0.1', '3306', 'user', 'userpwd', 'customers');
            return $db;
        };

        $container['customers'] = function($container) {
            $user = new Customer($container);
            return $user;
        };

        $container['errorHandler'] = function ($c) {
            return function ($request, $response, $exception) use ($c) {
                return $response->withStatus($exception->getCode()?:500)
                    ->write(
                        $c->get('view')->render($response, 'error.html')
                    );
            };
        };

        $container['view'] = function ($container) {
            $view = new \Slim\Views\Twig('../src/views');

            // Instantiate and add Slim specific extension
            $router = $container->get('router');
            $uri = \Slim\Http\Uri::createFromEnvironment(new \Slim\Http\Environment($_SERVER));
            $view->addExtension(new \Slim\Views\TwigExtension($router, $uri));

            return $view;
        };
    }
}