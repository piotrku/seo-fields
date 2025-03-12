<?php

return [
    'defaults' => [
        'sitename' => env('APP_NAME'),
        'title' => env('APP_NAME') . ' - What we are title',
        'description' => env('APP_NAME') . ' - What we are description',
        'keywords' => [env('APP_NAME'), 'keyword1', 'keyword2'],
        'image' => env('APP_URL') . '/assets/images/preview.jpg',
        'follow_type' => 'index, follow',
        'page_type' => 'website',
        'author' => env('APP_NAME') . ' © 2025',
        'logo' => env('APP_URL') . '/assets/images/logo.svg',
        'phonenumber' => null,
    ],

    'use_pretty_urls' => false,

    'fields' => [
        'title' => true,
        'description' => true,
        'robots' => true,
        'sitemap' => true,
        'image' => true,
        // 'keywords' => false,
        // 'page_type' => false,
    ],

    // 'microsoft' => [
    //     'clarity' => [
    //         'tracking_id' => 'xxxx_tracking_id_xxxxx',
    //     ],
    // ],

    // 'google' => [
    //     'GTM' => env('SEO_GTM', 'GTM-XXXXXXX'),     // GTM-XXXXXXX
    //     'GA' => env('SEO_GA', 'GA-XXXXXXX-XX'),     // GA-XXXXXXX-XX
    // ],

    // 'cookiebot' => [
    //     'enabled' => env('SEO_COOKIEBOT_ENABLED', false), // true
    //     'id' => env('SEO_COOKIEBOT_ID'), // 00000000-0000-0000-0000-000000000000
    // ],

    // 'google_optimize' => [
    //     'container' => env('SEO_GO', ''),   // GTM-XXXXXXX

    // 'facebook' => [
    //     'admins' => env('FACEBOOK_ADMINS', ''),
    //     'app_id' => env('FACEBOOK_APP_ID', ''),
    //     'pixel_id' => env('FACEBOOK_PIXEL_ID', ''),
    // ],

    // 'twitter' => [
    //     'site' => '@siteName',
    //     'creator' => '@creatorName',
    // ],

    // 'hotjar' => [
    //     'active' => env('HOTJAR_ACTIVE', false),
    //     'id' => env('HOTJAR_ID', null),
    //     'version' => env('HOTJAR_VERSION', 6),
    //     'async' => env('HOTJAR_ASYNC', 1),
    // ],

    /*
     * For storing the SEO images
     */
    // 'storage' => [
    //     'disk' => 'public',
    //     'path' => 'seo',
    // ],

    /*
    |--------------------------------------------------------------------------
    | SEO status
    |--------------------------------------------------------------------------
    |
    | Set SEO status, if its set to false then all pages will have
    | the 'noindex, nofollow' follow type and also removed the meta tags except the title tag.
    |
    */

    'seo_status' => env('SEO_STATUS', true),

    /*
    |--------------------------------------------------------------------------
    | SEO title formatter
    |--------------------------------------------------------------------------
    |
    | If you want a specific default format for your SEO titles, then you can
    | specify it here. Example could be ':text - Test site', then all pages would have
    | the ' - Test site' appended to the actual SEO title.
    |
    */

    // 'title_formatter' => ':text',

    /*
    |--------------------------------------------------------------------------
    | Follow type options
    |--------------------------------------------------------------------------
    |
    | Here is all the possible follow types shown in the admin panel
    | which is an array with key -> value.
    |
    */

    'follow_type_options' => [
        'index, follow' => 'Index and follow',
        'noindex, follow' => 'No index and follow',
        'index, nofollow' => 'Index and no follow',
        'noindex, nofollow' => 'No index and no follow',
    ],

    /*
    |--------------------------------------------------------------------------
    | Default follow type
    |--------------------------------------------------------------------------
    |
    | Set the default follow type.
    |
    */
    'default_follow_type' => env('SEO_DEFAULT_FOLLOW_TYPE', 'index, follow'),

    /*
    |--------------------------------------------------------------------------
    | Sitemap status
    |--------------------------------------------------------------------------
    |
    | Should there be a sitemap available
    |
    */
    'sitemap_status' => env('SITEMAP_STATUS', true),

    /*
    |--------------------------------------------------------------------------
    | Sitemap models
    |--------------------------------------------------------------------------
    |
    | Insert all the laravel models which should be in the sitemap
    |
    */
    'sitemap_models' => [
        App\Models\Page::class,
        App\Models\News::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Date format
    |--------------------------------------------------------------------------
    |
    | This is the date format which will be used in the sitemap for the
    | lastmod attribute.
    |
    */
    'sitemap_date_format' => 'Y-m-d',

    /*
    |--------------------------------------------------------------------------
    | Sitemap url
    |--------------------------------------------------------------------------
    |
    | Set the path of the sitemap
    |
    */
    'sitemap_path' => '/sitemap',

    /*
    |--------------------------------------------------------------------------
    | Robots.txt
    |--------------------------------------------------------------------------
    |
    | Override the class which builds the robots.txt file. If you are using this,
    | do not forget to delete the original public/robots.txt file.
    |
    */
    // 'robots_resolver' => Marshmallow\Seoable\Helpers\DefaultRobotsTxt::class,
    // 'robots_resolver' => App\Helpers\RobotsTxt::class,


    /*
    |--------------------------------------------------------------------------
    | Model resolver
    |--------------------------------------------------------------------------
    |
    | Set to your model object resolver that will return database model object
    |    based on $request to get the 'content_seo' data
    |
    */
    'model_resolver' => Piotrku\SeoFields\Helpers\ModelResolver::class,

    /*
    |--------------------------------------------------------------------------
    | SEO data guesser
    |--------------------------------------------------------------------------
    |
    | Set to your model object resolver that will return database model object
    |    based on $request to get the 'content_seo' data
    |
    */
    'seo_data_generator' => Piotrku\SeoFields\Helpers\SeoDataGenerator::class,

];
