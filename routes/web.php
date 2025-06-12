<?php


use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\Http\Controllers\CsrfCookieController;

Route::get('/', function () {
    return ['Laravel' => app()->version()];
});

// Use Sanctum's CsrfCookieController to handle CSRF cookie route
Route::middleware('web')->get('/sanctum/csrf-cookie', [CsrfCookieController::class, 'show']);

// Your other routes or comments...

require __DIR__.'/auth.php';
