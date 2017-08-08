<?php namespace Filld\Amqp\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @author Björn Schmitt <code@bjoern.io>
 * @see Filld\Amqp\Amqp
 */
class Amqp extends Facade
{

    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'Amqp';
    }

}