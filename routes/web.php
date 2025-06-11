<?php


use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

Route::get('/', function () {
    return ['Laravel' => app()->version()];
});

Route::get('/run-migrations', function () {
    Artisan::call('migrate', ['--force' => true]);
    return '✅ Migrations complete!';
});


require __DIR__.'/auth.php';
