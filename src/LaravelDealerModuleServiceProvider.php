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
        // merge default configs with publish configs
        $this->mergeDefaultConfig();

        $router = $this->app['router'];
        // model binding
        $router->model(config('laravel-dealer-module.url.dealer'),  'App\Dealer');
        $router->model(config('laravel-dealer-module.url.dealer_category'),  'App\DealerCategory');
    }

    /**
     * merge default configs with publish configs
     */
    protected function mergeDefaultConfig()
    {
        $config = $this->app['config']->get('laravel-dealer-module', []);
        $default = require __DIR__.'/../config/default.php';

        // admin dealer category routes
        $route = $config['routes']['admin']['dealer_category'];
        $default['routes']['admin']['dealer_category'] = $route;
        // admin dealer routes
        $route = $config['routes']['admin']['dealer'];
        $default['routes']['admin']['dealer'] = $route;
        $default['routes']['admin']['dealer_publish'] = $route;
        $default['routes']['admin']['dealer_notPublish'] = $route;
        // admin sub dealer categories nested categories
        $route = $config['routes']['admin']['nested_sub_categories'];
        $default['routes']['admin']['category_categories'] = $route;
        // admin sub dealer categories dealers
        $route = $config['routes']['admin']['sub_category_dealers'];
        $default['routes']['admin']['category_dealers'] = $route;
        $default['routes']['admin']['category_dealers_publish'] = $route;
        $default['routes']['admin']['category_dealers_notPublish'] = $route;

        // api dealer category routes
        $apiCat = $config['routes']['api']['dealer_category'];
        $default['routes']['api']['dealer_category'] = $apiCat;
        // api sub dealer categories nested categories
        $apiSubCat = $config['routes']['api']['nested_sub_categories'];
        $default['routes']['api']['category_categories_index'] = $apiSubCat;

        $default['routes']['api']['dealer_category_models'] = $apiCat || $apiSubCat;
        $default['routes']['api']['dealer_category_move'] = $apiCat || $apiSubCat;

        // api dealer routes
        $model = $config['routes']['api']['dealer'];
        $default['routes']['api']['dealer'] = $model;
        // api sub dealer categories dealers
        $subModel = $config['routes']['api']['sub_category_dealers'];
        $default['routes']['api']['category_dealers_index'] = $subModel;

        $default['routes']['api']['dealer_group'] = $model || $subModel;
        $default['routes']['api']['dealer_detail'] = $model || $subModel;
        $default['routes']['api']['dealer_fastEdit'] = $model || $subModel;
        $default['routes']['api']['dealer_publish'] = $model || $subModel;
        $default['routes']['api']['dealer_notPublish'] = $model || $subModel;

        $config['routes'] = $default['routes'];

        $this->app['config']->set('laravel-dealer-module', $config);
    }
}
