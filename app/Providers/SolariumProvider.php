<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Solarium\Client;

class SolariumProvider extends ServiceProvider
{
    protected $defer = true;

    public function provides()
    {
        return [Client::class];
    }

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(Client::class, function ($app) {
            return new Client($app['config']['solarium']);
        });
    }
}
