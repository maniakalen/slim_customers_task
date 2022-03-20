<?php

namespace Example\Interfaces;

/**
 * Interface to define mandatory db model methods for the model to be supported by the API
 */
interface DbModel
{
    /**
     * Return model table name
     *
     * @return array
     */
    public static function tableName();

    /**
     * Return array with model attributes corresponding to the table columns
     *
     * @return array
     */
    public static function attributes();

    /**
     * Return the model as key - value array
     *
     * @return array
     */
    public function asArray();

    /**
     * Populates model with the data provided as parameter
     *
     * @param array $data
     */
    public function populate($data);

    /**
     * Returns value of the model primary key
     *
     * @return mixed
     */
    public function primaryKey();
}