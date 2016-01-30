<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, Mandrill, and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
    ],

    'mandrill' => [
        'secret' => env('MANDRILL_SECRET'),
    ],

    'ses' => [
        'key'    => env('SES_KEY'),
        'secret' => env('SES_SECRET'),
        'region' => 'us-east-1',
    ],

    'stripe' => [
        'model'  => App\User::class,
        'key'    => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
    ],

    // http://developer.microsoftband.com/Content/docs/MS%20Health%20API%20Getting%20Started.pdf
    'microsoft' => [
        'urlAuthorize' => 'https://login.live.com/oauth20_authorize.srf',
        'urlAccessToken' => 'https://login.live.com/oauth20_token.srf',
        'clientId' => env('MICROSOFT_CLIENTID'),
        'clientSecret' => env('MICROSOFT_SECRET'),
        'scopeSeparator' => ' ',
        'scopes' => [
            'mshealth.ReadProfile',
            'mshealth.ReadActivityHistory',
            'mshealth.ReadDevices',
            'mshealth.ReadActivityLocation',
            'offline_access',
        ],
    ],

];
