<?php

return [
    /*
    |--------------------------------------------------------------------------
    | General config
    |--------------------------------------------------------------------------
    */
    'date_format'           => 'd.m.Y H:i:s',
    'icons' => [
        'dealer'             => 'icon-flag',
        'dealer_category'    => 'icon-share'
    ],

    /*
    |--------------------------------------------------------------------------
    | URL config
    |--------------------------------------------------------------------------
    */
    'url' => [
        'dealer_category'           => 'dealer-categories',     // dealer categories url
        'dealer'                    => 'dealers',               // dealers url
        'admin_url_prefix'          => 'admin',                 // admin dashboard url prefix
        'middleware'                => ['auth', 'permission']   // dealer module middleware
    ],

    /*
    |--------------------------------------------------------------------------
    | Controller config
    | if you make some changes on controller, you create your controller
    | and then extend the Laravel Dealer Module Controller. If you don't need
    | change controller, don't touch this config
    |--------------------------------------------------------------------------
    */
    'controller' => [
        'dealer_category_admin_namespace'    => 'ErenMustafaOzdal\LaravelDealerModule\Http\Controllers',
        'dealer_admin_namespace'             => 'ErenMustafaOzdal\LaravelDealerModule\Http\Controllers',
        'dealer_category_api_namespace'      => 'ErenMustafaOzdal\LaravelDealerModule\Http\Controllers',
        'dealer_api_namespace'               => 'ErenMustafaOzdal\LaravelDealerModule\Http\Controllers',
        'dealer_category'                    => 'DealerCategoryController',
        'dealer'                             => 'DealerController',
        'dealer_category_api'                => 'DealerCategoryApiController',
        'dealer_api'                         => 'DealerApiController'
    ],

    /*
    |--------------------------------------------------------------------------
    | Routes on / off
    | if you don't use any route; set false
    |--------------------------------------------------------------------------
    */
    'routes' => [
        'admin' => [
            'dealer_category'               => true,        // admin dealer category resource route
            'dealer'                        => true,        // admin dealer resource route
            'dealer_publish'                => true,        // admin dealer publish get route
            'dealer_notPublish'             => true,        // admin dealer not publish get route
            'category_categories'           => true,        // admin category nested categories resource route
            'category_dealers'              => true,        // admin category dealers resource route
            'category_dealers_publish'      => true,        // admin category dealers publish get route
            'category_dealers_notPublish'   => true         // admin category dealers not publish get route
        ],
        'api' => [
            'dealer_category'               => true,        // api dealer category resource route
            'dealer_category_models'        => true,        // api dealer category model post route
            'dealer_category_move'          => true,        // api dealer category move post route
            'dealer_category_detail'        => true,        // api dealer category detail get route
            'dealer'                        => true,        // api dealer resource route
            'dealer_group'                  => true,        // api dealer group post route
            'dealer_detail'                 => true,        // api dealer detail get route
            'dealer_fastEdit'               => true,        // api dealer fast edit post route
            'dealer_publish'                => true,        // api dealer publish post route
            'dealer_notPublish'             => true,        // api dealer not publish post route
            'category_categories_index'     => true,        // api category nested categories index get route
            'category_dealers_index'        => true,        // api category dealers index get route
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | View config
    |--------------------------------------------------------------------------
    | dot notation of blade view path, its position on the /resources/views directory
    */
    'views' => [
        // dealer category view
        'dealer_category' => [
            'layout'    => 'laravel-modules-core::layouts.admin',               // dealer layout
            'index'     => 'laravel-modules-core::dealer_category.index',        // get dealer category index view blade
            'create'    => 'laravel-modules-core::dealer_category.operation',    // get dealer category create view blade
            'show'      => 'laravel-modules-core::dealer_category.show',         // get dealer category show view blade
            'edit'      => 'laravel-modules-core::dealer_category.operation',    // get dealer category edit view blade
        ],
        // dealer view
        'dealer' => [
            'layout'    => 'laravel-modules-core::layouts.admin',               // dealer layout
            'index'     => 'laravel-modules-core::dealer.index',                 // get dealer index view blade
            'create'    => 'laravel-modules-core::dealer.operation',             // get dealer create view blade
            'show'      => 'laravel-modules-core::dealer.show',                  // get dealer show view blade
            'edit'      => 'laravel-modules-core::dealer.operation',             // get dealer edit view blade
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | Models config
    |--------------------------------------------------------------------------
    |
    | ## Options
    |
    | - default_img_path                : model default avatar or photo
    |
    | --- uploads                       : model uploads options
    | - relation                        : file is in the relation table and what is relation type [false|hasOne|hasMany]
    | - relation_model                  : relation model [\App\Model etc...]
    | - type                            : file type [image,file]
    | - column                          : file database column
    | - path                            : file path
    | - max_size                        : file allowed maximum size
    | - max_file                        : maximum file count
    | - aspect_ratio                    : if file is image; crop aspect ratio
    | - mimes                           : file allowed mimes
    | - thumbnails                      : if file is image; its thumbnails options
    |
    | NOT: Thumbnails fotoğrafları yüklenirken bakılır:
    |       1. eğer post olarak x1, y1, x2, y2, width ve height değerleri gönderilmemiş ise bu değerlere göre
    |       thumbnails ayarlarında belirtilen resimleri sistem içine kaydeder.
    |       Yani bu değerler post edilmişse aşağıdaki değerleri yok sayar.
    |       2. Eğer yukarıdaki ilgili değerler post edilmemişse, thumbnails ayarlarında belirtilen değerleri
    |       dikkate alarak thumbnails oluşturur
    |
    |       Ölçü Belirtme:
    |       1. İstenen resmin width ve height değerleri verilerek istenen net bir ölçüde resimler oluşturulabilir
    |       2. Width değeri null verilerek, height değerine göre ölçeklenebilir
    |       3. Height değeri null verilerek, width değerine göre ölçeklenebilir
    |--------------------------------------------------------------------------
    */
];
