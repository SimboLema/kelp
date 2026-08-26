<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env(
                'SLACK_BOT_USER_OAUTH_TOKEN',
            ),
            'channel' => env(
                'SLACK_BOT_USER_DEFAULT_CHANNEL',
            ),
        ],
    ],

    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect' => env('GOOGLE_REDIRECT_URI'),
    ],

    'kelp_payment' => [
        'base_url' => env('KELP_URL'),
        'email' => env('SURETECH_PAYMENT_EMAIL'),
        'password' => env('SURETECH_PAYMENT_PASSWORD'),
        'callback_token' => env('PAYMENT_KELP'),
        'callback_url' => env(
            'SURETECH_PAYMENT_CALLBACK_URL',
        ),
    ],

    'suretech' => [
        'base_url' => env('SURETECH_URL'),
        'secret' => env('SURETECH_SECRET'),
    ],

];
 