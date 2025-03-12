cd ../../vendor/laravel/nova
npm install

cd ../../nova-components/SeoFields
npm install
npm run watch
npm run dev
npm run prod



## edit cms/config/app.php

### add seo provider
```
'providers' => ServiceProvider::defaultProviders()->merge([
    Piotrku\SeoFields\FieldServiceProvider::class,
])->toArray(),
```

### add seo alias
```
'aliases' => Facade::defaultAliases()->merge([
    'Seo' => Piotrku\SeoFields\Facades\Seo::class,
])->toArray(),
```

### add field set to your nova ressource
```
use Piotrku\SeoFields\SeoFields;

public function fields(NovaRequest $request)
    {
        return [
            SeoFields::make('Seo fields', 'content_seo', [
                    Text::make('Title')
                        ->rules('max:70')
                        ->help('Recommended length 50-60 characters.')
                        ->translatable(),
                    Textarea::make('Description')
                        ->rules('max:180')
                        ->help('Recommended length 150-160 characters.')
                        ->translatable(),
                    Select::make('Robots')->options([
                            'index, follow' => 'Index and follow',
                            'noindex, follow' => 'No index and follow',
                            'index, nofollow' => 'Index and no follow',
                            'noindex, nofollow' => 'No index and no follow',
                        ])
                        ->resolveUsing(function ($value) {
                            return $value ?: 'index, follow';
                        }),
                    Boolean::make('Hide in sitemap', 'is_sitemap_hidden')
                        ->resolveUsing(fn ($value) => $value ?? false),
                    Heading::make(' '),
            ]),
        ];
    }
```

### run migration

### add type casting to your model
```
'content_seo' => 'array',
```


### add seo header generation to your header blade
```
{!! Seo::generateHead() !!}
```



### add seo traits to your models
```
use Piotrku\SeoFields\Traits\SeoSitemapTrait;
use Piotrku\SeoFields\Traits\SeoableTrait;

class Page extends Model
{
    use SeoSitemapTrait, SeoableTrait;

```

### implement trait enforced methods (for sitemap generation)
```
    /**
     * Get the Page url by item
     *
     * @return string
     */
    public function getSitemapItemUrl(): string
    {
        return route('news', ['slug' => $this->slug]);
    }

    /**
     * Query all the Page items which should be
     * part of the sitemap (crawlable for google).
     *
     * @return Builder
     */
    public static function getSitemapItems()
    {
        return static::all();
    }
```

### update config/seo.php accordingly


### sitemap

1. add model classes to seo.sitemap_models array
2. add SeoSitemapTrait to models and implement getSitemapItemUrl() & getSitemapItems() methods

### TODO

Add defaults language support


# MIT License

Copyright (c) 2025 Piotr Kubiak <piotr.kubiak@crafton.pl>

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.

## Third-Party Libraries

This software includes code from the following third-party libraries, which are licensed under the MIT License:

### 1. nova-simple-repeatable - Laravel Nova simple repeatable rows field
- **Source**: https://github.com/outl1ne/nova-simple-repeatable/
- **Copyright**: 2020 "Outl1ne" <info@optimistdigital.com>
- **License**: MIT

### 2. seoable - A Laravel Nova field which adds all SEO related meta fields to an Resource
- **Source**: https://github.com/marshmallow-packages/seoable
- **Copyright**: 2020 "Stef van Esch" <stef@marshmallow.dev>
- **License**: MIT


