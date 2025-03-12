<?php

namespace Piotrku\SeoFields;

use App\Models\Page;
use Illuminate\Http\Request;

class SeoService
{
    protected $request;
    protected $pageSeoData;
    protected $lang;
    protected $currentPage;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->lang = app()->getLocale();
    }

    public function generateHead()
    {
        $this->resolveCurrentPage();
        $pageSeoData = $this->getPageSeoData();

        if (empty($pageSeoData)) {
            return;
        }

        return view('seo-fields::seo-head', [
          // 'seoData' => self::getSeoData(),
          'seoData' => $pageSeoData,
        ])->render();

        // Generate SEO tags based on the resolved resource
        // $title = "Page Title for {$page->slug}";
        // $description = "Description for " . get_class($page) . " with ID {$page->id}";

        // return "<title>{$title}</title>\n<meta name='description' content='{$description}'>";
    }


    protected function resolveCurrentPage()
    {
        $modelResolverClass = config('seo.model_resolver', \Piotrku\SeoFields\Helpers\ModelResolver::class);
        $modelResolver = app($modelResolverClass, ['request' => $this->request]);
        $this->currentPage = $modelResolver->getModel();
    }


    protected function getPageSeoData()
    {
        if (empty($this->currentPage)) {
            $this->pageSeoData = $this->getGeneralSeoData();
            return;
        }

        $this->pageSeoData = is_array($this->currentPage->content_seo)
          ? current($this->currentPage->content_seo)
          : $this->getPageAutomaticSeoData();

        return $this->pageSeoData;
    }


    protected function getGeneralSeoData(): array
    {
        return [];
    }


    protected function getPageAutomaticSeoData(): array
    {
        return [];
    }


    public function getSeoTitle(): ?string
    {
        return empty($this->pageSeoData['title'][$this->lang])
          ? config('seo.defaults.title')
          : $this->pageSeoData['title'][$this->lang];
    }


    public function getSeoDescription(): ?string
    {
        return empty($this->pageSeoData['description'][$this->lang])
          ? config('seo.defaults.description')
          : $this->pageSeoData['description'][$this->lang];
    }


    public function getSeoRobots(): ?string
    {
        return empty($this->pageSeoData['robots'])
          ? config('seo.defaults.robots', 'index, follow')
          : $this->pageSeoData['robots'];
    }


    public function getSeoKeywordsAsString(): ?string
    {
        return null;
    }


    public function getSeoImageUrl(): ?string
    {
        return config('seo.defaults.image');
    }


}