<?php

namespace Example\Models;

use Example\Interfaces\DbModel;

/**
 * DbModel abstract class defining common model methods
 */
abstract class DbModelAbstract implements DbModel
{
    /**
     * @return int[]|string[]
     */
    public static function attributes()
    {
        return array_keys(get_class_vars(static::class));
    }

    /**
     * @return array
     */
    public function asArray()
    {
        return get_object_vars($this);
    }

    /**
     * $data can be either array or instance of stdClass
     *
     * @param array|object $data
     */
    public function populate($data)
    {
        foreach ($data as $attr => $value) {
            $this->$attr = $value;
        }
    }
}