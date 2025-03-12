<?php

namespace Piotrku\SeoFields\Helpers;

use Error;
use Carbon\Carbon;
use Marshmallow\Seoable\Seo;

class SeoSitemap
{
    /**
     * Array of the all the items in the sitemap.
     *
     * @var array
     */
    private $items = [];

    /**
     * Construct the sitemap class.
     *
     * @return void
     */
    public function __construct()
    {
        $sitemapModels = config('seo.sitemap_models');

        $this->attachModelItems($sitemapModels);
    }

    /**
     * Attach the model items.
     *
     * @return void
     */
    private function attachModelItems(array $sitemapModels = [])
    {
        foreach ($sitemapModels as $sitemapModel) {
            $items = $sitemapModel::getSitemapItems();

            if ($items && $items->count() > 0) {
                $this->items = array_merge($this->items, $items->reject(function ($item) {
                    if ($item->seoable) {
                        if (strpos(($item->seoable['robots'] ?? ''), 'noindex') !== false) {
                            return true;
                        }
                    }

                    if (!$item->showItemInSitemap()) {
                        return true;
                    }

                    if ($this->shouldBeExcluded($item)) {
                        return true;
                    }

                    return false;
                })->map(function ($item) {
                    return (object) [
                        'url' => $item->getSitemapItemUrl(),
                        'lastmod' => $item->getSitemapItemLastModified(),
                    ];
                })->toArray());
            }
        }
    }

    protected function shouldBeExcluded($item)
    {
        if ($exclude = !empty($item->seoable['is_sitemap_hidden'])) {
            return $exclude;
        }
        return false;
    }

    /**
     * Attach a custom sitemap item.
     *
     * @param string $path    Path on the current site
     * @param Carbon $lastmod Date of last edit
     *
     * @return SeoSitemap
     */
    public function attachCustom($path, ?Carbon $lastmod = null)
    {
        $date_format = config('seo.sitemap_date_format') ?? 'Y-m-d\TH:i:s.u\Z';

        $lastmod = $lastmod ? $lastmod->format($date_format) : null;

        $this->items[] = (object) [
            'url' => url($path),
            'lastmod' => $lastmod,
        ];

        return $this;
    }

    /**
     * Return sitemap items as array.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->items;
    }

    /**
     * Return xml for sitemap items.
     *
     * @return string
     */
    public function toXml()
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' .
            '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
        $lastmod = null;

        foreach ($this->items as $item) {
            $xml .= '<url>' .
                '<loc>' . ('/' == substr($item->url, 0, 1) ? url($item->url) : $item->url) . '</loc>' .
                '<lastmod>' . ($item->lastmod ?? $lastmod) . '</lastmod>' .
                '</url>';

            if ($item->lastmod) {
                $lastmod = $item->lastmod;
            }
        }
        $xml .= '</urlset>';

        return $xml;
    }
}
