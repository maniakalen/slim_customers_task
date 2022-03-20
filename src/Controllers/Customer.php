<?php

namespace Example\Controllers;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class Customer
{
    protected $container;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container) {
        $this->container = $container;
    }

    public function index(Request $request, Response $response)
    {
        try {
            [$field, $dir] = explode(':', $request->getQueryParam('order', 'id:asc'));
            $customers = $this->container->get('customers');
            return $this->container
                ->get('view')
                ->render(
                    $response,
                    'index.twig',
                    [
                        'customers' => $customers->getAllModels($field, $dir),
                        'ordBy' => $field,
                        'ordDir' => $dir
                    ]
                );
        } catch (\Throwable $t) {
        }
        return $response->withRedirect('/customers/error');
    }

    public function form(Request $request, Response $response)
    {
        try {
            return $this->container->get('view')->render($response, 'form.twig');
        } catch (\Throwable $t) {

        }
        return $response->withRedirect('/customers/error');
    }

    public function edit(Request $request, Response $response, $args)
    {
        try {
            $service = $this->container->get('customers');
            return $this->container
                ->get('view')
                ->render(
                    $response,
                    'edit.twig',
                    ['user' => $service->findById($args['id'])]
                );
        } catch (\Throwable $t) {
        }
        return $response->withRedirect('/customers/error');
    }

    public function create(Request $request, Response $response)
    {
        try {
            /** @var \Example\Services\Customer $service */
            $service = $this->container->get('customers');
            if ($service->create((object)$request->getParsedBody())) {
                return $response->withRedirect('/customers/index');
            }
        } catch (\Throwable $error) {

        }
        return $response->withRedirect('/customers/error');
    }

    public function delete(Request $request, Response $response, $attr)
    {
        try {
            /** @var \Example\Services\Customer $service */
            $service = $this->container->get('customers');
            if ($service->delete($attr['id'])) {
                return $response->withRedirect('/customers/index');
            }
        } catch (\Throwable $error) {
        }
        return $response->withRedirect('/customers/error');
    }

    public function update(Request $request, Response $response, $attr)
    {
        try {
            /** @var \Example\Services\Customer $service */
            $service = $this->container->get('customers');
            if ($service->update($attr['id'], (object)$request->getParsedBody())) {
                return $response->withRedirect('/customers/index');
            }
        } catch (\Throwable $error) {
            die($error->getMessage());
        }
        return $response->withRedirect('/customers/error');
    }

    public function error(Request $request, Response $response)
    {
        try {
            return $this->container->get('view')->render($response, 'error.twig');
        } catch (\Throwable $t) {
            die("Fatal error");
        }
    }
}