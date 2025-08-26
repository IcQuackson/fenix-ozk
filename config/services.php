<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],
    'fenix' => [
        'base_url' => env('FENIX_BASE_URL'),
        'authorize_url' => env('FENIX_OAUTH_AUTHORIZE'),
        'access_token_url' => env('FENIX_OAUTH_ACCESS_TOKEN'),
        'refresh_token_url' => env('FENIX_OAUTH_REFRESH_TOKEN'),
        'client_id' => env('FENIX_CLIENT_ID'),
        'client_secret' => env('FENIX_CLIENT_SECRET'),
        'redirect_uri' => env('FENIX_REDIRECT_URI'),
    ],


];
