<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings for cross-origin resource sharing
    | or "CORS". This determines what cross-origin operations may execute
    | in web browsers. You are free to adjust these settings as needed.
    |
    | To learn more: https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS
    |
    */

    'paths' => [
        'api/*', 
        'sanctum/csrf-cookie', 
        'login', 
        'logout', 
        'register',
        'broadcasting/auth' // Added in case you use broadcasting
    ],

    'allowed_methods' => ['*'],

    'allowed_origins' => [
        env('FRONTEND_URL', 'https://flare-frontend-app.vercel.app'),
        'https://flare-frontend-app.vercel.app',
        'https://flare-frontend-app-git-main-matthices-projects.vercel.app', // From your sanctum config
        'http://localhost:3000',
        'https://localhost:3000',
    ],

    'allowed_origins_patterns' => [
        'https://*--flare-frontend-app-*.vercel.app', // Preview deployments
        'https://flare-frontend-app-*.vercel.app',     // Branch deployments
    ],

    'allowed_headers' => [
        'Accept',
        'Authorization', 
        'Content-Type',
        'X-Requested-With',
        'X-CSRF-TOKEN',
        'X-XSRF-TOKEN',
    ],

    'exposed_headers' => [
        'X-CSRF-TOKEN',
    ],

    'max_age' => 86400, // Cache preflight for 24 hours

    'supports_credentials' => true,

];