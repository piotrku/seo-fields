<?php

use Illuminate\Support\Facades\Route;
use Piotrku\SeoFields\Helpers\SeoSitemap;

if (config('seo.sitemap_status')) {
    Route::get(config('seo.sitemap_path'), function ()
    {
        $sitemap = new SeoSitemap();
        return response($sitemap->toXml(), 200, [
            'Content-Type' => 'application/xml',
        ]);
    })->name('seo-fields.sitemap');
}

