<?php

namespace Example\Interfaces;

use Slim\App;

/**
 * Application modifications such as Routes, Container modifications.
 */
interface AppModificators
{
    /**
     * @param App $app
     */
    public static function register(App $app);
}