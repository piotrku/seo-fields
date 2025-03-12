<?php

namespace Piotrku\SeoFields\Helpers;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasFieldMediaHub;

class SeoDataGenerator
{
    use HasFieldMediaHub;

    protected $model;
    protected $lang;

    public function __construct(?Model $model = null)
    {
        $this->model = $model;
        $this->lang = app()->getLocale();
    }


    public function getDefaultSeoData(): array
    {
        $seoData = [
            'is_automatic' => true,
            'title' => [
                $this->lang => config('seo.defaults.title'),
            ],
            'description' => [
                $this->lang => config('seo.defaults.description'),
            ],
            'robots' => config('seo.defaults.robots'),
            'is_sitemap_hidden' => false,
        ];

        return $seoData;
    }


    public function getSeoData(): array
    {
        $seoData = [
            'is_automatic' => true,
            'title' => [
                $this->lang => $this->model->title ?? config('seo.defaults.title'),
            ],
            'description' => [
                $this->lang => $this->model->excerpt ?? config('seo.defaults.description'),
            ],
            'image' => empty($this->model->featured_image)
                ? config('seo.defaults.image')
                : $this->getFeaturedImage($this->model->featured_image),
            'robots' => config('seo.defaults.robots'),
            'is_sitemap_hidden' => false,
        ];

        return $seoData;
    }

    public function getFeaturedImage($mediaId)
    {
        $featuredImage = $this->getMediaImage(mediaId: $mediaId, key: 'featured_image');

        return empty($featuredImage['featured_image']['sizes']['xlarge'])
            ? ($featuredImage['image']['url'] ?? config('seo.defaults.image'))
            : $featuredImage['featured_image']['sizes']['xlarge'];
    }
}
