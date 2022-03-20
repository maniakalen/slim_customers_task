<?php

namespace Example\Configs\Routes;

use Example\Interfaces\AppModificators;
use Slim\App;

/**
 * Users routes registration
 */
class CustomersRouter implements AppModificators
{
    /**
     * @param App $app
     */
    public static function register(App $app)
    {
        $app->get('/customers/index', 'Example\Controllers\Customer:index');
        $app->get('/customers/form', 'Example\Controllers\Customer:form');
        $app->get('/customers/error', 'Example\Controllers\Customer:error');
        $app->get('/customers/delete/{id}', 'Example\Controllers\Customer:delete');
        $app->get('/customers/edit/{id}', 'Example\Controllers\Customer:edit');
        $app->post('/customers/update/{id}', 'Example\Controllers\Customer:update');
        $app->post('/customers/create', 'Example\Controllers\Customer:create');
    }
}