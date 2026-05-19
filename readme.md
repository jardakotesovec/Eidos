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
