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
    }

}