<?php

namespace Piotrku\SeoFields;

use Laravel\Nova\Nova;
use Laravel\Nova\Events\ServingNova;
use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Request;
use Outl1ne\NovaTranslationsLoader\LoadsNovaTranslations;

class FieldServiceProvider extends ServiceProvider
{
    use LoadsNovaTranslations;

    public function boot()
    {
        // $this->app->singleton('seo-fields-generator', function () {
        //     return new SeoGenerator;
        // });

        Nova::serving(function (ServingNova $event) {
            Nova::script('seo-fields', __DIR__ . '/../dist/js/field.js');
            Nova::style('seo-fields', __DIR__ . '/../dist/css/field.css');
        });

        // $this->loadMigrationsFrom(__DIR__ . '/../migrations');
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'seo-fields');
        $this->loadRoutesFrom(__DIR__ . '/routes.php');
        $this->publishes([ __DIR__ . '/../config/seo.php' => config_path('seo.php') ]);

        // $this->loadTranslations(__DIR__ . '/../lang', 'nova-seo-fields-field', true);
    }

    public function register()
    {
        // cms\vendor\piotrku\seo-fields\src\Facades\Seo.php
        $this->app->bind('seo-service', function ($app) {
            return new Services\SeoService($app->make(Request::class));
        });
    }
}
