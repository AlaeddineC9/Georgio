<?php

/**
 * Returns the importmap for this application.
 *
 * - "path" is a path inside the asset mapper system. Use the
 *     "debug:asset-map" command to see the full list of paths.
 *
 * - "entrypoint" (JavaScript only) set to true for any module that will
 *     be used as an "entrypoint" (and passed to the importmap() Twig function).
 *
 * The "importmap:require" command can be used to add new entries to this file.
 */
return [
    'app' => [
        'path' => './assets/app.js',
        'entrypoint' => true,
    ],
    '@hotwired/stimulus' => [
        'version' => '3.2.2',
    ],
    '@symfony/stimulus-bundle' => [
        'path' => './vendor/symfony/stimulus-bundle/assets/dist/loader.js',
    ],
    '@hotwired/turbo' => [
        'version' => '7.3.0',
    ],
    'vue' => [
        'version' => '3.5.4',
        'package_specifier' => 'vue/dist/vue.esm-bundler.js',
    ],
    '@vue/runtime-dom' => [
        'version' => '3.5.4',
    ],
    '@vue/compiler-dom' => [
        'version' => '3.5.4',
    ],
    '@vue/shared' => [
        'version' => '3.5.4',
    ],
    '@vue/runtime-core' => [
        'version' => '3.5.4',
    ],
    '@vue/compiler-core' => [
        'version' => '3.5.4',
    ],
    '@vue/reactivity' => [
        'version' => '3.5.4',
    ],
    '@symfony/ux-vue' => [
        'path' => './vendor/symfony/ux-vue/assets/dist/loader.js',
    ],
    '@swup/fade-theme' => [
        'version' => '1.0.5',
    ],
    '@swup/slide-theme' => [
        'version' => '1.0.5',
    ],
    '@swup/forms-plugin' => [
        'version' => '2.0.1',
    ],
    '@swup/plugin' => [
        'version' => '2.0.3',
    ],
    'swup' => [
        'version' => '3.1.1',
    ],
    'delegate-it' => [
        'version' => '6.0.1',
    ],
    '@swup/debug-plugin' => [
        'version' => '3.0.0',
    ],
    'react' => [
        'version' => '18.3.1',
    ],
    'react-dom' => [
        'version' => '18.3.1',
    ],
    'scheduler' => [
        'version' => '0.23.2',
    ],
    '@symfony/ux-react' => [
        'path' => './vendor/symfony/ux-react/assets/dist/loader.js',
    ],
    'svelte/internal' => [
        'version' => '3.59.2',
    ],
    '@symfony/ux-svelte' => [
        'path' => './vendor/symfony/ux-svelte/assets/dist/loader.js',
    ],
    'chart.js' => [
        'version' => '3.9.1',
    ],
    'tom-select' => [
        'version' => '2.3.1',
    ],
    'cropperjs' => [
        'version' => '1.6.2',
    ],
    'cropperjs/dist/cropper.min.css' => [
        'version' => '1.6.2',
        'type' => 'css',
    ],
];
