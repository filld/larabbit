<?php namespace Filld\Amqp;

use Illuminate\Support\ServiceProvider;

/**
 * Lumen Service Provider
 *
 * @author Björn Schmitt <code@bjoern.io>
 */
class LumenServiceProvider extends ServiceProvider
{

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {

    }

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('Filld\Amqp\Publisher', function ($app) {
            return new Publisher($app->config);
        });

        $this->app->bind('Filld\Amqp\Consumer', function ($app) {
            return new Consumer($app->config);
        });

        $this->app->bind('Amqp', 'Filld\Amqp\Amqp');

        if (!class_exists('Amqp')) {
            class_alias('Filld\Amqp\Facades\Amqp', 'Amqp');
        }

        // Add App facade if is lumen >5.2
        if ($this->version() >= 5.2 && !class_exists('App')) {
            class_alias('Illuminate\Support\Facades\App', 'App');
        }       
    }
    /** 
     * Get Lumen version
     */
    protected function version()
    {
        $version = explode('(', $this->app->version());
        if (isset($version[1])) {
            return substr($version[1], 0, 3);
        }        
        return null;

    }

}