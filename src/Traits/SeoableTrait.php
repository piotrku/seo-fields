<?php

namespace Piotrku\SeoFields\Traits;

trait SeoableTrait
{
    // public static function bootSeoable()
    // {
    //     static::created(function (Model $resource) {
    //         if ($resource->shouldStoreRecordOnInsert()) {
    //             DB::afterCommit(
    //                 function () use ($resource) {
    //                     $resource->seoable()->create([
    //                         'title' => app('seo')->set($resource)->getSeoTitle(),
    //                         'description' => app('seo')->set($resource)->getSeoDescription(),
    //                         'keywords' => app('seo')->set($resource)->getSeoKeywords(),
    //                         'follow_type' => app('seo')->set($resource)->getSeoFollowType(),
    //                         'image' => app('seo')->set($resource)->getSeoImageUrl(),
    //                         'page_type' => app('seo')->set($resource)->getSeoPageType(),
    //                     ]);
    //                 }
    //             );
    //         }
    //     });

    //     static::deleting(function (Model $resource) {
    //         /*
    //          * Delete the existing seoable information.
    //          */
    //         $resource->seoable()->delete();
    //     });
    // }

    public function getSeoableAttribute(): ?array
    {
        return empty($this->content_seo)
            ? null
            : (is_array($this->content_seo) ? current($this->content_seo) : null);
    }
}
