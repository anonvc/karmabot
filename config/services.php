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

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    
    'discord' => [
        'client_id' => env('DISCORD_APP_ID'),
        'client_secret' => env('DISCORD_SECRET'),
        'token' => env('DISCORD_BOT_TOKEN'),
        'uri' => env('DISCORD_URI'),
        'redirect' => '/auth/discord/callback',
    ],

    'alchemy-solana' => [
        'key' =>  env('ALCHEMY_KEY'),
        'uri' => env('ALCHEMY_URI'),
    ],
    
    'magic-eden' => [
        'uri' => 'https://api-mainnet.magiceden.dev/v2/',
        'limit' => 1000,
    ],

];
