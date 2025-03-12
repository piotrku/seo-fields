@if (config('seo.fields.title'))
    <title>{{ Seo::getSeoTitle() }}</title>
@endif

@if (config('seo.fields.description'))
    <meta name="description" content="{{ Seo::getSeoDescription() }}" />
@endif
@if (config('seo.fields.keywords') && Seo::getSeoKeywordsAsString())
    <meta name="keywords" content="{{ Seo::getSeoKeywordsAsString() }}" />
@endif
@if (config('seo.fields.robots'))
    <meta name="robots" content="{{ Seo::getSeoRobots() }}" />
@endif
<meta name="author" content="{{ config('seo.defaults.author') }}">
<meta name="twitter:card" content="{{ Seo::getSeoDescription() }}" />
@if (config('seo.fields.title'))
    <meta property="og:title" content="{{ Seo::getSeoTitle() }}" />
@endif
@if (config('seo.fields.description'))
    <meta property="og:description" content="{{ Seo::getSeoDescription() }}" />
@endif
@if (config('seo.fields.image'))
    <meta property="og:image" content="{{ Seo::getSeoImageUrl() }}" />
@endif
<meta property="og:site_name" content="{{ config('seo.defaults.sitename') }}" />
<meta property="og:url" content="{{ Seo::getSeoCanonicalUrl() }}" />
<meta property="og:locale" content="{{ Seo::getSeoLocale() }}" />
@if ($canonical = Seo::getSeoCanonicalUrl())
    <link rel="canonical" href="{{ $canonical }}" />
@endif
@if (config('seo.twitter.site'))
    <meta name="twitter:site" content="{{ config('seo.twitter.site') }}" />
@endif
@if (config('seo.twitter.creator'))
    <meta name="twitter:creator" content="{{ config('seo.twitter.creator') }}" />
@endif

@include('seo-fields::microsoft.head')
@include('seo-fields::google.ga-head')

{{--
@include('seoable::google.gtm-head')
@include('seoable::facebook.fb-head')
@include('seoable::microsoft.head')
@include('seoable::hotjar.hotjar')

@if (Seo::hasSchema())
    <script type="application/ld+json">
        {!! Seo::getSchema() !!}
    </script>
@endif

@if ($hrefs = Seo::getHrefLang())
    @foreach ($hrefs as $lang => $route)
        <link rel="alternate" href="{{ $route }}" hreflang="{{ $lang }}" />
    @endforeach
@endif

@if (config('seo.fields.page_type') && ($type = Seo::getSeoPageType()))
    <meta property="og:type" content="{{ $type }}" />
@endif

@if (!config('seo.hide_mr_mallow'))
    <!-- Marshmallow SEO - HEADER END -->
@endif --}}
