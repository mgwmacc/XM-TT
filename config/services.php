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
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.eu.mailgun.net'),
        'scheme' => 'https',
        //'transport' => 'mailgun',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'nasdaqListings' => [
        'uri' => env(
            'NASDAQ_LISTINGS_URI',

            //for this test task we put the default value here. (Local env var value is to be set)
            'https://pkgstore.datahub.io/core/nasdaq-listings/nasdaq-listed_json/data/a5bc7580d6176d60ac0b2142ca8d7df6/nasdaq-listed_json.json'
        )
    ],

    // We could also call the service 'Rapidapi Service' or something like that as
    // historical data, I suppose, is not only one type of data available. In this case the URI
    // would be different, as '/get-historical-data' part is a specific endpoint for the historical
    // data needed. So skipping due to the context of the test task.
    'historicalData' => [
        'uri' => env(
            'HISTORICAL_DATA_URI',

            //for this test task we put the default value here. (Local env var value is to be set)
            'https://yh-finance.p.rapidapi.com/stock/v3/get-historical-data'
        ),
        'key' => env(
            'HISTORICAL_DATA_API_KEY',

            //for this test task we put the default value here. (Local env var value is to be set)
            'fb06eebc46msh7adf1fd0b7bcee4p1519d3jsne400f43cec12'
        ),
        'host' => env(
            'HISTORICAL_DATA_API_HOST',

            //for this test task we put the default value here. (Local env var value is to be set)
            'yh-finance.p.rapidapi.com'
        ),
    ],
];
