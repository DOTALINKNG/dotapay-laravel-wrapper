<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Base URL
    |--------------------------------------------------------------------------
    |
    | Your DotaPay tenant base URL (no trailing slash).
    | Example: https://acme.dotapay.ng
    */
    'base_url' => env('DOTAPAY_BASE_URL', 'https://api.dotapay.ng'),

    /*
    |--------------------------------------------------------------------------
    | API prefix
    |--------------------------------------------------------------------------
    |
    | The prefix that precedes resources (customers/payment/settlements).
    | Usually: api/v1
    */
    'api_prefix' => env('DOTAPAY_API_PREFIX', 'api/v1'),

    /*
    |--------------------------------------------------------------------------
    | Authentication
    |--------------------------------------------------------------------------
    */
    'private_key' => env('DOTAPAY_PRIVATE_KEY'),

    // Header name used by DotaPay backend. In PHP it becomes HTTP_<HEADER>.
    'private_key_header' => env('DOTAPAY_PRIVATE_KEY_HEADER', 'DPPRIVATEKEY'),

    /*
    |--------------------------------------------------------------------------
    | HTTP client defaults
    |--------------------------------------------------------------------------
    */
    'timeout' => (float) env('DOTAPAY_TIMEOUT', 20),
    'connect_timeout' => (float) env('DOTAPAY_CONNECT_TIMEOUT', 10),

    // Retries for 429/5xx and connection exceptions.
    'retries' => (int) env('DOTAPAY_RETRIES', 2),
    'retry_sleep_ms' => (int) env('DOTAPAY_RETRY_SLEEP_MS', 300),

    // When true, the SDK will throw DotapayRequestException on non-2xx.
    'throw' => (bool) env('DOTAPAY_THROW', true),

    // Optional user-agent.
    'user_agent' => env('DOTAPAY_USER_AGENT', 'dotapay-laravel-sdk/1.0'),
];
