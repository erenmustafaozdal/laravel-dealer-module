<?php

return [
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
            'dealer'                        => true,        // api dealer resource route
            'dealer_group'                  => true,        // api dealer group post route
            'dealer_detail'                 => true,        // api dealer detail get route
            'dealer_fastEdit'               => true,        // api dealer fast edit post route
            'dealer_publish'                => true,        // api dealer publish post route
            'dealer_notPublish'             => true,        // api dealer not publish post route
            'category_categories_index'     => true,        // api category nested categories index get route
            'category_dealers_index'        => true,        // api category dealers index get route
        ]
    ]
];
