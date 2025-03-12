<?php

namespace Piotrku\SeoFields\Helpers;

use Illuminate\Support\Facades\Facade;
use Illuminate\Http\Request;
use App\Models\Page;
use App\Models\News;

class ModelResolver
{
    protected $request;
    protected $lang;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->lang = app()->getLocale();
    }


    public function getModel()
    {
        $slug = $this->request->route()->parameter('slug');
        $routeName = $this->request->route()->getName();

        $searchSlug = '';
        $model = '';

        switch ($routeName) {
            case 'homepage':
                $searchSlug = 'homepage';
                $model = Page::class;
                break;
            case 'news-item':
                $searchSlug = $slug;
                $model = News::class;
                break;
            default:
                $searchSlug = $slug;
                $model = Page::class;
        }

        // $this->request->route()->getName()
        // if (empty($slug)) die('slug empty');

        $currentPage = $model::where('slug', $searchSlug)->first();

        return $currentPage;
    }
}
