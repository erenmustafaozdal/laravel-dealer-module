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
            'dealer_category'           => true,        // Is the route to be used categories admin
            'dealer'                    => true,        // Is the route to be used dealers admin
            'nested_sub_categories'     => true,        // Did subcategory nested categories admin route will be used
            'sub_category_dealers'      => true,        // Did subcategory dealer admin route will be used
        ],
        'api' => [
            'dealer_category'           => true,        // Is the route to be used categories api
            'dealer'                    => true,        // Is the route to be used dealers api
            'nested_sub_categories'     => true,        // Did subcategory nested categories api route will be used
            'sub_category_dealers'      => true,        // Did subcategory dealer api route will be used
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
    | Permissions
    |--------------------------------------------------------------------------
    */
    'permissions' => [
        'dealer_category' => [
            'title'                 => 'Bayi Kategorileri',
            'routes' => [
                'admin.dealer_category.index' => [
                    'title'         => 'Veri Tablosu',
                    'description'   => 'Bu izne sahip olanlar bayi kategorileri veri tablosu sayfasına gidebilir.',
                ],
                'admin.dealer_category.create' => [
                    'title'         => 'Ekleme Sayfası',
                    'description'   => 'Bu izne sahip olanlar bayi kategorisi ekleme sayfasına gidebilir',
                ],
                'admin.dealer_category.store' => [
                    'title'         => 'Ekleme',
                    'description'   => 'Bu izne sahip olanlar bayi kategorisi ekleyebilir',
                ],
                'admin.dealer_category.show' => [
                    'title'         => 'Gösterme',
                    'description'   => 'Bu izne sahip olanlar bayi kategorisi bilgilerini görüntüleyebilir',
                ],
                'admin.dealer_category.edit' => [
                    'title'         => 'Düzenleme Sayfası',
                    'description'   => 'Bu izne sahip olanlar bayi kategorisini düzenleme sayfasına gidebilir',
                ],
                'admin.dealer_category.update' => [
                    'title'         => 'Düzenleme',
                    'description'   => 'Bu izne sahip olanlar bayi kategorisini düzenleyebilir',
                ],
                'admin.dealer_category.destroy' => [
                    'title'         => 'Silme',
                    'description'   => 'Bu izne sahip olanlar bayi kategorisini silebilir',
                ],
                'api.dealer_category.index' => [
                    'title'         => 'Listeleme',
                    'description'   => 'Bu izne sahip olanlar bayi kategorilerini veri tablosunda listeleyebilir',
                ],
                'api.dealer_category.store' => [
                    'title'         => 'Hızlı Ekleme',
                    'description'   => 'Bu izne sahip olanlar bayi kategorilerini veri tablosunda hızlı ekleyebilir.',
                ],
                'api.dealer_category.update' => [
                    'title'         => 'Hızlı Düzenleme',
                    'description'   => 'Bu izne sahip olanlar bayi kategorilerini veri tablosunda hızlı düzenleyebilir.',
                ],
                'api.dealer_category.destroy' => [
                    'title'         => 'Silme',
                    'description'   => 'Bu izne sahip olanlar bayi kategorilerini veri tablosunda silebilir',
                ],
                'api.dealer_category.models' => [
                    'title'         => 'Seçim İçin Listeleme',
                    'description'   => 'Bu izne sahip olanlar bayi kategorilerini bazı seçim kutularında listeleyebilir',
                ],
                'api.dealer_category.move' => [
                    'title'         => 'Taşıma',
                    'description'   => 'Bu izne sahip olanlar bayi kategorilerini taşıyarak yerini değiştirebilir.',
                ],
            ],
        ],
        'dealer' => [
            'title'                 => 'Bayiler',
            'routes' => [
                'admin.dealer.index' => [
                    'title'         => 'Veri Tablosu',
                    'description'   => 'Bu izne sahip olanlar bayiler veri tablosu sayfasına gidebilir.',
                ],
                'admin.dealer.create' => [
                    'title'         => 'Ekleme Sayfası',
                    'description'   => 'Bu izne sahip olanlar bayi ekleme sayfasına gidebilir',
                ],
                'admin.dealer.store' => [
                    'title'         => 'Ekleme',
                    'description'   => 'Bu izne sahip olanlar bayi ekleyebilir',
                ],
                'admin.dealer.show' => [
                    'title'         => 'Gösterme',
                    'description'   => 'Bu izne sahip olanlar bayi bilgilerini görüntüleyebilir',
                ],
                'admin.dealer.edit' => [
                    'title'         => 'Düzenleme Sayfası',
                    'description'   => 'Bu izne sahip olanlar bayiyi düzenleme sayfasına gidebilir',
                ],
                'admin.dealer.update' => [
                    'title'         => 'Düzenleme',
                    'description'   => 'Bu izne sahip olanlar bayiyi düzenleyebilir',
                ],
                'admin.dealer.destroy' => [
                    'title'         => 'Silme',
                    'description'   => 'Bu izne sahip olanlar bayiyi silebilir',
                ],
                'admin.dealer.publish' => [
                    'title'         => 'Yayınlama',
                    'description'   => 'Bu izne sahip olanlar bayiyi yayınlayabilir',
                ],
                'admin.dealer.notPublish' => [
                    'title'         => 'Yayından Kaldırma',
                    'description'   => 'Bu izne sahip olanlar bayiyi yayından kaldırabilir',
                ],
                'api.dealer.index' => [
                    'title'         => 'Listeleme',
                    'description'   => 'Bu izne sahip olanlar bayileri veri tablosunda listeleyebilir',
                ],
                'api.dealer.store' => [
                    'title'         => 'Hızlı Ekleme',
                    'description'   => 'Bu izne sahip olanlar bayileri veri tablosunda hızlı ekleyebilir.',
                ],
                'api.dealer.update' => [
                    'title'         => 'Hızlı Düzenleme',
                    'description'   => 'Bu izne sahip olanlar bayileri veri tablosunda hızlı düzenleyebilir.',
                ],
                'api.dealer.destroy' => [
                    'title'         => 'Silme',
                    'description'   => 'Bu izne sahip olanlar bayileri veri tablosunda silebilir',
                ],
                'api.dealer.group' => [
                    'title'         => 'Toplu İşlem',
                    'description'   => 'Bu izne sahip olanlar bayiler veri tablosunda toplu işlem yapabilir',
                ],
                'api.dealer.detail' => [
                    'title'         => 'Detaylar',
                    'description'   => 'Bu izne sahip olanlar bayiler tablosunda detayını görebilir.',
                ],
                'api.dealer.fastEdit' => [
                    'title'         => 'Hızlı Düzenleme Bilgileri',
                    'description'   => 'Bu izne sahip olanlar bayiler tablosunda hızlı düzenleme amacıyla bilgileri getirebilir.',
                ],
                'api.dealer.publish' => [
                    'title'         => 'Hızlı Yayınlama',
                    'description'   => 'Bu izne sahip olanlar bayiler tablosunda bayiyi yayınlanyabilir.',
                ],
                'api.dealer.notPublish' => [
                    'title'         => 'Hızlı Yayından Kaldırma',
                    'description'   => 'Bu izne sahip olanlar bayiler tablosunda bayiyi yayından kaldırabilir.',
                ],
            ],
        ]
    ],
];
