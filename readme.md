# Eidos Theme for OJS

An official theme from PKP that is highly configurable.

## Usage

> This assumes you know [how to build a custom OJS theme](https://docs.pkp.sfu.ca/pkp-theming-guide/en/).

Install the dependencies.

```
npm install
```

Run vite in local development mode.

```
npm run start
```

Build vite assets before deploying your theme to production.

```
npm run build
```

## Temporary Docs

### Layouts

Uses the `Layout` component to pass page title, description, meta tags, etc to pages.

```
<!-- indexJournal.blade -->
<x-eidostheme::layout
    title="{{ $currentContext->getLocalizedName() }}"
    description="{{ $currentContext->getLocalizedData('description') }}"
>
    <h1>Page</h1>
</x-eidostheme::layout>
```

[Attributes](https://laravel.com/docs/11.x/blade#component-attributes) passed to the layout will be assigned to the `<body>` tag. Some attributes are defined in `layout.blade`.

```
<!-- indexJournal.blade -->
<x-eidostheme::layout
    title="{{ $currentContext->getLocalizedName() }}"
    description="{{ $currentContext->getLocalizedData('description') }}"
    class="my-custom-page-class"
>
    <h1>Page</h1>
</x-eidostheme::layout>
```

```
<!-- Output -->
<body dir="ltr" class="pkp-page-index pkp-page-op my-custom-page-class">
```

A `head` [slot](https://laravel.com/docs/11.x/blade#slots) exists to pass custom content for the `<head>`.

```
<x-eidostheme::layout
    title="{{ $currentContext->getLocalizedName() }}"
    description="{{ $currentContext->getLocalizedData('description') }}"
>
    <x-slot:head>
        <meta name="test" content="hello there.">
        <script>console.log('hello')</script>
    </x-slot:head>

    <h1>Page</h1>
</x-eidostheme::layout>
```

### Article Metadata Blocks

> I think there is still some work to do to decide the right place to store all of these pieces. For now, default blocks are registered by `ThemePlugin`, stored in `PKPTemplateManager`, and loaded by the Eidos theme's `Layout` component.

Every theme can load the default metadata blocks by calling the following in the `init()` function:

```php
$this->addDefaultMetadataBlocks();
```

Register a custom metadata block with the template manager:

```php
$templateMgr = TemplateManager::getManager($this->request);
$templateMgr->registerArticleMetadataComponent(
    new MetadataBlock(
        id: 'keywords',
        title: 'Keywords',
        description: 'Example keywords description',
        component: 'metadata.keywords',
    )
);
```

Optionally pass a `loader` callback function to load custom data.

```php
$templateMgr = TemplateManager::getManager($this->request);
$templateMgr->registerArticleMetadataComponent(
    new MetadataBlock(
        id: 'metrics',
        title: 'Metrics',
        description: 'Example metrics description',
        component: 'metadata.metrics',
        loader: function(Publication $publication, Submission $submission) {
            view()->share('metricsViews', 123);
            view()->share('metricsDownloads', 50);
        }
    )
);
```

Templates are loaded based on the `component` property. For example, the component `metadata.default` will load the template at `templates/components/metadata/default.blade`.

```php
@props([
    'id',
    'title',
])

<div class="metadata-block metadata-{{ $id }}">
    <h3 class="metadata-block-title">
        {!! $title !!}
    </h3>
    {!! $slot !!}
</div>
```

Use the default template for consistent title and content display.

```php
{{-- templates/components/metadata/keywords.blade --}}

@if (!empty($publication->getLocalizedData('keywords')))
    <x-metadata.default
        id="keywords"
        title="{{ __('article.subject') }}"
    >
        <div class="metadata-block-content">
            @foreach ($publication->getLocalizedData('keywords') as $keyword)
                {{ $keyword['name'] }}@if(!$loop->last){{ __('common.commaListSeparator') }}@endif
            @endforeach
        </div>
    </x-metadata.default>
@endif
```

### Notice

Use the notice component to add a message, warning, or error.

```html
<x-notice>
    <div>This is a preview and has not been published.</div>
</x-notice>
```

Add an optional title and actions.

```html
<x-notice>
    <x-slot:title>
        Preview
    </x-slot:title>
    <div>This is a preview and has not been published.</div>
    <x-slot:actions>
        <a class="button" href="...">
            View Submission
        </a>
        <a class="button" href="...">
            Go to homepage
        </a>
    </x-slot>
</x-notice>
```

### Body Classes

Classes are assigned to the `<body>` element which can be used to adapt styles based on theme options. These include:

- `site-width-full | site-width-fixed` to adapt the header based on the Site Width option.
- `font-<name>`, `font-titles-<name>`, and `font-actions-<name>` to apply custom styles for fonts. When no custom font options are enabled, the `<name>` will be `default`. Use this to style the default [Noto Sans](https://fonts.google.com/noto/specimen/Noto+Sans) font, which supports variable weights and widths.

## Credit

This library is distributed under GPL 3.0. The Vite integration is based on [php-vite](https://github.com/mindplay-dk/php-vite) by [@mindplay-dk](https://github.com/mindplay-dk).
