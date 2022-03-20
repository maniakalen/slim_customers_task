<?php

namespace Example\Services;

use Example\Models\CustomerModel;
use Psr\Container\ContainerInterface;

/**
 * User management service
 */
class Customer
{
    protected $container;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Returns an array with all users
     *
     * @return array
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function getAllModels($orderBy = 'id', $orderDir = 'asc')
    {
        $model = new CustomerModel();
        if (!in_array($orderBy, $model->attributes())) {
            throw new \Exception('Invalid attribute');
        }
        /** @var Database $db */
        $db = $this->container->get('db');
        $models = $db->findModels($model);
        usort($models, function(CustomerModel $m1, CustomerModel $m2) use ($orderBy, $orderDir) {
            if ($orderBy == 'id' || $orderBy == 'priority') {
                return $orderDir == 'asc' ? $m1->$orderBy - $m2->$orderBy: $m2->$orderBy - $m1->$orderBy;
            } else {
                return $orderDir == 'asc' ? strcmp($m1->$orderBy, $m2->$orderBy) : strcmp($m2->$orderBy, $m1->$orderBy);
            }
        });
        return $models;
    }

    /**
     * Returns a User model corresponding to the id provided. Throws exception if no user is found.
     *
     * @param int $id
     * @return false|mixed
     * @throws \Exception
     */
    public function findById($id)
    {
        /** @var Database $db */
        $db = $this->container->get('db');
        $models = $db->findModels(new CustomerModel(), ['id' => $id]);
        if (empty($models)) {
            throw new \Exception("User not found");
        }
        return reset($models);
    }

    /**
     * Updates user corresponding to the id with the data provided
     *
     * @param int $id
     * @param object $data
     * @return bool
     * @throws \Exception
     */
    public function update($id, $data)
    {
        /** @var Database $db */
        $db = $this->container->get('db');
        $model = $this->findById($id);
        $model->populate($data);
        return $db->update($model);
    }

    /**
     * Deletes user registry corresponding to the id provided
     *
     * @param int $id
     * @return bool
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function delete($id)
    {
        /** @var Database $db */
        $db = $this->container->get('db');
        return $db->delete(CustomerModel::tableName(), $id);
    }

    /**
     * Generates new user with the data provided
     *
     * @param object $data
     * @return bool
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function create($data)
    {

        /** @var Database $db */
        $db = $this->container->get('db');
        $user = new CustomerModel();
        $user->populate($data);
        return $db->insert($user);

    }
}