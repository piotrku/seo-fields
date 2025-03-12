<?php

namespace Piotrku\SeoFields\Facades;

use Illuminate\Support\Facades\Facade;

class SeoAnalytics extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'seo-analytics-service';
    }
}
