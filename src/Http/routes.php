<?php
//max level nested function 100 hatasını düzeltiyor
ini_set('xdebug.max_nesting_level', 300);

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
/*==========  Media Category Module  ==========*/
Route::group([
    'prefix' => config('laravel-dealer-module.url.admin_url_prefix'),
    'middleware' => config('laravel-dealer-module.url.middleware'),
    'namespace' => config('laravel-dealer-module.controller.dealer_category_admin_namespace')
], function()
{
    if (config('laravel-dealer-module.routes.admin.dealer_category')) {
        Route::resource(config('laravel-dealer-module.url.dealer_category'), config('laravel-dealer-module.controller.dealer_category'), [
            'names' => [
                'index'         => 'admin.dealer_category.index',
                'create'        => 'admin.dealer_category.create',
                'store'         => 'admin.dealer_category.store',
                'show'          => 'admin.dealer_category.show',
                'edit'          => 'admin.dealer_category.edit',
                'update'        => 'admin.dealer_category.update',
                'destroy'       => 'admin.dealer_category.destroy',
            ]
        ]);
    }

    // category categories
    if (config('laravel-dealer-module.routes.admin.category_categories')) {
        Route::group(['middleware' => 'nested_model:DealerCategory'], function() {
            Route::resource(config('laravel-dealer-module.url.dealer_category') . '/{id}/' . config('laravel-dealer-module.url.dealer_category'), config('laravel-dealer-module.controller.dealer_category'), [
                'names' => [
                    'index' => 'admin.dealer_category.dealer_category.index',
                    'create' => 'admin.dealer_category.dealer_category.create',
                    'store' => 'admin.dealer_category.dealer_category.store',
                    'show' => 'admin.dealer_category.dealer_category.show',
                    'edit' => 'admin.dealer_category.dealer_category.edit',
                    'update' => 'admin.dealer_category.dealer_category.update',
                    'destroy' => 'admin.dealer_category.dealer_category.destroy',
                ]
            ]);
        });
    }
});

/*==========  Media Module  ==========*/
Route::group([
    'prefix'        => config('laravel-dealer-module.url.admin_url_prefix'),
    'middleware'    => config('laravel-dealer-module.url.middleware'),
    'namespace'     => config('laravel-dealer-module.controller.dealer_admin_namespace')
], function()
{
    // admin publish dealer
    if (config('laravel-dealer-module.routes.admin.dealer_publish')) {
        Route::get('dealer/{' . config('laravel-dealer-module.url.dealer') . '}/publish', [
            'as'                => 'admin.dealer.publish',
            'uses'              => config('laravel-dealer-module.controller.dealer').'@publish'
        ]);
    }
    // admin not publish dealer
    if (config('laravel-dealer-module.routes.admin.dealer_notPublish')) {
        Route::get('dealer/{' . config('laravel-dealer-module.url.dealer') . '}/not-publish', [
            'as'                => 'admin.dealer.notPublish',
            'uses'              => config('laravel-dealer-module.controller.dealer').'@notPublish'
        ]);
    }
    if (config('laravel-dealer-module.routes.admin.dealer')) {
        Route::resource(config('laravel-dealer-module.url.dealer'), config('laravel-dealer-module.controller.dealer'), [
            'names' => [
                'index'         => 'admin.dealer.index',
                'create'        => 'admin.dealer.create',
                'store'         => 'admin.dealer.store',
                'show'          => 'admin.dealer.show',
                'edit'          => 'admin.dealer.edit',
                'update'        => 'admin.dealer.update',
                'destroy'       => 'admin.dealer.destroy',
            ]
        ]);
    }

    /*==========  Category dealers  ==========*/
    // admin publish dealer
    if (config('laravel-dealer-module.routes.admin.category_dealers_publish')) {
        Route::get(config('laravel-dealer-module.url.dealer_category') . '/{id}/' . config('laravel-dealer-module.url.dealer') . '/{' . config('laravel-dealer-module.url.dealer') . '}/publish', [
            'middleware'        => 'related_model:DealerCategory,dealers',
            'as'                => 'admin.dealer_category.dealer.publish',
            'uses'              => config('laravel-dealer-module.controller.dealer').'@publish'
        ]);
    }
    // admin not publish dealer
    if (config('laravel-dealer-module.routes.admin.category_dealers_notPublish')) {
        Route::get(config('laravel-dealer-module.url.dealer_category') . '/{id}/' . config('laravel-dealer-module.url.dealer') . '/{' . config('laravel-dealer-module.url.dealer') . '}/not-publish', [
            'middleware'        => 'related_model:DealerCategory,dealers',
            'as'                => 'admin.dealer_category.dealer.notPublish',
            'uses'              => config('laravel-dealer-module.controller.dealer').'@notPublish'
        ]);
    }

    // category dealers
    if (config('laravel-dealer-module.routes.admin.category_dealers')) {
        Route::group(['middleware' => 'related_model:DealerCategory,dealers'], function() {
            Route::resource(config('laravel-dealer-module.url.dealer_category') . '/{id}/' . config('laravel-dealer-module.url.dealer'), config('laravel-dealer-module.controller.dealer'), [
                'names' => [
                    'index' => 'admin.dealer_category.dealer.index',
                    'create' => 'admin.dealer_category.dealer.create',
                    'store' => 'admin.dealer_category.dealer.store',
                    'show' => 'admin.dealer_category.dealer.show',
                    'edit' => 'admin.dealer_category.dealer.edit',
                    'update' => 'admin.dealer_category.dealer.update',
                    'destroy' => 'admin.dealer_category.dealer.destroy',
                ]
            ]);
        });
    }
});



