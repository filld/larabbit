<?php namespace Filld\Amqp;

use Filld\Amqp\Consumer;
use Filld\Amqp\Publisher;
use Illuminate\Support\ServiceProvider;

class AmqpServiceProvider extends ServiceProvider
{
    
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind('Amqp', 'Filld\Amqp\Amqp');
        if (!class_exists('Amqp')) {
            class_alias('Filld\Amqp\Facades\Amqp', 'Amqp');
        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('Filld\Amqp\Publisher', function ($app) {
            return new Publisher(config());
        });
        $this->app->singleton('Filld\Amqp\Consumer', function ($app) {
            return new Consumer(config());
        });

    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['Amqp', 'Filld\Amqp\Publisher', 'Filld\Amqp\Consumer'];
    }

}
