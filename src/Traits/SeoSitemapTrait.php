<?php

namespace Piotrku\SeoFields\Traits;

trait SeoSitemapTrait
{
    abstract public function getSitemapItemUrl(): string;
    abstract public static function getSitemapItems();

    public function getSitemapItemLastModified()
    {
        if (isset($this->updated_at) || isset($this->created_at))
        {
            $date_format = config('seo.sitemap_date_format') ?? 'Y-m-d\TH:i:s.u\Z';

            return isset($this->updated_at)
                ? $this->updated_at->format($date_format)
                : $this->created_at->format($date_format);
        }

        return null;
    }

    public function showItemInSitemap(): bool
    {
        $show = true;

        if ($this->seoable)
        {
            $isSitemapHidden = $this->seoable['is_sitemap_hidden'] ?? false;
            $show = empty($isSitemapHidden);
        }

        return $show;
    }
}
