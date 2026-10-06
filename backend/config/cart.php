<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Cart Storage Driver
    |--------------------------------------------------------------------------
    |
    | This option controls the default cart storage driver that will be used
    | to store cart items. You may set this to any of the storage options
    | listed below.
    |
    | Supported: "database", "session", "both"
    |
    */
    /**
     * IMPORTANT
     * ----------
     * We force the default driver to "session" because the package's "both"
     * driver has a bug for authenticated users (it writes DB rows with
     * session_id = NULL but reads them back filtered by session_id).
     *
     * If you want DB persistence later, we can override the package queries
     * safely in-app without editing vendor code.
     */
    'driver' => env('CART_DRIVER', 'session'),

    /*
    |--------------------------------------------------------------------------
    | Cart Database Connection
    |--------------------------------------------------------------------------
    |
    | This is the database connection that will be used to store cart items
    | when using the "database" or "both" storage driver.
    |
    */
    'connection' => env('CART_DB_CONNECTION', null),

    /*
    |--------------------------------------------------------------------------
    | Cart Items Table
    |--------------------------------------------------------------------------
    |
    | This is the table that will be used to store cart items when using
    | the "database" or "both" storage driver.
    |
    */
    'table' => 'cart_items',

    /*
    |--------------------------------------------------------------------------
    | Session Key
    |--------------------------------------------------------------------------
    |
    | This is the session key that will be used to store cart items when
    | using the "session" or "both" storage driver.
    |
    */
    'session_key' => 'shopping_cart',
];