/*
|--------------------------------------------------------------------------
| Api Routes
|--------------------------------------------------------------------------
*/
/*==========  Media Category Module  ==========*/
Route::group([
    'prefix'        => 'api',
    'middleware'    => config('laravel-dealer-module.url.middleware'),
    'namespace'     => config('laravel-dealer-module.controller.dealer_category_api_namespace')
], function()
{
    // api dealer category
    if (config('laravel-dealer-module.routes.api.dealer_category_models')) {
        Route::post('dealer-category/models', [
            'as'                => 'api.dealer_category.models',
            'uses'              => config('laravel-dealer-module.controller.dealer_category_api').'@models'
        ]);
    }
    // api dealer category move
    if (config('laravel-dealer-module.routes.api.dealer_category_move')) {
        Route::post('dealer-category/{id}/move', [
            'as'                => 'api.dealer_category.move',
            'uses'              => config('laravel-dealer-module.controller.dealer_category_api').'@move'
        ]);
    }
    // dealer category resource
    if (config('laravel-dealer-module.routes.api.dealer_category')) {
        Route::resource(config('laravel-dealer-module.url.dealer_category'), config('laravel-dealer-module.controller.dealer_category_api'), [
            'names' => [
                'index'         => 'api.dealer_category.index',
                'store'         => 'api.dealer_category.store',
                'update'        => 'api.dealer_category.update',
                'destroy'       => 'api.dealer_category.destroy',
            ]
        ]);
    }

    // category categories
    if (config('laravel-dealer-module.routes.api.category_categories_index')) {
        Route::get(config('laravel-dealer-module.url.dealer_category') . '/{id}/' . config('laravel-dealer-module.url.dealer_category'), [
            'middleware'        => 'nested_model:DealerCategory',
            'as'                => 'api.dealer_category.dealer_category.index',
            'uses'              => config('laravel-dealer-module.controller.dealer_category_api').'@index'
        ]);
    }
});

/*==========  Media Module  ==========*/
Route::group([
    'prefix'        => 'api',
    'middleware'    => config('laravel-dealer-module.url.middleware'),
    'namespace'     => config('laravel-dealer-module.controller.dealer_api_namespace')
], function()
{
    // api group action
    if (config('laravel-dealer-module.routes.api.dealer_group')) {
        Route::post('dealer/group-action', [
            'as'                => 'api.dealer.group',
            'uses'              => config('laravel-dealer-module.controller.dealer_api').'@group'
        ]);
    }
    // data table detail row
    if (config('laravel-dealer-module.routes.api.dealer_detail')) {
        Route::get('dealer/{id}/detail', [
            'as'                => 'api.dealer.detail',
            'uses'              => config('laravel-dealer-module.controller.dealer_api').'@detail'
        ]);
    }
    // get dealer category edit data for modal edit
    if (config('laravel-dealer-module.routes.api.dealer_fastEdit')) {
        Route::post('dealer/{id}/fast-edit', [
            'as'                => 'api.dealer.fastEdit',
            'uses'              => config('laravel-dealer-module.controller.dealer_api').'@fastEdit'
        ]);
    }
    // api publish dealer
    if (config('laravel-dealer-module.routes.api.dealer_publish')) {
        Route::post('dealer/{' . config('laravel-dealer-module.url.dealer') . '}/publish', [
            'as'                => 'api.dealer.publish',
            'uses'              => config('laravel-dealer-module.controller.dealer_api').'@publish'
        ]);
    }
    // api not publish dealer
    if (config('laravel-dealer-module.routes.api.dealer_notPublish')) {
        Route::post('dealer/{' . config('laravel-dealer-module.url.dealer') . '}/not-publish', [
            'as'                => 'api.dealer.notPublish',
            'uses'              => config('laravel-dealer-module.controller.dealer_api').'@notPublish'
        ]);
    }
    if (config('laravel-dealer-module.routes.api.dealer')) {
        Route::resource(config('laravel-dealer-module.url.dealer'), config('laravel-dealer-module.controller.dealer_api'), [
            'names' => [
                'index'         => 'api.dealer.index',
                'store'         => 'api.dealer.store',
                'update'        => 'api.dealer.update',
                'destroy'       => 'api.dealer.destroy',
            ]
        ]);
    }
    // category dealers
    if (config('laravel-dealer-module.routes.api.category_dealers_index')) {
        Route::get(config('laravel-dealer-module.url.dealer_category') . '/{id}/' . config('laravel-dealer-module.url.dealer'), [
            'middleware'        => 'related_model:DealerCategory,dealers',
            'as'                => 'api.dealer_category.dealer.index',
            'uses'              => config('laravel-dealer-module.controller.dealer_api').'@index'
        ]);
    }
});
