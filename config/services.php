<?php

return [

    'github' => [
        'client_id' => env('GITHUB_CLIENT_ID'),
        'client_secret' => env('GITHUB_CLIENT_SECRET'),
        'redirect' => env('GITHUB_REDIRECT_URI'),
    ],

    'lastfm' => [
        'api_key' => env('LASTFM_API_KEY'),
        'secret' => env('LASTFM_SECRET'),
        'username' => env('LASTFM_USERNAME'),
    ]
];
