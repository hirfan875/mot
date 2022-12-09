<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Media Placeholder
    |--------------------------------------------------------------------------
    |
    */

    'placeholder' => 'placeholder.jpg',

    /*
    |--------------------------------------------------------------------------
    | Media Paths
    |--------------------------------------------------------------------------
    |
    */

    'path' => [

        'original' => env('MEDIA_ORIGINAL', '/app/public/original/'),
        'thumbnail' => env('MEDIA_THUMBNAIL', '/app/public/thumbnail/'),
        'upload' => env('MEDIA_UPLOAD', '/app/public/'),

    ],

    /*
    |--------------------------------------------------------------------------
    | thumbnail size
    |--------------------------------------------------------------------------
    |
    */

    'thumbnail' => [

        'width' => 150,
        'height' => 200
    ],

    /*
    |--------------------------------------------------------------------------
    | Media Sizes
    |--------------------------------------------------------------------------
    |
    */

    'sizes' => [

        'product' => [
            [
                'width' => 280,
                'height' => 300,
                'ratio' => 0.93,
                'title' => 'Product Listing Image',
                'slug' => 'product_listing'
            ],
            [
                'width' => 500,
                'height' => 390,
                'ratio' => 1.28,
                'title' => 'Product Detail Image',
                'slug' => 'product_detail'
            ],
            [
                'width' => 150,
                'height' => 200,
                'ratio' => 0.75,
                'title' => 'Product Thumbnail',
                'slug' => 'product_thumbnail'
            ]
        ],

        'deal' => [
            [
                'width' => 310,
                'height' => 460,
                'ratio' => 0.68,
                'title' => 'Deal Image',
                'slug' => 'deal_home'
            ],
            [
                'width' => 175,
                'height' => 225,
                'ratio' => 0.77,
                'title' => 'Deal Mobile',
                'slug' => 'deal_mobile'
            ]
        ],

        'sponsor_category' => [
            [
                'width' => 500,
                'height' => 200,
                'ratio' => 2.50,
                'title' => 'Sponsor Category Image',
                'slug' => 'sponsor_category'
            ],
            [
                'width' => 300,
                'height' => 120,
                'ratio' => 2.50,
                'title' => 'Sponsor Category Mobile',
                'slug' => 'sponsor_category_mobile'
            ]
        ],
        
        'slider' => [
            [
                'width' => 915,
                'height' => 430,
                'ratio' => 2.12,
                'title' => 'Slider Image',
                'slug' => 'slider'
            ],
            [
                'width' => 400,
                'height' => 200,
                'ratio' => 2.00,
                'title' => 'Slider Mobile',
                'slug' => 'slider_mobile'
            ]
        ],

        'default' => [
            [
                'width' => 350,
                'height' => 350,
                'ratio' => 1,
                'title' => 'Medium Image',
                'slug' => 'medium'
            ],
            [
                'width' => 700,
                'height' => 700,
                'ratio' => 1,
                'title' => 'Large Image',
                'slug' => 'large'
            ]
        ]

    ]

];
