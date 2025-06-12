<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Laravel\Sanctum\Http\Controllers\CsrfCookieController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Api\FlareController;
use App\Http\Resources\UserResource;
use App\Models\User;



Route::get('/users', function () {
    return UserResource::collection(User::all());
});

Route::get('/ping', function () {
    return response()->json(['message' => 'pong']);
});

Route::post('/register', [RegisteredUserController::class, 'store']);
Route::post('/login', [AuthenticatedSessionController::class, 'store']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy']);

    Route::get('/me', function (Request $request) {
        return $request->user();
    });

    Route::get('/flares', [FlareController::class, 'index']);
    Route::post('/flares', [FlareController::class, 'store']);
    Route::get('/flares/{id}', [FlareController::class, 'show']);
    Route::delete('/flares/{id}', [FlareController::class, 'destroy']);
});

Route::get('/known-places/nearby', [FlareController::class, 'nearbyKnownPlaces']);
Route::get('/known-places', function () {
    return \App\Models\KnownPlace::select('id', 'name', 'lat', 'lon')->get();
});
