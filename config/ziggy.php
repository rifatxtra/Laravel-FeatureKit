<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Output File
    |--------------------------------------------------------------------------
    |
    | Path to the JavaScript file where Ziggy should output its route list.
    | Set this to `false` to prevent Ziggy from generating a routes file.
    |
    */

    'output' => [
        'file' => null,
    ],

    /*
    |--------------------------------------------------------------------------
    | Route Groups
    |--------------------------------------------------------------------------
    |
    | Optionally, define which routes should be included in the output.
    | Use `only` to include specific routes, or `except` to exclude them.
    |
    */

    'only' => [],
    'except' => [],

    /*
    |--------------------------------------------------------------------------
    | Middleware
    |--------------------------------------------------------------------------
    |
    | Optionally, define middleware that should be applied when generating
    | the Ziggy routes.
    |
    */

    'middleware' => [],
];
