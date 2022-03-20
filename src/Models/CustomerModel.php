<?php

namespace Example\Models;

/**
 * DbModel class for customer entries
 */
class CustomerModel extends DbModelAbstract
{

    public $id;
    public $first_name;
    public $last_name;
    public $email;
    public $phone;
    public $priority;

    /**
     * @return string
     */
    public static function tableName()
    {
        return 'customers';
    }

    /**
     * @return mixed
     */
    public function primaryKey()
    {
        return $this->id;
    }
}