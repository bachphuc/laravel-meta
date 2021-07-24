<?php

namespace bachphuc\LaravelMeta\Facades;

use Illuminate\Support\Facades\Facade;

class LaravelMetaFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() { return 'laravel_meta'; }    
}