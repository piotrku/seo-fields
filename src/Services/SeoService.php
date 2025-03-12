<?php

namespace Piotrku\SeoFields\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SeoService
{
    protected $request;
    protected $pageSeoData;
    protected $lang;
    protected $currentPage;

    protected $seoDataGeneratorClass;
    protected $modelResolverClass;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->lang = app()->getLocale();

        $this->seoDataGeneratorClass = config('seo.seo_data_generaotr', \Piotrku\SeoFields\Helpers\SeoDataGenerator::class);
        $this->modelResolverClass = config('seo.model_resolver', \Piotrku\SeoFields\Helpers\ModelResolver::class);
    }

    public function generateHead()
    {
        $this->resolveCurrentPage();
        $pageSeoData = $this->getPageSeoData();

        if (empty($pageSeoData)) {
            die('no page data found');
        }

        return view('seo-fields::seo-head', [
          'seoData' => $pageSeoData,
        ])->render();
    }


    protected function resolveCurrentPage()
    {
        $modelResolver = app($this->modelResolverClass, ['request' => $this->request]);
        $this->currentPage = $modelResolver->getModel();
    }


    protected function getPageSeoData()
    {
        if (empty($this->currentPage)) {
            $this->pageSeoData = $this->getDefaultSeoData();
            return $this->pageSeoData;
        }

        $this->pageSeoData = is_array($this->currentPage->seoable)
          ? $this->currentPage->seoable
          : $this->getGuessedPageSeoData();

        return $this->pageSeoData;
    }


    protected function getDefaultSeoData(): array
    {
        $seoDataGenerator = app($this->seoDataGeneratorClass);
        $this->pageSeoData = $seoDataGenerator->getDefaultSeoData();

        return $this->pageSeoData;
    }


    protected function getGuessedPageSeoData()
    {
        $this->guessPageSeoData();

        return $this->pageSeoData;
    }


    protected function guessPageSeoData()
    {
        $seoDataGenerator = app($this->seoDataGeneratorClass, ['model' => $this->currentPage]);
        $this->pageSeoData = $seoDataGenerator->getSeoData();
    }


    public function getSeoTitle(): ?string
    {
        return empty($this->pageSeoData['title'][$this->lang])
          ? config('seo.defaults.title')
          : $this->escAttr($this->pageSeoData['title'][$this->lang]);
    }


    public function getSeoDescription(): ?string
    {
        return empty($this->pageSeoData['description'][$this->lang])
          ? config('seo.defaults.description')
          : $this->escAttr($this->pageSeoData['description'][$this->lang]);
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
        return empty($this->pageSeoData['image'])
            ? config('seo.defaults.image')
            : $this->pageSeoData['image'];
    }


    public function getSeoCanonicalUrl(): ?string
    {
        return $this->request->url();
    }

    public function getSeoLocale()
    {
        $locale = false === strpos($this->lang, '_')
            ? $this->lang . '_' . Str::upper(app()->getLocale())
            : $this->lang;

        return $locale;
    }

    public function escAttr(?string $string): string
    {
        return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
    }
}