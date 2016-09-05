<?php

namespace ErenMustafaOzdal\LaravelDealerModule;

use Illuminate\Support\ServiceProvider;

class LaravelDealerModuleServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        if (! $this->app->routesAreCached()) {
            require __DIR__.'/Http/routes.php';
        }

        $this->publishes([
            __DIR__.'/../database/migrations/' => database_path('migrations')
        ], 'migrations');

        $this->publishes([
            __DIR__.'/../config/laravel-dealer-module.php' => config_path('laravel-dealer-module.php')
        ], 'config');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register('ErenMustafaOzdal\LaravelModulesBase\LaravelModulesBaseServiceProvider');
        $this->app->register('Baum\Providers\BaumServiceProvider');

        $this->mergeConfigFrom(
            __DIR__.'/../config/laravel-dealer-module.php', 'laravel-dealer-module'
        );

        $router = $this->app['router'];
        // model binding
        $router->model(config('laravel-dealer-module.url.dealer'),  'App\Dealer');
        $router->model(config('laravel-dealer-module.url.dealer_category'),  'App\DealerCategory');
    }
}
