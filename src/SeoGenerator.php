<?php

namespace Piotrku\SeoFields;

use App\Models\Page;

class SeoGenerator
{
    public static function generate()
    {
        return view('seo-fields::seo-head', [
            'seoData' => self::getSeoData(),
        ])->render();
    }

    protected static function getSeoData()
    {
        // Logic to retrieve the current page's SEO data
        // This could involve determining the current route/model
        // and fetching the relevant SEO fields data

        // Example implementation (you'll need to adapt this)
        $currentPage = Page::where('id', 7)->first();

        return $currentPage->seoFields ?? [];
    }
}